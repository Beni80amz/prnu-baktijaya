import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:intl/intl.dart';
import 'package:intl/date_symbol_data_local.dart';
import '../../core/theme/app_theme.dart';
import '../providers/providers.dart';
import '../../data/models/agenda_model.dart';
import 'package:share_plus/share_plus.dart';

class AgendaScreen extends ConsumerStatefulWidget {
  const AgendaScreen({super.key});

  @override
  ConsumerState<AgendaScreen> createState() => _AgendaScreenState();
}

class _AgendaScreenState extends ConsumerState<AgendaScreen> {
  DateTime _selectedDate = DateTime.now();
  DateTime _focusedMonth = DateTime.now();

  @override
  void initState() {
    super.initState();
    initializeDateFormatting('id_ID', null);
  }

  void _onDateSelected(DateTime date) {
    setState(() {
      _selectedDate = date;
    });
  }

  void _changeMonth(int offset) {
    setState(() {
      _focusedMonth = DateTime(_focusedMonth.year, _focusedMonth.month + offset);
    });
  }

  List<DateTime> _getDaysInMonth(DateTime month) {
    final lastDay = DateTime(month.year, month.month + 1, 0);
    final days = <DateTime>[];
    for (int i = 1; i <= lastDay.day; i++) {
      days.add(DateTime(month.year, month.month, i));
    }
    return days;
  }

