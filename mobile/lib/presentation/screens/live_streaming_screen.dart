import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import 'package:share_plus/share_plus.dart';
import '../../core/theme/app_theme.dart';
import '../providers/providers.dart';
import '../../data/models/live_streaming_model.dart';
import 'dart:math';

// ==========================================
// 1. ENTRY POINT: Fetch Data First
// ==========================================
class LiveStreamingScreen extends ConsumerWidget {
  const LiveStreamingScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final liveDataAsync = ref.watch(liveStreamingProvider);
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final backgroundColor = isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6);

    return liveDataAsync.when(
      loading: () => Scaffold(
        backgroundColor: backgroundColor,
        body: const Center(child: CircularProgressIndicator()),
      ),
      error: (err, stack) => Scaffold(
        backgroundColor: backgroundColor,
        appBar: AppBar(title: const Text('Live Streaming')),
        body: Center(child: Text('Error: $err')),
      ),
      data: (data) {
        if (data.video.youtubeId == null) {
          return Scaffold(
             backgroundColor: backgroundColor,
             appBar: AppBar(title: const Text('Live Streaming')),
             body: const Center(child: Text('Video tidak tersedia')),
          );
        }
        // Data is ready, launch the actual Player Page
        return LiveStreamingPage(data: data);
      },
    );
  }
}

// ==========================================
// 2. PLAYER PAGE: Manages Controller & UI
// ==========================================
class LiveStreamingPage extends StatefulWidget {
  final LiveStreamingData data;
  const LiveStreamingPage({super.key, required this.data});

  @override
  State<LiveStreamingPage> createState() => _LiveStreamingPageState();
}

