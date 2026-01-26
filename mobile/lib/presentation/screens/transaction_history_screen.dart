import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../core/theme/app_theme.dart';
import '../../core/network/dio_client.dart';
import '../../core/constants/api_constants.dart';
import 'financial_report_screen.dart';

class TransactionHistoryScreen extends StatefulWidget {
  const TransactionHistoryScreen({super.key});

  @override
  State<TransactionHistoryScreen> createState() => _TransactionHistoryScreenState();
}

class _TransactionHistoryScreenState extends State<TransactionHistoryScreen> {
  bool _isLoading = true;
  String? _error;
  Map<String, dynamic>? _historyData;
  String _searchQuery = '';
  String _selectedType = 'all'; // 'all', 'income', 'expense'
  final TextEditingController _searchController = TextEditingController();
  final _currencyFormat = NumberFormat.currency(locale: 'id', symbol: 'Rp ', decimalDigits: 0);

  @override
  void initState() {
    super.initState();
    _fetchHistory();
  }

  Future<void> _fetchHistory() async {
    setState(() {
      _isLoading = true;
      _error = null;
    });

    try {
      final dio = DioClient().dio;
      final params = <String, dynamic>{};
      if (_searchQuery.isNotEmpty) params['search'] = _searchQuery;
      if (_selectedType != 'all') params['type'] = _selectedType;

      final response = await dio.get(ApiConstants.kasHistory, queryParameters: params);
      
      if (response.data['success']) {
        setState(() {
          _historyData = response.data['data'];
          _isLoading = false;
        });
      } else {
        setState(() {
          _error = 'Gagal memuat riwayat';
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

  void _onSearchChanged(String value) {
    setState(() {
      _searchQuery = value;
    });
    // Debounce or immediate? Immediate for now for simplicity
    _fetchHistory();
  }

  void _onTypeChanged(String type) {
    setState(() {
      _selectedType = type;
    });
    _fetchHistory();
  }

  String _formatAmount(dynamic amount) {
    if (amount == null) return 'Rp 0';
    return _currencyFormat.format(amount is String ? double.parse(amount) : amount);
  }

  IconData _getIconData(String? iconType) {
    switch (iconType) {
      case 'person_heart': return Icons.favorite;
      case 'volunteer_activism': return Icons.volunteer_activism;
      case 'payments': return Icons.payments;
      case 'groups': return Icons.groups;
      case 'settings': return Icons.settings;
      case 'inventory_2': return Icons.inventory_2;
      default: return Icons.account_balance_wallet;
    }
  }

  Color _getIconColor(String? iconType, String type) {
    if (type == 'income') return AppTheme.teal;
    if (iconType == 'groups') return Colors.orange;
    if (iconType == 'settings') return Colors.blue;
    return Colors.red;
  }

  @override
  Widget build(BuildContext context) {
    final summary = _historyData?['summary'];
    final groups = _historyData?['transactions'] as List<dynamic>? ?? [];

    return Scaffold(
      backgroundColor: Theme.of(context).brightness == Brightness.dark 
          ? const Color(0xFF102219) 
          : const Color(0xFFF5F8F7),
      appBar: AppBar(
        titleSpacing: 0,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios, size: 20),
          onPressed: () => Navigator.pop(context),
        ),
        title: const Text(
          'Riwayat Transaksi Kas',
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const FinancialReportScreen()),
              );
            },
          ),
          const SizedBox(width: 8),
        ],
        elevation: 0,
        backgroundColor: Colors.transparent,
      ),
      body: Column(
        children: [
          // Search & Filters Header
          Container(
            padding: const EdgeInsets.all(16),
            child: Column(
              children: [
                Row(
                  children: [
                    Expanded(
                      child: Container(
                        height: 48,
                        decoration: BoxDecoration(
                          color: Theme.of(context).cardColor,
                          borderRadius: BorderRadius.circular(12),
                          border: Border.all(color: Colors.grey.withOpacity(0.2)),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.02),
                              blurRadius: 10,
                              offset: const Offset(0, 4),
                            ),
                          ],
                        ),
                        child: TextField(
                          controller: _searchController,
                          onChanged: _onSearchChanged,
                          decoration: const InputDecoration(
                            hintText: 'Cari transaksi...',
                            prefixIcon: Icon(Icons.search, color: AppTheme.teal),
                            border: InputBorder.none,
                            contentPadding: EdgeInsets.symmetric(vertical: 12),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 12),
                    Container(
                      height: 48,
                      width: 48,
                      decoration: BoxDecoration(
                        color: Theme.of(context).cardColor,
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: Colors.grey.withOpacity(0.2)),
                      ),
                      child: IconButton(
                        icon: const Icon(Icons.tune, color: Colors.grey),
                        onPressed: () {},
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      _buildFilterChip('Semua', 'all'),
                      const SizedBox(width: 8),
                      _buildFilterChip('Pemasukan', 'income'),
                      const SizedBox(width: 8),
                      _buildFilterChip('Pengeluaran', 'expense'),
                      const SizedBox(width: 8),
                      _buildFilterChip('Bulan Ini', 'month', icon: Icons.expand_more),
                    ],
                  ),
                ),
              ],
            ),
          ),

          Expanded(
            child: RefreshIndicator(
              onRefresh: _fetchHistory,
              color: AppTheme.teal,
              child: _isLoading && _historyData == null
                ? const Center(child: CircularProgressIndicator(color: AppTheme.teal))
                : _error != null && _historyData == null
                  ? Center(child: Text(_error!))
                  : ListView(
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      children: [
                        // Summary Cards
                        Row(
                          children: [
                            Expanded(
                              child: _buildSummaryCard(
                                title: 'Total Masuk',
                                amount: _formatAmount(summary?['total_income']),
                                icon: Icons.arrow_downward,
                                color: AppTheme.teal,
                                trend: '+8% bulan ini',
                                trendUp: true,
                              ),
                            ),
                            const SizedBox(width: 12),
                            Expanded(
                              child: _buildSummaryCard(
                                title: 'Total Keluar',
                                amount: _formatAmount(summary?['total_expense']),
                                icon: Icons.arrow_upward,
                                color: Colors.red,
                                trend: '-5% bulan ini',
                                trendUp: false,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 24),

                        // Groups list
                        if (groups.isEmpty)
                          const Padding(
                            padding: EdgeInsets.symmetric(vertical: 40),
                            child: Center(child: Text('Tidak ada transaksi')),
                          )
                        else
                          ...groups.map((group) {
                            return _buildGroup(group);
                          }),
                          
                        const SizedBox(height: 100),
                      ],
                    ),
            ),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        backgroundColor: AppTheme.teal,
        child: const Icon(Icons.add, color: Colors.white, size: 30),
      ),
    );
  }

  Widget _buildFilterChip(String label, String type, {IconData? icon}) {
    final isSelected = _selectedType == type;
    return InkWell(
      onTap: () => _onTypeChanged(type),
      child: Container(
        height: 32,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.teal : Theme.of(context).cardColor,
          borderRadius: BorderRadius.circular(20),
          border: isSelected ? null : Border.all(color: Colors.grey.withOpacity(0.2)),
        ),
        child: Row(
          mainAxisSize: MainAxisSize.min,
          children: [
            Text(
              label,
              style: TextStyle(
                color: isSelected ? Colors.white : Colors.grey[600],
                fontSize: 12,
                fontWeight: isSelected ? FontWeight.bold : FontWeight.w500,
              ),
            ),
            if (icon != null) ...[
              const SizedBox(width: 4),
              Icon(icon, size: 16, color: isSelected ? Colors.white : Colors.grey[600]),
            ],
          ],
        ),
      ),
    );
  }

  Widget _buildSummaryCard({
    required String title,
    required String amount,
    required IconData icon,
    required Color color,
    required String trend,
    required bool trendUp,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withOpacity(0.2)),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(4),
                decoration: BoxDecoration(
                  color: color.withOpacity(0.2),
                  shape: BoxShape.circle,
                ),
                child: Icon(icon, color: color, size: 14),
              ),
              const SizedBox(width: 8),
              Text(
                title.toUpperCase(),
                style: TextStyle(
                  color: Colors.grey[600],
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                  letterSpacing: 0.5,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Text(
            amount,
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16),
          ),
          const SizedBox(height: 4),
          Row(
            children: [
              Icon(
                trendUp ? Icons.trending_up : Icons.trending_down,
                size: 14,
                color: color,
              ),
              const SizedBox(width: 4),
              Text(
                trend,
                style: TextStyle(color: color, fontSize: 10, fontWeight: FontWeight.bold),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildGroup(Map<String, dynamic> group) {
    final items = group['items'] as List<dynamic>;
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Padding(
          padding: const EdgeInsets.only(left: 4, bottom: 12),
          child: Text(
            group['label'].toString().toUpperCase(),
            style: TextStyle(
              color: Colors.grey[500],
              fontSize: 11,
              fontWeight: FontWeight.bold,
              letterSpacing: 1.2,
            ),
          ),
        ),
        Container(
          decoration: BoxDecoration(
            color: Theme.of(context).cardColor,
            borderRadius: BorderRadius.circular(20),
            border: Border.all(color: Colors.grey.withOpacity(0.1)),
          ),
          child: Column(
            children: items.asMap().entries.map((entry) {
              final index = entry.key;
              final item = entry.value;
              return Column(
                children: [
                  _buildTransactionItem(item),
                  if (index < items.length - 1)
                    Divider(height: 1, color: Colors.grey.withOpacity(0.1), indent: 70),
                ],
              );
            }).toList(),
          ),
        ),
        const SizedBox(height: 24),
      ],
    );
  }

  Widget _buildTransactionItem(Map<String, dynamic> item) {
    final isIncome = item['type'] == 'income';
    final amountText = _formatAmount(item['amount']);
    final iconType = item['icon_type'] as String?;

    return Container(
      padding: const EdgeInsets.all(16),
      child: Row(
        children: [
          Container(
            height: 40,
            width: 40,
            decoration: BoxDecoration(
              color: _getIconColor(iconType, item['type']).withOpacity(0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(
              _getIconData(iconType),
              color: _getIconColor(iconType, item['type']),
              size: 20,
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  item['title'],
                  style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, height: 1.2),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 4),
                Text(
                  '${item['category']} • ${item['time']}',
                  style: TextStyle(color: Colors.grey[500], fontSize: 11),
                ),
              ],
            ),
          ),
          Column(
            crossAxisAlignment: CrossAxisAlignment.end,
            children: [
              Text(
                '${isIncome ? '+' : '-'}$amountText',
                style: TextStyle(
                  color: isIncome ? AppTheme.teal : Colors.red,
                  fontWeight: FontWeight.bold,
                  fontSize: 14,
                ),
              ),
              const SizedBox(height: 4),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                decoration: BoxDecoration(
                  color: (isIncome ? AppTheme.teal : Colors.red).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(4),
                ),
                child: Text(
                  isIncome ? 'Pemasukan' : 'Pengeluaran',
                  style: TextStyle(
                    color: isIncome ? AppTheme.teal : Colors.red,
                    fontSize: 10,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
