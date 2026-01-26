import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_staggered_grid_view/flutter_staggered_grid_view.dart';
import 'package:intl/intl.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/gallery_model.dart';
import '../../data/models/category_model.dart';
import '../providers/providers.dart';

class GalleryScreen extends ConsumerStatefulWidget {
  const GalleryScreen({super.key});

  @override
  ConsumerState<GalleryScreen> createState() => _GalleryScreenState();
}

class _GalleryScreenState extends ConsumerState<GalleryScreen> {
  final ScrollController _scrollController = ScrollController();
  int _currentPage = 1;
  bool _isLoadingMore = false;
  List<GalleryItem> _allItems = [];

  @override
  void initState() {
    super.initState();
    _scrollController.addListener(_onScroll);
  }

  void _onScroll() {
    if (_scrollController.position.pixels >= _scrollController.position.maxScrollExtent - 200) {
      _loadMore();
    }
  }

  Future<void> _loadMore() async {
    if (_isLoadingMore) return;
    
    // Check if we have more pages
    final asyncData = ref.read(paginatedGalleryProvider(_currentPage));
    if (asyncData.hasValue) {
      final lastPage = asyncData.value!['last_page'];
      if (_currentPage < lastPage) {
        setState(() => _isLoadingMore = true);
        _currentPage++;
        await ref.read(paginatedGalleryProvider(_currentPage).future);
        setState(() => _isLoadingMore = false);
      }
    }
  }

