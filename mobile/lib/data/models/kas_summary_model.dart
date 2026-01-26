class KasSummary {
  final double generalBalance;
  final String lastUpdate;

  KasSummary({
    required this.generalBalance,
    required this.lastUpdate,
  });

  factory KasSummary.fromJson(Map<String, dynamic> json) {
    final data = json['data'] ?? {};
    final balances = data['balances'] ?? {};
    return KasSummary(
      generalBalance: (balances['general'] ?? 0.0).toDouble(),
      lastUpdate: data['last_update'] ?? '',
    );
  }
}
