import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/theme/app_theme.dart';
import '../providers/providers.dart';
import 'package:url_launcher/url_launcher.dart';
import 'tanya_kiai_screen.dart';
import 'ruang_doa_screen.dart';
import 'zakat_calculator_screen.dart';
import 'peta_masjid_screen.dart';

class MemberServicesScreen extends ConsumerWidget {
  const MemberServicesScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final backgroundColor = isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6);
    final settingsAsync = ref.watch(settingsProvider);

    return Scaffold(
      backgroundColor: backgroundColor,
      appBar: AppBar(
        title: Text(
          'Layanan Jamaah',
          style: TextStyle(
            color: isDark ? Colors.white : Colors.black, // Correct text color
            fontWeight: FontWeight.bold,
            fontSize: 18,
          ),
        ),
        backgroundColor: isDark ? Colors.grey[900] : Colors.white,
        elevation: 0,
        centerTitle: true,
        iconTheme: IconThemeData(color: isDark ? Colors.white : Colors.black),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // 1. Live Streaming Card
            // 2. Layanan Utama Header
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  'Layanan Utama',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                    color: isDark ? Colors.white : Colors.black,
                  ),
                ),
                TextButton(
                  onPressed: () {},
                  child: const Text('Lihat Semua'),
                ),
              ],
            ),
            const SizedBox(height: 12),

            // 3. Grid Layanan
            GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: 2,
              crossAxisSpacing: 16,
              mainAxisSpacing: 16,
              childAspectRatio: 0.85, // Adjust card aspect ratio
              children: [
                _buildServiceCard(
                  context,
                  'Peta Masjid', // Replaced "Pendaftaran Amalan" based on user request to include Peta Masjid
                  'Cari lokasi masjid terdekat',
                  Icons.map,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const PetaMasjidScreen())),
                  isDark,
                ),
                _buildServiceCard(
                  context,
                  'Permohonan Doa',
                  'Kirimkan hajat doa Anda',
                  Icons.volunteer_activism,
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const RuangDoaScreen())),
                  isDark,
                ),
                _buildServiceCard(
                  context,
                  'Konsultasi Syariah',
                  'Tanya jawab hukum Islam',
                  Icons.gavel, 
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const TanyaKiaiScreen())),
                  isDark,
                ),
                _buildServiceCard(
                  context,
                  'Zakat & Infaq',
                  'Salurkan bantuan umat',
                  Icons.calculate, 
                  () => Navigator.push(context, MaterialPageRoute(builder: (_) => const ZakatCalculatorScreen())),
                  isDark,
                ),
              ],
            ),

            const SizedBox(height: 24),

            // 4. Tanya Kiai AI Banner
            Text(
              'Butuh Bimbingan?',
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: isDark ? Colors.white : Colors.black,
              ),
            ),
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: AppTheme.teal.withOpacity(0.05),
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppTheme.teal.withOpacity(0.2)),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Icon(Icons.psychology, color: AppTheme.teal, size: 28),
                      const SizedBox(width: 8),
                      const Text(
                        'Tanya Kiai AI',
                        style: TextStyle(
                          fontSize: 16, 
                          fontWeight: FontWeight.bold,
                          color: AppTheme.teal
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Punya pertanyaan seputar fiqih atau kehidupan sehari-hari? Konsultasi langsung dengan asisten digital kami.',
                    style: TextStyle(
                      fontSize: 12,
                      color: isDark ? Colors.grey[300] : Colors.grey[700],
                    ),
                  ),
                  const SizedBox(height: 12),
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton(
                      onPressed: () => Navigator.push(context, MaterialPageRoute(builder: (_) => const TanyaKiaiScreen())),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.teal,
                        foregroundColor: Colors.white,
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                      ),
                      child: const Text('Tanya Sekarang'),
                    ),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 24),
          ],
        ),
      ),
    );
  }



  Widget _buildServiceCard(BuildContext context, String title, String subtitle, IconData icon, VoidCallback onTap, bool isDark) {
    return Container(
      decoration: BoxDecoration(
        color: isDark ? Colors.grey[850] : Colors.grey[50], // Light gray bg
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: isDark ? Colors.white10 : Colors.grey[200]!),
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: onTap,
          borderRadius: BorderRadius.circular(16),
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Center(
                  child: Icon(icon, size: 40, color: const Color(0xFF00796B)),
                ),
                const SizedBox(height: 16),
                Text(
                  title,
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: isDark ? Colors.white : Colors.black87,
                  ),
                  textAlign: TextAlign.start,
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: TextStyle(
                    fontSize: 10,
                    color: isDark ? Colors.grey[400] : Colors.grey[600],
                  ),
                  textAlign: TextAlign.start,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
