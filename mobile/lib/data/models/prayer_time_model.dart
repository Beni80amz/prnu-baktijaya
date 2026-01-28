class PrayerTimes {
  final dynamic cityId;
  final String cityName;
  final Map<String, String> times;
  final String date;
  final String? hijri;

  PrayerTimes({
    required this.cityId,
    required this.cityName,
    required this.times,
    required this.date,
    this.hijri,
  });

  factory PrayerTimes.fromJson(Map<String, dynamic> json) {
    return PrayerTimes(
      cityId: json['city_id'],
      cityName: json['city_name'] ?? '',
      times: Map<String, String>.from(json['times'] ?? {}),
      date: json['date'] ?? '',
      hijri: json['hijri'],
    );
  }
}
