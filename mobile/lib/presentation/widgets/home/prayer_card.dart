import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../data/models/prayer_time_model.dart';
import '../../providers/providers.dart';
import '../../screens/city_selector_screen.dart';

class PrayerCard extends ConsumerWidget {
  const PrayerCard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final prayerAsync = ref.watch(prayerTimeProvider);

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
      child: prayerAsync.when(
        data: (prayerData) => _buildContent(context, prayerData),
        loading: () => const Center(
          child: Padding(
            padding: EdgeInsets.symmetric(vertical: 20),
            child: CircularProgressIndicator(),
          ),
        ),
        error: (err, stack) => Center(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Icon(Icons.error_outline, color: Colors.red, size: 24),
              const SizedBox(height: 8),
              const Text('Gagal memuat jadwal sholat', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
              Text(err.toString(), style: const TextStyle(fontSize: 10, color: Colors.grey), textAlign: TextAlign.center, maxLines: 2),
              TextButton(
                onPressed: () => ref.refresh(prayerTimeProvider),
                child: const Text('Coba Lagi', style: TextStyle(fontSize: 11)),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildContent(BuildContext context, PrayerTimes prayerData) {
    final nextPrayer = _getNextPrayer(prayerData);

    return Row(
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
                  Expanded(
                    child: Text(
                      'NEXT PRAYER • ${prayerData.cityName.toUpperCase()}',
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(
                        color: Colors.grey[500],
                        fontSize: 10,
                        fontWeight: FontWeight.w500,
                        letterSpacing: 1.2,
                      ),
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
                onPressed: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => const CitySelectorScreen()),
                  );
                },
                icon: const Icon(Icons.location_city, size: 16),
                label: const Text('Pilih Wilayah'),
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
            remaining: '${hours > 0 ? '$hours jam ' : ''}$mins menit lagi',
          );
        }
      } catch (e) {
        continue;
      }
    }
    
    // If all prayers passed, fallback to Subuh tomorrow or just show last one
    return _NextPrayerInfo(
      name: 'Subuh',
      time: prayerData.times['subuh'] ?? '--:--',
      remaining: 'Besok',
    );
  }
}

class _NextPrayerInfo {
  final String name;
  final String time;
  final String remaining;

  _NextPrayerInfo({required this.name, required this.time, required this.remaining});
}
