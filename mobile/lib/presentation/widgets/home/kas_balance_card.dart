import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../screens/kas_digital_screen.dart';

class KasBalanceCard extends StatelessWidget {
  const KasBalanceCard({super.key});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.black.withOpacity(Theme.of(context).brightness == Brightness.dark ? 0.2 : 1.0),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: Colors.white10),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          const Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                'SALDO KAS ORGANISASI',
                style: TextStyle(
                  color: Colors.white54,
                  fontSize: 10,
                  fontWeight: FontWeight.bold,
                  letterSpacing: 1.2,
                ),
              ),
              SizedBox(height: 4),
              Text(
                'Rp 12.450.000',
                style: TextStyle(
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
                MaterialPageRoute(builder: (context) => const KasDigitalScreen()),
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
      ),
    );
  }
}
