import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:carousel_slider/carousel_slider.dart';
import '../../core/theme/app_theme.dart';

class DonationScreen extends ConsumerStatefulWidget {
  const DonationScreen({super.key});

  @override
  ConsumerState<DonationScreen> createState() => _DonationScreenState();
}

class _DonationScreenState extends ConsumerState<DonationScreen> {
  int _selectedCampaignIndex = 0;
  int _selectedNominal = 20000;
  String _customNominal = '';
  final TextEditingController _customNominalController = TextEditingController();
  
  // Form Controllers
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _purposeController = TextEditingController();
  bool _isAnonymous = false;
  String _paymentMethod = 'qris';

  final List<Map<String, String>> _campaigns = [
    {
      'id': '1',
      'title': 'Renovasi Masjid Al-Ikhlas',
      'image': 'https://placehold.co/600x300/166534/FFFFFF?text=Renovasi+Masjid',
    },
    {
      'id': '2',
      'title': 'Santunan Yatim Piatu',
      'image': 'https://placehold.co/600x300/15803d/FFFFFF?text=Santunan+Yatim',
    },
    {
      'id': '3',
      'title': 'Tanggap Bencana',
      'image': 'https://placehold.co/600x300/14532d/FFFFFF?text=Tanggap+Bencana',
    },
  ];

  final List<int> _nominals = [10000, 20000, 50000, 100000];

  @override
  void dispose() {
    _customNominalController.dispose();
    _nameController.dispose();
    _phoneController.dispose();
    _purposeController.dispose();
    super.dispose();
  }

  String _formatRupiah(num number) {
    return 'Rp ${number.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}';
  }

  int get _totalAmount {
    if (_customNominal.isNotEmpty) {
      return int.tryParse(_customNominal) ?? 0;
    }
    return _selectedNominal;
  }

