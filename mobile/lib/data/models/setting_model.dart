class AppSetting {
  final String siteName;
  final String? siteLogo;

  AppSetting({
    required this.siteName,
    this.siteLogo,
  });

  factory AppSetting.fromJson(Map<String, dynamic> json) {
    final data = json['data'] ?? {};
    return AppSetting(
      siteName: data['site_name'] ?? 'PRNU Baktijaya',
      siteLogo: data['site_logo'],
    );
  }
}
