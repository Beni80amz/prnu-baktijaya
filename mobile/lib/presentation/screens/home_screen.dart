import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/providers.dart';
import '../../core/theme/app_theme.dart';
import 'news_detail_screen.dart';


class HomeScreen extends ConsumerWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final prayerTimesAsync = ref.watch(prayerTimeProvider);
    final newsAsync = ref.watch(newsProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text('PRNU Baktijaya'),
        actions: [
          IconButton(
            icon: const Icon(Icons.person),
            onPressed: () {
              // Navigate to profile
            },
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          await Future.wait([
            ref.refresh(prayerTimeProvider.future),
            ref.refresh(newsProvider.future),
          ]);
        },
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Prayer Times Widget
              _buildSectionTitle('Jadwal Sholat'),
              const SizedBox(height: 10),
              prayerTimesAsync.when(
                data: (data) => _buildPrayerTimesCard(data),
                loading: () => const Center(child: CircularProgressIndicator()),
                error: (err, stack) => Text('Error: $err', style: const TextStyle(color: Colors.red)),
              ),

              const SizedBox(height: 20),

              // News Widget
              _buildSectionTitle('Berita Terkini'),
              const SizedBox(height: 10),
              newsAsync.when(
                data: (newsList) => Column(
                  children: newsList.map((news) => _buildNewsCard(context, news)).toList(),
                ),
                loading: () => const Center(child: CircularProgressIndicator()),
                error: (err, stack) => Text('Error: $err', style: const TextStyle(color: Colors.red)),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Text(
      title,
      style: const TextStyle(
        fontSize: 18,
        fontWeight: FontWeight.bold,
        color: AppTheme.teal,
      ),
    );
  }

  Widget _buildPrayerTimesCard(dynamic data) {
    // Assuming data is PrayerTimes model
    return Card(
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      color: AppTheme.teal,
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            Text(
              data.cityName,
              style: const TextStyle(color: Colors.white, fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                _buildTimeItem('Subuh', data.times['subuh']),
                _buildTimeItem('Dzuhur', data.times['dzuhur']),
                _buildTimeItem('Ashar', data.times['ashar']),
                _buildTimeItem('Maghrib', data.times['maghrib']),
                _buildTimeItem('Isya', data.times['isya']),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildTimeItem(String name, String? time) {
    return Column(
      children: [
        Text(name, style: const TextStyle(color: Colors.white70, fontSize: 12)),
        const SizedBox(height: 4),
        Text(time ?? '-', style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
      ],
    );
  }

  Widget _buildNewsCard(BuildContext context, dynamic news) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListTile(
        contentPadding: const EdgeInsets.all(10),
        title: Text(
          news.title,
          maxLines: 2,
          overflow: TextOverflow.ellipsis,
          style: const TextStyle(fontWeight: FontWeight.bold),
        ),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 5),
            Text(
              news.publishedAt,
              style: const TextStyle(fontSize: 12, color: Colors.grey),
            ),
          ],
        ),
        // trailing: Image.network(news.image ?? '', width: 60, fit: BoxFit.cover, errorBuilder: (c,e,s) => const Icon(Icons.image)),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => NewsDetailScreen(news: news),
            ),
          );
        },
      ),
    );
  }
}
