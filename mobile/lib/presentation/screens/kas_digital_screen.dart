import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../core/theme/app_theme.dart';
import '../../core/network/dio_client.dart';
import '../../core/constants/api_constants.dart';
import 'transaction_history_screen.dart';
import 'financial_report_screen.dart';

class KasDigitalScreen extends StatefulWidget {
  const KasDigitalScreen({super.key});

  @override
  State<KasDigitalScreen> createState() => _KasDigitalScreenState();
}

class _KasDigitalScreenState extends State<KasDigitalScreen> {
  bool _isLoading = true;
  String? _error;
  Map<String, dynamic>? _kasData;
  final _currencyFormat = NumberFormat.currency(locale: 'id', symbol: 'Rp ', decimalDigits: 0);

  @override
  void initState() {
    super.initState();
    _fetchKasData();
  }

  Future<void> _fetchKasData() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final dio = DioClient().dio;
      final response = await dio.get(ApiConstants.kas);
      
      if (response.data['success']) {
        setState(() {
          _kasData = response.data['data'];
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Failed to load data';
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _error = 'Error: $e';
        _isLoading = false;
      });
    }
  }

  String _formatAmount(dynamic amount) {
    if (amount == null) return 'Rp 0';
    return _currencyFormat.format(amount is String ? double.parse(amount) : amount);
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading && _kasData == null) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator(color: AppTheme.teal)),
      );
    }

    if (_error != null && _kasData == null) {
      return Scaffold(
        appBar: AppBar(title: const Text('Kas & Transparansi [v2.1]')),
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(_error!),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: _fetchKasData,
                child: const Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    final balances = _kasData?['balances'];
    final transactions = _kasData?['recent_transactions'] as List<dynamic>? ?? [];

    return Scaffold(
      backgroundColor: Theme.of(context).brightness == Brightness.dark 
          ? const Color(0xFF0F2323) 
          : const Color(0xFFF5F8F8),
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, size: 20),
          onPressed: () {
            if (Navigator.canPop(context)) {
              Navigator.pop(context);
            } else {
              // Fail-safe to avoid being stuck
              Navigator.of(context).pushReplacementNamed('/'); 
            }
          },
        ),
        title: const Text(
          'Kas & Transparansi [Live]',
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
        ),
        centerTitle: true,
        actions: [
          IconButton(
            icon: const Icon(Icons.info_outline),
            onPressed: () {},
          ),
        ],
        elevation: 0,
        backgroundColor: Colors.transparent,
      ),
      body: RefreshIndicator(
        onRefresh: _fetchKasData,
        color: AppTheme.teal,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text(
                      'PRNU Baktijaya',
                      style: TextStyle(
                        color: AppTheme.teal,
                        fontWeight: FontWeight.w600,
                        fontSize: 14,
                      ),
                    ),
                    const Text(
                      'Saldo Kas Digital',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 24,
                      ),
                    ),
                    Text(
                      'Pembaruan terakhir: ${_kasData?['last_update'] ?? 'Baru saja'}',
                      style: TextStyle(
                        color: Colors.grey[600],
                        fontSize: 12,
                      ),
                    ),
                  ],
                ),
              ),
              
              // Summary Cards
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                child: Column(
                  children: [
                    _buildBalanceCard(
                      title: 'KAS ORGANISASI',
                      subtitle: 'Tersedia di Kas Utama',
                      amount: _formatAmount(balances?['general']),
                      icon: Icons.account_balance_wallet,
                      gradient: const [AppTheme.teal, Color(0xFF004D4D)],
                      tag: 'Active',
                      buttonLabel: 'Detail Kas',
                    ),
                    const SizedBox(height: 16),
                    _buildBalanceCard(
                      title: 'INFAQ & SHODAQOH',
                      subtitle: 'Jumlah Pemasukan Infaq/Shodaqoh',
                      amount: _formatAmount(balances?['infaq_shodaqoh']),
                      icon: Icons.volunteer_activism,
                      gradient: const [Color(0xFF10B981), Color(0xFF065F46)],
                      tag: 'Dinamis',
                      buttonLabel: 'Detail Infaq',
                    ),
                    const SizedBox(height: 16),
                    _buildBalanceCard(
                      title: 'PENHIMPUNAN KOIN NU',
                      subtitle: 'Jumlah Penghimpunan Koin NU',
                      amount: _formatAmount(balances?['koin_nu']),
                      icon: Icons.monetization_on,
                      gradient: const [Color(0xFF059669), Color(0xFF064E3B)],
                      tag: 'Dinamis',
                      buttonLabel: 'Detail Koin',
                    ),
                    const SizedBox(height: 16),
                    _buildBalanceCard(
                      title: 'TOTAL PENGELUARAN',
                      subtitle: 'Seluruh Biaya & Pengeluaran',
                      amount: _formatAmount(balances?['total_expense']),
                      icon: Icons.trending_down,
                      gradient: const [Color(0xFFEF4444), Color(0xFF991B1B)],
                      tag: 'Dinamis',
                      buttonLabel: 'Detail Biaya',
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // Trends Header
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Tren Keuangan',
                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
                    ),
                    Container(
                      padding: const EdgeInsets.all(4),
                      decoration: BoxDecoration(
                        color: Colors.grey[200],
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Row(
                        children: [
                          _buildTrendButton('6Bln', true),
                          _buildTrendButton('1Thn', false),
                        ],
                      ),
                    ),
                  ],
                ),
              ),

              // Dynamic Chart
              _buildDynamicChart(_kasData?['monthly_trends'] as List<dynamic>?),

              // History Header
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    const Text(
                      'Riwayat Transaksi',
                      style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(builder: (context) => const TransactionHistoryScreen()),
                        );
                      },
                      child: const Text('Lihat Semua', style: TextStyle(color: AppTheme.teal)),
                    ),
                  ],
                ),
              ),

              // Transaction List
              if (transactions.isEmpty)
                const Padding(
                  padding: EdgeInsets.all(32.0),
                  child: Center(child: Text('Belum ada transaksi')),
                )
              else
                ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: transactions.length,
                  itemBuilder: (context, index) {
                    final item = transactions[index];
                    return _buildTransactionItem(
                      title: item['title'],
                      date: item['date'],
                      amount: item['amount'],
                      type: item['type'],
                      account: item['fund_type'] == 'general' ? 'Kas Organisasi' : 'LAZISNU',
                    );
                  },
                ),

              const SizedBox(height: 100), // Space for sticky button
            ],
          ),
        ),
      ),
      bottomSheet: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Theme.of(context).scaffoldBackgroundColor.withOpacity(0.9),
          border: const Border(top: BorderSide(color: Color(0xFFEEEEEE))),
        ),
        child: SizedBox(
          width: double.infinity,
          height: 56,
          child: ElevatedButton.icon(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const FinancialReportScreen()),
              );
            },
            icon: const Icon(Icons.picture_as_pdf),
            label: const Text('Download Laporan PDF'),
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.teal,
              foregroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              textStyle: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDynamicChart(List<dynamic>? trends) {
    if (trends == null || trends.isEmpty) {
      return Container(
        margin: const EdgeInsets.all(16),
        height: 200,
        decoration: BoxDecoration(
          color: Theme.of(context).cardColor,
          borderRadius: BorderRadius.circular(16),
        ),
        child: const Center(child: Text('Data tren belum tersedia')),
      );
    }

    double maxVal = 0;
    for (var t in trends) {
      if (t['income'] > maxVal) maxVal = t['income'].toDouble();
      if (t['expense'] > maxVal) maxVal = t['expense'].toDouble();
    }
    if (maxVal == 0) maxVal = 1;

    return Container(
      margin: const EdgeInsets.all(16),
      padding: const EdgeInsets.all(16),
      height: 220,
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.withOpacity(0.1)),
      ),
      child: Column(
        children: [
          Expanded(
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.end,
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: trends.map((t) {
                final incomeFactor = (t['income'] / maxVal).toDouble();
                final isMax = t['income'] >= maxVal && maxVal > 0;
                return _buildBar(incomeFactor, t['month'], isMax: isMax);
              }).toList(),
            ),
          ),
          const Divider(height: 32),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              _buildLegend(AppTheme.teal, 'Pemasukan'),
              const SizedBox(width: 16),
              _buildLegend(Colors.red[400]!, 'Pengeluaran'),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildBalanceCard({
    required String title,
    required String subtitle,
    required String amount,
    required IconData icon,
    required List<Color> gradient,
    required String tag,
    required String buttonLabel,
  }) {
    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        gradient: LinearGradient(
          colors: gradient,
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: gradient[0].withOpacity(0.3),
            blurRadius: 10,
            offset: const Offset(0, 5),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: Colors.white, size: 24),
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                decoration: BoxDecoration(
                  color: Colors.white.withOpacity(0.1),
                  borderRadius: BorderRadius.circular(20),
                  border: Border.all(color: Colors.white.withOpacity(0.2)),
                ),
                child: Text(
                  tag,
                  style: const TextStyle(color: Colors.white, fontSize: 10, fontWeight: FontWeight.bold),
                ),
              ),
            ],
          ),
          const SizedBox(height: 20),
          Text(
            title,
            style: TextStyle(
              color: Colors.white.withOpacity(0.8),
              fontSize: 12,
              fontWeight: FontWeight.bold,
              letterSpacing: 1,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            amount,
            style: const TextStyle(
              color: Colors.white,
              fontSize: 28,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 20),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                subtitle,
                style: TextStyle(
                  color: Colors.white.withOpacity(0.6),
                  fontSize: 12,
                ),
              ),
              TextButton(
                onPressed: () {},
                style: TextButton.styleFrom(
                  backgroundColor: Colors.white.withOpacity(0.2),
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                ),
                child: Text(
                  buttonLabel,
                  style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildTrendButton(String label, bool isActive) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(
        color: isActive ? Colors.white : Colors.transparent,
        borderRadius: BorderRadius.circular(6),
        boxShadow: isActive ? [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 4)] : null,
      ),
      child: Text(
        label,
        style: TextStyle(
          color: isActive ? AppTheme.teal : Colors.grey,
          fontSize: 12,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }

  Widget _buildBar(double factor, String month, {bool isMax = false}) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.end,
      children: [
        if (isMax)
          Container(
            padding: const EdgeInsets.symmetric(horizontal: 4, vertical: 2),
            margin: const EdgeInsets.only(bottom: 4),
            decoration: BoxDecoration(
              color: AppTheme.teal,
              borderRadius: BorderRadius.circular(4),
            ),
            child: const Text('Max', style: TextStyle(color: Colors.white, fontSize: 8)),
          ),
        Container(
          width: 32,
          height: 120 * factor,
          decoration: BoxDecoration(
            color: isMax ? AppTheme.teal : AppTheme.teal.withOpacity(0.3),
            borderRadius: BorderRadius.circular(6),
          ),
        ),
        const SizedBox(height: 8),
        Text(
          month,
          style: const TextStyle(fontSize: 10, color: Colors.grey),
        ),
      ],
    );
  }

  Widget _buildLegend(Color color, String label) {
    return Row(
      children: [
        Container(
          width: 12,
          height: 12,
          decoration: BoxDecoration(color: color, borderRadius: BorderRadius.circular(3)),
        ),
        const SizedBox(width: 8),
        Text(label, style: const TextStyle(fontSize: 12, color: Colors.grey)),
      ],
    );
  }

  Widget _buildTransactionItem({
    required String title,
    required String date,
    required dynamic amount,
    required String type,
    required String account,
  }) {
    final isIncome = type == 'income';
    final formattedAmount = _currencyFormat.format(amount is String ? double.parse(amount) : amount);

    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Theme.of(context).cardColor,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.grey.withOpacity(0.05)),
      ),
      child: Row(
        children: [
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: (isIncome ? Colors.green : Colors.red).withOpacity(0.1),
              shape: BoxShape.circle,
            ),
            child: Icon(
              isIncome ? Icons.arrow_downward : Icons.arrow_upward,
              color: isIncome ? Colors.green : Colors.red,
              size: 20,
            ),
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
                const SizedBox(height: 4),
                Text(
                  '$date • $account',
                  style: TextStyle(color: Colors.grey[500], fontSize: 11),
                ),
              ],
            ),
          ),
          Text(
            '${isIncome ? '+' : '-'} $formattedAmount',
            style: TextStyle(
              color: isIncome ? Colors.green : Colors.red,
              fontWeight: FontWeight.bold,
              fontSize: 14,
            ),
          ),
        ],
      ),
    );
  }
}
