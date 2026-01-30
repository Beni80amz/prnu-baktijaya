import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../screens/kas_digital_screen.dart';
import '../../screens/news_list_screen.dart';
import '../../screens/umkm_screen.dart';
import '../../screens/member_services_screen.dart';
import '../../screens/live_streaming_screen.dart';
import '../../screens/donation_screen.dart';
import '../../screens/article_list_screen.dart';

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
          padding: EdgeInsets.zero,
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          crossAxisCount: 4,
          mainAxisSpacing: 8,
          crossAxisSpacing: 8,
          childAspectRatio: 0.85,
          children: [
            _buildServiceItem(
              context,
              'Berita',
              Icons.newspaper,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const NewsListScreen())),
            ),
            _buildServiceItem(
              context,
              'Live Streaming',
              Icons.live_tv,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const LiveStreamingScreen())),
            ),
            _buildServiceItem(
              context,
              'Kas Digital',
              Icons.account_balance_wallet,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const KasDigitalScreen())),
            ),
            _buildServiceItem(
              context,
              'Donasi',
              Icons.volunteer_activism,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const DonationScreen())),
            ),
            _buildServiceItem(
              context,
              'Layanan Jama\'ah',
              Icons.mosque,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const MemberServicesScreen())),
            ),
            _buildServiceItem(
              context,
              'UMKM',
              Icons.storefront,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const UmkmScreen())),
            ),
            _buildServiceItem(
              context,
              'Artikel',
              Icons.menu_book,
              onTap: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ArticleListScreen())),
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
        padding: const EdgeInsets.all(8),
        decoration: BoxDecoration(
          color: isDark ? Colors.grey[850] : Colors.white,
          borderRadius: BorderRadius.circular(12),
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
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Container(
              padding: const EdgeInsets.all(8),
              decoration: BoxDecoration(
                color: AppTheme.teal.withOpacity(0.1),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Icon(icon, color: AppTheme.teal, size: 22),
            ),
            const SizedBox(height: 6),
            Text(
              title,
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 10,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
