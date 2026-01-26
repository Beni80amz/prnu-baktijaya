import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../data/models/news_model.dart';
import '../../screens/news_detail_screen.dart';

class LatestNewsSection extends StatelessWidget {
  final List<News> newsList;

  const LatestNewsSection({super.key, required this.newsList});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Berita Terbaru',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            Text(
              'Lainnya',
              style: TextStyle(
                color: Theme.of(context).primaryColor,
                fontSize: 12,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        SizedBox(
          height: 220,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            itemCount: newsList.isEmpty ? 2 : newsList.length,
            separatorBuilder: (context, index) => const SizedBox(width: 16),
            itemBuilder: (context, index) {
              if (newsList.isEmpty) {
                // Return placeholder as in HTML
                return _buildPlaceholderCard(context, index);
              }
              return _buildNewsCard(context, newsList[index]);
            },
          ),
        ),
      ],
    );
  }

  Widget _buildNewsCard(BuildContext context, News news) {
    return InkWell(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => NewsDetailScreen(news: news),
          ),
        );
      },
      borderRadius: BorderRadius.circular(12),
      child: Container(
        width: 240,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(12),
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            ClipRRect(
              borderRadius: BorderRadius.circular(12),
              child: AspectRatio(
                aspectRatio: 16 / 9,
                child: Image.network(
                  news.image ?? '',
                  fit: BoxFit.cover,
                  errorBuilder: (context, error, stackTrace) => Container(
                    color: Colors.grey[300],
                    child: const Icon(Icons.image, color: Colors.grey),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 8),
            const Text(
              'BERITA',
              style: TextStyle(
                color: AppTheme.teal,
                fontSize: 10,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              news.title,
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
              style: const TextStyle(
                fontSize: 14,
                fontWeight: FontWeight.bold,
                height: 1.2,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              _formatDate(news.publishedAt),
              style: const TextStyle(
                color: Colors.grey,
                fontSize: 10,
              ),
            ),
          ],
        ),
      ),
    );
  }

  String _formatDate(String dateStr) {
    try {
      if (dateStr.isEmpty) return '';
      // Handle "Z" and other timezone formats by parsing as UTC if needed
      final date = DateTime.parse(dateStr).toLocal();
      return DateFormat('dd MMMM yyyy, HH:mm', 'id_ID').format(date);
    } catch (e) {
      debugPrint('Error formatting date: $e');
      return dateStr;
    }
  }

  Widget _buildPlaceholderCard(BuildContext context, int index) {
    final images = [
      "https://lh3.googleusercontent.com/aida-public/AB6AXuDjGX64YV_XW5g10ElIz9BbExos3olN-DKIwOLVXeJ3iwfZ4GakI_0xcCTs9Mc5e_-_2SWbmM64Q2FrLJwF5lD9Turyd295pEurP8PC7YA7pQ1c-jHMbuIENRm-uiw10hadwhQbXzZZ213uJ5spCqD0SahCuejvYWiSFS-qoCUaPSJbFf5duSsqdBMp7W6lxLc9-9o_gOioGq4EVo4b5oOCTca4GPwI8msYepFwYX8cBagO1ihAUB6eABVFMP-0NCzSVhNQk1Ri__c",
      "https://lh3.googleusercontent.com/aida-public/AB6AXuA8m_S0Nnh8kzRCXUYYZGiZ1riGeKgMiyoGzx6U0R6kP9dMRnlHKGUs95KJ9QOw59FQWMoxWkGeOocE_LrwmINC_My0sz2YVPZJtJ7FDPXY-Kf98wPtj3d4uWLpJXh8JTpl35wlzTk8EPsvbedZ9V_nEBzQQDsGd9VIWogZ9FvDhEm2Il151BScExE_no-ut8EXpUU0f-t22ULFIv9oKiXOmo-nNjMNylGbA0nf-yOSjzO2OF1IZsi8_H7OrIDvlnMezypAFgWTsWU"
    ];
    final titles = [
      "Lailatul Ijtima' Ranting Baktijaya Bulan Ini",
      "Sosialisasi Digitalisasi Kas Masjid Se-Baktijaya"
    ];
    final categories = ["KEGIATAN", "INFORMASI"];
    final dates = ["2 hours ago", "Yesterday"];

    return Container(
      width: 240,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(12),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          ClipRRect(
            borderRadius: BorderRadius.circular(12),
            child: AspectRatio(
              aspectRatio: 16 / 9,
              child: Image.network(
                images[index],
                fit: BoxFit.cover,
              ),
            ),
          ),
          const SizedBox(height: 8),
          Text(
            categories[index],
            style: const TextStyle(
              color: AppTheme.teal,
              fontSize: 10,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            titles[index],
            maxLines: 2,
            overflow: TextOverflow.ellipsis,
            style: const TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.bold,
              height: 1.2,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            dates[index],
            style: const TextStyle(
              color: Colors.grey,
              fontSize: 10,
            ),
          ),
        ],
      ),
    );
  }
}
