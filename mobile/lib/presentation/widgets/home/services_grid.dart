import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../screens/kas_digital_screen.dart';

class ServicesGrid extends StatelessWidget {
  const ServicesGrid({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Layanan Utama',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            Text(
              'See All',
              style: TextStyle(
                color: Theme.of(context).primaryColor,
                fontSize: 12,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
        const SizedBox(height: 8),
        GridView.count(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          crossAxisCount: 2,
          mainAxisSpacing: 8,
          crossAxisSpacing: 8,
          childAspectRatio: 3.0, // Even shorter cards
          children: [
            _buildServiceItem(
              context,
              'Berita Ranting',
              Icons.newspaper,
              onTap: () {},
            ),
            _buildServiceItem(
              context,
              'Layanan Jamaah',
              Icons.volunteer_activism,
              onTap: () {},
            ),
            _buildServiceItem(
              context,
              'Kas Digital (v2)',
              Icons.account_balance_wallet,
              onTap: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => const KasDigitalScreen()),
                );
              },
            ),
            _buildServiceItem(
              context,
              'Tanya Kiai',
              Icons.forum,
              onTap: () {},
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildServiceItem(BuildContext context, String title, IconData icon, {required VoidCallback onTap}) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8), // Much tighter padding
        decoration: BoxDecoration(
          color: isDark ? Colors.grey[850] : Colors.white,
          borderRadius: BorderRadius.circular(12), // Slightly smaller radius
          border: Border.all(
            color: isDark ? Colors.white10 : Colors.black.withOpacity(0.05),
          ),
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.02),
              blurRadius: 8,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Container(
              padding: const EdgeInsets.all(4), // Tiny icon container
              decoration: BoxDecoration(
                color: AppTheme.teal.withOpacity(0.1),
                borderRadius: BorderRadius.circular(4),
              ),
              child: Icon(icon, color: AppTheme.teal, size: 16), // Tiny icon
            ),
            Text(
              title,
              style: const TextStyle(
                fontSize: 11, // Smaller text
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
