import 'package:flutter/material.dart';
import '../../core/theme/app_theme.dart';

class DoaFormScreen extends StatefulWidget {
  const DoaFormScreen({super.key});

  @override
  State<DoaFormScreen> createState() => _DoaFormScreenState();
}

class _DoaFormScreenState extends State<DoaFormScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _intentController = TextEditingController();

  Future<void> _submitDoa() async {
    if (_formKey.currentState!.validate()) {
      // Simulate API Call
      ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Mengirim permohonan...')));
      
      await Future.delayed(const Duration(seconds: 2));

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Permohonan doa berhasil dikirim')));
        Navigator.pop(context);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Permohonan Doa')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            children: [
              TextFormField(
                controller: _nameController,
                decoration: const InputDecoration(
                  labelText: 'Nama Lengkap (Bin/Binti)',
                  border: OutlineInputBorder(),
                ),
                validator: (value) => value == null || value.isEmpty ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _intentController,
                decoration: const InputDecoration(
                  labelText: 'Hajat / Doa Khusus',
                  border: OutlineInputBorder(),
                ),
                maxLines: 4,
                validator: (value) => value == null || value.isEmpty ? 'Wajib diisi' : null,
              ),
              const SizedBox(height: 24),
              SizedBox(
                width: double.infinity,
                height: 50,
                child: ElevatedButton(
                  onPressed: _submitDoa,
                  child: const Text('Kirim Permohonan'),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