class _LiveStreamingPageState extends State<LiveStreamingPage> with SingleTickerProviderStateMixin {
  late YoutubePlayerController _controller;
  late TabController _tabController;

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    
    _controller = YoutubePlayerController(
      initialVideoId: widget.data.video.youtubeId!,
      flags: const YoutubePlayerFlags(
        autoPlay: false, // Keep false, user should tap play
        mute: false,
        isLive: false, // Always false to allow seeking if available
        forceHD: false, // Changed to false for better compatibility
        enableCaption: false,
      ),
    );
  }

  @override
  void dispose() {
    _tabController.dispose();
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final backgroundColor = isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6);

    return YoutubePlayerBuilder(
      player: YoutubePlayer(
        controller: _controller,
        showVideoProgressIndicator: true,
        progressIndicatorColor: AppTheme.teal,
        bottomActions: [
           CurrentPosition(),
           ProgressBar(isExpanded: true, colors: const ProgressBarColors(
             playedColor: AppTheme.teal,
             handleColor: AppTheme.teal,
           )),
           RemainingDuration(),
           FullScreenButton(),
        ],
      ),
      builder: (context, player) {
        return Scaffold(
          backgroundColor: backgroundColor,
          body: SafeArea(
            child: Column(
              children: [
                // HEADER
                _buildHeader(context, isDark, widget.data.video.youtubeUrl),
                
                // VIDEO SECTION
                _buildVideoSection(widget.data.video, player),

                // TABS & CONTENT
                Expanded(
                  child: Column(
                    children: [
                      _buildEventInfo(isDark, widget.data.info),
                      _buildTabBar(isDark),
                       Expanded(
                        child: TabBarView(
                          controller: _tabController,
                          children: [
                            // LIVE CHAT TAB (Isolated Consumer)
                            LiveChatTab(upcoming: widget.data.upcoming),
                            // ATTENDANCE TAB
                            const AttendanceTab(),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
        );
      },
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
          alignment: Alignment.topLeft,
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
                      
                    if (video.isLive)
                    Padding(
                      padding: const EdgeInsets.only(left: 8.0),
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                        decoration: BoxDecoration(
                          color: Colors.black45,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: const Row(
                          children: [
                            Icon(Icons.visibility, color: Colors.white, size: 12),
                            SizedBox(width: 4),
                            Text(
                              'Menonton',
                              style: TextStyle(color: Colors.white, fontSize: 10),
                            ),
                          ],
                        ),
                      ),
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
                image: NetworkImage(
                    info.speakerAvatar ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuAg4azJBetkBkyD_LODwbpE-dzqvivEZBdLutvyb6bkMonZ6wvg0BUrhv6RBMCSfUoyA6tjNAtkGRvhgb9TkTdieSCIcoJ_Ihgx9RWUzko6Ke__ZOUks0_H-Nh5-343MIbwtWs-SKJl3Hqbveun2mDit_qRzklFDlZ0DnNPfiODkCkItUZmoyCaKqoJxToX1ojbUdGlsR7JO85DImoHTY7FjXTN9eXprsfb-J9oXGa0FNbhdaeDdZ9fQQsIStOJ81JcTe5UkOVaYHk'),
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
  final List<UpcomingSchedule> upcoming;
  const LiveChatTab({super.key, required this.upcoming});

  @override
  ConsumerState<LiveChatTab> createState() => _LiveChatTabState();
}

class _LiveChatTabState extends ConsumerState<LiveChatTab> {
  final TextEditingController _chatController = TextEditingController();

  Future<void> _onRefresh() async {
     return ref.refresh(liveStreamingProvider.future);
  }

  void _sendChat() async {
    if (_chatController.text.trim().isEmpty) return;
    final message = _chatController.text.trim();
    _chatController.clear();
    const name = "Jamaah Mobile"; 
    
    try {
      final repository = ref.read(repositoryProvider);
      await repository.postLiveChat(name, message);
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal mengirim pesan: $e')));
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
        Expanded(
          child: RefreshIndicator(
            onRefresh: _onRefresh,
            child: chatsAsync.when(
              loading: () => const Center(child: CircularProgressIndicator()),
              error: (err, _) => Center(child: Text('Gagal memuat chat')),
              data: (chats) {
                if (chats.isEmpty) {
                   return Center(child: Text('Belum ada pesan. Jadilah yang pertama!', style: TextStyle(color: Colors.grey)));
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
                    
                    return _buildChatMessage(
                      chat.name.substring(0, min(2, chat.name.length)).toUpperCase(), 
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
        _buildUpcomingCarousel(isDark, widget.upcoming),
        _buildChatInput(isDark),
      ],
    );
  }
  
  int min(int a, int b) => a < b ? a : b;

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
                    if (isSpecial) ...[
                      const SizedBox(width: 4),
                      Icon(Icons.stars, size: 12, color: color),
                    ],
                    const SizedBox(width: 8),
                    Text(
                      time,
                      style: const TextStyle(fontSize: 10, color: Colors.grey),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                 Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      color: isSpecial ? color.withOpacity(0.1) : (isDark ? Colors.white10 : Colors.grey[100]),
                      borderRadius: BorderRadius.circular(8),
                      border: isSpecial ? Border.all(color: color.withOpacity(0.3)) : null,
                    ),
                    child: Text(
                      message,
                      style: TextStyle(
                        fontSize: 12,
                        color: isDark ? Colors.white : Colors.black87,
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

  Widget _buildUpcomingCarousel(bool isDark, List<UpcomingSchedule> upcoming) {
    if (upcoming.isEmpty) return const SizedBox.shrink();
    
    return Container(
      color: isDark ? Colors.black26 : Colors.grey[50], 
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'JADWAL LIVE MENDATANG',
                  style: TextStyle(
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                    color: isDark ? Colors.grey[400] : Colors.grey[600],
                    letterSpacing: 1.0,
                  ),
                ),
                const Text(
                  'Lihat Semua',
                  style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: AppTheme.teal),
                ),
              ],
            ),
          ),
          const SizedBox(height: 8),
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              children: upcoming.map((schedule) => 
                Padding(
                  padding: const EdgeInsets.only(right: 12),
                  child: _buildUpcomingCard(
                    schedule.title,
                    schedule.description,
                    schedule.scheduledStart,
                    schedule.thumbnail,
                    isDark,
                  ),
                )
              ).toList(),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildUpcomingCard(String title, String subtitle, String time, String imageUrl, bool isDark) {
    return Container(
      width: 200,
      decoration: BoxDecoration(
        color: isDark ? Colors.grey[900] : Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: const [BoxShadow(color: Colors.black12, blurRadius: 4, offset: Offset(0, 2))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Stack(
            children: [
              ClipRRect(
                borderRadius: const BorderRadius.vertical(top: Radius.circular(12)),
                child: Image.network(imageUrl, height: 100, width: double.infinity, fit: BoxFit.cover,
                  errorBuilder: (c, e, s) => Container(height: 100, color: Colors.grey, child: const Icon(Icons.error)),
                ),
              ),
              Positioned(
                top: 8,
                right: 8,
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                  decoration: BoxDecoration(color: Colors.black54, borderRadius: BorderRadius.circular(4)),
                  child: Text(time, style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold)),
                ),
              ),
            ],
          ),
          Padding(
            padding: const EdgeInsets.all(10),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: isDark ? Colors.white : Colors.black,
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                Text(
                  subtitle,
                  style: TextStyle(
                    fontSize: 10,
                    color: isDark ? Colors.grey[400] : Colors.grey[600],
                  ),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
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
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        border: Border(top: BorderSide(color: isDark ? Colors.white10 : Colors.black12)),
        color: isDark ? Colors.black26 : Colors.white,
      ),
      child: Column(
        children: [
          Row(
            children: [
              Expanded(
                child: TextField(
                  controller: _chatController,
                  style: TextStyle(color: isDark ? Colors.white : Colors.black),
                  decoration: InputDecoration(
                    hintText: 'Tulis komentar atau doa...',
                    hintStyle: TextStyle(color: isDark ? Colors.grey[500] : Colors.grey),
                    fillColor: isDark ? Colors.white10 : Colors.grey[100],
                    filled: true,
                    isDense: true,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 10),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(24),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 8),
              CircleAvatar(
                backgroundColor: AppTheme.teal,
                child: IconButton(
                  icon: const Icon(Icons.send, color: Colors.white, size: 18),
                  onPressed: _sendChat,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              _buildQuickAction('🤲 Kirim Doa', isDark),
              const SizedBox(width: 8),
              _buildQuickAction('✨ Sholawat', isDark),
              const SizedBox(width: 8),
              _buildQuickAction('👏 Amin', isDark),
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
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
        decoration: BoxDecoration(
          color: AppTheme.teal.withOpacity(0.1),
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: AppTheme.teal.withOpacity(0.3)),
        ),
        child: Text(
          label,
          style: const TextStyle(
            color: AppTheme.teal,
            fontSize: 10,
            fontWeight: FontWeight.bold,
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
  final _attendanceNameController = TextEditingController();
  final _attendanceAddressController = TextEditingController();
  final _attendanceMessageController = TextEditingController();
  final _attendanceFormKey = GlobalKey<FormState>();

  void _submitAttendance() async {
    if (!_attendanceFormKey.currentState!.validate()) return;
    
    try {
      final repository = ref.read(repositoryProvider);
      await repository.postLiveAttendance(
        _attendanceNameController.text,
        _attendanceAddressController.text,
        _attendanceMessageController.text,
      );
      
      _attendanceNameController.clear();
      _attendanceAddressController.clear();
      _attendanceMessageController.clear();
      
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Daftar hadir berhasil dikirim!')));
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal mengirim daftar hadir: $e')));
    }
  }

  @override
  void dispose() {
    _attendanceNameController.dispose();
    _attendanceAddressController.dispose();
    _attendanceMessageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(16),
      child: Form(
        key: _attendanceFormKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Text('Isi Daftar Hadir', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: isDark ? Colors.white : Colors.black)),
            const SizedBox(height: 16),
            TextFormField(
              controller: _attendanceNameController,
              decoration: InputDecoration(
                labelText: 'Nama Lengkap',
                border: const OutlineInputBorder(),
                fillColor: isDark ? Colors.white10 : Colors.grey[100],
                filled: true,
              ),
              style: TextStyle(color: isDark ? Colors.white : Colors.black),
              validator: (v) => (v == null || v.isEmpty) ? 'Nama wajib diisi' : null,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _attendanceAddressController,
              decoration: InputDecoration(
                labelText: 'Alamat',
                border: const OutlineInputBorder(),
                fillColor: isDark ? Colors.white10 : Colors.grey[100],
                filled: true,
              ),
              style: TextStyle(color: isDark ? Colors.white : Colors.black),
              validator: (v) => (v == null || v.isEmpty) ? 'Alamat wajib diisi' : null,
            ),
            const SizedBox(height: 12),
            TextFormField(
              controller: _attendanceMessageController,
              decoration: InputDecoration(
                labelText: 'Pesan / Doa (Opsional)',
                border: const OutlineInputBorder(),
                fillColor: isDark ? Colors.white10 : Colors.grey[100],
                filled: true,
              ),
              style: TextStyle(color: isDark ? Colors.white : Colors.black),
              maxLines: 3,
            ),
            const SizedBox(height: 24),
            ElevatedButton(
              onPressed: _submitAttendance,
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.teal,
                padding: const EdgeInsets.symmetric(vertical: 16),
              ),
              child: const Text('Kirim Kehadiran', style: TextStyle(color: Colors.white, fontSize: 16)),
            ),
          ],
        ),
      ),
    );
  }
}
