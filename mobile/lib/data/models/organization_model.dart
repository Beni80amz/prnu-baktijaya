class OrganizationProfile {
  final Map<String, dynamic> settings;
  final List<StructureItem> structure;
  final List<VolunteerItem> volunteers;

  OrganizationProfile({
    required this.settings,
    required this.structure,
    required this.volunteers,
  });

  factory OrganizationProfile.fromJson(Map<String, dynamic> json) {
    final data = (json['data'] as Map<String, dynamic>?) ?? {};
    final settingsMap = (data['settings'] as Map<String, dynamic>?) ?? {};
    final structureList = (data['structure'] as List?) ?? [];
    final volunteerList = (data['volunteers'] as List?) ?? [];

    return OrganizationProfile(
      settings: settingsMap,
      structure: structureList.map((e) => StructureItem.fromJson(Map<String, dynamic>.from(e))).toList(),
      volunteers: volunteerList.map((e) => VolunteerItem.fromJson(Map<String, dynamic>.from(e))).toList(),
    );
  }

  String? get siteLogo => settings['site_logo']?.toString();
  String? get siteName => settings['site_name']?.toString();
  String? get visi => settings['visi']?.toString();
  String? get misi1 => settings['misi_1']?.toString();
  String? get misi2 => settings['misi_2']?.toString();
  String? get misi3 => settings['misi_3']?.toString();
  String? get address => settings['contact_address']?.toString();
  String? get email => settings['contact_email']?.toString();
  String? get phone => settings['contact_phone']?.toString();
  String? get mapLink => settings['contact_map_link']?.toString();
  String? get instagram => settings['social_instagram']?.toString();
  String? get heroTitle => settings['profile_hero_title']?.toString() ?? settings['site_name']?.toString() ?? 'PRNU Baktijaya';
  String? get description => settings['profile_description']?.toString();
  String? get image => settings['profile_image']?.toString();
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

class VolunteerItem {
  final int id;
  final String name;
  final String region;
  final String? photo;

  VolunteerItem({
    required this.id,
    required this.name,
    required this.region,
    this.photo,
  });

  factory VolunteerItem.fromJson(Map<String, dynamic> json) {
    return VolunteerItem(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      name: json['name']?.toString() ?? '',
      region: json['region']?.toString() ?? '-',
      photo: json['photo']?.toString(),
    );
  }
}
