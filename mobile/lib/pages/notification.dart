import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';

class NotificationPage extends StatefulWidget {
  const NotificationPage({super.key});

  @override
  _NotificationPageState createState() => _NotificationPageState();
}

class _NotificationPageState extends State<NotificationPage> {
  int _selectedIndex = 1; // Set the initial index to Job Openings

  // Fetch notifications from the API
  Future<List<dynamic>> fetchNotifications() async {
    try {
      final response = await http.post(
        Uri.parse('https://www.hireme-app.online/config/notification.php'),
        body: {'user_id': '-1'}, // Replace with dynamic user ID
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        debugPrint(
            'Response data: $data'); // Debug log to check the raw response

        if (data['success']) {
          return data['notifications'];
        } else {
          debugPrint('No notifications found or error in response.');
        }
      } else {
        debugPrint(
            'Error: Server returned non-200 status code: ${response.statusCode}');
      }
    } catch (e) {
      debugPrint('Error fetching notifications: $e');
    }
    return [];
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });

    // Navigation logic for the bottom navigation bar
  }

  // Method to convert and format the date
  String convertDate(String dateString) {
    final DateTime parsedDate =
        DateFormat('yyyy-MM-dd HH:mm:ss').parse(dateString);
    final formattedDate = DateFormat('MMM dd, yyyy').format(parsedDate);
    return formattedDate;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: Row(
          children: [
            Image.asset(
              'assets/images/hireme_logo1.png',
              height: 40,
            ),
            const SizedBox(width: 15),
            Text(
              'Notifications',
              style: GoogleFonts.roboto(
                textStyle: const TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ],
        ),
        backgroundColor: Colors.blue,
        iconTheme: const IconThemeData(
          color: Colors.white,
        ),
      ),
      body: Container(
        decoration: const BoxDecoration(
          image: DecorationImage(
            image: AssetImage("assets/images/backg.png"),
            fit: BoxFit.cover,
          ),
        ),
        child: FutureBuilder<List<dynamic>>(
          future: fetchNotifications(),
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }
            if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return const Center(child: Text('No notifications available.'));
            }
            return ListView.builder(
              itemCount: snapshot.data!.length,
              itemBuilder: (context, index) {
                final notification = snapshot.data![index];

                String createdAt = convertDate(notification['created_at']);
                String? content = notification['content'];
                String? jobStatus = notification['job_update_status'];
                String? interviewStatus = notification['interview_status'];
                String? interviewDate = notification['interview_date'];
                String? applicationStatus = notification['application_status'];
                String? interviewScheduledDate =
                    notification['interview_scheduled_date'];

                return _buildNotificationCard(
                  content: content,
                  date: createdAt,
                  jobStatus: jobStatus,
                  interviewStatus: interviewStatus,
                  interviewDate: interviewDate,
                  applicationStatus: applicationStatus,
                  interviewScheduledDate: interviewScheduledDate,
                );
              },
            );
          },
        ),
      ),
      bottomNavigationBar: BottomNavigationBar(
        type: BottomNavigationBarType.fixed,
        items: const <BottomNavigationBarItem>[
          BottomNavigationBarItem(
            icon: Icon(Icons.person_outline),
            activeIcon: Icon(Icons.person),
            label: 'Profile',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.work_outline),
            activeIcon: Icon(Icons.work),
            label: 'Job Openings',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.assignment_outlined),
            activeIcon: Icon(Icons.assignment),
            label: 'Job Application',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.info_outline),
            activeIcon: Icon(Icons.info),
            label: 'About',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.recommend_outlined),
            activeIcon: Icon(Icons.recommend),
            label: 'Recommended Jobs',
          ),
        ],
        currentIndex: _selectedIndex,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        selectedFontSize: 14,
        unselectedFontSize: 12,
        selectedIconTheme: const IconThemeData(size: 30),
        unselectedIconTheme: const IconThemeData(size: 24),
        onTap: _onItemTapped,
      ),
    );
  }

  // Build notification card widget
  Widget _buildNotificationCard({
    required String? content,
    required String date,
    String? jobStatus,
    String? interviewStatus,
    String? interviewDate,
    String? applicationStatus,
    String? interviewScheduledDate,
  }) {
    return Card(
      elevation: 8.0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(15.0),
      ),
      child: Container(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (content != null)
              Text(
                content,
                style: GoogleFonts.roboto(
                  textStyle: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            if (jobStatus != null)
              Text(
                "Application Status: $jobStatus",
                style: GoogleFonts.roboto(
                  textStyle: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.bold,
                    color: Colors.blue,
                  ),
                ),
              ),
            if (interviewStatus != null && interviewDate != null)
              Text(
                "Interview Status: $interviewStatus\nInterview Date: $interviewDate",
                style: GoogleFonts.roboto(
                  textStyle: const TextStyle(
                    fontSize: 16,
                    color: Colors.green,
                  ),
                ),
              ),
            if (applicationStatus != null)
              Text(
                "Application Status: $applicationStatus",
                style: GoogleFonts.roboto(
                  textStyle: const TextStyle(
                    fontSize: 16,
                    color: Colors.orange,
                  ),
                ),
              ),
            if (interviewScheduledDate != null)
              Text(
                "Scheduled Interview: $interviewScheduledDate",
                style: GoogleFonts.roboto(
                  textStyle: const TextStyle(
                    fontSize: 16,
                    color: Colors.red,
                  ),
                ),
              ),
            const SizedBox(height: 10),
            Text(
              date,
              style: GoogleFonts.roboto(
                textStyle: const TextStyle(
                  fontSize: 14,
                  color: Colors.grey,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

extension on NotificationPage {
  void onNotificationClick(String jobId) {}
}

class JobDetailsScreen extends StatelessWidget {
  final String jobId;

  const JobDetailsScreen({Key? key, required this.jobId}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Job Details'),
      ),
      body: Center(
        child: Text(
          'Details for Job ID: $jobId',
          style: const TextStyle(fontSize: 18),
        ),
      ),
    );
  }
}
