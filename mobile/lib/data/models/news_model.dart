class News {
  final int id;
  final String title;
  final String slug;
  final String? excerpt;
  final String? content;
  final String? image;
  final String publishedAt;

  News({
    required this.id,
    required this.title,
    required this.slug,
    this.excerpt,
    this.content,
    this.image,
    required this.publishedAt,
  });

  factory News.fromJson(Map<String, dynamic> json) {
    return News(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      title: json['title'] ?? '',
      slug: json['slug'] ?? '',
      excerpt: json['excerpt'],
      content: json['content'],
      image: json['image'], // Ensure backend returns full URL or handle it here
      publishedAt: json['published_at'] ?? '',
    );
  }
}
