import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/theme/app_theme.dart';
import '../providers/providers.dart';
import '../../data/models/city_model.dart';
import 'package:geolocator/geolocator.dart';

class CitySelectorScreen extends ConsumerStatefulWidget {
  const CitySelectorScreen({super.key});

  @override
  ConsumerState<CitySelectorScreen> createState() => _CitySelectorScreenState();
}

class _CitySelectorScreenState extends ConsumerState<CitySelectorScreen> {
  final _searchController = TextEditingController();
  String _searchQuery = '';
  bool _isLoadingLocation = false;

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  Future<void> _useCurrentLocation() async {
    setState(() => _isLoadingLocation = true);
    try {
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        throw 'Location services are disabled.';
      }

      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied) {
          throw 'Location permissions are denied';
        }
      }

      if (permission == LocationPermission.deniedForever) {
        throw 'Location permissions are permanently denied, we cannot request permissions.';
      }

      Position position = await Geolocator.getCurrentPosition();
      // In a real app, you would use a geocoding service to get the city name
      // For this demo, let's assume we search for a city based on coordinates
      // or just show a message.
      
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('GPS detected. Searching for nearest city...')),
      );
      
      // Since the API uses specific City IDs, we'd ideally reverse geocode.
      // For now, let's mock finding "Depok" if they use GPS in this region.
      // In production, you'd call a reverse geocoding API.
      
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    } finally {
      setState(() => _isLoadingLocation = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final searchResults = ref.watch(citySearchProvider(_searchQuery));

    return Scaffold(
      appBar: AppBar(
        title: const Text('Pilih Wilayah'),
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: TextField(
              controller: _searchController,
              decoration: InputDecoration(
                hintText: 'Cari Kota atau Kabupaten...',
                prefixIcon: const Icon(Icons.search),
                suffixIcon: _searchQuery.isNotEmpty 
                    ? IconButton(
                        icon: const Icon(Icons.clear),
                        onPressed: () {
                          _searchController.clear();
                          setState(() => _searchQuery = '');
                        },
                      )
                    : null,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              onChanged: (value) {
                setState(() => _searchQuery = value);
              },
            ),
          ),
          ListTile(
            leading: _isLoadingLocation 
                ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(strokeWidth: 2))
                : const Icon(Icons.my_location, color: AppTheme.teal),
            title: const Text('Gunakan Lokasi Saat Ini'),
            subtitle: const Text('Sesuaikan dengan posisi GPS HP'),
            onTap: _isLoadingLocation ? null : _useCurrentLocation,
          ),
          const Divider(),
          Expanded(
            child: searchResults.when(
              data: (cities) {
                if (cities.isEmpty && _searchQuery.isNotEmpty) {
                  return const Center(child: Text('Kota tidak ditemukan'));
                }
                if (cities.isEmpty) {
                  return const Center(child: Text('Masukkan nama kota untuk mencari'));
                }
                return ListView.builder(
                  itemCount: cities.length,
                  itemBuilder: (context, index) {
                    final city = cities[index];
                    return ListTile(
                      title: Text(city.name),
                      onTap: () {
                        ref.read(selectedCityProvider.notifier).setSelectedCity(city);
                        Navigator.pop(context);
                      },
                    );
                  },
                );
              },
              loading: () => const Center(child: CircularProgressIndicator()),
              error: (err, stack) => Center(child: Text('Error: $err')),
            ),
          ),
        ],
      ),
    );
  }
}