  void _submitDonation() {
    // Show confirmation dialog or process donation
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Konfirmasi Donasi'),
        content: Text('Anda akan mendonasikan ${_formatRupiah(_totalAmount)}. Lanjutkan?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          FilledButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text('Fitur pembayaran akan segera tersedia via API')),
              );
            },
            child: const Text('Lanjut Pembayaran'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Donasi', style: TextStyle(fontWeight: FontWeight.bold)),
        centerTitle: true,
        backgroundColor: isDark ? const Color(0xFF102216) : Colors.white,
        elevation: 0,
      ),
      backgroundColor: isDark ? const Color(0xFF102216) : Colors.grey[50],
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 16),
            
            // 1. Campaign Carousel
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Text('Pilih Kampanye'.toUpperCase(), style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
            ),
            const SizedBox(height: 12),
            CarouselSlider.builder(
              itemCount: _campaigns.length,
              options: CarouselOptions(
                height: 180,
                viewportFraction: 0.9,
                enlargeCenterPage: true,
                enableInfiniteScroll: false,
                onPageChanged: (index, reason) {
                  setState(() => _selectedCampaignIndex = index);
                },
              ),
              itemBuilder: (context, index, realIndex) {
                final campaign = _campaigns[index];
                final isSelected = _selectedCampaignIndex == index;
                
                return AnimatedContainer(
                  duration: const Duration(milliseconds: 300),
                  margin: const EdgeInsets.symmetric(horizontal: 4),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(16),
                    border: isSelected ? Border.all(color: AppTheme.teal, width: 2) : null,
                    image: DecorationImage(
                      image: NetworkImage(campaign['image']!),
                      fit: BoxFit.cover,
                    ),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.1),
                        blurRadius: 10,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Stack(
                    children: [
                      Container(
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(14),
                          gradient: LinearGradient(
                            begin: Alignment.topCenter,
                            end: Alignment.bottomCenter,
                            colors: [Colors.transparent, Colors.black.withOpacity(0.8)],
                          ),
                        ),
                      ),
                      Positioned(
                        bottom: 16,
                        left: 16,
                        right: 16,
                        child: Text(
                          campaign['title']!,
                          style: const TextStyle(
                            color: Colors.white,
                            fontSize: 18,
                            fontWeight: FontWeight.bold,
                            shadows: [Shadow(color: Colors.black, blurRadius: 4)],
                          ),
                        ),
                      ),
                      if (isSelected)
                        Positioned(
                          top: 12,
                          right: 12,
                          child: Container(
                            padding: const EdgeInsets.all(4),
                            decoration: const BoxDecoration(color: AppTheme.teal, shape: BoxShape.circle),
                            child: const Icon(Icons.check, color: Colors.white, size: 20),
                          ),
                        ),
                    ],
                  ),
                );
              },
            ),

            const SizedBox(height: 24),

            // 2. Nominal Selection
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Pilih Nominal'.toUpperCase(), style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                      crossAxisCount: 2,
                      childAspectRatio: 2.5,
                      crossAxisSpacing: 12,
                      mainAxisSpacing: 12,
                    ),
                    itemCount: _nominals.length,
                    itemBuilder: (context, index) {
                      final amount = _nominals[index];
                      final isSelected = _selectedNominal == amount && _customNominal.isEmpty;
                      
                      return InkWell(
                        onTap: () {
                          setState(() {
                            _selectedNominal = amount;
                            _customNominal = '';
                            _customNominalController.clear();
                          });
                        },
                        borderRadius: BorderRadius.circular(12),
                        child: Container(
                          alignment: Alignment.center,
                          decoration: BoxDecoration(
                            color: isSelected ? AppTheme.teal : (isDark ? Colors.white10 : Colors.white),
                            borderRadius: BorderRadius.circular(12),
                            border: Border.all(
                              color: isSelected ? AppTheme.teal : (isDark ? Colors.white24 : Colors.grey[300]!),
                            ),
                          ),
                          child: Text(
                            _formatRupiah(amount),
                            style: TextStyle(
                              color: isSelected ? Colors.white : (isDark ? Colors.white : Colors.grey[800]),
                              fontWeight: FontWeight.bold,
                              fontSize: 16,
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                  const SizedBox(height: 12),
                  TextField(
                    controller: _customNominalController,
                    keyboardType: TextInputType.number,
                    onChanged: (value) {
                      setState(() {
                        _customNominal = value;
                        if (value.isNotEmpty) _selectedNominal = 0;
                      });
                    },
                    decoration: InputDecoration(
                      prefixText: 'Rp ',
                      hintText: 'Nominal Lainnya (Min. 10.000)',
                      filled: true,
                      fillColor: isDark ? Colors.white10 : Colors.white,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: isDark ? Colors.white24 : Colors.grey[300]!),
                      ),
                      enabledBorder: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide(color: isDark ? Colors.white24 : Colors.grey[300]!),
                      ),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            // 3. Donor Info
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Data Donatur'.toUpperCase(), style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  
                  // Hamba Allah Toggle
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                    decoration: BoxDecoration(
                      color: AppTheme.teal.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: AppTheme.teal.withOpacity(0.2)),
                    ),
                    child: SwitchListTile(
                      contentPadding: EdgeInsets.zero,
                      title: const Text('Donasi sebagai Hamba Allah', style: TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                      value: _isAnonymous,
                      activeColor: AppTheme.teal,
                      onChanged: (val) => setState(() => _isAnonymous = val),
                    ),
                  ),
                  
                  if (!_isAnonymous) ...[
                    const SizedBox(height: 16),
                    TextField(
                      controller: _nameController,
                      decoration: InputDecoration(
                        labelText: 'Nama Lengkap',
                        filled: true,
                        fillColor: isDark ? Colors.white10 : Colors.white,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                    const SizedBox(height: 16),
                    TextField(
                      controller: _phoneController,
                      keyboardType: TextInputType.phone,
                      decoration: InputDecoration(
                        labelText: 'Nomor HP (Opsional)',
                        filled: true,
                        fillColor: isDark ? Colors.white10 : Colors.white,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                      ),
                    ),
                  ],
                  const SizedBox(height: 16),
                  TextField(
                    controller: _purposeController,
                    maxLines: 2,
                    decoration: InputDecoration(
                      labelText: 'Tujuan Donasi / Doa (Opsional)',
                      filled: true,
                      fillColor: isDark ? Colors.white10 : Colors.white,
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            // 4. Payment Method
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text('Metode Pembayaran'.toUpperCase(), style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 12),
                  _buildPaymentOption(
                    'qris',
                    'QRIS (GoPay, OVO, Dana)',
                    'Proses otomatis & instan',
                    Icons.qr_code_scanner,
                    isDark,
                  ),
                  const SizedBox(height: 12),
                  _buildPaymentOption(
                    'bank_transfer',
                    'Transfer Bank (Manual)',
                    'Verifikasi via WhatsApp',
                    Icons.account_balance,
                    isDark,
                  ),
                ],
              ),
            ),

            const SizedBox(height: 100), // Space for footer
          ],
        ),
      ),
      bottomSheet: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isDark ? const Color(0xFF1E2E23) : Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.05),
              blurRadius: 10,
              offset: const Offset(0, -5),
            ),
          ],
        ),
        child: SafeArea(
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Total Donasi', style: TextStyle(fontWeight: FontWeight.w500)),
                  Text(
                    _formatRupiah(_totalAmount),
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: AppTheme.teal),
                  ),
                ],
              ),
              const SizedBox(height: 16),
              SizedBox(
                width: double.infinity,
                child: FilledButton(
                  onPressed: (_totalAmount < 10000) ? null : _submitDonation,
                  style: FilledButton.styleFrom(
                    backgroundColor: AppTheme.teal,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: const Text('Konfirmasi Donasi', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildPaymentOption(String value, String title, String subtitle, IconData icon, bool isDark) {
    final isSelected = _paymentMethod == value;
    return InkWell(
      onTap: () => setState(() => _paymentMethod = value),
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.teal.withOpacity(0.1) : (isDark ? Colors.white10 : Colors.white),
          borderRadius: BorderRadius.circular(12),
          border: Border.all(
            color: isSelected ? AppTheme.teal : (isDark ? Colors.white24 : Colors.grey[300]!),
            width: isSelected ? 2 : 1,
          ),
        ),
        child: Row(
          children: [
            Icon(icon, color: isSelected ? AppTheme.teal : Colors.grey),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
                  Text(subtitle, style: const TextStyle(fontSize: 12, color: Colors.grey)),
                ],
              ),
            ),
            if (isSelected)
              const Icon(Icons.check_circle, color: AppTheme.teal),
          ],
        ),
      ),
    );
  }
}
