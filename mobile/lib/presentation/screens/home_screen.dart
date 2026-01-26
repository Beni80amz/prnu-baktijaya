import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';
import '../widgets/home/prayer_card.dart';
import '../widgets/home/dawuh_card.dart';
import '../widgets/home/services_grid.dart';
import '../widgets/home/kas_balance_card.dart';
import '../widgets/home/latest_news_section.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            Container(
              width: 40,
              height: 40,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                border: Border.all(color: AppTheme.teal.withOpacity(0.2), width: 2),
                image: const DecorationImage(
                  image: NetworkImage("https://lh3.googleusercontent.com/aida-public/AB6AXuBiUPY8ypA38vHHlqPv7iyc77_QOm1GszqASPGNUhNzmMqH5-GlGusH0lXxh5nZaUtlWHH3c9E8ie4xIizKJ3glvUsGIJbXByC3P9vTAro773GC4MZWYjxcG9ekmokDl7uH7y1CbrZxFIQ8E3Gj26_JExXK9pzW9F8vAc_LdgSxowDvGqvOK7KdyvL7hjDujVeLWnvRoxziX0TaZZN1oWQ4yCEJGVIyjnCxM6flDZTmp_9XDoeC8u-Jbz7eZlWR2RmDRRXoj94NDsQ"),
                  fit: BoxFit.cover,
                ),
              ),
            ),
            const SizedBox(width: 12),
            const Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  'PRNU BAKTIJAYA',
                  style: TextStyle(
                    color: AppTheme.teal,
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Text(
                  'RANTING NU',
                  style: TextStyle(
                    color: Colors.grey,
                    fontSize: 10,
                    fontWeight: FontWeight.w800,
                    letterSpacing: 1.5,
                  ),
                ),
              ],
            ),
          ],
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
      body: const SingleChildScrollView(
        padding: EdgeInsets.symmetric(horizontal: 16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            SizedBox(height: 16),

            // Prayer Times Card (Hardcoded Placeholder)
            PrayerCard(),

            SizedBox(height: 16),

            // Dawuh Card
            DawuhCard(),

            SizedBox(height: 12),

            // Services Grid
            ServicesGrid(),

            SizedBox(height: 8),

            // Kas Balance Card
            KasBalanceCard(),

            SizedBox(height: 16),

            // Latest News Section (Hardcoded Placeholder)
            LatestNewsSection(newsList: []),

            SizedBox(height: 100), // Space for bottom nav
          ],
        ),
      ),
    );
  }
}
