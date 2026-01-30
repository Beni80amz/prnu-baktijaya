import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:share_plus/share_plus.dart';
import '../../core/theme/app_theme.dart';
import '../providers/providers.dart';
import '../../data/models/live_streaming_model.dart';

// ==========================================
// 1. ENTRY POINT: Fetch Data First
// ==========================================
class LiveStreamingScreen extends ConsumerStatefulWidget {
  const LiveStreamingScreen({super.key});

  @override
  ConsumerState<LiveStreamingScreen> createState() => _LiveStreamingScreenState();
}

class _LiveStreamingScreenState extends ConsumerState<LiveStreamingScreen> {
  Future<void> _onRefresh() async {
    // Invalidate the provider to refetch data
    ref.invalidate(liveStreamingProvider);
    // Wait for the new data to load
    await ref.read(liveStreamingProvider.future);
  }

  @override
  Widget build(BuildContext context) {
    final liveAsync = ref.watch(liveStreamingProvider);

    return liveAsync.when(
      data: (data) {
        // Debug: Print video data
        debugPrint('=== LIVE STREAMING DEBUG ===');
        debugPrint('Video ID: ${data.video.youtubeId}');
        debugPrint('Is Live: ${data.video.isLive}');
        debugPrint('YouTube URL: ${data.video.youtubeUrl}');
        debugPrint('Title: ${data.video.title}');
        debugPrint('============================');
        
        return LiveStreamingPage(
          data: data, 
          onRefresh: _onRefresh,
        );
      },
      loading: () => const Scaffold(body: Center(child: CircularProgressIndicator())),
      error: (err, _) => Scaffold(
        body: RefreshIndicator(
          onRefresh: _onRefresh,
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            child: SizedBox(
              height: MediaQuery.of(context).size.height,
              child: Center(child: Text('Gagal memuat data: $err')),
            ),
          ),
        ),
      ),
    );
  }
}

// ==========================================
// 2. PLAYER PAGE: Manages Controller & UI
// ==========================================
class LiveStreamingPage extends StatefulWidget {
  final LiveStreamingData data;
  final Future<void> Function() onRefresh;
  
  const LiveStreamingPage({
    super.key, 
    required this.data,
    required this.onRefresh,
  });

  @override
  State<LiveStreamingPage> createState() => _LiveStreamingPageState();
}

