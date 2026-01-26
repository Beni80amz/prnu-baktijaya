import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';
import 'doa_form_screen.dart';
import 'tanya_kiai_screen.dart';

class ServiceMenuScreen extends StatelessWidget {
  const ServiceMenuScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Layanan Jamaah'),
      ),
      body: GridView.count(
        crossAxisCount: 2,
        padding: const EdgeInsets.all(16),
        mainAxisSpacing: 16,
        crossAxisSpacing: 16,
        children: [
          _buildMenuCard(
            context,
            icon: Icons.mosque,
            title: 'Permohonan Doa',
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const DoaFormScreen()),
              );
            },
          ),
          _buildMenuCard(
            context,
            icon: Icons.question_answer,
            title: 'Tanya Kiai',
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const TanyaKiaiScreen()),
              );
            },
          ),
          _buildMenuCard(
            context,
            icon: Icons.calculate,
            title: 'Kalkulator Zakat',
            onTap: () {
              ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Fitur Kalkulator Zakat segera hadir')));
            },
          ),
          _buildMenuCard(
            context,
            icon: Icons.volunteer_activism,
            title: 'Donasi / Infaq',
            onTap: () {
              ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Fitur Donasi segera hadir')));
            },
          ),
        ],
      ),
    );
  }

  Widget _buildMenuCard(BuildContext context,
      {required IconData icon,
      required String title,
      required VoidCallback onTap}) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, size: 48, color: AppTheme.teal),
            const SizedBox(height: 12),
            Text(
              title,
              textAlign: TextAlign.center,
              style: const TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