  @override
  void dispose() {
    _scrollController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final type = ref.watch(galleryTypeFilterProvider);
    final selectedCategoryId = ref.watch(galleryCategoryFilterProvider);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6),
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: Icon(Icons.arrow_back_ios, color: isDark ? Colors.white : Colors.black, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
        title: Text(
          'Galeri Dokumentasi',
          style: TextStyle(color: isDark ? Colors.white : Colors.black, fontSize: 20, fontWeight: FontWeight.bold),
        ),
        actions: [
          IconButton(
            icon: Icon(Icons.search, color: isDark ? Colors.white : Colors.black),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          // Tab Switcher
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
            child: Container(
              height: 50,
              padding: const EdgeInsets.all(4),
              decoration: BoxDecoration(
                color: isDark ? Colors.white.withOpacity(0.05) : Colors.black.withOpacity(0.05),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Row(
                children: [
                  Expanded(child: _buildTabItem('Foto', 'photo', type == 'photo', isDark)),
                  Expanded(child: _buildTabItem('Video', 'video', type == 'video', isDark)),
                ],
              ),
            ),
          ),

          // Categories Chips
          _buildCategoryFilter(selectedCategoryId, isDark),

          // Content
          Expanded(
            child: Consumer(
              builder: (context, ref, child) {
                final galleryAsync = ref.watch(paginatedGalleryProvider(1));
                
                return galleryAsync.when(
                  data: (data) {
                    final List<GalleryItem> items = data['items'];
                    if (items.isEmpty) {
                      return const Center(child: Text('Belum ada dokumentasi'));
                    }

                    if (type == 'photo') {
                      return _buildPhotoGrid(items, isDark);
                    } else {
                      return _buildVideoList(items, isDark);
                    }
                  },
                  loading: () => const Center(child: CircularProgressIndicator()),
                  error: (err, stack) => Center(child: Text('Error: $err')),
                );
              },
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTabItem(String label, String value, bool isSelected, bool isDark) {
    return GestureDetector(
      onTap: () {
        ref.read(galleryTypeFilterProvider.notifier).state = value;
        setState(() => _currentPage = 1);
      },
      child: Container(
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.teal : Colors.transparent,
          borderRadius: BorderRadius.circular(8),
          boxShadow: isSelected ? [BoxShadow(color: AppTheme.teal.withOpacity(0.3), blurRadius: 8, offset: const Offset(0, 2))] : [],
        ),
        alignment: Alignment.center,
        child: Text(
          label,
          style: TextStyle(
            color: isSelected ? Colors.black : (isDark ? Colors.grey : Colors.grey[600]),
            fontWeight: FontWeight.bold,
          ),
        ),
      ),
    );
  }

  Widget _buildCategoryFilter(int? selectedId, bool isDark) {
    final categoriesAsync = ref.watch(galleryCategoriesProvider);

    return categoriesAsync.when(
      data: (categories) {
        return Container(
          height: 50,
          margin: const EdgeInsets.symmetric(vertical: 8),
          child: ListView.builder(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            itemCount: categories.length + 1,
            itemBuilder: (context, index) {
              if (index == 0) {
                return _buildChip('Semua', null, selectedId == null, isDark);
              }
              final cat = categories[index - 1];
              return _buildChip(cat.name, cat.id, selectedId == cat.id, isDark);
            },
          ),
        );
      },
      loading: () => const SizedBox(height: 50),
      error: (_, __) => const SizedBox(height: 50),
    );
  }

  Widget _buildChip(String label, int? id, bool isSelected, bool isDark) {
    return Padding(
      padding: const EdgeInsets.only(right: 8.0),
      child: ChoiceChip(
        label: Text(label),
        selected: isSelected,
        onSelected: (val) {
          ref.read(galleryCategoryFilterProvider.notifier).state = id;
          setState(() => _currentPage = 1);
        },
        selectedColor: AppTheme.teal,
        backgroundColor: isDark ? Colors.white.withOpacity(0.1) : Colors.black.withOpacity(0.05),
        labelStyle: TextStyle(
          color: isSelected ? Colors.black : (isDark ? Colors.white70 : Colors.black87),
          fontSize: 12,
          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
        ),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        side: BorderSide.none,
        showCheckmark: false,
      ),
    );
  }

  Widget _buildPhotoGrid(List<GalleryItem> items, bool isDark) {
    return GridView.builder(
      controller: _scrollController,
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
        mainAxisSpacing: 12,
        crossAxisSpacing: 12,
        childAspectRatio: 1, // Square grid
      ),
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      itemBuilder: (context, index) {
        final item = items[index];
        return _buildPhotoCard(item, isDark);
      },
    );
  }

  Widget _buildPhotoCard(GalleryItem item, bool isDark) {
    return GestureDetector(
      onTap: () => _showImagePreview(item.firstImage),
      child: Hero(
        tag: 'gallery_${item.id}',
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(16),
            boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.1), blurRadius: 5)],
          ),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: Stack(
              fit: StackFit.expand,
              children: [
                Image.network(
                  item.firstImage,
                  fit: BoxFit.cover,
                  errorBuilder: (_, __, ___) => Container(color: Colors.grey, height: 150),
                ),
                Positioned(
                  bottom: 0,
                  left: 0,
                  right: 0,
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(
                      gradient: LinearGradient(
                        begin: Alignment.bottomCenter,
                        end: Alignment.topCenter,
                        colors: [Colors.black.withOpacity(0.8), Colors.transparent],
                      ),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Text(
                          item.title,
                          style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        if (item.eventDate != null)
                          Text(
                            DateFormat('dd MMM yyyy').format(item.eventDate!),
                            style: TextStyle(color: Colors.white.withOpacity(0.7), fontSize: 8),
                          ),
                    ],
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    ),
  );
}

  void _showImagePreview(String imageUrl) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        backgroundColor: Colors.transparent,
        insetPadding: EdgeInsets.zero,
        child: Stack(
          children: [
            GestureDetector(
              onTap: () => Navigator.pop(context),
              child: Container(
                width: double.infinity,
                height: double.infinity,
                color: Colors.black87,
              ),
            ),
            Center(
              child: InteractiveViewer(
                minScale: 0.5,
                maxScale: 4.0,
                child: Image.network(
                  imageUrl,
                  fit: BoxFit.contain,
                  loadingBuilder: (context, child, loadingProgress) {
                    if (loadingProgress == null) return child;
                    return const Center(child: CircularProgressIndicator(color: AppTheme.teal));
                  },
                ),
              ),
            ),
            Positioned(
              top: 40,
              right: 20,
              child: IconButton(
                icon: const Icon(Icons.close, color: Colors.white, size: 30),
                onPressed: () => Navigator.pop(context),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Future<void> _launchUrl(String urlString) async {
    final Uri url = Uri.parse(urlString);
    if (!await launchUrl(url, mode: LaunchMode.externalApplication)) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Tidak dapat membuka link')),
        );
      }
    }
  }

  Widget _buildVideoList(List<GalleryItem> items, bool isDark) {
    return ListView.builder(
      controller: _scrollController,
      padding: const EdgeInsets.all(16),
      itemCount: items.length,
      itemBuilder: (context, index) {
        final item = items[index];
        return Padding(
          padding: const EdgeInsets.only(bottom: 16.0),
          child: GestureDetector(
            onTap: () {
              if (item.videoUrl != null && item.videoUrl!.isNotEmpty) {
                _launchUrl(item.videoUrl!);
              }
            },
            child: Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: isDark ? Colors.white.withOpacity(0.05) : Colors.white,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppTheme.teal.withOpacity(0.1)),
              ),
              child: Row(
                children: [
                  Stack(
                    alignment: Alignment.center,
                    children: [
                      ClipRRect(
                        borderRadius: BorderRadius.circular(8),
                        child: Image.network(
                          item.firstImage,
                          width: 120,
                          height: 70,
                          fit: BoxFit.cover,
                          errorBuilder: (_, __, ___) => Container(color: Colors.grey, width: 120, height: 70),
                        ),
                      ),
                      Container(
                        decoration: const BoxDecoration(color: Colors.black26, shape: BoxShape.circle),
                        child: const Icon(Icons.play_circle_filled, color: AppTheme.teal, size: 36),
                      ),
                    ],
                  ),
                  const SizedBox(width: 16),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          item.title,
                          style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                          maxLines: 2,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 4),
                        Text(
                          item.description ?? 'Dokumentasi Video',
                          style: TextStyle(color: isDark ? Colors.grey[400] : Colors.grey[600], fontSize: 10),
                          maxLines: 1,
                          overflow: TextOverflow.ellipsis,
                        ),
                        const SizedBox(height: 8),
                        Text(
                          item.eventDate != null ? DateFormat('dd MMM yyyy').format(item.eventDate!) : '',
                          style: const TextStyle(color: AppTheme.teal, fontSize: 10, fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        );
      },
    );
  }
}
