import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../../core/theme/app_theme.dart';
import '../../core/constants/api_constants.dart';
import '../providers/providers.dart';

// Provider for articles data
final articlesProvider = FutureProvider<List<dynamic>>((ref) async {
  final dioClient = ref.read(dioClientProvider);
  final response = await dioClient.dio.get(ApiConstants.artikel);
  return response.data['data'] ?? [];
});

class ArticleListScreen extends ConsumerStatefulWidget {
  const ArticleListScreen({super.key});

  @override
  ConsumerState<ArticleListScreen> createState() => _ArticleListScreenState();
}

class _ArticleListScreenState extends ConsumerState<ArticleListScreen> {
  final TextEditingController _searchController = TextEditingController();
  String _searchQuery = '';
  String? _selectedType;

  final List<String> _types = ['opini', 'khutbah', 'kajian'];

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final articlesAsync = ref.watch(articlesProvider);
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
                      IconButton(
                        icon: const Icon(Icons.arrow_back_ios, color: AppTheme.teal, size: 20),
                        onPressed: () {
                          if (Navigator.canPop(context)) {
                            Navigator.pop(context);
                          }
                        },
                        padding: EdgeInsets.zero,
                        constraints: const BoxConstraints(),
                      ),
                      const SizedBox(width: 8),
                      const Text(
                        'Artikel & Opini',
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: AppTheme.teal,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  // Search Bar
                  TextField(
                    controller: _searchController,
                    onChanged: (val) => setState(() => _searchQuery = val.toLowerCase()),
                    decoration: InputDecoration(
                      hintText: 'Cari artikel...',
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
                  const SizedBox(height: 12),
                  // Type Filters
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        _buildFilterChip(null, 'Semua'),
                        ..._types.map((type) => _buildFilterChip(type, _capitalize(type))),
                      ],
                    ),
                  ),
                ],
              ),
            ),

            // Articles List
            Expanded(
              child: articlesAsync.when(
                data: (articles) {
                  final filtered = articles.where((article) {
                    final title = (article['title'] ?? '').toString().toLowerCase();
                    final matchesSearch = title.contains(_searchQuery);
                    final matchesType = _selectedType == null || article['type'] == _selectedType;
                    return matchesSearch && matchesType;
                  }).toList();

                  if (filtered.isEmpty) {
                    return RefreshIndicator(
                      onRefresh: () async => ref.refresh(articlesProvider.future),
                      child: SingleChildScrollView(
                        physics: const AlwaysScrollableScrollPhysics(),
                        child: SizedBox(
                          height: MediaQuery.of(context).size.height - 200,
                          child: Center(
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(Icons.menu_book, size: 64, color: Colors.grey[400]),
                                const SizedBox(height: 16),
                                const Text('Tidak ada artikel ditemukan', style: TextStyle(color: Colors.grey)),
                              ],
                            ),
                          ),
                        ),
                      ),
                    );
                  }

                  return RefreshIndicator(
                    onRefresh: () async => ref.refresh(articlesProvider.future),
                    child: ListView.builder(
                      physics: const AlwaysScrollableScrollPhysics(),
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      itemCount: filtered.length,
                      itemBuilder: (context, index) {
                        final article = filtered[index];
                        return _buildArticleCard(article, isDark);
                      },
                    ),
                  );
                },
                loading: () => const Center(child: CircularProgressIndicator()),
                error: (err, stack) => RefreshIndicator(
                  onRefresh: () async => ref.refresh(articlesProvider.future),
                  child: SingleChildScrollView(
                    physics: const AlwaysScrollableScrollPhysics(),
                    child: SizedBox(
                      height: MediaQuery.of(context).size.height - 200,
                      child: Center(child: Text('Gagal memuat: $err')),
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

  Widget _buildFilterChip(String? type, String label) {
    final isSelected = _selectedType == type;
    return Padding(
      padding: const EdgeInsets.only(right: 8),
      child: ChoiceChip(
        label: Text(
          label,
          style: TextStyle(
            color: isSelected ? Colors.white : Colors.grey[600],
            fontSize: 12,
            fontWeight: FontWeight.bold,
          ),
        ),
        selected: isSelected,
        onSelected: (_) => setState(() => _selectedType = type),
        selectedColor: AppTheme.teal,
        backgroundColor: Theme.of(context).brightness == Brightness.dark ? Colors.grey[800] : Colors.white,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      ),
    );
  }

  Widget _buildArticleCard(Map<String, dynamic> article, bool isDark) {
    return GestureDetector(
      onTap: () => _showArticleDetail(article),
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        decoration: BoxDecoration(
          color: isDark ? Colors.grey[900] : Colors.white,
          borderRadius: BorderRadius.circular(16),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.03),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image
            ClipRRect(
              borderRadius: const BorderRadius.vertical(top: Radius.circular(16)),
              child: AspectRatio(
                aspectRatio: 16 / 9,
                child: article['featured_image'] != null
                    ? Image.network(
                        article['featured_image'].toString().startsWith('http')
                            ? article['featured_image']
                            : '${ApiConstants.baseUrl.replaceAll('/api/', '')}/storage/${article['featured_image']}',
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => Container(
                          color: const Color(0xFFB8860B).withOpacity(0.2),
                          child: const Center(child: Icon(Icons.menu_book, size: 40, color: Color(0xFFB8860B))),
                        ),
                      )
                    : Container(
                        color: const Color(0xFFB8860B).withOpacity(0.2),
                        child: const Center(child: Icon(Icons.menu_book, size: 40, color: Color(0xFFB8860B))),
                      ),
              ),
            ),
            // Type Badge
            if (article['type'] != null)
              Positioned(
                top: 12,
                left: 12,
                child: Container(
                  margin: const EdgeInsets.only(left: 12, top: 12),
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(
                    color: const Color(0xFFB8860B),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    _capitalize(article['type']),
                    style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
            // Content
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Meta
                  Row(
                    children: [
                      Icon(Icons.person, size: 14, color: Colors.grey[500]),
                      const SizedBox(width: 4),
                      Text(
                        article['author_name'] ?? 'Anonim',
                        style: TextStyle(fontSize: 11, color: Colors.grey[500]),
                      ),
                      const SizedBox(width: 12),
                      Icon(Icons.calendar_today, size: 14, color: Colors.grey[500]),
                      const SizedBox(width: 4),
                      Text(
                        _formatDate(article['published_at']),
                        style: TextStyle(fontSize: 11, color: Colors.grey[500]),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  // Title
                  Text(
                    article['title'] ?? '',
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, height: 1.3),
                  ),
                  const SizedBox(height: 8),
                  // Excerpt
                  Text(
                    article['excerpt'] ?? '',
                    maxLines: 3,
                    overflow: TextOverflow.ellipsis,
                    style: TextStyle(fontSize: 13, color: Colors.grey[600], height: 1.4),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  void _showArticleDetail(Map<String, dynamic> article) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => ArticleDetailScreen(article: article),
      ),
    );
  }

  String _capitalize(String s) => s[0].toUpperCase() + s.substring(1);

  String _formatDate(String? dateStr) {
    if (dateStr == null) return '';
    try {
      final date = DateTime.parse(dateStr).toLocal();
      return DateFormat('dd MMM yyyy', 'id_ID').format(date);
    } catch (e) {
      return dateStr;
    }
  }
}

// Article Detail Screen
class ArticleDetailScreen extends StatelessWidget {
  final Map<String, dynamic> article;

  const ArticleDetailScreen({super.key, required this.article});

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF102210) : const Color(0xFFF5F8F8),
      body: SafeArea(
        child: CustomScrollView(
          slivers: [
            // App Bar
            SliverAppBar(
              backgroundColor: isDark ? const Color(0xFF102210) : Colors.white,
              leading: IconButton(
                icon: const Icon(Icons.arrow_back_ios, color: AppTheme.teal),
                onPressed: () => Navigator.pop(context),
              ),
              title: const Text('Detail Artikel', style: TextStyle(color: AppTheme.teal, fontWeight: FontWeight.bold)),
              pinned: true,
            ),
            // Content
            SliverToBoxAdapter(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Image
                  if (article['featured_image'] != null)
                    AspectRatio(
                      aspectRatio: 16 / 9,
                      child: Image.network(
                        article['featured_image'].toString().startsWith('http')
                            ? article['featured_image']
                            : '${ApiConstants.baseUrl.replaceAll('/api/', '')}/storage/${article['featured_image']}',
                        fit: BoxFit.cover,
                        errorBuilder: (_, __, ___) => Container(color: Colors.grey[300]),
                      ),
                    ),
                  Padding(
                    padding: const EdgeInsets.all(20),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        // Type Badge
                        if (article['type'] != null)
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(
                              color: const Color(0xFFB8860B),
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              article['type'].toString().toUpperCase(),
                              style: const TextStyle(color: Colors.white, fontSize: 11, fontWeight: FontWeight.bold),
                            ),
                          ),
                        const SizedBox(height: 16),
                        // Title
                        Text(
                          article['title'] ?? '',
                          style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, height: 1.3),
                        ),
                        const SizedBox(height: 12),
                        // Meta
                        Row(
                          children: [
                            const Icon(Icons.person, size: 16, color: Colors.grey),
                            const SizedBox(width: 6),
                            Text(article['author_name'] ?? 'Anonim', style: const TextStyle(color: Colors.grey)),
                          ],
                        ),
                        const SizedBox(height: 20),
                        // Content
                        Text(
                          article['content'] ?? article['excerpt'] ?? '',
                          style: TextStyle(fontSize: 15, height: 1.8, color: isDark ? Colors.white70 : Colors.black87),
                        ),
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
  }
}
