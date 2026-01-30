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
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      channelName: json['channel_name'] ?? '',
      speakerAvatar: json['speaker_avatar'],
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
      youtubeId: json['youtube_id'],
      youtubeUrl: json['youtube_url'],
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      thumbnail: json['thumbnail'],
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
      qrisImage: json['qris_image'],
      bankName: json['bank_name'],
      bankNumber: json['bank_number'],
      bankOwner: json['bank_owner'],
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
      title: json['title'] ?? '',
      thumbnail: json['thumbnail'] ?? '',
      scheduledStart: json['scheduled_start'] ?? '',
      description: json['description'] ?? '',
      videoId: json['video_id'] ?? '',
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
      id: json['id'],
      name: json['name'],
      message: json['message'],
      avatarColor: json['avatar_color'] ?? 'bg-gray-500',
      createdAt: json['created_at'] ?? '',
    );
  }
}
