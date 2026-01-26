import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';
import 'home_screen.dart';
import 'kas_digital_screen.dart';
import 'news_list_screen.dart';
import 'gallery_screen.dart';
import 'organization_profile_screen.dart';

class MainScreen extends StatefulWidget {
  const MainScreen({super.key});

  @override
  State<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  int _currentIndex = 0;

  final List<Widget> _screens = const [
    HomeScreen(),
    NewsListScreen(),
    KasDigitalScreen(), // FAB navigation
    GalleryScreen(), // Galeri tab
    OrganizationProfileScreen(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBody: true,
      body: IndexedStack(
        index: _currentIndex,
        children: _screens,
      ),
      floatingActionButton: Container(
        height: 64,
        width: 64,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          border: Border.all(
            color: Theme.of(context).scaffoldBackgroundColor,
            width: 4,
          ),
          boxShadow: [
            BoxShadow(
              color: AppTheme.teal.withOpacity(0.4),
              blurRadius: 10,
              offset: const Offset(0, 4),
            ),
          ],
        ),
        child: FloatingActionButton(
          onPressed: () => setState(() => _currentIndex = 2),
          backgroundColor: AppTheme.teal,
          elevation: 0,
          shape: const CircleBorder(),
          child: const Icon(
            Icons.account_balance_wallet,
            color: Colors.white,
            size: 32,
          ),
        ),
      ),
      floatingActionButtonLocation: FloatingActionButtonLocation.centerDocked,
      bottomNavigationBar: BottomAppBar(
        padding: const EdgeInsets.symmetric(horizontal: 10),
        height: 70,
        color: Theme.of(context).brightness == Brightness.dark
            ? const Color(0xFF1A1A1A)
            : Colors.white,
        shape: const CircularNotchedRectangle(),
        notchMargin: 8,
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            _buildNavItem(0, Icons.home, 'Home'),
            _buildNavItem(1, Icons.newspaper, 'Berita'),
            const SizedBox(width: 48), // Space for FAB
            _buildNavItem(3, Icons.collections, 'Galeri'),
            _buildNavItem(4, Icons.person, 'Profil'),
          ],
        ),
      ),
    );
  }

  Widget _buildNavItem(int index, IconData icon, String label) {
    final isSelected = _currentIndex == index;
    return InkWell(
      onTap: () => setState(() => _currentIndex = index),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(
            icon,
            color: isSelected ? AppTheme.teal : Colors.grey[400],
            size: 24,
          ),
          const SizedBox(height: 4),
          Text(
            label,
            style: TextStyle(
              color: isSelected ? AppTheme.teal : Colors.grey[400],
              fontSize: 10,
              fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }
}
