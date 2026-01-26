class Dawuh {
  final int id;
  final String quote;
  final String ulamaName;
  final String? image;

  Dawuh({
    required this.id,
    required this.quote,
    required this.ulamaName,
    this.image,
  });

  factory Dawuh.fromJson(Map<String, dynamic> json) {
    return Dawuh(
      id: json['id'],
      quote: json['quote'] ?? '',
      ulamaName: json['ulama_name'] ?? '',
      image: json['image'],
    );
  }
}
