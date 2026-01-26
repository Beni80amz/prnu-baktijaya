class OrganizationProfile {
  final Map<String, dynamic> settings;
  final List<StructureItem> structure;

  OrganizationProfile({
    required this.settings,
    required this.structure,
  });

  factory OrganizationProfile.fromJson(Map<String, dynamic> json) {
    final data = (json['data'] as Map<String, dynamic>?) ?? {};
    final settingsMap = (data['settings'] as Map<String, dynamic>?) ?? {};
    final structureList = (data['structure'] as List?) ?? [];

    return OrganizationProfile(
      settings: settingsMap,
      structure: structureList.map((e) => StructureItem.fromJson(e as Map<String, dynamic>)).toList(),
    );
  }

  String? get siteLogo => settings['site_logo'];
  String? get visi => settings['visi'];
  String? get misi1 => settings['misi_1'];
  String? get misi2 => settings['misi_2'];
  String? get misi3 => settings['misi_3'];
  String? get address => settings['contact_address'];
  String? get email => settings['contact_email'];
  String? get phone => settings['contact_phone'];
  String? get mapLink => settings['contact_map_link'];
  String? get instagram => settings['social_instagram'];
  String? get heroTitle => (settings['profile_hero_title'] as String?) ?? (settings['site_name'] as String?) ?? 'PRNU Baktijaya';
  String? get description => settings['profile_description'];
  String? get image => settings['profile_image'];
}

class StructureItem {
  final int id;
  final String name;
  final String position;
  final String type;
  final String? photo;

  StructureItem({
    required this.id,
    required this.name,
    required this.position,
    required this.type,
    this.photo,
  });

  factory StructureItem.fromJson(Map<String, dynamic> json) {
    return StructureItem(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      name: json['name']?.toString() ?? '',
      position: json['position']?.toString() ?? '',
      type: json['type']?.toString() ?? 'Lainnya',
      photo: json['photo']?.toString(),
    );
  }
}
