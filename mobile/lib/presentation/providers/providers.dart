import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../data/models/news_comment_model.dart';
import '../../data/models/category_model.dart';
import '../../data/models/organization_model.dart';
import '../../data/models/gallery_model.dart';
import '../../core/network/dio_client.dart';
import '../../data/repositories/repository.dart';
import '../../data/models/news_model.dart';
import '../../data/models/prayer_time_model.dart';
import '../../data/models/city_model.dart';
import '../../data/models/setting_model.dart';
import '../../data/models/kas_summary_model.dart';
import '../../data/models/dawuh_model.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';

// Network & Repository Providers
final dioClientProvider = Provider((ref) => DioClient());
final repositoryProvider = Provider((ref) => Repository(ref.read(dioClientProvider)));

// City State management
final selectedCityProvider = StateNotifierProvider<SelectedCityNotifier, City?>((ref) {
  return SelectedCityNotifier();
});

class SelectedCityNotifier extends StateNotifier<City?> {
  SelectedCityNotifier() : super(null) {
    _loadFromPrefs();
  }

  static const String _prefKey = 'selected_city';

  Future<void> _loadFromPrefs() async {
    final prefs = await SharedPreferences.getInstance();
    final jsonStr = prefs.getString(_prefKey);
    if (jsonStr != null) {
      try {
        state = City.fromJson(json.decode(jsonStr));
      } catch (_) {
        // Fallback to null if parsing fails
      }
    }
  }

  Future<void> setSelectedCity(City city) async {
    state = city;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_prefKey, json.encode(city.toJson()));
  }
}

// Data Providers
final newsProvider = FutureProvider<List<News>>((ref) async {
  final repository = ref.read(repositoryProvider);
  return repository.getNews();
});

final prayerTimeProvider = FutureProvider<PrayerTimes>((ref) async {
  final repository = ref.read(repositoryProvider);
  final selectedCity = ref.watch(selectedCityProvider);
  return repository.getPrayerTimes(cityId: selectedCity?.id);
});

final citySearchProvider = FutureProvider.family<List<City>, String>((ref, query) async {
  if (query.isEmpty) return [];
  final repository = ref.read(repositoryProvider);
  return repository.searchCity(query);
});

final settingsProvider = FutureProvider<AppSetting>((ref) async {
  final repository = ref.read(repositoryProvider);
  return repository.getSettings();
});

final kasSummaryProvider = FutureProvider<KasSummary>((ref) async {
  final repository = ref.read(repositoryProvider);
  return repository.getKasSummary();
});

final dawuhProvider = FutureProvider<List<Dawuh>>((ref) async {
  final repository = ref.read(repositoryProvider);
  return repository.getDawuhs();
});

final commentsProvider = FutureProvider.family<List<NewsComment>, int>((ref, newsId) async {
  final repository = ref.watch(repositoryProvider);
  return repository.getComments(newsId);
});

final categoriesProvider = FutureProvider.family<List<Category>, String?>((ref, type) async {
  final repository = ref.watch(repositoryProvider);
  return repository.getCategories(type: type);
});

final newsSearchProvider = StateProvider<String>((ref) => '');
final newsCategoryFilterProvider = StateProvider<int?>((ref) => null);

final paginatedNewsProvider = FutureProvider.family<Map<String, dynamic>, int>((ref, page) async {
  final repository = ref.watch(repositoryProvider);
  final search = ref.watch(newsSearchProvider);
  final categoryId = ref.watch(newsCategoryFilterProvider);
  return repository.getPaginatedNews(page: page, search: search, categoryId: categoryId);
});

final organizationProvider = FutureProvider<OrganizationProfile>((ref) async {
  final repository = ref.watch(repositoryProvider);
  return repository.getOrganization();
});

// Gallery
final galleryTypeFilterProvider = StateProvider<String>((ref) => 'photo');
final galleryCategoryFilterProvider = StateProvider<int?>((ref) => null);

final galleryCategoriesProvider = FutureProvider<List<Category>>((ref) async {
  final repository = ref.watch(repositoryProvider);
  return repository.getCategories(type: 'gallery');
});

final paginatedGalleryProvider = FutureProvider.family<Map<String, dynamic>, int>((ref, page) async {
  final repository = ref.watch(repositoryProvider);
  final type = ref.watch(galleryTypeFilterProvider);
  final categoryId = ref.watch(galleryCategoryFilterProvider);
  return repository.getGalleries(page: page, type: type, categoryId: categoryId);
});
