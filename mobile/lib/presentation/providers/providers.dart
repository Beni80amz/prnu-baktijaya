import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/network/dio_client.dart';
import '../../data/repositories/repository.dart';
import '../../data/models/news_model.dart';
import '../../data/models/prayer_time_model.dart';

// Network & Repository Providers
final dioClientProvider = Provider((ref) => DioClient());
final repositoryProvider = Provider((ref) => Repository(ref.read(dioClientProvider)));

// Data Providers
final newsProvider = FutureProvider<List<News>>((ref) async {
  final repository = ref.read(repositoryProvider);
  return repository.getNews();
});

final prayerTimeProvider = FutureProvider<PrayerTimes>((ref) async {
  final repository = ref.read(repositoryProvider);
  return repository.getPrayerTimes();
});
