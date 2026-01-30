import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../../core/theme/app_theme.dart';
import '../../core/constants/api_constants.dart';
import '../providers/providers.dart';

// Provider for zakat config (gold price)
final zakatConfigProvider = FutureProvider<Map<String, dynamic>>((ref) async {
  final dioClient = ref.read(dioClientProvider);
  final response = await dioClient.dio.get('zakat/config');
  return response.data;
});

class ZakatCalculatorScreen extends ConsumerStatefulWidget {
  const ZakatCalculatorScreen({super.key});

  @override
  ConsumerState<ZakatCalculatorScreen> createState() => _ZakatCalculatorScreenState();
}

class _ZakatCalculatorScreenState extends ConsumerState<ZakatCalculatorScreen> {
  String _selectedType = 'maal';
  final _currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);
  
  // Maal
  double _totalWealth = 0;
  
  // Profesi
  double _monthlySalary = 0;
  double _otherIncome = 0;
  
  // Emas
  double _goldWeight = 0;
  
  // Default gold price (will be updated from API)
  double _goldPrice = 1100000;

  @override
  void initState() {
    super.initState();
    _loadConfig();
  }

  void _loadConfig() async {
    try {
      final dioClient = ref.read(dioClientProvider);
      final response = await dioClient.dio.get('zakat/config');
      final data = response.data;
      if (data['data'] != null && data['data']['gold_price'] != null) {
        setState(() => _goldPrice = (data['data']['gold_price'] as num).toDouble());
      } else if (data['gold_price'] != null) {
         setState(() => _goldPrice = (data['gold_price'] as num).toDouble());
      }
    } catch (_) {}
  }

  double get _nisab => _goldPrice * 85;
  
  double get _maalZakat => _totalWealth >= _nisab ? _totalWealth * 0.025 : 0;
  
  double get _profesiZakat {
    final total = _monthlySalary + _otherIncome;
    return total * 0.025;
  }
  
  double get _emasZakat => _goldWeight >= 85 ? _goldWeight * _goldPrice * 0.025 : 0;

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;

    return Scaffold(
      backgroundColor: isDark ? const Color(0xFF102210) : const Color(0xFFF5F8F8),
      body: SafeArea(
        child: SingleChildScrollView(
          child: Column(
            children: [
              // Header
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Row(
                  children: [
                    IconButton(
                      icon: const Icon(Icons.arrow_back_ios, color: AppTheme.teal, size: 20),
                      onPressed: () {
                        if (Navigator.canPop(context)) {
                          Navigator.pop(context);
                        }
                      },
                      padding: EdgeInsets.zero,
                      constraints: const BoxConstraints(),
                    ),
                    const SizedBox(width: 8),
                    const Text(
                      'Kalkulator Zakat',
                      style: TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.teal,
                      ),
                    ),
                  ],
                ),
              ),

              // Type Selection
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  children: [
                    _buildTypeButton('maal', 'Zakat Maal', Icons.payments),
                    const SizedBox(width: 8),
                    _buildTypeButton('profesi', 'Penghasilan', Icons.work),
                    const SizedBox(width: 8),
                    _buildTypeButton('emas', 'Emas', Icons.diamond),
                  ],
                ),
              ),

              const SizedBox(height: 16),

              // Nisab Info
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.green.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: Colors.green.withOpacity(0.2)),
                  ),
                  child: Row(
                    children: [
                      const Icon(Icons.info_outline, color: Colors.green, size: 20),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            const Text(
                              'Informasi Nisab',
                              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.green),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              'Harga Emas: ${_currencyFormat.format(_goldPrice)}/gram\nNisab (85gr): ${_currencyFormat.format(_nisab)}',
                              style: TextStyle(fontSize: 11, color: Colors.green[700]),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 20),

              // Calculator Form
              Padding(
                padding: const EdgeInsets.all(16),
                child: Container(
                  padding: const EdgeInsets.all(20),
                  decoration: BoxDecoration(
                    color: isDark ? Colors.grey[900] : Colors.white,
                    borderRadius: BorderRadius.circular(20),
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withOpacity(0.05),
                        blurRadius: 10,
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: _buildCalculatorContent(isDark),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildTypeButton(String type, String label, IconData icon) {
    final isSelected = _selectedType == type;
    return Expanded(
      child: GestureDetector(
        onTap: () => setState(() => _selectedType = type),
        child: Container(
          padding: const EdgeInsets.symmetric(vertical: 14),
          decoration: BoxDecoration(
            color: isSelected ? AppTheme.teal : (Theme.of(context).brightness == Brightness.dark ? Colors.grey[800] : Colors.white),
            borderRadius: BorderRadius.circular(12),
            boxShadow: isSelected
                ? [BoxShadow(color: AppTheme.teal.withOpacity(0.3), blurRadius: 8, offset: const Offset(0, 4))]
                : null,
          ),
          child: Column(
            children: [
              Icon(icon, color: isSelected ? Colors.white : Colors.grey, size: 22),
              const SizedBox(height: 4),
              Text(
                label,
                style: TextStyle(
                  color: isSelected ? Colors.white : Colors.grey,
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCalculatorContent(bool isDark) {
    switch (_selectedType) {
      case 'maal':
        return _buildMaalCalculator(isDark);
      case 'profesi':
        return _buildProfesiCalculator(isDark);
      case 'emas':
        return _buildEmasCalculator(isDark);
      default:
        return const SizedBox();
    }
  }

  Widget _buildMaalCalculator(bool isDark) {
    return Column(
      children: [
        _buildInputField(
          label: 'Total Harta (Tabungan/Saham/Piutang)',
          prefix: 'Rp',
          onChanged: (v) => setState(() => _totalWealth = double.tryParse(v) ?? 0),
          isDark: isDark,
        ),
        const SizedBox(height: 24),
        _buildResultCard(
          label: 'Total Zakat Maal Anda',
          value: _maalZakat,
          color: AppTheme.teal,
        ),
      ],
    );
  }

  Widget _buildProfesiCalculator(bool isDark) {
    return Column(
      children: [
        _buildInputField(
          label: 'Gaji Bulanan',
          prefix: 'Rp',
          onChanged: (v) => setState(() => _monthlySalary = double.tryParse(v) ?? 0),
          isDark: isDark,
        ),
        const SizedBox(height: 16),
        _buildInputField(
          label: 'Penghasilan Lain',
          prefix: 'Rp',
          onChanged: (v) => setState(() => _otherIncome = double.tryParse(v) ?? 0),
          isDark: isDark,
        ),
        const SizedBox(height: 24),
        _buildResultCard(
          label: 'Zakat Profesi Per Bulan',
          value: _profesiZakat,
          color: const Color(0xFFB8860B),
        ),
      ],
    );
  }

  Widget _buildEmasCalculator(bool isDark) {
    return Column(
      children: [
        _buildInputField(
          label: 'Berat Emas',
          suffix: 'gram',
          onChanged: (v) => setState(() => _goldWeight = double.tryParse(v) ?? 0),
          isDark: isDark,
        ),
        const SizedBox(height: 24),
        _buildResultCard(
          label: 'Zakat Emas Anda',
          value: _emasZakat,
          color: Colors.amber[700]!,
        ),
        if (_goldWeight > 0 && _goldWeight < 85)
          Padding(
            padding: const EdgeInsets.only(top: 12),
            child: Text(
              'Emas belum mencapai nisab (85 gram)',
              style: TextStyle(color: Colors.orange[700], fontSize: 12, fontStyle: FontStyle.italic),
            ),
          ),
      ],
    );
  }

  Widget _buildInputField({
    required String label,
    required Function(String) onChanged,
    required bool isDark,
    String? prefix,
    String? suffix,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14)),
        const SizedBox(height: 8),
        TextField(
          keyboardType: TextInputType.number,
          inputFormatters: [FilteringTextInputFormatter.digitsOnly],
          onChanged: onChanged,
          decoration: InputDecoration(
            prefixText: prefix != null ? '$prefix ' : null,
            suffixText: suffix,
            filled: true,
            fillColor: isDark ? Colors.grey[800] : Colors.grey[100],
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(12),
              borderSide: BorderSide.none,
            ),
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
          ),
          style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
        ),
      ],
    );
  }

  Widget _buildResultCard({required String label, required double value, required Color color}) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        children: [
          Text(
            label,
            style: TextStyle(
              color: color,
              fontWeight: FontWeight.bold,
              fontSize: 12,
              letterSpacing: 1,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            _currencyFormat.format(value),
            style: TextStyle(
              color: color,
              fontWeight: FontWeight.w900,
              fontSize: 28,
            ),
          ),
        ],
      ),
    );
  }
}
