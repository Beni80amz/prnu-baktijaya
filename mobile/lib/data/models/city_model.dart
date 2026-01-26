class City {
  final String id;
  final String name;

  City({
    required this.id,
    required this.name,
  });

  factory City.fromJson(Map<String, dynamic> json) {
    return City(
      id: json['id'] ?? '',
      name: json['lokasi'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'lokasi': name,
    };
  }
}
