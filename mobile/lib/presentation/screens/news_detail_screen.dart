import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:share_plus/share_plus.dart';
import 'package:flutter_html/flutter_html.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/news_model.dart';
import '../../data/models/news_comment_model.dart';
import '../providers/providers.dart';

class NewsDetailScreen extends ConsumerStatefulWidget {
  final News news;

  const NewsDetailScreen({super.key, required this.news});

  @override
  ConsumerState<NewsDetailScreen> createState() => _NewsDetailScreenState();
}

class _NewsDetailScreenState extends ConsumerState<NewsDetailScreen> {
  final TextEditingController _commentController = TextEditingController();
  final TextEditingController _nameController = TextEditingController();
  bool _isSending = false;

  @override
  void dispose() {
    _commentController.dispose();
    _nameController.dispose();
    super.dispose();
  }

  Future<void> _sendComment() async {
    if (_commentController.text.trim().isEmpty) return;

    setState(() => _isSending = true);
    try {
      final repository = ref.read(repositoryProvider);
      await repository.postComment(
        widget.news.id,
        _nameController.text.trim().isEmpty ? 'Anonim' : _nameController.text.trim(),
        _commentController.text.trim(),
      );
      _commentController.clear();
      // Wait for backend to process and then refresh
      ref.invalidate(commentsProvider(widget.news.id));
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Komentar berhasil dikirim')),
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengirim komentar: $e')),
        );
      }
    } finally {
      if (mounted) setState(() => _isSending = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final newsAsync = ref.watch(newsProvider);
    final commentsAsync = ref.watch(commentsProvider(widget.news.id));
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF102210) : const Color(0xFFF6F8F6),
      body: Stack(
        children: [
          CustomScrollView(
            slivers: [
              // Navigation Bar
              SliverAppBar(
                pinned: true,
                floating: false,
                backgroundColor: (isDark ? const Color(0xFF102210) : const Color(0xFFF6F8F6)).withOpacity(0.8),
                elevation: 0,
                leading: IconButton(
                  icon: const Icon(Icons.arrow_back_ios, size: 20),
                  onPressed: () => Navigator.pop(context),
                  color: isDark ? Colors.white : Colors.black,
                ),
                title: const Text(
                  'Detail Berita',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                centerTitle: true,
                actions: [
                  IconButton(
                    icon: const Icon(Icons.bookmark_outline),
                    onPressed: () {},
                    color: isDark ? Colors.white : Colors.black,
                  ),
                ],
              ),

              // Header Image
              SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(16),
                    child: AspectRatio(
                      aspectRatio: 16 / 9,
                      child: widget.news.image != null
                          ? Image.network(
                              widget.news.image!,
                              fit: BoxFit.cover,
                              errorBuilder: (context, error, stackTrace) => Container(
                                color: Colors.grey[300],
                                child: const Icon(Icons.image, size: 50, color: Colors.grey),
                              ),
                            )
                          : Container(color: Colors.grey[300]),
                    ),
                  ),
                ),
              ),

              // Content Section
              SliverPadding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 16.0),
                sliver: SliverList(
                  delegate: SliverChildListDelegate([
                    // Category Tag
                    Align(
                      alignment: Alignment.centerLeft,
                      child: Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                        decoration: BoxDecoration(
                          color: AppTheme.teal,
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: const Text(
                          'BERITA',
                          style: TextStyle(
                            color: Colors.white,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 12),

                    // Title
                    Text(
                      widget.news.title,
                      style: TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                        color: isDark ? Colors.white : const Color(0xFF0D1B0D),
                        height: 1.2,
                      ),
                    ),
                    const SizedBox(height: 12),

                    // Meta Info
                    Text(
                      '${_formatDate(widget.news.publishedAt)} • Oleh Admin PRNU',
                      style: const TextStyle(
                        color: AppTheme.teal,
                        fontSize: 12,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                    const SizedBox(height: 16),

                    // Share Buttons
                    Row(
                      children: [
                        const Text(
                          'Bagikan:',
                          style: TextStyle(
                            fontSize: 14,
                            fontWeight: FontWeight.bold,
                            color: Colors.grey,
                          ),
                        ),
                        const SizedBox(width: 12),
                        _buildShareButton(
                          icon: Icons.chat,
                          color: const Color(0xFF25D366),
                          onTap: () => _shareContent(context, widget.news),
                        ),
                        const SizedBox(width: 8),
                        _buildShareButton(
                          icon: Icons.facebook,
                          color: const Color(0xFF1877F2),
                          onTap: () => _shareContent(context, widget.news),
                        ),
                        const SizedBox(width: 8),
                        _buildShareButton(
                          icon: Icons.share,
                          color: isDark ? Colors.grey[800]! : Colors.grey[200]!,
                          iconColor: isDark ? Colors.white : Colors.black,
                          onTap: () => _shareContent(context, widget.news),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),

                    // Body with HTML rendering
                    Html(
                      data: widget.news.content ?? widget.news.excerpt ?? '',
                      style: {
                        "body": Style(
                          fontSize: FontSize(16),
                          color: isDark ? Colors.grey[300] : const Color(0xFF0D1B0D),
                          lineHeight: LineHeight.em(1.6),
                          margin: Margins.zero,
                          padding: HtmlPaddings.zero,
                        ),
                        "blockquote": Style(
                          padding: HtmlPaddings.all(16),
                          backgroundColor: AppTheme.teal.withOpacity(0.05),
                          border: const Border(left: BorderSide(color: AppTheme.teal, width: 4)),
                          fontStyle: FontStyle.italic,
                          color: isDark ? Colors.grey[400] : Colors.grey[700],
                        ),
                      },
                    ),
                    const SizedBox(height: 32),

                    // Related News Section
                    const Divider(),
                    const SizedBox(height: 24),
                    const Text(
                      'Berita Terkait',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 16),
                    newsAsync.when(
                      data: (list) {
                        final related = list.where((e) => e.id != widget.news.id).take(2).toList();
                        return Column(
                          children: related.map((item) => _buildRelatedItem(context, item)).toList(),
                        );
                      },
                      loading: () => const Center(child: CircularProgressIndicator()),
                      error: (err, stack) => const SizedBox.shrink(),
                    ),
                    const SizedBox(height: 40),

                    // Comments Section
                    commentsAsync.when(
                      data: (comments) => Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                'Komentar (${comments.length})',
                                style: const TextStyle(
                                  fontSize: 20,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              const Icon(Icons.forum, color: Colors.grey),
                            ],
                          ),
                          const SizedBox(height: 20),
                          if (comments.isEmpty)
                            const Center(
                              child: Padding(
                                padding: EdgeInsets.symmetric(vertical: 20),
                                child: Text('Belum ada komentar. Jadilah yang pertama!', style: TextStyle(color: Colors.grey)),
                              ),
                            ),
                          ...comments.map((c) => Padding(
                                padding: const EdgeInsets.only(bottom: 16.0),
                                child: _buildCommentItem(
                                  c.name ?? 'Anonim',
                                  (c.name ?? 'A').substring(0, 1).toUpperCase(),
                                  c.comment,
                                  _formatCommentDate(c.createdAt),
                                ),
                              )),
                        ],
                      ),
                      loading: () => const Center(child: CircularProgressIndicator()),
                      error: (err, stack) => Text('Gagal memuat komentar: $err'),
                    ),
                    const SizedBox(height: 160), // Extra height for sticky input
                  ]),
                ),
              ),
            ],
          ),

          // Sticky Comment Input
          Positioned(
            bottom: 0,
            left: 0,
            right: 0,
            child: Container(
              padding: EdgeInsets.fromLTRB(16, 12, 16, MediaQuery.of(context).padding.bottom + 12),
              decoration: BoxDecoration(
                color: isDark ? const Color(0xFF102210) : const Color(0xFFF6F8F6),
                border: Border(top: BorderSide(color: isDark ? Colors.white10 : Colors.black.withOpacity(0.05))),
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: [
                  // Name field for guests
                  Container(
                    margin: const EdgeInsets.only(bottom: 8),
                    padding: const EdgeInsets.symmetric(horizontal: 16),
                    decoration: BoxDecoration(
                      color: isDark ? Colors.grey[900] : Colors.white,
                      borderRadius: BorderRadius.circular(30),
                      border: Border.all(color: isDark ? Colors.white10 : Colors.black.withOpacity(0.1)),
                    ),
                    child: TextField(
                      controller: _nameController,
                      style: TextStyle(fontSize: 14, color: isDark ? Colors.white : Colors.black),
                      decoration: const InputDecoration(
                        hintText: 'Nama Anda (opsional, default: Anonim)',
                        border: InputBorder.none,
                        hintStyle: TextStyle(fontSize: 13),
                        contentPadding: EdgeInsets.symmetric(vertical: 10),
                      ),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
                    decoration: BoxDecoration(
                      color: isDark ? Colors.grey[900] : Colors.white,
                      borderRadius: BorderRadius.circular(30),
                      border: Border.all(color: isDark ? Colors.white10 : Colors.black.withOpacity(0.1)),
                    ),
                    child: Row(
                      children: [
                        Expanded(
                          child: TextField(
                            controller: _commentController,
                            decoration: const InputDecoration(
                              hintText: 'Tulis komentar...',
                              border: InputBorder.none,
                              hintStyle: TextStyle(fontSize: 14),
                            ),
                          ),
                        ),
                        InkWell(
                          onTap: _isSending ? null : _sendComment,
                          child: Container(
                            padding: const EdgeInsets.all(8),
                            decoration: BoxDecoration(
                              color: _isSending ? Colors.grey : AppTheme.teal,
                              shape: BoxShape.circle,
                            ),
                            child: _isSending
                                ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                                : const Icon(Icons.send, color: Colors.white, size: 18),
                          ),
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

  Widget _buildShareButton({required IconData icon, required Color color, Color iconColor = Colors.white, required VoidCallback onTap}) {
    return InkWell(
      onTap: onTap,
      child: Container(
        width: 36,
        height: 36,
        decoration: BoxDecoration(
          color: color,
          shape: BoxShape.circle,
        ),
        child: Icon(icon, color: iconColor, size: 18),
      ),
    );
  }

  Widget _buildRelatedItem(BuildContext context, News item) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Theme.of(context).brightness == Brightness.dark ? Colors.grey[900] : Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(8),
            child: SizedBox(
              width: 80,
              height: 80,
              child: item.image != null
                  ? Image.network(item.image!, fit: BoxFit.cover)
                  : Container(color: Colors.grey[300]),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'BERITA',
                  style: TextStyle(
                    color: AppTheme.teal,
                    fontSize: 8,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  item.title,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  _formatDate(item.publishedAt),
                  style: const TextStyle(
                    fontSize: 10,
                    color: Colors.grey,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCommentItem(String name, String initial, String comment, String time) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          width: 40,
          height: 40,
          decoration: BoxDecoration(
            color: AppTheme.teal.withOpacity(0.1),
            shape: BoxShape.circle,
          ),
          child: Center(
            child: Text(
              initial,
              style: const TextStyle(color: AppTheme.teal, fontWeight: FontWeight.bold),
            ),
          ),
        ),
        const SizedBox(width: 12),
        Expanded(
          child: Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(
              color: Colors.grey.withOpacity(0.05),
              borderRadius: const BorderRadius.only(
                topRight: Radius.circular(16),
                bottomRight: Radius.circular(16),
                bottomLeft: Radius.circular(16),
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(name, style: const TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                    Text(time, style: const TextStyle(fontSize: 10, color: Colors.grey)),
                  ],
                ),
                const SizedBox(height: 4),
                Text(
                  comment,
                  style: const TextStyle(fontSize: 12, height: 1.4),
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  void _shareContent(BuildContext context, News news) {
    Share.share(
      '${news.title}\n\nBaca selengkapnya di PRNU Baktijaya',
      subject: news.title,
    );
  }

  String _formatDate(String dateStr) {
    try {
      if (dateStr.isEmpty) return '';
      final date = DateTime.parse(dateStr).toLocal();
      return DateFormat('dd MMMM yyyy', 'id_ID').format(date);
    } catch (e) {
      return dateStr;
    }
  }
  String _formatCommentDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr).toLocal();
      final now = DateTime.now();
      final difference = now.difference(date);

      if (difference.inMinutes < 60) {
        return '${difference.inMinutes} menit yang lalu';
      } else if (difference.inHours < 24) {
        return '${difference.inHours} jam yang lalu';
      } else {
        return DateFormat('dd MMM yyyy', 'id_ID').format(date);
      }
    } catch (e) {
      return dateStr;
    }
  }
}
