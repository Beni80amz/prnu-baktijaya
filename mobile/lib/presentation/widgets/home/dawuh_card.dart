import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:carousel_slider/carousel_slider.dart';
import '../../../../core/theme/app_theme.dart';
import '../../providers/providers.dart';
import '../../../data/models/dawuh_model.dart';

class DawuhCard extends ConsumerWidget {
  const DawuhCard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final dawuhsAsync = ref.watch(dawuhProvider);

    return dawuhsAsync.when(
      data: (dawuhs) {
        if (dawuhs.isEmpty) return const SizedBox.shrink();
        
        return CarouselSlider(
          options: CarouselOptions(
            height: 200,
            viewportFraction: 0.92,
            autoPlay: true,
            autoPlayInterval: const Duration(seconds: 5),
            enlargeCenterPage: false,
            enableInfiniteScroll: true,
          ),
          items: dawuhs.map((dawuh) {
            return Builder(
              builder: (BuildContext context) {
                return _buildDawuhItem(context, dawuh);
              },
            );
          }).toList(),
        );
      },
      loading: () => Container(
        height: 200,
        decoration: BoxDecoration(
          color: AppTheme.teal.withOpacity(0.1),
          borderRadius: BorderRadius.circular(16),
        ),
        child: const Center(child: CircularProgressIndicator()),
      ),
      error: (err, stack) => const SizedBox.shrink(),
    );
  }

  Widget _buildDawuhItem(BuildContext context, Dawuh dawuh) {
    return Container(
      width: double.infinity,
      margin: const EdgeInsets.symmetric(horizontal: 6.0),
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(16),
        image: const DecorationImage(
          image: NetworkImage(
            "https://lh3.googleusercontent.com/aida-public/AB6AXuB_zfgfMw9G72bFAlTEbNmciE-THEh8aGkoYroxiGJrvbGVHIUDcz0rg3n_HLSGxPAxAlUgof2ZL9U6grF0GTxGui0Dy7rXiwfR5_DFYkUWOqtDK22Y0GZOBu3LWT0ou8ysAwKbHDzSwq4qxFKg7U6jCZI20-IOlB-mYRM7p4y7zS-ky14qQlX1Yv_psRekOswFG0CWDzNOLzZVt6QtxfqDNZ7nstNAc_hzKwLn391qaYb994bxHlEosHGsUT0MfukzBfUHTDUsmRw"
          ),
          fit: BoxFit.cover,
        ),
      ),
      child: Container(
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(16),
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [
              AppTheme.teal.withOpacity(0.1),
              AppTheme.teal.withOpacity(0.9),
            ],
          ),
        ),
        padding: const EdgeInsets.all(20),
        child: Stack(
          children: [
            Positioned(
              top: 0,
              left: 0,
              child: Icon(
                Icons.format_quote,
                color: AppTheme.gold.withOpacity(0.5),
                size: 40,
              ),
            ),
            Align(
              alignment: Alignment.bottomLeft,
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    '"${dawuh.quote}"',
                    style: const TextStyle(
                      color: Colors.white,
                      fontSize: 16,
                      fontWeight: FontWeight.w500,
                      fontStyle: FontStyle.italic,
                      letterSpacing: -0.5,
                    ),
                    maxLines: 3,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      Container(
                        width: 24,
                        height: 1,
                        color: AppTheme.gold,
                      ),
                      const SizedBox(width: 8),
                      Text(
                        'DAWUH ${dawuh.ulamaName.toUpperCase()}',
                        style: const TextStyle(
                          color: AppTheme.gold,
                          fontSize: 10,
                          fontWeight: FontWeight.w800,
                          letterSpacing: 1.5,
                        ),
                      ),
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
}
