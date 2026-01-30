class LiveStreamingData {
  final LiveInfo info;
  final LiveVideo video;
  final LiveDonation donation;
  final List<UpcomingSchedule> upcoming;

  LiveStreamingData({
    required this.info,
    required this.video,
    required this.donation,
    required this.upcoming,
  });

  factory LiveStreamingData.fromJson(Map<String, dynamic> json) {
    return LiveStreamingData(
      info: LiveInfo.fromJson(json['info'] ?? {}),
      video: LiveVideo.fromJson(json['video'] ?? {}),
      donation: LiveDonation.fromJson(json['donation'] ?? {}),
      upcoming: (json['upcoming'] as List?)
              ?.map((e) => UpcomingSchedule.fromJson(e))
              .toList() ??
          [],
    );
  }
}

// Helper function for safe string conversion
String _safeString(dynamic value, [String defaultValue = '']) {
  if (value == null) return defaultValue;
  return value.toString();
}

class LiveInfo {
  final String title;
  final String description;
  final String channelName;
  final String? speakerAvatar;

  LiveInfo({
    required this.title,
    required this.description,
    required this.channelName,
    this.speakerAvatar,
  });

  factory LiveInfo.fromJson(Map<String, dynamic> json) {
    return LiveInfo(
      title: _safeString(json['title']),
      description: _safeString(json['description']),
      channelName: _safeString(json['channel_name']),
      speakerAvatar: json['speaker_avatar']?.toString(),
    );
  }
}

class LiveVideo {
  final bool isLive;
  final String? youtubeId;
  final String? youtubeUrl;
  final String title;
  final String description;
  final String? thumbnail;

  LiveVideo({
    required this.isLive,
    this.youtubeId,
    this.youtubeUrl,
    required this.title,
    required this.description,
    this.thumbnail,
  });

  factory LiveVideo.fromJson(Map<String, dynamic> json) {
    return LiveVideo(
      isLive: json['is_live'] ?? false,
      youtubeId: json['youtube_id']?.toString(),
      youtubeUrl: json['youtube_url']?.toString(),
      title: _safeString(json['title']),
      description: _safeString(json['description']),
      thumbnail: json['thumbnail']?.toString(),
    );
  }
}

class LiveDonation {
  final String? qrisImage;
  final String? bankName;
  final String? bankNumber;
  final String? bankOwner;

  LiveDonation({
    this.qrisImage,
    this.bankName,
    this.bankNumber,
    this.bankOwner,
  });

  factory LiveDonation.fromJson(Map<String, dynamic> json) {
    return LiveDonation(
      qrisImage: json['qris_image']?.toString(),
      bankName: json['bank_name']?.toString(),
      bankNumber: json['bank_number']?.toString(),
      bankOwner: json['bank_owner']?.toString(),
    );
  }
}

class UpcomingSchedule {
  final String title;
  final String thumbnail;
  final String scheduledStart;
  final String description;
  final String videoId;

  UpcomingSchedule({
    required this.title,
    required this.thumbnail,
    required this.scheduledStart,
    required this.description,
    required this.videoId,
  });

  factory UpcomingSchedule.fromJson(Map<String, dynamic> json) {
    return UpcomingSchedule(
      title: _safeString(json['title']),
      thumbnail: _safeString(json['thumbnail']),
      scheduledStart: _safeString(json['scheduled_start']),
      description: _safeString(json['description']),
      videoId: _safeString(json['video_id']),
    );
  }
}

class LiveChatModel {
  final int id;
  final String name;
  final String message;
  final String avatarColor;
  final String createdAt;

  LiveChatModel({
    required this.id,
    required this.name,
    required this.message,
    required this.avatarColor,
    required this.createdAt,
  });

  factory LiveChatModel.fromJson(Map<String, dynamic> json) {
    return LiveChatModel(
      id: json['id'] is int ? json['id'] : int.tryParse(json['id'].toString()) ?? 0,
      name: _safeString(json['name']),
      message: _safeString(json['message']),
      avatarColor: _safeString(json['avatar_color'], 'bg-gray-500'),
      createdAt: _safeString(json['created_at']),
    );
  }
}