  @override
  Widget build(BuildContext context) {
    final isDark = Theme.of(context).brightness == Brightness.dark;
    final primaryColor = AppTheme.teal;
    final backgroundColor = isDark ? const Color(0xFF102216) : const Color(0xFFF6F8F6);
    final cardColor = isDark ? const Color(0xFF1E2E23) : Colors.white;
    final textColor = isDark ? Colors.white : const Color(0xFF0F172A); // Slate 900

    final agendaAsync = ref.watch(agendaProvider);
    final prayerAsync = ref.watch(prayerTimeProvider); // Fetch Hijri Date

    return Scaffold(
      backgroundColor: backgroundColor,
      body: SafeArea(
        child: RefreshIndicator(
          onRefresh: () async {
            ref.invalidate(agendaProvider);
            ref.invalidate(prayerTimeProvider);
          },
          child: CustomScrollView(
          slivers: [
            // Header
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Row(
                      children: [
                        Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(
                            color: primaryColor.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: Icon(Icons.mosque, color: primaryColor),
                        ),
                        const SizedBox(width: 12),
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              'Agenda NU',
                              style: TextStyle(
                                fontSize: 18,
                                fontWeight: FontWeight.bold,
                                color: textColor,
                              ),
                            ),
                            Text(
                              'PRNU Baktijaya',
                              style: TextStyle(
                                fontSize: 12,
                                fontWeight: FontWeight.w500,
                                color: primaryColor,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    Row(
                      children: [
                        _buildIconButton(Icons.notifications_outlined, isDark),
                        const SizedBox(width: 8),
                        _buildIconButton(Icons.person_outline, isDark),
                      ],
                    ),
                  ],
                ),
              ),
            ),

            // Calendar Section
            SliverToBoxAdapter(
              child: Container(
                margin: const EdgeInsets.symmetric(horizontal: 16),
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: isDark ? const Color(0xFF152A1F) : Colors.white, // Slightly lighter dark
                  borderRadius: BorderRadius.circular(16),
                  border: Border.all(
                    color: primaryColor.withOpacity(0.1),
                  ),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.black.withOpacity(0.05),
                      blurRadius: 10,
                      offset: const Offset(0, 4),
                    ),
                  ],
                ),
                child: Column(
                  children: [
                    // Calendar Header
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        IconButton(
                          icon: const Icon(Icons.chevron_left),
                          onPressed: () => _changeMonth(-1),
                          color: Colors.grey,
                        ),
                        Column(
                          children: [
                            prayerAsync.when(
                              data: (data) => Text(
                                _extractHijriMonthYear(data.hijri), 
                                style: TextStyle(
                                  fontSize: 16, 
                                  fontWeight: FontWeight.bold, 
                                  color: primaryColor,
                                ),
                              ),
                              loading: () => Text('Memuat...', style: TextStyle(fontSize: 14, color: primaryColor)),
                              error: (_,__) => Text('Hijriyah', style: TextStyle(fontSize: 14, color: primaryColor)),
                            ),
                            Text(
                              DateFormat('MMMM yyyy', 'id_ID').format(_focusedMonth),
                              style: const TextStyle(
                                fontSize: 12,
                                color: Colors.grey,
                                fontWeight: FontWeight.w500,
                              ),
                            ),
                          ],
                        ),
                        IconButton(
                          icon: const Icon(Icons.chevron_right),
                          onPressed: () => _changeMonth(1),
                          color: Colors.grey,
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    
                    // Days Header
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB']
                          .map((day) => SizedBox(
                                width: 40,
                                child: Text(
                                  day,
                                  textAlign: TextAlign.center,
                                  style: const TextStyle(
                                    fontSize: 10,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.grey,
                                  ),
                                ),
                              ))
                          .toList(),
                    ),
                    const SizedBox(height: 8),

                    // Days Grid (Simplified for UI demo)
                    // Showing a single week row or simple grid for the focused month
                    // For improved UX in limited time, I'll show a scrollable horizontal week or limited grid.
                    // Let's do a proper Wrap/Grid for the days.
                    Wrap(
                      spacing: 4,
                      runSpacing: 8,
                      alignment: WrapAlignment.spaceBetween,
                      children: _buildCalendarDays(primaryColor, textColor),
                    ),

                    const SizedBox(height: 16),
                    Divider(color: Colors.grey.withOpacity(0.2)),
                    const SizedBox(height: 8),
                    
                    // Legend
                    Row(
                      children: [
                        _buildLegendItem(primaryColor, 'Agenda NU'),
                        const SizedBox(width: 16),
                        _buildLegendItem(const Color(0xFFFFD700), 'Hari Besar (PHBI)'),
                      ],
                    ),
                  ],
                ),
              ),
            ),

            // Agenda List Header
            SliverToBoxAdapter(
              child: Padding(
                padding: const EdgeInsets.fromLTRB(16, 24, 16, 12),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Agenda Terdekat',
                      style: TextStyle(
                        fontSize: 18,
                        fontWeight: FontWeight.bold,
                        color: textColor,
                      ),
                    ),
                    Text(
                      'Lihat Semua',
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: primaryColor,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Agenda List
            agendaAsync.when(
              data: (agendas) {
                if (agendas.isEmpty) {
                  return const SliverToBoxAdapter(
                    child: Padding(
                      padding: EdgeInsets.all(16),
                      child: Center(child: Text("Belum ada agenda")),
                    ),
                  );
                }
                return SliverList(
                  delegate: SliverChildBuilderDelegate(
                    (context, index) {
                      final agenda = agendas[index];
                      return _buildAgendaCard(agenda, isDark, primaryColor, cardColor, textColor);
                    },
                    childCount: agendas.length,
                  ),
                );
              },
              loading: () => const SliverToBoxAdapter(
                child: Center(child: CircularProgressIndicator()),
              ),
              error: (err, stack) => SliverToBoxAdapter(
                child: Padding(
                  padding: const EdgeInsets.all(16),
                  child: Text('Error: $err', style: const TextStyle(color: Colors.red)),
                ),
              ),
            ),

            const SliverToBoxAdapter(child: SizedBox(height: 100)),
          ],
        ),
        ),
      ),
    );
  }

  Widget _buildIconButton(IconData icon, bool isDark) {
    return Container(
      width: 40,
      height: 40,
      decoration: BoxDecoration(
        color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[200],
        shape: BoxShape.circle,
      ),
      child: Icon(icon, size: 20, color: isDark ? Colors.white : Colors.black),
    );
  }

  List<Widget> _buildCalendarDays(Color primaryColor, Color? textColor) {
    final daysInMonth = _getDaysInMonth(_focusedMonth);
    // Find first day of week offset
    final firstDayWeekday = daysInMonth.first.weekday; // 1=Mon, 7=Sun
    final offset = firstDayWeekday == 7 ? 0 : firstDayWeekday; 
    
    final List<Widget> dayWidgets = [];
    
    // Add empty placeholders
    for (int i = 0; i < offset; i++) {
        dayWidgets.add(const SizedBox(width: 40, height: 40));
    }

    // Add days
    for (var date in daysInMonth) {
      final isSelected = date.day == _selectedDate.day && 
                         date.month == _selectedDate.month && 
                         date.year == _selectedDate.year;
      final isToday = date.day == DateTime.now().day && 
                      date.month == DateTime.now().month && 
                      date.year == DateTime.now().year;

      dayWidgets.add(
        InkWell(
          onTap: () => _onDateSelected(date),
          borderRadius: BorderRadius.circular(12),
          child: Container(
            width: 40,
            height: 46, // slightly taller for event dots
            decoration: BoxDecoration(
              color: isSelected ? primaryColor : Colors.transparent,
              borderRadius: BorderRadius.circular(12),
              border: isToday && !isSelected ? Border.all(color: primaryColor) : null,
            ),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  '${date.day}',
                  style: TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.bold,
                    color: isSelected 
                        ? Colors.white 
                        : (isToday ? primaryColor : textColor),
                  ),
                ),
                // Event indicators
                if (date.day % 5 == 0) // Dummy logic for indicators
                  Padding(
                    padding: const EdgeInsets.only(top: 4),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Container(
                          width: 4,
                          height: 4,
                          decoration: const BoxDecoration(
                            color: Color(0xFFFFD700), // Gold
                            shape: BoxShape.circle,
                          ),
                        ),
                      ],
                    ),
                  ),
              ],
            ),
          ),
        ),
      );
    }
    return dayWidgets;
  }

  Widget _buildLegendItem(Color color, String label) {
    return Row(
      children: [
        Container(
          width: 8,
          height: 8,
          decoration: BoxDecoration(color: color, shape: BoxShape.circle),
        ),
        const SizedBox(width: 6),
        Text(
          label,
          style: const TextStyle(fontSize: 10, color: Colors.grey),
        ),
      ],
    );
  }

  Widget _buildAgendaCard(Agenda agenda, bool isDark, Color primaryColor, Color cardColor, Color? textColor) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: cardColor,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: isDark ? Colors.white10 : Colors.grey[200]!),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.02),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: 48,
            height: 48,
            decoration: BoxDecoration(
              color: primaryColor.withOpacity(0.1),
              borderRadius: BorderRadius.circular(12),
            ),
            child: Icon(Icons.event_note, color: primaryColor),
          ),
          const SizedBox(width: 14),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(
                        agenda.title,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                          color: textColor,
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                      decoration: BoxDecoration(
                        color: primaryColor.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        _calculateDaysUntil(agenda.date),
                        style: TextStyle(
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                          color: primaryColor,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                _buildInfoRow(
                  Icons.access_time, 
                  '${_formatDate(agenda.date)}${agenda.time != null ? ' • ${agenda.time}' : ''}', 
                  primaryColor
                ),
                const SizedBox(height: 4),
                _buildInfoRow(Icons.location_on_outlined, agenda.location, primaryColor),
                
                const SizedBox(height: 16),
                Row(
                  children: [
                    Expanded(
                      child: SizedBox(
                        height: 40,
                        child: ElevatedButton.icon(
                          onPressed: () {
                            // Save logic
                          },
                          style: ElevatedButton.styleFrom(
                            backgroundColor: primaryColor,
                            foregroundColor: Colors.white,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(8),
                            ),
                            padding: EdgeInsets.zero,
                          ),
                          icon: const Icon(Icons.calendar_today, size: 16),
                          label: const Text('Simpan ke HP', style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold)),
                        ),
                      ),
                    ),
                    const SizedBox(width: 8),
                    Container(
                      width: 40,
                      height: 40,
                      decoration: BoxDecoration(
                        color: isDark ? Colors.white.withOpacity(0.1) : Colors.grey[100],
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: IconButton(
                        icon: const Icon(Icons.share, size: 18),
                        onPressed: () {
                          Share.share('${agenda.title}\n${agenda.date} at ${agenda.time}\n${agenda.location}');
                        },
                        color: Colors.grey,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String text, Color color) {
    return Row(
      children: [
        Icon(icon, size: 14, color: Colors.grey),
        const SizedBox(width: 6),
        Text(
          text,
          style: const TextStyle(fontSize: 12, color: Colors.grey),
        ),
      ],
    );
  }

  String _formatDate(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      return DateFormat('EEEE, d MMM', 'id_ID').format(date);
    } catch (e) {
      return dateStr;
    }
  }

  String _calculateDaysUntil(String dateStr) {
    try {
      final date = DateTime.parse(dateStr);
      final now = DateTime.now();
      final diff = date.difference(DateTime(now.year, now.month, now.day)).inDays;
      
      if (diff < 0) return 'Selesai';
      if (diff == 0) return 'Hari Ini';
      if (diff == 1) return 'Besok';
      return 'H-$diff';
    } catch (e) {
      return '-';
    }
  }

  String _extractHijriMonthYear(String? hijriFull) {
    if (hijriFull == null) return 'Hijriyah';
    // Example format: "10 Ramadhan 1445 H" -> Extract "Ramadhan 1445 H"
    // Split by space
    final parts = hijriFull.split(' ');
    if (parts.length > 2) {
      return parts.sublist(1).join(' ');
    }
    return hijriFull;
  }
}
