import 'package:flutter/material.dart';
import 'dart:ui' as ui;
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'core/theme/app_theme.dart';
import 'presentation/screens/main_screen.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await initializeDateFormatting('id_ID', null);
  
  // Add error handler to capture full stacktrace
  FlutterError.onError = (FlutterErrorDetails details) {
    debugPrint('=== FLUTTER ERROR ===');
    debugPrint('Error: ${details.exception}');
    debugPrint('Stack: ${details.stack}');
    debugPrint('Context: ${details.context}');
    debugPrint('=====================');
    FlutterError.presentError(details);
  };

  // Capture Async Errors
  ui.PlatformDispatcher.instance.onError = (error, stack) {
    debugPrint('=== ASYNC ERROR ===');
    debugPrint('Error: $error');
    debugPrint('Stack: $stack');
    debugPrint('===================');
    return true;
  };
  
  runApp(const ProviderScope(child: MyApp()));
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'PRNU Baktijaya',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode: ThemeMode.system, // Follow system setting
      home: const MainScreen(),
    );
  }
}
