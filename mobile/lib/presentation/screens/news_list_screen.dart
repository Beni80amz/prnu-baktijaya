import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/news_model.dart';
import '../../data/models/category_model.dart';
import '../providers/providers.dart';
import 'news_detail_screen.dart';

class NewsListScreen extends ConsumerStatefulWidget {
  const NewsListScreen({super.key});

  @override
  ConsumerState<NewsListScreen> createState() => _NewsListScreenState();
}

class _NewsListScreenState extends ConsumerState<NewsListScreen> {
  final TextEditingController _searchController = TextEditingController();
  final ScrollController _scrollController = ScrollController();
  int _currentPage = 1;
  bool _isLoadingMore = false;
  bool _hasMore = true;

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
  }

  @override
  void dispose() {
    _searchController.dispose();
    _scrollController.dispose();
    super.dispose();
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200 &&
        !_isLoadingMore &&
        _hasMore) {
      // Logic for infinite scroll would go here, 
      // but for simplicity in this implementation we'll stick to basic loading for now.
    }
  }

  @override
  Widget build(BuildContext context) {
    final newsAsync = ref.watch(paginatedNewsProvider(_currentPage));
    final categoriesAsync = ref.watch(categoriesProvider('news'));
    final selectedCategoryId = ref.watch(newsCategoryFilterProvider);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF102210) : const Color(0xFFF5F8F8),
      body: SafeArea(
        child: Column(
          children: [
            // Header
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                children: [
                  Row(
                    children: [
                      if (Navigator.canPop(context))
                        IconButton(
                          icon: const Icon(Icons.arrow_back_ios, color: AppTheme.teal, size: 20),
                          onPressed: () => Navigator.of(context).pop(),
                          padding: EdgeInsets.zero,
                          constraints: const BoxConstraints(),
                        ),
                      const SizedBox(width: 8),
                      const Text(
                        'Berita Ranting',
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: AppTheme.teal,
                        ),
                      ),
                      const Spacer(),
                      Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(
                          color: isDark ? Colors.grey[900] : Colors.white,
                          shape: BoxShape.circle,
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.05),
                              blurRadius: 10,
                            )
                          ],
                        ),
                        child: const Icon(Icons.tune, color: AppTheme.teal, size: 20),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  // Search Bar
                  TextField(
                    controller: _searchController,
                    onChanged: (val) {
                      ref.read(newsSearchProvider.notifier).state = val;
                      ref.invalidate(paginatedNewsProvider(_currentPage));
                    },
                    decoration: InputDecoration(
                      hintText: 'Cari berita atau kegiatan...',
                      prefixIcon: const Icon(Icons.search, color: Colors.grey),
                      filled: true,
                      fillColor: isDark ? Colors.grey[900] : Colors.white,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide.none,
                      ),
                      contentPadding: const EdgeInsets.symmetric(vertical: 12),
                    ),
                  ),
                  const SizedBox(height: 16),
                  // Categories
                  categoriesAsync.when(
                    data: (categories) => SingleChildScrollView(
                      scrollDirection: Axis.horizontal,
                      child: Row(
                        children: [
                          _buildCategoryChip(
                            label: 'Semua',
                            isSelected: selectedCategoryId == null,
                            onTap: () {
                              ref.read(newsCategoryFilterProvider.notifier).state = null;
                              ref.invalidate(paginatedNewsProvider(_currentPage));
                            },
                          ),
                          ...categories.map((cat) => _buildCategoryChip(
                                label: cat.name,
                                isSelected: selectedCategoryId == cat.id,
                                onTap: () {
                                  ref.read(newsCategoryFilterProvider.notifier).state = cat.id;
                                  ref.invalidate(paginatedNewsProvider(_currentPage));
                                },
                              )),
                        ],
                      ),
                    ),
                    loading: () => const SizedBox(height: 32, child: LinearProgressIndicator()),
                    error: (err, stack) => Text('Error loading categories: $err'),
                  ),
                ],
              ),
            ),

            // News List
            Expanded(
              child: newsAsync.when(
                data: (data) {
                  final List newsItems = data['data'] ?? [];
                  if (newsItems.isEmpty) {
                    return RefreshIndicator(
                      onRefresh: () async => ref.invalidate(paginatedNewsProvider(_currentPage)), // Force reload
                      child: SingleChildScrollView(
                        physics: const AlwaysScrollableScrollPhysics(),
                        child: SizedBox(
                          height: MediaQuery.of(context).size.height - 200,
                          child: const Center(child: Text('Tidak ada berita ditemukan')),
                        ),
                      ),
                    );
                  }
                  return RefreshIndicator(
                    onRefresh: () async {
                      // Invalidate current page to reload
                      return ref.refresh(paginatedNewsProvider(_currentPage).future);
                    },
                    child: ListView.builder(
                      controller: _scrollController,
                      physics: const AlwaysScrollableScrollPhysics(),
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: newsItems.length,
                      itemBuilder: (context, index) {
                        final news = News.fromJson(newsItems[index]);
                        // Alternating designs based on HTML
                        if (index < 3) {
                          return _buildLargeCard(context, news);
                        } else {
                          return _buildSmallCard(context, news);
                        }
                      },
                    ),
                  );
                },
                loading: () => const Center(child: CircularProgressIndicator()),
                error: (err, stack) => RefreshIndicator(
                  onRefresh: () async => ref.refresh(paginatedNewsProvider(_currentPage).future),
                  child: SingleChildScrollView(
                    physics: const AlwaysScrollableScrollPhysics(),
                    child: SizedBox(
                      height: MediaQuery.of(context).size.height - 200,
                      child: Center(child: Text('Gagal memuat berita: $err')),
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCategoryChip({required String label, required bool isSelected, required VoidCallback onTap}) {
    return Padding(
      padding: const EdgeInsets.only(right: 8.0),
      child: ChoiceChip(
        label: Text(
          label,
          style: TextStyle(
            color: isSelected ? Colors.white : Colors.grey[600],
            fontSize: 12,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.w600,
          ),
        ),
        selected: isSelected,
        onSelected: (_) => onTap(),
        selectedColor: AppTheme.teal,
        backgroundColor: Theme.of(context).brightness == Brightness.dark ? Colors.grey[900] : Colors.white,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        side: isSelected ? BorderSide.none : BorderSide(color: Colors.black.withOpacity(0.05)),
      ),
    );
  }

  Widget _buildLargeCard(BuildContext context, News news) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return GestureDetector(
      onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => NewsDetailScreen(news: news))),
      child: Container(
        margin: const EdgeInsets.only(bottom: 20),
        decoration: BoxDecoration(
          color: isDark ? Colors.grey[900] : Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 10,
              offset: const Offset(0, 4),
            )
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(20)),
              child: Stack(
                children: [
                  AspectRatio(
                    aspectRatio: 16 / 9,
                    child: news.image != null
                        ? Image.network(news.image!, fit: BoxFit.cover)
                        : Container(color: Colors.grey[300]),
                  ),
                  Positioned(
                    top: 12,
                    left: 12,
                    child: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                      decoration: BoxDecoration(
                        color: AppTheme.teal.withOpacity(0.9),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: const Text(
                        'KEGIATAN',
                        style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    news.title,
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, height: 1.2),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          const Icon(Icons.schedule, size: 14, color: Colors.grey),
                          const SizedBox(width: 4),
                          Text(
                            _formatDate(news.publishedAt),
                            style: const TextStyle(color: Colors.grey, fontSize: 12),
                          ),
                        ],
                      ),
                      const Icon(Icons.bookmark_outline, color: AppTheme.teal, size: 20),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSmallCard(BuildContext context, News news) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    return GestureDetector(
      onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => NewsDetailScreen(news: news))),
      child: Container(
        height: 100,
        margin: const EdgeInsets.only(bottom: 12),
        decoration: BoxDecoration(
          color: isDark ? Colors.grey[900] : Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 8,
              offset: const Offset(0, 2),
            )
          ],
        ),
        child: Row(
          children: [
            ClipRRect(
              borderRadius: const BorderRadius.only(topLeft: Radius.circular(16), bottomLeft: Radius.circular(16)),
              child: SizedBox(
                width: 100,
                child: news.image != null
                    ? Image.network(news.image!, fit: BoxFit.cover)
                    : Container(color: Colors.grey[300]),
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(12.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'WARTA',
                          style: TextStyle(color: AppTheme.teal, fontSize: 9, fontWeight: FontWeight.w800, letterSpacing: 0.5),
                        ),
                        const SizedBox(height: 4),
                        Text(
                          news.title,
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                          style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, height: 1.2),
                        ),
                      ],
                    ),
                    Text(
                      _formatDate(news.publishedAt),
                      style: const TextStyle(color: Colors.grey, fontSize: 10),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _formatDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr).toLocal();
      return DateFormat('dd MMM yyyy', 'id_ID').format(date);
    } catch (e) {
      return dateStr;
    }
  }
}
