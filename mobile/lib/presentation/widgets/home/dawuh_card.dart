import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

class DawuhCard extends StatelessWidget {
  const DawuhCard({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      width: double.infinity,
      height: 180,
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
                  const Text(
                    '"The greatest struggle is the battle with your own soul."',
                    style: TextStyle(
                      color: Colors.white,
                      fontSize: 18,
                      fontWeight: FontWeight.w500,
                      fontStyle: FontStyle.italic,
                      letterSpacing: -0.5,
                    ),
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
                      const Text(
                        'DAWUH KH HASYIM ASY\'ARI',
                        style: TextStyle(
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
