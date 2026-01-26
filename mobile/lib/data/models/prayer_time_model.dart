class PrayerTimes {
  final dynamic cityId;
  final String cityName;
  final Map<String, String> times;
  final String date;

  PrayerTimes({
    required this.cityId,
    required this.cityName,
    required this.times,
    required this.date,
  });

  factory PrayerTimes.fromJson(Map<String, dynamic> json) {
    return PrayerTimes(
      cityId: json['city_id'],
      cityName: json['city_name'] ?? '',
      times: Map<String, String>.from(json['times'] ?? {}),
      date: json['date'] ?? '',
    );
  }
}