class _LiveStreamingPageState extends State<LiveStreamingPage> with SingleTickerProviderStateMixin {
  WebViewController? _controller;
  late TabController _tabController;
  bool _hasValidVideo = false;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _initializePlayer();
  }

  void _initializePlayer() {
    final videoId = widget.data.video.youtubeId;
    
    if (videoId != null && videoId.isNotEmpty) {
      _hasValidVideo = true;
      _controller = WebViewController()
        ..setJavaScriptMode(JavaScriptMode.unrestricted)
        ..setBackgroundColor(const Color(0xFF000000))
        ..loadRequest(Uri.parse('https://www.youtube.com/embed/$videoId?autoplay=0&controls=1&playsinline=1'));
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final backgroundColor = isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6);

    // If no valid video, show fallback UI
    if (!_hasValidVideo) {
      return Scaffold(
        backgroundColor: backgroundColor,
        body: SafeArea(
          child: Column(
            children: [
              _buildHeader(context, isDark, null),
              _buildNoVideoPlaceholder(isDark),
              _buildEventInfo(isDark, widget.data.info),
              if (widget.data.upcoming.isNotEmpty)
                _buildUpcomingSection(isDark, widget.data.upcoming),
              _buildTabBar(isDark),
              Expanded(
                child: TabBarView(
                  controller: _tabController,
                  children: const [
                    LiveChatTab(),
                    AttendanceTab(),
                  ],
                ),
              ),
            ],
          ),
        ),
      );
    }

    return Scaffold(
      backgroundColor: backgroundColor,
      body: SafeArea(
        child: Column(
          children: [
            // HEADER
            _buildHeader(context, isDark, widget.data.video.youtubeUrl),
            
            // VIDEO PLAYER
            if (_hasValidVideo && _controller != null)
               _buildVideoSection(widget.data.video, WebViewWidget(controller: _controller!))
            else
               _buildNoVideoPlaceholder(isDark),

            // EVENT INFO
            _buildEventInfo(isDark, widget.data.info),

            // UPCOMING SCHEDULES
            if (widget.data.upcoming.isNotEmpty)
              _buildUpcomingSection(isDark, widget.data.upcoming),

            // TABS
            _buildTabBar(isDark),
            
            // TAB CONTENT
            Expanded(
              child: TabBarView(
                controller: _tabController,
                children: const [
                  LiveChatTab(),
                  AttendanceTab(),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildNoVideoPlaceholder(bool isDark) {
    return Container(
      height: 200,
      color: Colors.black,
      child: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.videocam_off, color: Colors.white54, size: 48),
            const SizedBox(height: 12),
            Text(
              'Video tidak tersedia',
              style: TextStyle(color: Colors.white70, fontSize: 14),
            ),
            const SizedBox(height: 4),
            Text(
              'Tidak ada live streaming atau video terbaru',
              style: TextStyle(color: Colors.white38, fontSize: 11),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildUpcomingSection(bool isDark, List<UpcomingSchedule> upcoming) {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 8),
      decoration: BoxDecoration(
        border: Border(bottom: BorderSide(color: isDark ? Colors.white10 : Colors.black12)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16),
            child: Text(
              'JADWAL MENDATANG',
              style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey, letterSpacing: 1),
            ),
          ),
          const SizedBox(height: 8),
          SizedBox(
            height: 70,
            child: ListView.builder(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              scrollDirection: Axis.horizontal,
              itemCount: upcoming.length,
              itemBuilder: (context, index) {
                final schedule = upcoming[index];
                return _buildCompactUpcomingCard(schedule, isDark);
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCompactUpcomingCard(UpcomingSchedule schedule, bool isDark) {
    return Container(
      width: 200,
      margin: const EdgeInsets.only(right: 12),
      padding: const EdgeInsets.all(10),
      decoration: BoxDecoration(
        color: isDark ? Colors.white10 : Colors.grey[100],
        borderRadius: BorderRadius.circular(8),
      ),
      child: Row(
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(6),
            child: Image.network(
              schedule.thumbnail,
              width: 50,
              height: 50,
              fit: BoxFit.cover,
              errorBuilder: (_, __, ___) => Container(width: 50, height: 50, color: Colors.grey),
            ),
          ),
          const SizedBox(width: 10),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  schedule.scheduledStart,
                  style: TextStyle(fontSize: 9, color: AppTheme.teal, fontWeight: FontWeight.bold),
                ),
                const SizedBox(height: 2),
                Text(
                  schedule.title,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(fontSize: 11, fontWeight: FontWeight.w600),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildHeader(BuildContext context, bool isDark, String? url) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          IconButton(
            icon: Icon(Icons.chevron_left, color: isDark ? Colors.white : Colors.black),
            onPressed: () => Navigator.pop(context),
          ),
          Text(
            'Live Streaming',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: isDark ? Colors.white : Colors.black,
            ),
          ),
          IconButton(
            icon: Icon(Icons.share, color: isDark ? Colors.white : Colors.black),
            onPressed: () {
              if (url != null) {
                Share.share('Ayo tonton siaran langsung ini: $url');
              }
            },
          ),
        ],
      ),
    );
  }

  Widget _buildVideoSection(LiveVideo video, Widget playerWidget) {
    return Column(
      children: [
        Stack(
          children: [
            // The Player Widget passed from Builder
            playerWidget,
            
            // STATUS BADGE OVERLAY
            Positioned(
              top: 10,
              left: 10,
              child: IgnorePointer(
                child: Row(
                  children: [
                    if (video.isLive)
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: Colors.red,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: const Row(
                          children: [
                            Icon(Icons.circle, color: Colors.white, size: 8),
                            SizedBox(width: 4),
                            Text(
                              'LIVE',
                              style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                            ),
                          ],
                        ),
                      )
                    else 
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: Colors.grey[800],
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: const Text('VIDEO TERBARU', style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                      ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildEventInfo(bool isDark, LiveInfo info) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        border: Border(bottom: BorderSide(color: isDark ? Colors.white10 : Colors.black12)),
      ),
      child: Row(
        children: [
          Container(
            width: 40,
            height: 40,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              border: Border.all(color: AppTheme.teal),
              image: DecorationImage(
                image: (info.speakerAvatar != null && info.speakerAvatar!.isNotEmpty)
                    ? NetworkImage(info.speakerAvatar!)
                    : NetworkImage('https://lh3.googleusercontent.com/aida-public/AB6AXuAg4azJBetkBkyD_LODwbpE-dzqvivEZBdLutvyb6bkMonZ6wvg0BUrhv6RBMCSfUoyA6tjNAtkGRvhgb9TkTdieSCIcoJ_Ihgx9RWUzko6Ke__ZOUks0_H-Nh5-343MIbwtWs-SKJl3Hqbveun2mDit_qRzklFDlZ0DnNPfiODkCkItUZmoyCaKqoJxToX1ojbUdGlsR7JO85DImoHTY7FjXTN9eXprsfb-J9oXGa0FNbhdaeDdZ9fQQsIStOJ81JcTe5UkOVaYHk'),
                fit: BoxFit.cover,
                onError: (e, s) {}, 
              ),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  info.title,
                  style: TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: isDark ? Colors.white : Colors.black,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Row(
                  children: [
                    Expanded(
                      child: Text(
                        info.channelName,
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                          color: isDark ? Colors.grey[300] : Colors.grey[800],
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    const SizedBox(width: 4),
                    const Icon(Icons.verified, color: AppTheme.gold, size: 14),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTabBar(bool isDark) {
    return Container(
      decoration: BoxDecoration(
        border: Border(bottom: BorderSide(color: isDark ? Colors.white10 : Colors.black12)),
      ),
      child: TabBar(
        controller: _tabController,
        labelColor: AppTheme.teal,
        unselectedLabelColor: Colors.grey,
        indicatorColor: AppTheme.teal,
        tabs: const [
          Tab(text: 'Live Chat'),
          Tab(text: 'Daftar Hadir'),
        ],
      ),
    );
  }
}

// ==========================================
// 3. LIVE CHAT TAB (Isolated Consumer)
// ==========================================
class LiveChatTab extends ConsumerStatefulWidget {
  const LiveChatTab({super.key});

  @override
  ConsumerState<LiveChatTab> createState() => _LiveChatTabState();
}

class _LiveChatTabState extends ConsumerState<LiveChatTab> {
  final TextEditingController _chatController = TextEditingController();

  Future<void> _onRefresh() async {
     return ref.refresh(liveChatsProvider.future);
  }

  void _sendChat() async {
    if (_chatController.text.trim().isEmpty) return;
    final message = _chatController.text.trim();
    _chatController.clear();
    const name = "Jamaah Mobile"; 
    
    try {
      final repository = ref.read(repositoryProvider);
      await repository.postLiveChat(name, message);
      // Refresh chat list after sending
      ref.invalidate(liveChatsProvider);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal mengirim pesan: $e')));
      }
    }
  }

  @override
  void dispose() {
    _chatController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final chatsAsync = ref.watch(liveChatsProvider);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Column(
      children: [
        // CHAT LIST
        Expanded(
          child: RefreshIndicator(
            onRefresh: _onRefresh,
            child: chatsAsync.when(
              loading: () => const Center(child: CircularProgressIndicator()),
              error: (err, _) => Center(child: Text('Gagal memuat chat', style: TextStyle(color: Colors.grey))),
              data: (chats) {
                if (chats.isEmpty) {
                   return ListView(
                     physics: const AlwaysScrollableScrollPhysics(),
                     children: const [
                       SizedBox(height: 100),
                       Center(child: Text('Belum ada pesan. Jadilah yang pertama!', style: TextStyle(color: Colors.grey))),
                     ],
                   );
                }
                return ListView.builder(
                  padding: const EdgeInsets.all(16),
                  physics: const AlwaysScrollableScrollPhysics(),
                  itemCount: chats.length,
                  itemBuilder: (context, index) {
                    final chat = chats[index];
                    Color avatarColor = Colors.grey; 
                    if (chat.avatarColor.contains('red')) avatarColor = Colors.red;
                    else if (chat.avatarColor.contains('blue')) avatarColor = Colors.blue;
                    else if (chat.avatarColor.contains('green')) avatarColor = Colors.green;
                    else if (chat.avatarColor.contains('yellow')) avatarColor = Colors.orange;
                    else if (chat.avatarColor.contains('purple')) avatarColor = Colors.purple;
                    
                    final initialsLength = chat.name.length < 2 ? chat.name.length : 2;
                    return _buildChatMessage(
                      chat.name.substring(0, initialsLength).toUpperCase(), 
                      chat.name, 
                      chat.message, 
                      chat.createdAt, 
                      isDark, 
                      avatarColor
                    );
                  },
                );
              },
            ),
          ),
        ),
        // CHAT INPUT
        _buildChatInput(isDark),
      ],
    );
  }

  Widget _buildChatMessage(String initials, String name, String message, String time, bool isDark, Color color,
      {bool isSpecial = false, bool isQuote = false}) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 16,
            backgroundColor: isSpecial ? color.withOpacity(0.2) : (isDark ? Colors.white10 : Colors.grey[200]),
            child: Text(
              initials,
              style: TextStyle(
                fontSize: 10,
                fontWeight: FontWeight.bold,
                color: isSpecial ? color : (isDark ? Colors.white : Colors.black),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    Text(
                      name,
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: isSpecial ? color : AppTheme.teal,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      time,
                      style: TextStyle(
                        fontSize: 10,
                        color: Colors.grey[500],
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: isSpecial ? color.withOpacity(0.1) : (isDark ? Colors.white.withOpacity(0.05) : Colors.grey[100]),
                    borderRadius: const BorderRadius.only(
                      topRight: Radius.circular(12),
                      bottomLeft: Radius.circular(12),
                      bottomRight: Radius.circular(12),
                    ),
                  ),
                  child: Text(
                    message,
                    style: TextStyle(
                      fontSize: 13,
                      height: 1.4,
                      color: isDark ? Colors.grey[300] : Colors.grey[800],
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildChatInput(bool isDark) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF102216) : Colors.white,
        border: Border(top: BorderSide(color: isDark ? Colors.white10 : Colors.black12)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, -5),
          ),
        ],
      ),
      child: Column(
        children: [
          // QUICK ACTIONS
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: [
                _buildQuickAction('Masha Allah ❤️', isDark),
                _buildQuickAction('Alhamdulillah ✨', isDark),
                _buildQuickAction('Barakallah 🙏', isDark),
                _buildQuickAction('Subhanallah 🤲', isDark),
              ],
            ),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Expanded(
                child: TextField(
                  controller: _chatController,
                  decoration: InputDecoration(
                    hintText: 'Tulis pesan...',
                    hintStyle: TextStyle(color: isDark ? Colors.grey[600] : Colors.grey[400]),
                    fillColor: isDark ? Colors.white.withOpacity(0.05) : Colors.grey[100],
                    filled: true,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(24),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                  ),
                ),
              ),
              const SizedBox(width: 8),
              Container(
                decoration: const BoxDecoration(
                  color: AppTheme.teal,
                  shape: BoxShape.circle,
                ),
                child: IconButton(
                  icon: const Icon(Icons.send, color: Colors.white, size: 20),
                  onPressed: _sendChat,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildQuickAction(String label, bool isDark) {
    return GestureDetector(
      onTap: () {
        _chatController.text = label;
        _sendChat();
      },
      child: Container(
        margin: const EdgeInsets.only(right: 8),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        decoration: BoxDecoration(
          color: isDark ? Colors.white.withOpacity(0.05) : Colors.grey[100],
          borderRadius: BorderRadius.circular(16),
          border: Border.all(color: isDark ? Colors.white10 : Colors.black12),
        ),
        child: Text(
          label,
          style: TextStyle(
            fontSize: 12,
            color: isDark ? Colors.grey[400] : Colors.grey[600],
          ),
        ),
      ),
    );
  }
}

// ==========================================
// 4. ATTENDANCE TAB
// ==========================================
class AttendanceTab extends ConsumerStatefulWidget {
  const AttendanceTab({super.key});

  @override
  ConsumerState<AttendanceTab> createState() => _AttendanceTabState();
}

class _AttendanceTabState extends ConsumerState<AttendanceTab> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _regionController = TextEditingController();
  bool _isSubmitting = false;

  void _submitAttendance() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isSubmitting = true);
      // Simulate API call
      await Future.delayed(const Duration(seconds: 1));
      if (mounted) {
        setState(() => _isSubmitting = false);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Terima kasih, kehadiran Anda telah dicatat!')),
        );
        _nameController.clear();
        _regionController.clear();
      }
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _regionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Daftar Hadir Jamaah',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              'Silakan isi form di bawah ini untuk mendata kehadiran Anda dalam kajian live ini.',
              style: TextStyle(color: Colors.grey[500], height: 1.5),
            ),
            const SizedBox(height: 32),
            _buildTextField('Nama Lengkap', 'Masukkan nama Anda', _nameController, isDark),
            const SizedBox(height: 20),
            _buildTextField('Asal Wilayah / Ranting', 'Contoh: Baktijaya, Sukmajaya', _regionController, isDark),
            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submitAttendance,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppTheme.teal,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: _isSubmitting
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text('Kirim Kehadiran', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTextField(String label, String hint, TextEditingController controller, bool isDark) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
        ),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          decoration: InputDecoration(
            hintText: hint,
            hintStyle: TextStyle(color: isDark ? Colors.grey[600] : Colors.grey[400]),
            fillColor: isDark ? Colors.white.withOpacity(0.05) : Colors.grey[100],
            filled: true,
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          ),
          validator: (val) => val == null || val.isEmpty ? 'Field ini wajib diisi' : null,
        ),
      ],
    );
  }
}
