import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../data/models/prayer_time_model.dart';

class PrayerCard extends StatelessWidget {
  final PrayerTimes? data;

  const PrayerCard({super.key, this.data});

  @override
  Widget build(BuildContext context) {
    // Hardcoded mock data if none provided
    final prayerData = data ?? PrayerTimes(
      cityId: 0,
      cityName: 'Baktijaya',
      times: {
        'subuh': '04:35',
        'dzuhur': '12:10',
        'ashar': '15:30',
        'maghrib': '18:05',
        'isya': '19:15',
      },
      date: '2026-01-26',
    );

    final nextPrayer = _getNextPrayer(prayerData);

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Theme.of(context).brightness == Brightness.dark 
            ? Colors.grey[850] 
            : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.teal.withOpacity(0.1)),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Row(
        children: [
          Expanded(
            flex: 3,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  children: [
                    const Icon(Icons.location_on, color: AppTheme.teal, size: 14),
                    const SizedBox(width: 4),
                    Text(
                      'NEXT PRAYER • ${prayerData.cityName.toUpperCase()}',
                      style: TextStyle(
                        color: Colors.grey[500],
                        fontSize: 10,
                        fontWeight: FontWeight.w500,
                        letterSpacing: 1.2,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 4),
                Text(
                  '${nextPrayer.name} ${nextPrayer.time}',
                  style: const TextStyle(
                    color: AppTheme.teal,
                    fontSize: 24,
                    fontWeight: FontWeight.w900,
                  ),
                ),
                const SizedBox(height: 2),
                Text(
                  nextPrayer.remaining,
                  style: const TextStyle(
                    color: AppTheme.gold,
                    fontSize: 12,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(height: 12),
                ElevatedButton.icon(
                  onPressed: () {},
                  icon: const Icon(Icons.schedule, size: 16),
                  label: const Text('Full Schedule'),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.teal.withOpacity(0.1),
                    foregroundColor: AppTheme.teal,
                    elevation: 0,
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 0),
                    textStyle: const TextStyle(fontSize: 11, fontWeight: FontWeight.bold),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(width: 12),
          Container(
            width: 80,
            height: 80,
            decoration: BoxDecoration(
              color: AppTheme.teal.withOpacity(0.05),
              borderRadius: BorderRadius.circular(12),
              border: Border.all(color: AppTheme.teal.withOpacity(0.05)),
            ),
            child: const Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(Icons.wb_twilight, color: AppTheme.teal, size: 32),
                SizedBox(height: 4),
                Text(
                  'SUNSET',
                  style: TextStyle(
                    color: AppTheme.teal,
                    fontSize: 9,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  _NextPrayerInfo _getNextPrayer(PrayerTimes prayerData) {
    final now = DateTime.now();
    
    // Ordered list of prayers
    final prayerNames = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
    
    for (final name in prayerNames) {
      final timeStr = prayerData.times[name.toLowerCase()];
      if (timeStr == null) continue;
      
      try {
        final timeParts = timeStr.split(':');
        final prayerTime = DateTime(
          now.year, now.month, now.day, 
          int.parse(timeParts[0]), int.parse(timeParts[1])
        );
        
        if (prayerTime.isAfter(now)) {
          final diff = prayerTime.difference(now);
          final hours = diff.inHours;
          final mins = diff.inMinutes % 60;
          return _NextPrayerInfo(
            name: name,
            time: timeStr,
            remaining: '${hours > 0 ? '$hours hour ' : ''}$mins minutes remaining',
          );
        }
      } catch (e) {
        continue;
      }
    }
    
    // If all prayers passed, next is Maghrib for demonstration
    return _NextPrayerInfo(
      name: 'Maghrib',
      time: '18:05',
      remaining: '1 hour 12 minutes remaining',
    );
  }
}

class _NextPrayerInfo {
  final String name;
  final String time;
  final String remaining;

  _NextPrayerInfo({required this.name, required this.time, required this.remaining});
}
