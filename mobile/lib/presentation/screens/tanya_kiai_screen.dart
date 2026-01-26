import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';

class TanyaKiaiScreen extends StatefulWidget {
  const TanyaKiaiScreen({super.key});

  @override
  State<TanyaKiaiScreen> createState() => _TanyaKiaiScreenState();
}

class _TanyaKiaiScreenState extends State<TanyaKiaiScreen> {
  final _questionController = TextEditingController();

  void _submitQuestion() {
    if (_questionController.text.isNotEmpty) {
       // Simulate API
       ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Pertanyaan terkirim. Menunggu jawaban Kiai.')));
       Navigator.pop(context);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Tanya Kiai')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            const Text(
              'Konsultasi masalah agama Islam bersama Kiai PRNU Baktijaya.',
              style: TextStyle(fontSize: 14, color: Colors.grey),
            ),
            const SizedBox(height: 20),
            TextField(
              controller: _questionController,
              decoration: const InputDecoration(
                labelText: 'Tulis Pertanyaan Anda',
                border: OutlineInputBorder(),
                alignLabelWithHint: true,
              ),
              maxLines: 5,
            ),
            const SizedBox(height: 20),
             SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: _submitQuestion,
                  child: const Text('Kirim Pertanyaan'),
                ),
              ),
          ],
        ),
      ),
    );
  }
}
