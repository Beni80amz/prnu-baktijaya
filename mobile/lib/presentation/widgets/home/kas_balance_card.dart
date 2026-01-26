import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import '../../../../core/theme/app_theme.dart';
import '../../screens/kas_digital_screen.dart';
import '../../screens/financial_report_screen.dart';
import '../../providers/providers.dart';

class KasBalanceCard extends ConsumerWidget {
  const KasBalanceCard({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final kasAsync = ref.watch(kasSummaryProvider);
    final currencyFormat = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp ', decimalDigits: 0);

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.black.withOpacity(Theme.of(context).brightness == Brightness.dark ? 0.2 : 1.0),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.white10),
      ),
      child: kasAsync.when(
        data: (kas) => _buildContent(context, kas, currencyFormat),
        loading: () => const Center(
          child: Padding(
            padding: EdgeInsets.symmetric(vertical: 20),
            child: CircularProgressIndicator(color: AppTheme.gold),
          ),
        ),
        error: (err, stack) => Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisSize: MainAxisSize.min,
                children: [
                  const Text('Gagal memuat saldo', style: TextStyle(color: Colors.white70, fontSize: 12, fontWeight: FontWeight.bold)),
                  Text(err.toString(), style: const TextStyle(color: Colors.white38, fontSize: 9), overflow: TextOverflow.ellipsis),
                ],
              ),
            ),
            IconButton(
              icon: const Icon(Icons.refresh, color: AppTheme.gold),
              onPressed: () => ref.refresh(kasSummaryProvider),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildContent(BuildContext context, dynamic kas, NumberFormat currencyFormat) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'SALDO KAS ORGANISASI • ${kas.lastUpdate}',
              style: const TextStyle(
                color: Colors.white54,
                fontSize: 10,
                fontWeight: FontWeight.bold,
                letterSpacing: 1.2,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              currencyFormat.format(kas.generalBalance),
              style: const TextStyle(
                color: Colors.white,
                fontSize: 20,
                fontWeight: FontWeight.bold,
                fontFamily: 'monospace',
              ),
            ),
          ],
        ),
        ElevatedButton(
          onPressed: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => const FinancialReportScreen()),
            );
          },
          style: ElevatedButton.styleFrom(
            backgroundColor: AppTheme.gold,
            foregroundColor: AppTheme.black,
            elevation: 0,
            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 0),
            textStyle: const TextStyle(fontSize: 11, fontWeight: FontWeight.w900, letterSpacing: -0.5),
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
          ),
          child: const Text('AUDIT REPORT'),
        ),
      ],
    );
  }
}
