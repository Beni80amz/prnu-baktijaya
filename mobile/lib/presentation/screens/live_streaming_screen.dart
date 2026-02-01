import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:youtube_player_flutter/youtube_player_flutter.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:share_plus/share_plus.dart';
import '../../core/theme/app_theme.dart';
import '../providers/providers.dart';
import '../../data/models/live_streaming_model.dart';

// ==========================================
// CONSTANTS & THEME
// ==========================================
class LiveStreamColors {
  static const Color primary = Color(0xFF11D452);
  static const Color accentGold = Color(0xFFD4AF37);
  static const Color backgroundLight = Color(0xFFF6F8F6);
  static const Color backgroundDark = Color(0xFF102216);
}

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
    ref.invalidate(liveStreamingProvider);
    await ref.read(liveStreamingProvider.future);
  }

  @override
  Widget build(BuildContext context) {
    final liveAsync = ref.watch(liveStreamingProvider);

    return liveAsync.when(
      data: (data) {
        return LiveStreamingPage(
          data: data, 
          onRefresh: _onRefresh,
        );
      },
      loading: () => const Scaffold(
        backgroundColor: LiveStreamColors.backgroundDark,
        body: Center(child: CircularProgressIndicator(color: LiveStreamColors.primary)),
      ),
      error: (err, _) => Scaffold(
        backgroundColor: LiveStreamColors.backgroundDark,
        body: RefreshIndicator(
          onRefresh: _onRefresh,
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            child: SizedBox(
              height: MediaQuery.of(context).size.height,
              child: Center(child: Text('Gagal memuat data: $err', style: const TextStyle(color: Colors.white))),
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
  YoutubePlayerController? _controller;
  late TabController _tabController;
  bool _hasValidVideo = false;
  Key _playerKey = UniqueKey();

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 2, vsync: this);
    _initializePlayer(autoPlay: false);
  }

  void _initializePlayer({bool autoPlay = false}) {
    final videoId = widget.data.video.youtubeId?.trim();
    
    if (videoId != null && videoId.isNotEmpty) {
      _hasValidVideo = true;
      _controller = YoutubePlayerController(
        initialVideoId: videoId,
        flags: YoutubePlayerFlags(
          autoPlay: autoPlay,
          mute: false,
          isLive: widget.data.video.isLive, 
          forceHD: false,
          enableCaption: false,
          controlsVisibleAtStart: true,
        ),
      );
    }
  }

  @override
  void dispose() {
    _controller?.dispose();
    _tabController.dispose();
    super.dispose();
  }

  // ==========================================
  // FIXED LAYOUT & PLAY BUTTON
  // ==========================================
  bool _isPlaying = false;
  bool _isPlayerReady = false;

  void _onManualPlay() {
    setState(() {
      _isPlaying = true;
    });
    if (_isPlayerReady) {
      _controller?.play();
    }
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final bgColor = isDark ? LiveStreamColors.backgroundDark : LiveStreamColors.backgroundLight;
    final textColor = isDark ? Colors.white : Colors.black;

    return Scaffold(
      backgroundColor: bgColor,
      body: SafeArea(
        child: Column(
          children: [
            _buildHeader(context, isDark, widget.data.video.youtubeUrl),
            Expanded(
              child: NestedScrollView(
                headerSliverBuilder: (context, innerBoxIsScrolled) {
                  return [
                    SliverToBoxAdapter(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildVideoSection(),
                          _buildEventInfo(isDark, textColor),
                        ],
                      ),
                    ),
                    SliverPersistentHeader(
                      delegate: _SliverAppBarDelegate(
                        Container(
                          color: bgColor,
                          child: _buildTabBar(isDark, textColor),
                        ),
                      ),
                      pinned: true,
                    ),
                  ];
                },
                body: TabBarView(
                  controller: _tabController,
                  children: [
                    LiveChatTab(upcoming: widget.data.upcoming),
                    const AttendanceTab(),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context, bool isDark, String? url) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
      color: isDark ? LiveStreamColors.backgroundDark : LiveStreamColors.backgroundLight,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          CircleAvatar(
            backgroundColor: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.05),
            radius: 20,
            child: IconButton(
              icon: Icon(Icons.chevron_left, color: isDark ? Colors.white : Colors.black),
              onPressed: () => Navigator.pop(context),
            ),
          ),
          Text(
            'Live Streaming',
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: isDark ? Colors.white : Colors.black,
            ),
          ),
          CircleAvatar(
            backgroundColor: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.05),
            radius: 20,
            child: IconButton(
              icon: Icon(Icons.share, color: isDark ? Colors.white : Colors.black, size: 20),
              onPressed: () {
                if (url != null) {
                  Share.share('Ayo tonton siaran langsung ini: $url');
                }
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildVideoSection() {
    if (!_hasValidVideo) {
       return AspectRatio(
        aspectRatio: 16 / 9,
        child: Container(
          color: Colors.black,
          child: const Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(Icons.videocam_off, color: Colors.white54, size: 48),
                SizedBox(height: 12),
                Text('Video tidak tersedia', style: TextStyle(color: Colors.white70)),
              ],
            ),
          ),
        ),
      );
    }

    return AspectRatio(
      aspectRatio: 16 / 9,
      child: Stack(
        alignment: Alignment.center,
        children: [
           YoutubePlayer(
            controller: _controller!,
            showVideoProgressIndicator: true,
            progressIndicatorColor: LiveStreamColors.primary,
            onReady: () {
              _isPlayerReady = true;
              _controller!.addListener(() {
                if (mounted) {
                   if (_controller!.value.isPlaying != _isPlaying) {
                      setState(() => _isPlaying = _controller!.value.isPlaying);
                   }
                }
              });
            },
            onEnded: (meta) {
               setState(() => _isPlaying = false);
            },
          ),
          
          if (!_isPlaying)
            GestureDetector(
              onTap: _onManualPlay,
              behavior: HitTestBehavior.opaque,
              child: Container(
                width: double.infinity,
                height: double.infinity,
                color: Colors.black.withOpacity(0.4),
                child: Center(
                  child: Container(
                    width: 72,
                    height: 72,
                    decoration: BoxDecoration(
                      color: Colors.black.withOpacity(0.6),
                      shape: BoxShape.circle,
                      border: Border.all(color: Colors.white, width: 3),
                      boxShadow: [
                        BoxShadow(
                          color: Colors.black.withOpacity(0.3),
                          blurRadius: 10,
                          spreadRadius: 2,
                        )
                      ]
                    ),
                    child: const Icon(Icons.play_arrow_rounded, color: Colors.white, size: 48),
                  ),
                ),
              ),
            ),
          
          Positioned(
            top: 12,
            left: 12,
            child: IgnorePointer( 
              child: Row(
                children: [
                  if (widget.data.video.isLive)
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: Colors.red[600],
                        borderRadius: BorderRadius.circular(4),
                      ),
                      child: Row(
                        children: [
                           Container(
                             width: 6,
                             height: 6,
                             decoration: const BoxDecoration(
                               color: Colors.white,
                               shape: BoxShape.circle,
                             ),
                           ),
                           const SizedBox(width: 6),
                           const Text(
                             'LIVE',
                             style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                           ),
                        ],
                      ),
                    ),
                  const SizedBox(width: 8),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.black.withOpacity(0.6),
                      borderRadius: BorderRadius.circular(4),
                    ),
                    child: const Row(
                      children: [
                        Icon(Icons.visibility, color: Colors.white, size: 12),
                        SizedBox(width: 4),
                        Text(
                          '1.2k Menonton', // Mock data
                          style: TextStyle(color: Colors.white, fontSize: 10),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildEventInfo(bool isDark, Color textColor) {
    final info = widget.data.info;
    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            info.title,
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              height: 1.3,
              color: textColor,
            ),
          ),
          const SizedBox(height: 12),
          Row(
            children: [
               Container(
                 width: 40,
                 height: 40,
                 decoration: BoxDecoration(
                   shape: BoxShape.circle,
                   border: Border.all(color: LiveStreamColors.primary, width: 1.5),
                   image: DecorationImage(
                     image: NetworkImage(info.speakerAvatar ?? 'https://via.placeholder.com/150'),
                     fit: BoxFit.cover,
                     onError: (_, __) {},
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
                         Flexible(
                           child: Text(
                             info.channelName, // Or Speaker Name if available in future
                               style: TextStyle(
                               fontSize: 14,
                               fontWeight: FontWeight.w600,
                               color: textColor,
                             ),
                           ),
                         ),
                         const SizedBox(width: 4),
                         const Icon(Icons.verified, color: LiveStreamColors.accentGold, size: 14),
                       ],
                     ),
                     Text(
                       'PRNU Baktijaya • Sukmajaya, Depok', // Hardcoded location as per plan
                       style: TextStyle(
                         fontSize: 11,
                         color: isDark ? Colors.white.withOpacity(0.6) : Colors.black.withOpacity(0.6),
                       ),
                     ),
                   ],
                 ),
               ),
            ],
          ),
          const SizedBox(height: 8),
          Divider(color: isDark ? Colors.white.withOpacity(0.1) : Colors.black.withOpacity(0.1)),
        ],
      ),
    );
  }

  Widget _buildTabBar(bool isDark, Color textColor) {
    return Container(
      decoration: BoxDecoration(
        border: Border(bottom: BorderSide(color: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.05))),
      ),
      child: TabBar(
        controller: _tabController,
        labelColor: LiveStreamColors.primary,
        unselectedLabelColor: isDark ? Colors.grey[500] : Colors.grey[600],
        indicatorColor: LiveStreamColors.primary,
        indicatorSize: TabBarIndicatorSize.tab,
        labelStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
        tabs: const [
          Tab(text: 'Live Chat'),
          Tab(text: 'Daftar Hadir'),
        ],
      ),
    );
  }
}

// Delegate for Sticky Header
class _SliverAppBarDelegate extends SliverPersistentHeaderDelegate {
  final Widget _child;
  _SliverAppBarDelegate(this._child);

  @override
  double get minExtent => 48.0;
  @override
  double get maxExtent => 48.0;
  @override
  Widget build(BuildContext context, double shrinkOffset, bool overlapsContent) {
    return _child;
  }
  @override
  bool shouldRebuild(_SliverAppBarDelegate oldDelegate) => true;
}


// ==========================================
// 3. LIVE CHAT TAB
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
      ref.invalidate(liveChatsProvider);
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Gagal mengirim pesan: $e')));
      }
    }
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
            child: CustomScrollView(
              slivers: [
                chatsAsync.when(
                  loading: () => const SliverFillRemaining(
                    child: Center(child: CircularProgressIndicator()),
                  ),
                  error: (err, _) => const SliverFillRemaining(
                    child: Center(child: Text('Gagal memuat chat', style: TextStyle(color: Colors.grey))),
                  ),
                  data: (chats) {
                    if (chats.isEmpty) {
                      return const SliverToBoxAdapter(
                        child: SizedBox(
                          height: 200,
                          child: Center(child: Text('Belum ada pesan.', style: TextStyle(color: Colors.grey))),
                        ),
                      );
                    }
                    return SliverList(
                      delegate: SliverChildBuilderDelegate(
                        (context, index) {
                          final chat = chats[index];
                           // Determine avatar color roughly based on name hash or stored color
                          Color avatarColor = Colors.grey; 
                          if (chat.avatarColor.contains('red')) avatarColor = Colors.red;
                          else if (chat.avatarColor.contains('blue')) avatarColor = Colors.blue;
                          else if (chat.avatarColor.contains('green')) avatarColor = Colors.green;
                          else if (chat.avatarColor.contains('yellow')) avatarColor = Colors.orange;
                          else if (chat.avatarColor.contains('purple')) avatarColor = Colors.purple;
                          
                          final isAdmin = chat.name.toLowerCase().contains('admin');
                          final startName = chat.name.length > 0 ? chat.name[0] : '?';

                          return isAdmin 
                          ? _buildAdminMessage(chat, isDark)
                          : _buildUserMessage(chat, isDark, avatarColor, startName);
                        },
                        childCount: chats.length,
                      ),
                    );
                  },
                ),
                
                // UPCOMING SECTION
                if (widget.upcoming.isNotEmpty) 
                  SliverToBoxAdapter(
                    child: Padding(
                      padding: const EdgeInsets.only(top: 24, bottom: 80),
                      child: _buildUpcomingSection(isDark),
                    ),
                  ),
              ],
            ),
          ),
        ),
        
        // INPUT AREA
        _buildChatInput(isDark),
      ],
    );
  }

  Widget _buildUserMessage(LiveChatModel chat, bool isDark, Color color, String initials) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          CircleAvatar(
            radius: 16,
            backgroundColor: isDark ? Colors.white10 : Colors.grey[200],
            child: Text(
              initials.toUpperCase(),
              style: TextStyle(
                fontSize: 11,
                fontWeight: FontWeight.bold,
                color: isDark ? Colors.white : Colors.black87,
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
                      chat.name,
                      style: const TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: LiveStreamColors.primary,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      chat.createdAt,
                      style: TextStyle(
                        fontSize: 10,
                        color: Colors.grey[500],
                        fontWeight: FontWeight.normal,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.03),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    chat.message,
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

  Widget _buildAdminMessage(LiveChatModel chat, bool isDark) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 32,
            height: 32,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              color: LiveStreamColors.accentGold.withOpacity(0.2),
              border: Border.all(color: LiveStreamColors.accentGold.withOpacity(0.3)),
            ),
            child: const Center(
              child: Text(
                'M',
                style: TextStyle(color: LiveStreamColors.accentGold, fontWeight: FontWeight.bold, fontSize: 12),
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
                    const Text(
                      'Admin Baktijaya',
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: LiveStreamColors.accentGold,
                      ),
                    ),
                    const SizedBox(width: 4),
                    const Icon(Icons.stars, color: LiveStreamColors.accentGold, size: 12),
                     const SizedBox(width: 8),
                    Text(
                      chat.createdAt,
                      style: TextStyle(
                        fontSize: 10,
                        color: Colors.grey[500],
                        fontWeight: FontWeight.normal,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: LiveStreamColors.accentGold.withOpacity(0.1),
                    border: Border.all(color: LiveStreamColors.accentGold.withOpacity(0.2)),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Text(
                    chat.message,
                    style: TextStyle(
                      fontSize: 13,
                      height: 1.4,
                      fontStyle: FontStyle.italic,
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
        color: isDark ? LiveStreamColors.backgroundDark : LiveStreamColors.backgroundLight,
        border: Border(top: BorderSide(color: isDark ? Colors.white10 : Colors.black12)),
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
                    hintStyle: TextStyle(color: isDark ? Colors.grey[600] : Colors.grey[400], fontSize: 13),
                    fillColor: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.05),
                    filled: true,
                    contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 12),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
              ),
              const SizedBox(width: 8),
              GestureDetector(
                onTap: _sendChat,
                child: Container(
                  width: 44,
                  height: 44,
                  decoration: BoxDecoration(
                    color: LiveStreamColors.primary,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: LiveStreamColors.primary.withOpacity(0.3),
                        blurRadius: 8,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: const Icon(Icons.send, color: Colors.white, size: 20),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          SizedBox(
            height: 28,
            child: ListView(
              scrollDirection: Axis.horizontal,
              children: [
                _buildChip('🤲 Kirim Doa', isDark),
                _buildChip('✨ Sholawat', isDark),
                _buildChip('👏 Amin', isDark),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildChip(String label, bool isDark) {
    return GestureDetector(
      onTap: () {
        _chatController.text = label;
      },
      child: Container(
        margin: const EdgeInsets.only(right: 8),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 0),
        decoration: BoxDecoration(
          color: LiveStreamColors.primary.withOpacity(0.1),
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: LiveStreamColors.primary.withOpacity(0.2)),
        ),
        child: Center(
          child: Text(
            label,
            style: const TextStyle(
              fontSize: 10,
              fontWeight: FontWeight.bold,
              color: LiveStreamColors.primary,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildUpcomingSection(bool isDark) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.symmetric(vertical: 16),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                'JADWAL LIVE MENDATANG',
                style: TextStyle(
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                  color: isDark ? Colors.grey[400] : Colors.grey[600],
                  letterSpacing: 0.5,
                ),
              ),
              const Text(
                'Lihat Semua',
                style: TextStyle(
                  fontSize: 11,
                  fontWeight: FontWeight.bold,
                  color: LiveStreamColors.primary,
                ),
              ),
            ],
          ),
        ),
        SizedBox(
          height: 280,
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            itemCount: widget.upcoming.length,
            itemBuilder: (context, index) {
              final schedule = widget.upcoming[index];
              return Container(
                width: 240,
                margin: const EdgeInsets.only(right: 16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Stack(
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(12),
                          child: AspectRatio(
                            aspectRatio: 16/9,
                            child: Image.network(
                              schedule.thumbnail,
                              fit: BoxFit.cover,
                              errorBuilder: (_,__,___) => Container(color: Colors.grey[800]),
                            ),
                          ),
                        ),
                        Positioned(
                          top: 8,
                          right: 8,
                          child: Container(
                            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                            decoration: BoxDecoration(
                              color: Colors.black.withOpacity(0.7),
                              borderRadius: BorderRadius.circular(6),
                            ),
                            child: Text(
                              schedule.scheduledStart,
                              style: const TextStyle(color: Colors.white, fontSize: 9, fontWeight: FontWeight.bold),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text(
                      schedule.title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: isDark ? Colors.white : Colors.black,
                        height: 1.2,
                      ),
                    ),
                    const SizedBox(height: 2),
                    Text(
                      'Bersama Ustadz ...', // Placeholder as per design or data
                      style: TextStyle(
                        fontSize: 10,
                        color: isDark ? Colors.white.withOpacity(0.5) : Colors.black.withOpacity(0.5),
                      ),
                    ),
                    const SizedBox(height: 8),
                    Container(
                      width: double.infinity,
                      padding: const EdgeInsets.symmetric(vertical: 6),
                      decoration: BoxDecoration(
                        color: LiveStreamColors.primary.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: const Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        children: [
                          Icon(Icons.notifications_active, size: 12, color: LiveStreamColors.primary),
                          SizedBox(width: 4),
                          Text(
                            'Ingatkan Saya',
                            style: TextStyle(
                              fontSize: 10,
                              fontWeight: FontWeight.bold,
                              color: LiveStreamColors.primary,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              );
            },
          ),
        ),
      ],
    );
  }
}

// ==========================================
// 4. ATTENDANCE TAB
// ==========================================
class AttendanceTab extends StatefulWidget {
  const AttendanceTab({super.key});

  @override
  State<AttendanceTab> createState() => _AttendanceTabState();
}

class _AttendanceTabState extends State<AttendanceTab> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _regionController = TextEditingController();
  bool _isSubmitting = false;

  void _submitAttendance() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isSubmitting = true);
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
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final textColor = isDark ? Colors.white : Colors.black;

    return SingleChildScrollView(
      padding: const EdgeInsets.all(24),
      child: Form(
        key: _formKey,
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'Daftar Hadir Jamaah',
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: textColor),
            ),
            const SizedBox(height: 8),
            Text(
              'Silakan isi form di bawah ini untuk mendata kehadiran Anda.',
              style: TextStyle(color: isDark ? Colors.grey[400] : Colors.grey[600], fontSize: 13, height: 1.5),
            ),
            const SizedBox(height: 24),
            _buildTextField('Nama Lengkap', _nameController, isDark),
            const SizedBox(height: 16),
            _buildTextField('Asal Wilayah', _regionController, isDark),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              height: 48,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submitAttendance,
                style: ElevatedButton.styleFrom(
                  backgroundColor: LiveStreamColors.primary,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  elevation: 4,
                  shadowColor: LiveStreamColors.primary.withOpacity(0.4),
                ),
                child: _isSubmitting
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
                    : const Text('Kirim Kehadiran', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTextField(String label, TextEditingController controller, bool isDark) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(
          label,
          style: TextStyle(
            fontSize: 12,
            fontWeight: FontWeight.bold,
            color: isDark ? Colors.grey[300] : Colors.grey[700],
          ),
        ),
        const SizedBox(height: 8),
        TextFormField(
          controller: controller,
          style: TextStyle(color: isDark ? Colors.white : Colors.black),
          decoration: InputDecoration(
            filled: true,
            fillColor: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.05),
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          ),
          validator: (value) => value!.isEmpty ? 'Wajib diisi' : null,
        ),
      ],
    );
  }
}
