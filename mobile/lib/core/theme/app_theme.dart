import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  // Brand Colors
  static const Color teal = Color(0xFF007F80);
  static const Color gold = Color(0xFFD4AF37);
  static const Color white = Color(0xFFFFFFFF);
  static const Color black = Color(0xFF0F2323);
  static const Color backgroundLight = Color(0xFFF5F8F8);
  static const Color backgroundDark = Color(0xFF0F2323);

  // Light Theme
  static ThemeData get lightTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: teal,
        primary: teal,
        secondary: gold,
        surface: white,
        brightness: Brightness.light,
      ),
      scaffoldBackgroundColor: backgroundLight,
      textTheme: GoogleFonts.interTextTheme(),
      appBarTheme: const AppBarTheme(
        backgroundColor: backgroundLight,
        foregroundColor: teal,
        centerTitle: false,
        elevation: 0,
      ),
    );
  }

  // Dark Theme
  static ThemeData get darkTheme {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: teal,
        primary: teal,
        secondary: gold,
        surface: backgroundDark,
        brightness: Brightness.dark,
      ),
      scaffoldBackgroundColor: backgroundDark,
      textTheme: GoogleFonts.interTextTheme(ThemeData.dark().textTheme),
      appBarTheme: const AppBarTheme(
        backgroundColor: backgroundDark,
        foregroundColor: white,
        centerTitle: false,
        elevation: 0,
      ),
    );
  }
}
