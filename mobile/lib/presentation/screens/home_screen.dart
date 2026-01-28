import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/theme/app_theme.dart';
import '../widgets/home/prayer_card.dart';
import '../widgets/home/dawuh_card.dart';
import '../widgets/home/services_grid.dart';
import '../widgets/home/kas_balance_card.dart';
import '../widgets/home/latest_news_section.dart';
import '../providers/providers.dart';

class HomeScreen extends ConsumerWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final settingsAsync = ref.watch(settingsProvider);

    return Scaffold(
      appBar: AppBar(
        title: settingsAsync.when(
          data: (settings) => Row(
            children: [
              Container(
                width: 40,
                height: 40,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: AppTheme.teal.withOpacity(0.2), width: 2),
                  image: DecorationImage(
                    image: (settings.siteLogo != null)
                        ? NetworkImage(settings.siteLogo!)
                        : const NetworkImage("https://lh3.googleusercontent.com/aida-public/AB6AXuBiUPY8ypA38vHHlqPv7iyc77_QOm1GszqASPGNUhNzmMqH5-GlGusH0lXxh5nZaUtlWHH3c9E8ie4xIizKJ3glvUsGIJbXByC3P9vTAro773GC4MZWYjxcG9ekmokDl7uH7y1CbrZxFIQ8E3Gj26_JExXK9pzW9F8vAc_LdgSxowDvGqvOK7KdyvL7hjDujVeLWnvRoxziX0TaZZN1oWQ4yCEJGVIyjnCxM6flDZTmp_9XDoeC8u-Jbz7eZlWR2RmDRRXoj94NDsQ"),
                    fit: BoxFit.cover,
                  ),
                ),
              ),
              const SizedBox(width: 12),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  Text(
                    settings.siteName.toUpperCase(),
                    style: const TextStyle(
                      color: AppTheme.teal,
                      fontSize: 14,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  const Text(
                    'Ranting NU Baktijaya',
                    style: TextStyle(
                      color: Colors.grey,
                      fontSize: 10,
                      fontWeight: FontWeight.w600,
                      letterSpacing: 0.5,
                    ),
                  ),
                ],
              ),
            ],
          ),
          loading: () => const Row(
            children: [
              SizedBox(width: 40, height: 40, child: CircularProgressIndicator(strokeWidth: 2)),
              SizedBox(width: 12),
              Text('Loading...', style: TextStyle(fontSize: 14)),
            ],
          ),
          error: (err, stack) => const Text('PRNU BAKTIJAYA', style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
        ),
        actions: [
          Padding(
            padding: const EdgeInsets.only(right: 16.0),
            child: Stack(
              alignment: Alignment.center,
              children: [
                Container(
                  decoration: BoxDecoration(
                    color: Theme.of(context).brightness == Brightness.dark ? Colors.grey[850] : Colors.white,
                    shape: BoxShape.circle,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.05),
                        blurRadius: 5,
                        offset: const Offset(0, 2),
                      ),
                    ],
                  ),
                  child: IconButton(
                    icon: const Icon(Icons.notifications_none, color: AppTheme.teal),
                    onPressed: () {},
                  ),
                ),
                Positioned(
                  top: 12,
                  right: 12,
                  child: Container(
                    width: 8,
                    height: 8,
                    decoration: BoxDecoration(
                      color: Colors.red,
                      shape: BoxShape.circle,
                      border: Border.all(color: Colors.white, width: 2),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: () async {
          ref.invalidate(newsProvider);
          ref.invalidate(prayerTimeProvider);
          ref.invalidate(kasSummaryProvider);
          ref.invalidate(dawuhProvider);
          ref.invalidate(settingsProvider);
          return Future.delayed(const Duration(milliseconds: 500));
        },
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          padding: const EdgeInsets.symmetric(horizontal: 16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 16),

              // Prayer Times Card
              const PrayerCard(),

              const SizedBox(height: 16),

              // Dawuh Card
              const DawuhCard(),

              const SizedBox(height: 16),

              // Services Grid
              const ServicesGrid(),

              const SizedBox(height: 12),

              // Kas Balance Card
              const KasBalanceCard(),

              const SizedBox(height: 16),

              // Latest News Section
              ref.watch(newsProvider).when(
                data: (news) => LatestNewsSection(newsList: news),
                loading: () => const Center(child: CircularProgressIndicator()),
                error: (err, stack) => const LatestNewsSection(newsList: []),
              ),

              const SizedBox(height: 100), // Space for bottom nav
            ],
          ),
        ),
      ),
    );
  }
}
