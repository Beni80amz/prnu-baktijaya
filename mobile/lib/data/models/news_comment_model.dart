class NewsComment {
  final int id;
  final int newsId;
  final String? name;
  final String comment;
  final String createdAt;

  NewsComment({
    required this.id,
    required this.newsId,
    this.name,
    required this.comment,
    required this.createdAt,
  });

  factory NewsComment.fromJson(Map<String, dynamic> json) {
    return NewsComment(
      id: json['id'],
      newsId: json['news_id'],
      name: json['name'],
      comment: json['comment'],
      createdAt: json['created_at'],
    );
  }
}
