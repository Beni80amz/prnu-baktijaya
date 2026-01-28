import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:url_launcher/url_launcher.dart';
import '../../core/theme/app_theme.dart';
import '../../data/models/organization_model.dart';
import '../providers/providers.dart';

class OrganizationProfileScreen extends ConsumerWidget {
  const OrganizationProfileScreen({super.key});

  Future<void> _launchUrl(String url) async {
    if (!await launchUrl(Uri.parse(url))) {
      throw Exception('Could not launch $url');
    }
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final orgAsync = ref.watch(organizationProvider);
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6),
      body: orgAsync.when(
        data: (org) => RefreshIndicator(
          onRefresh: () async => ref.refresh(organizationProvider.future),
          child: CustomScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            slivers: [
              // AppBar
            SliverAppBar(
              pinned: true,
              backgroundColor: isDark ? const Color(0xFF102216).withOpacity(0.9) : const Color(0xFFF6F8F6).withOpacity(0.9),
              elevation: 0,
              leading: Navigator.canPop(context)
                  ? IconButton(
                      icon: const Icon(Icons.arrow_back_ios, size: 20),
                      onPressed: () => Navigator.pop(context),
                    )
                  : null,
              title: const Text('Profil Organisasi', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              centerTitle: true,
              actions: [
                IconButton(icon: const Icon(Icons.share, size: 20), onPressed: () {}),
              ],
            ),

            // Hero Section
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Container(
                  height: 320,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(16),
                    image: DecorationImage(
                      image: (org.image != null && org.image!.isNotEmpty)
                          ? NetworkImage(org.image!) as ImageProvider
                          : const NetworkImage("https://lh3.googleusercontent.com/aida-public/AB6AXuAYfiT8uFZk04njY-GOXnF2vMxX8oIiDKlYJoLrt8RCRBm6kCVR1o_88LscRcqClzR-Jt6o4kmhE6qoAegizTKtORjPfVJC5gTRIrtgX6eD5YT4ybqPWj-MKMvgA3O_u1EXiUVLOVgZWWP3FL4gVjuAWrNc4jZEkX9n_XMh7c2574qxqEv3BH5_qgDw5zlVuQ1-LP9wqRjnC1RuWidxAxLAFIonBy8jE2I74k9Ll1O0DbWILWWqUYf19BDDoNuKZ8SFIzqcUcSAmKI"),
                      fit: BoxFit.cover,
                      colorFilter: ColorFilter.mode(Colors.black.withOpacity(0.5), BlendMode.darken),
                    ),
                    border: Border.all(color: Colors.white.withOpacity(0.1)),
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(24.0),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.end,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            Container(
                              padding: const EdgeInsets.all(4),
                              decoration: BoxDecoration(
                                color: Colors.white,
                                shape: BoxShape.circle,
                                border: Border.all(color: AppTheme.teal.withOpacity(0.5)),
                              ),
                              child: org.siteLogo != null 
                                ? ClipRRect(
                                    borderRadius: BorderRadius.circular(20),
                                    child: Image.network(org.siteLogo!, width: 32, height: 32, fit: BoxFit.contain),
                                  )
                                : const Icon(Icons.mosque, color: AppTheme.teal, size: 24),
                            ),
                            const SizedBox(width: 12),
                            Container(
                              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                              decoration: BoxDecoration(
                                color: const Color(0xFFFFD700).withOpacity(0.2),
                                borderRadius: BorderRadius.circular(4),
                                border: Border.all(color: const Color(0xFFFFD700).withOpacity(0.3)),
                              ),
                              child: const Text('Nahdlatul Ulama', style: TextStyle(color: Color(0xFFFFD700), fontSize: 10, fontWeight: FontWeight.bold)),
                            ),
                          ],
                        ),
                        const SizedBox(height: 16),
                        Text(
                          org.heroTitle ?? 'PRNU Baktijaya',
                          style: const TextStyle(color: Colors.white, fontSize: 32, fontWeight: FontWeight.w900, letterSpacing: -0.5),
                        ),
                        const Text(
                          'Kecamatan Sukmajaya, Kota Depok',
                          style: TextStyle(color: AppTheme.teal, fontSize: 14, fontWeight: FontWeight.w500),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),

            // Vision & Mission
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSectionHeader('Visi & Misi'),
                    if (org.visi != null) ...[
                      const Text('Visi:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                      Html(data: org.visi!, style: {"body": Style(margin: Margins.zero, padding: HtmlPaddings.zero, color: isDark ? Colors.grey[300] : Colors.grey[700])}),
                      const SizedBox(height: 12),
                    ],
                    const Text('Misi:', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                    if (org.misi1 != null) _buildMisiItem(org.misi1!, isDark),
                    if (org.misi2 != null) _buildMisiItem(org.misi2!, isDark),
                    if (org.misi3 != null) _buildMisiItem(org.misi3!, isDark),
                  ],
                ),
              ),
            ),

            // Structure
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSectionHeader('Struktur Organisasi', trailing: Icons.account_tree),
                    const SizedBox(height: 16),
                    ..._buildStructureGroups(org.structure, isDark),
                  ],
                ),
              ),
            ),

            // Contact & Location
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildSectionHeader('Kontak & Lokasi'),
                    const SizedBox(height: 16),
                    // Map Section
                    GestureDetector(
                      onTap: () {
                        // Using the address for search is much more reliable than using the embed URL
                        final encodedAddress = Uri.encodeComponent(org.address ?? 'Sekretariat PRNU Baktijaya');
                        final googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=$encodedAddress';
                        _launchUrl(googleMapsUrl);
                      },
                      child: Container(
                        height: 180,
                        width: double.infinity,
                        decoration: BoxDecoration(
                          color: isDark ? const Color(0xFF1E2E23) : Colors.grey[200],
                          borderRadius: BorderRadius.circular(16),
                          image: const DecorationImage(
                            image: NetworkImage("https://images.unsplash.com/photo-1524661135-423995f22d0b?q=80&w=1000&auto=format&fit=crop"),
                            fit: BoxFit.cover,
                            opacity: 0.3,
                          ),
                          border: Border.all(color: AppTheme.teal.withOpacity(0.2)),
                        ),
                        child: Stack(
                          children: [
                            Center(
                              child: Column(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Container(
                                    padding: const EdgeInsets.all(12),
                                    decoration: BoxDecoration(
                                      color: AppTheme.teal,
                                      shape: BoxShape.circle,
                                      boxShadow: [
                                        BoxShadow(
                                          color: AppTheme.teal.withOpacity(0.4),
                                          blurRadius: 12,
                                          spreadRadius: 2,
                                        )
                                      ],
                                    ),
                                    child: const Icon(Icons.location_on, color: Colors.white, size: 28),
                                  ),
                                  const SizedBox(height: 12),
                                  Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                                    decoration: BoxDecoration(
                                      color: Colors.black54,
                                      borderRadius: BorderRadius.circular(20),
                                    ),
                                    child: const Text(
                                      'Klik untuk navigasi ke lokasi',
                                      style: TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    const SizedBox(height: 20),
                    _buildContactItem(Icons.pin_drop, 'Kantor Sekretariat', org.address ?? 'Jl. Kerinci Raya No. 12, Baktijaya, Sukmajaya, Depok 16418', isDark),
                    _buildContactItem(Icons.mail, 'Email Resmi', org.email ?? 'info@prnubaktijaya.or.id', isDark),
                    const SizedBox(height: 24),
                    Row(
                      children: [
                        Expanded(child: _buildActionButton(Icons.chat, 'WhatsApp', AppTheme.teal, Colors.black, () => _launchUrl('https://wa.me/${org.phone}'))),
                        const SizedBox(width: 12),
                        Expanded(child: _buildActionButton(Icons.photo_camera, 'Instagram', isDark ? Colors.white : Colors.black, isDark ? Colors.black : Colors.white, () => _launchUrl(org.instagram ?? 'https://instagram.com'))),
                      ],
                    ),
                    const SizedBox(height: 80), // Footer spacer
                  ],
                ),
              ),
            ),
          ],
          ),
        ),
        loading: () => const Center(child: CircularProgressIndicator()),
        error: (err, stack) => RefreshIndicator(
          onRefresh: () async => ref.refresh(organizationProvider.future),
          child: SingleChildScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            child: SizedBox(
              height: MediaQuery.of(context).size.height,
              child: Center(child: Text('Error: $err')),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildSectionHeader(String title, {IconData? trailing}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 16.0),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Row(
            children: [
              Container(width: 4, height: 24, decoration: BoxDecoration(color: AppTheme.teal, borderRadius: BorderRadius.circular(2))),
              const SizedBox(width: 12),
              Text(title, style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, letterSpacing: -0.5)),
            ],
          ),
          if (trailing != null) Icon(trailing, color: AppTheme.teal),
        ],
      ),
    );
  }

  Widget _buildMisiItem(String text, bool isDark) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8.0),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Padding(padding: EdgeInsets.only(top: 6.0), child: Icon(Icons.circle, size: 6, color: AppTheme.teal)),
          const SizedBox(width: 12),
          Expanded(child: Text(text, style: TextStyle(color: isDark ? Colors.grey[400] : Colors.grey[700], height: 1.5))),
        ],
      ),
    );
  }

  List<Widget> _buildStructureGroups(List<StructureItem> items, bool isDark) {
    final groups = <String, List<StructureItem>>{};
    for (var item in items) {
      groups.putIfAbsent(item.type, () => []).add(item);
    }

    final List<Widget> widgets = [];
    groups.forEach((type, list) {
      widgets.add(Padding(
        padding: const EdgeInsets.only(bottom: 8.0),
        child: Text(type.toUpperCase(), style: const TextStyle(color: Color(0xFFFFD700), fontSize: 11, fontWeight: FontWeight.bold, letterSpacing: 1.5)),
      ));
      for (var member in list) {
        widgets.add(_buildMemberCard(member, isDark));
        widgets.add(const SizedBox(height: 12));
      }
      widgets.add(const SizedBox(height: 12));
    });
    return widgets;
  }

  Widget _buildMemberCard(StructureItem item, bool isDark) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: isDark ? const Color(0xFF1E2E23) : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: AppTheme.teal.withOpacity(0.1)),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10)],
      ),
      child: Row(
        children: [
          Container(
            width: 50,
            height: 50,
            decoration: BoxDecoration(
              shape: BoxShape.circle,
              border: Border.all(color: AppTheme.teal, width: 1.5),
              image: DecorationImage(
                image: item.photo != null ? NetworkImage(item.photo!) : const NetworkImage("https://via.placeholder.com/150"),
                fit: BoxFit.cover,
              ),
            ),
          ),
          const SizedBox(width: 16),
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(item.name, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15)),
              Text(item.position, style: TextStyle(color: isDark ? Colors.grey[400] : Colors.grey[600], fontSize: 13)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildContactItem(IconData icon, String title, String value, bool isDark) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16.0),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(color: AppTheme.teal.withOpacity(0.1), borderRadius: BorderRadius.circular(12)),
            child: Icon(icon, color: AppTheme.teal, size: 20),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(title, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
                const SizedBox(height: 2),
                Text(value, style: TextStyle(color: isDark ? Colors.grey[400] : Colors.grey[600], fontSize: 13)),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildActionButton(IconData icon, String label, Color bgColor, Color textColor, VoidCallback onTap) {
    return InkWell(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12),
        decoration: BoxDecoration(color: bgColor, borderRadius: BorderRadius.circular(12)),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: textColor, size: 18),
            const SizedBox(width: 8),
            Text(label, style: TextStyle(color: textColor, fontWeight: FontWeight.bold, fontSize: 14)),
          ],
        ),
      ),
    );
  }
}
