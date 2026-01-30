class AppSetting {
  final String siteName;
  final String? siteLogo;
  final String? socialYoutube;
  final bool isLive;

  AppSetting({
    required this.siteName,
    this.siteLogo,
    this.socialYoutube,
    this.isLive = false,
  });

  factory AppSetting.fromJson(Map<String, dynamic> json) {
    final data = json['data'] ?? {};
    return AppSetting(
      siteName: data['site_name'] ?? 'PRNU Baktijaya',
      siteLogo: data['site_logo'],
      socialYoutube: data['social_youtube'],
      isLive: data['is_live'] ?? false,
    );
  }
}
