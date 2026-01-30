import '../../core/network/dio_client.dart';
import '../models/news_model.dart';
import '../models/prayer_time_model.dart';
import '../models/city_model.dart';
import '../models/setting_model.dart';
import '../models/kas_summary_model.dart';
import '../models/dawuh_model.dart';
import '../models/dawuh_model.dart';
import '../models/category_model.dart';
import '../models/organization_model.dart';
import '../models/gallery_model.dart';
import '../models/news_comment_model.dart';
import '../models/agenda_model.dart';
import '../models/agenda_model.dart';
import '../models/live_streaming_model.dart';
import '../../core/constants/api_constants.dart';

class Repository {
  final DioClient _dioClient;

  Repository(this._dioClient);

  Future<List<Agenda>> getAgendas() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.agenda);
      final List data = response.data['data'] ?? [];
      return data.map((e) => Agenda.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to load agendas: $e');
    }
  }

  Future<List<News>> getNews() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.berita);
      final data = response.data['data'] as List;
      return data.map((e) => News.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to load news: $e');
    }
  }

  Future<Map<String, dynamic>> getPaginatedNews({int page = 1, int? categoryId, String? search}) async {
    try {
      final response = await _dioClient.dio.get(
        ApiConstants.berita,
        queryParameters: {
          'page': page,
          if (categoryId != null) 'category_id': categoryId,
          if (search != null) 'search': search,
        },
      );
      return response.data;
    } catch (e) {
      throw Exception('Failed to load paginated news: $e');
    }
  }

  Future<OrganizationProfile> getOrganization() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.organization);
      return OrganizationProfile.fromJson(response.data);
    } catch (e) {
      throw Exception('Failed to load organization profile: $e');
    }
  }

  Future<Map<String, dynamic>> getGalleries({int page = 1, String? type, int? categoryId}) async {
    try {
      final response = await _dioClient.dio.get(
        ApiConstants.galeri,
        queryParameters: {
          'page': page,
          if (type != null) 'type': type,
          if (categoryId != null) 'category_id': categoryId,
        },
      );
      
      final List data = response.data['data'];
      final List<GalleryItem> items = data.map((e) => GalleryItem.fromJson(e)).toList();
      
      return {
        'items': items,
        'current_page': response.data['current_page'],
        'last_page': response.data['last_page'],
        'total': response.data['total'],
      };
    } catch (e) {
      throw Exception('Failed to load galleries: $e');
    }
  }

  Future<List<Category>> getCategories({String? type}) async {
    try {
      final response = await _dioClient.dio.get(
        ApiConstants.categories,
        queryParameters: {if (type != null) 'type': type},
      );
      final List data = response.data['data'] ?? [];
      return data.map((e) => Category.fromJson(e)).toList();
    } catch (e) {
      return []; // Return empty list on error to prevent UI breaking 
    }
  }

  Future<PrayerTimes> getPrayerTimes({String? cityId, String? date}) async {
    try {
      final queryParams = {
        if (cityId != null) 'city_id': cityId,
        if (date != null) 'date': date,
      };
      final response = await _dioClient.dio.get(
        ApiConstants.jadwalSholat,
        queryParameters: queryParams.isEmpty ? null : queryParams,
      );
      return PrayerTimes.fromJson(response.data);
    } catch (e) {
      throw Exception('Failed to load prayer times: $e');
    }
  }

  Future<List<City>> getCities() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.kota);
      final data = response.data as List;
      return data.map((e) => City.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to load cities: $e');
    }
  }

  Future<List<City>> searchCity(String query) async {
    try {
      final response = await _dioClient.dio.get(
        ApiConstants.kota,
        queryParameters: {'search': query},
      );
      final data = response.data as List;
      return data.map((e) => City.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to search city: $e');
    }
  }

  Future<AppSetting> getSettings() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.settings);
      return AppSetting.fromJson(response.data);
    } catch (e) {
      throw Exception('Failed to load settings: $e');
    }
  }

  Future<KasSummary> getKasSummary() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.kas);
      return KasSummary.fromJson(response.data);
    } catch (e) {
      throw Exception('Failed to load kas summary: $e');
    }
  }

  Future<List<Dawuh>> getDawuhs() async {
    try {
      final response = await _dioClient.dio.get(ApiConstants.dawuh);
      final List data = response.data['data'] ?? [];
      return data.map((e) => Dawuh.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to load dawuhs: $e');
    }
  }

  Future<List<NewsComment>> getComments(int newsId) async {
    try {
      final response = await _dioClient.dio.get('news/$newsId/comments');
      final List data = response.data['data'] ?? [];
      return data.map((e) => NewsComment.fromJson(e)).toList();
    } catch (e) {
      throw Exception('Failed to load comments: $e');
    }
  }

  Future<void> postComment(int newsId, String name, String comment) async {
    try {
      await _dioClient.dio.post('news/$newsId/comments', data: {
        'name': name,
        'comment': comment,
      });
    } catch (e) {
      throw Exception('Failed to post comment: $e');
    }
  }

  // --- Live Streaming Methods ---
  
  Future<LiveStreamingData> getLiveStreamingData() async {
    try {
      final response = await _dioClient.dio.get('live-streaming');
      return LiveStreamingData.fromJson(response.data['data']);
    } catch (e) {
      throw Exception('Failed to load live streaming data: $e');
    }
  }

  Future<List<LiveChatModel>> getLiveChats() async {
    try {
      final response = await _dioClient.dio.get('live-streaming/chats');
      final List data = response.data['data'] ?? [];
      return data.map((e) => LiveChatModel.fromJson(e)).toList();
    } catch (e) {
      // Return empty list instead of throwing to prevent blocking the stream
      return []; 
    }
  }

  Future<void> postLiveChat(String name, String message) async {
    try {
      await _dioClient.dio.post('live-streaming/chat', data: {
        'name': name,
        'message': message,
      });
    } catch (e) {
      throw Exception('Failed to send chat: $e');
    }
  }

  Future<void> postLiveAttendance(String name, String address, String? message) async {
    try {
      await _dioClient.dio.post('live-streaming/attendance', data: {
        'name': name,
        'address': address,
        'message': message,
      });
    } catch (e) {
      throw Exception('Failed to submit attendance: $e');
    }
  }
}
