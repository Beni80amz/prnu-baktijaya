class GalleryItem {
  final int id;
  final int? categoryId;
  final String title;
  final String? slug;
  final String? description;
  final String type; // 'photo' or 'video'
  final List<String> images;
  final String? videoUrl;
  final DateTime? eventDate;
  final bool isFeatured;

  GalleryItem({
    required this.id,
    this.categoryId,
    required this.title,
    this.slug,
    this.description,
    required this.type,
    required this.images,
    this.videoUrl,
    this.eventDate,
    this.isFeatured = false,
  });

  factory GalleryItem.fromJson(Map<String, dynamic> json) {
    var imagesList = <String>[];
    if (json['images'] is List) {
      imagesList = (json['images'] as List).map((e) => e.toString()).toList();
    } else if (json['images'] is String && json['images'].toString().startsWith('http')) {
      imagesList = [json['images'].toString()];
    }

    return GalleryItem(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      categoryId: json['category_id'] is int 
          ? json['category_id'] 
          : (json['category_id'] != null ? int.tryParse(json['category_id'].toString()) : null),
      title: json['title'] ?? '',
      slug: json['slug'],
      description: json['description'],
      type: json['type'] ?? 'photo',
      images: imagesList,
      videoUrl: json['video_url'],
      eventDate: json['event_date'] != null ? DateTime.tryParse(json['event_date']) : null,
      isFeatured: json['is_featured'] == 1 || json['is_featured'] == true || json['is_featured'] == '1',
    );
  }

  String get firstImage => images.isNotEmpty ? images.first : 'https://via.placeholder.com/400x300';
}
