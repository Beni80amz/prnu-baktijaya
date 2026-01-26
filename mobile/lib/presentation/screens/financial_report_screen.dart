import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';
import '../../core/network/dio_client.dart';
import '../../core/constants/api_constants.dart';

class FinancialReportScreen extends StatefulWidget {
  const FinancialReportScreen({super.key});

  @override
  State<FinancialReportScreen> createState() => _FinancialReportScreenState();
}

class _FinancialReportScreenState extends State<FinancialReportScreen> {
  bool _isLoading = true;
  String? _error;
  List<dynamic> _availableYears = [];
  List<dynamic> _availableTypes = [];
  List<dynamic> _reports = [];

  String? _selectedReportType;
  String? _selectedPeriod;

  // Colors from HTML/Theme
  static const Color nuGold = Color(0xFFFFD700);

  @override
  void initState() {
    super.initState();
    _fetchReportData();
  }

  Future<void> _fetchReportData() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final dio = DioClient().dio;
      final params = <String, dynamic>{};
      if (_selectedPeriod != null) params['year'] = _selectedPeriod;
      if (_selectedReportType != null) params['type'] = _selectedReportType;

      final response = await dio.get(ApiConstants.kasReports, queryParameters: params);
      
      if (response.data['success']) {
        final data = response.data['data'];
        setState(() {
          _availableYears = data['filters']['years'];
          _availableTypes = data['filters']['types'];
          _reports = data['reports'];
          
          // Set defaults if not set
          _selectedPeriod ??= _availableYears.isNotEmpty ? _availableYears[0].toString() : null;
          _selectedReportType ??= _availableTypes.isNotEmpty ? _availableTypes[0]['id'].toString() : null;
          
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Gagal memuat data laporan';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _error = 'Kesalahan: $e';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).brightness == Brightness.dark 
          ? const Color(0xFF102219) 
          : const Color(0xFFF5F8F7),
      appBar: AppBar(
        titleSpacing: 0,
        backgroundColor: Colors.transparent,
        elevation: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, color: AppTheme.teal, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          'Laporan Keuangan',
          style: TextStyle(color: AppTheme.teal, fontWeight: FontWeight.bold, fontSize: 18),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.info_outline, color: AppTheme.teal),
            onPressed: () {},
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _fetchReportData,
        color: AppTheme.teal,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Filter Section Header
              const Padding(
                padding: EdgeInsets.fromLTRB(16, 24, 16, 8),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Filter Laporan',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 4),
                    Text(
                      'Pilih kriteria laporan yang ingin Anda lihat',
                      style: TextStyle(color: Colors.grey, fontSize: 14),
                    ),
                  ],
                ),
              ),

              // Filter Forms
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Tipe Laporan', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
                    const SizedBox(height: 8),
                    _buildDropdown(
                      value: _selectedReportType,
                      items: _availableTypes.map((t) {
                        return DropdownMenuItem(value: t['id'].toString(), child: Text(t['name'].toString()));
                      }).toList(),
                      onChanged: (val) {
                        setState(() => _selectedReportType = val);
                        _fetchReportData();
                      },
                      icon: Icons.unfold_more,
                    ),
                    const SizedBox(height: 16),
                    const Text('Periode', style: TextStyle(fontSize: 14, fontWeight: FontWeight.w600)),
                    const SizedBox(height: 8),
                    _buildDropdown(
                      value: _selectedPeriod,
                      items: _availableYears.map((y) {
                        return DropdownMenuItem(value: y.toString(), child: Text('Tahun $y'));
                      }).toList(),
                      onChanged: (val) {
                        setState(() => _selectedPeriod = val);
                        _fetchReportData();
                      },
                      icon: Icons.calendar_month,
                    ),
                  ],
                ),
              ),

              // List Header
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 24, 16, 8),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Daftar Laporan',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: AppTheme.teal.withOpacity(0.1),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        '${_reports.length} Tersedia',
                        style: const TextStyle(color: AppTheme.teal, fontSize: 12, fontWeight: FontWeight.bold),
                      ),
                    ),
                  ],
                ),
              ),

              // Report List
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: _isLoading && _reports.isEmpty
                    ? const Padding(
                        padding: EdgeInsets.symmetric(vertical: 40),
                        child: Center(
                          child: CircularProgressIndicator(color: AppTheme.teal),
                        ),
                      )
                    : _error != null && _reports.isEmpty
                        ? const Padding(
                            padding: EdgeInsets.symmetric(vertical: 40),
                            child: Center(
                              child: Text('Gagal memuat data laporan'),
                            ),
                          )
                        : Column(
                            children: _reports.map((report) {
                              return _buildReportCard(
                                report['title'], 
                                report['subtitle'], 
                                report['size']
                              );
                            }).toList(),
                          ),
              ),

              // Transparency Note
              Container(
                margin: const EdgeInsets.all(16),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: AppTheme.teal.withOpacity(0.05),
                  borderRadius: BorderRadius.circular(16),
                  border: const Border(
                    left: BorderSide(color: nuGold, width: 4),
                  ),
                ),
                child: const Row(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Icon(Icons.verified, color: nuGold, size: 24),
                    SizedBox(width: 12),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Transparansi Digital',
                            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                          ),
                          SizedBox(height: 4),
                          Text(
                            'Seluruh laporan keuangan yang diunduh adalah sah dan telah ditandatangani secara digital oleh Bendahara PRNU Baktijaya.',
                            style: TextStyle(color: Colors.grey, fontSize: 12, height: 1.5),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 100),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDropdown({
    required String? value,
    required List<DropdownMenuItem<String>> items,
    required ValueChanged<String?> onChanged,
    required IconData icon,
  }) {
    // If value is not in items, set to null or first item
    String? validValue = value;
    if (value != null && !items.any((item) => item.value == value)) {
      validValue = items.isNotEmpty ? items.first.value : null;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: const Color(0xFFCEE8DB)),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<String>(
          value: validValue,
          items: items,
          onChanged: onChanged,
          isExpanded: true,
          icon: Icon(icon, color: AppTheme.teal),
          style: TextStyle(
            color: Theme.of(context).textTheme.bodyLarge?.color,
            fontSize: 16,
          ),
        ),
      ),
    );
  }

  Widget _buildReportCard(String title, String type, String size) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        children: [
          Container(
            height: 48,
            width: 48,
            decoration: BoxDecoration(
              color: Colors.red.withOpacity(0.05),
              borderRadius: BorderRadius.circular(8),
            ),
            child: const Icon(Icons.picture_as_pdf, color: Colors.red, size: 30),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                ),
                const SizedBox(height: 2),
                Row(
                  children: [
                    Text(type, style: const TextStyle(color: Colors.grey, fontSize: 12)),
                    const SizedBox(width: 8),
                    const Icon(Icons.circle, size: 4, color: Colors.grey),
                    const SizedBox(width: 8),
                    Text(size, style: const TextStyle(color: Colors.grey, fontSize: 12)),
                  ],
                ),
              ],
            ),
          ),
          Container(
            height: 40,
            width: 40,
            decoration: BoxDecoration(
              color: AppTheme.teal,
              shape: BoxShape.circle,
              boxShadow: [
                BoxShadow(
                  color: AppTheme.teal.withOpacity(0.3),
                  blurRadius: 8,
                  offset: const Offset(0, 4),
                ),
              ],
            ),
            child: const Icon(Icons.download, color: Colors.white, size: 20),
          ),
        ],
      ),
    );
  }
}
