class Agenda {
  final int id;
  final String title;
  final String date;
  final String? time;
  final String location;
  final String? description;

  Agenda({
    required this.id,
    required this.title,
    required this.date,
    this.time,
    required this.location,
    this.description,
  });

  factory Agenda.fromJson(Map<String, dynamic> json) {
    return Agenda(
      id: json['id'],
      title: json['title'],
      date: json['date'],
      time: json['time'],
      location: json['location'],
      description: json['description'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'date': date,
      'time': time,
      'location': location,
      'description': description,
    };
  }
}
