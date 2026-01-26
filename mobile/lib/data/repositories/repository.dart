import 'package:dio/dio.dart';
import '../../core/network/dio_client.dart';
import '../models/news_model.dart';
import '../models/prayer_time_model.dart';
import '../../core/constants/api_constants.dart';

class Repository {
  final DioClient _dioClient;

  Repository(this._dioClient);

  Future<List<News>> getNews() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.berita);
      final data = response.data['data'] as List;
      return data.map((e) => News.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to load news: $e');
    }
  }

  Future<PrayerTimes> getPrayerTimes() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.jadwalSholat);
      return PrayerTimes.fromJson(response.data);
    } catch (e) {
      throw Exception('Failed to load prayer times: $e');
    }
  }
}
