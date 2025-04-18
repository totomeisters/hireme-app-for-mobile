import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:hireme_app/pages/jobrecommended.dart';
import 'package:hireme_app/pages/profile.dart';
import 'package:hireme_app/pages/jobopening.dart';
import 'package:hireme_app/pages/about.dart';
import 'package:hireme_app/services/job.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:shimmer/shimmer.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class JobApplication extends StatefulWidget {
  const JobApplication({super.key});

  @override
  _JobApplicationState createState() => _JobApplicationState();
}

class _JobApplicationState extends State<JobApplication> {
  List appliedJobs = [];
  List interviewList = [];
  List notifications = [];
  bool isLoading = true;
  int _selectedIndex = 2;
  String searchQuery = "";

  @override
  void initState() {
    super.initState();
    fetchData();
    fetchNotifications();
  }

  Future<void> fetchData() async {
    setState(() {
      isLoading = true;
    });
    await appliedJobsData();
    await interviewListData();
    setState(() {
      isLoading = false;
    });
  }

  Future<void> appliedJobsData() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('userid');
      if (userId == null) {
        debugPrint("Error: User ID is null.");
        return;
      }

      var response = await JobService.jobApplied(userId);

      if (response["verdict"] == true) {
        setState(() {
          appliedJobs = response["application_list"] ?? [];
        });
      } else {
        debugPrint("Error: ${response["message"]}");
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response["message"] ?? 'No Data.'),
            duration: const Duration(seconds: 3),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (e) {
      debugPrint("Unexpected error while fetching applied jobs: $e");
    }
  }

  Future<void> interviewListData() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('userid');
      if (userId == null) {
        debugPrint("Error: User ID is null.");
        return;
      }

      var response = await JobService.interviewList(userId);

      if (response["verdict"] == true) {
        setState(() {
          interviewList = response["application_list"] ?? [];
        });
      } else {
        debugPrint("Error: ${response["message"]}");
      }
    } catch (e) {
      debugPrint("Unexpected error while fetching interview list: $e");
    }
  }

  Future<void> fetchNotifications() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('userid') ?? '-1';

      final response = await http.post(
        Uri.parse('https://www.hireme-app.online/config/notification.php'),
        body: {'user_id': userId},
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['success']) {
          setState(() {
            notifications = data['notifications'];
          });
        }
      }
    } catch (e) {
      debugPrint('Error fetching notifications: $e');
    }
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
    switch (index) {
      case 0:
        Navigator.push(
            context, MaterialPageRoute(builder: (_) => const Profile()));
        break;
      case 1:
        Navigator.push(
            context, MaterialPageRoute(builder: (_) => const JobOpening()));
        break;
      case 2:
        break; // Already here
      case 3:
        Navigator.push(
            context, MaterialPageRoute(builder: (_) => const About()));
        break;
      case 4:
        Navigator.push(
            context, MaterialPageRoute(builder: (_) => const Jobrecommended()));
        break;
    }
  }

  @override
  Widget build(BuildContext context) {
    return DefaultTabController(
      length: 2,
      child: Scaffold(
        appBar: AppBar(
          automaticallyImplyLeading: false,
          title: Row(
            children: [
              Hero(
                tag: 'logo',
                child:
                    Image.asset('assets/images/hireme_logo1.png', height: 40),
              ),
              const SizedBox(width: 10),
              Text(
                'Job Application',
                style: GoogleFonts.roboto(
                    fontWeight: FontWeight.bold, color: Colors.white),
              ),
            ],
          ),
          backgroundColor: Colors.blue,
          bottom: TabBar(
            indicatorWeight: 4,
            indicatorSize: TabBarIndicatorSize.label,
            tabs: const [
              Tab(text: 'Applied'),
              Tab(text: 'Interview'),
            ],
            labelColor: Colors.white,
            unselectedLabelColor: Colors.white70,
          ),
        ),
        body: Column(
          children: [
            Expanded(
              child: TabBarView(
                children: [
                  _buildJobList(appliedJobs, 'No Jobs Applied', false),
                  _buildJobList(interviewList, 'No Interviews Scheduled', true),
                ],
              ),
            ),
            const Divider(),
            _buildNotificationSection(),
          ],
        ),
        bottomNavigationBar: BottomNavigationBar(
          type: BottomNavigationBarType.fixed,
          currentIndex: _selectedIndex,
          selectedItemColor: Colors.blue,
          unselectedItemColor: Colors.grey,
          selectedFontSize: 14,
          unselectedFontSize: 12,
          onTap: _onItemTapped,
          items: const [
            BottomNavigationBarItem(
                icon: Icon(Icons.person_outline), label: 'Profile'),
            BottomNavigationBarItem(
                icon: Icon(Icons.work_outline), label: 'Job Openings'),
            BottomNavigationBarItem(
                icon: Icon(Icons.assignment_outlined), label: 'Applications'),
            BottomNavigationBarItem(
                icon: Icon(Icons.info_outline), label: 'About'),
            BottomNavigationBarItem(
                icon: Icon(Icons.recommend_outlined), label: 'Recommended'),
          ],
        ),
      ),
    );
  }

  Widget _buildJobList(List jobs, String emptyMessage, bool isInterviewTab) {
    return isLoading
        ? const Center(child: CircularProgressIndicator())
        : jobs.isEmpty
            ? Center(child: Text(emptyMessage))
            : ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: jobs.length,
                itemBuilder: (_, index) {
                  final job = jobs[index];
                  return ListTile(
                    title: Text(job['JobTitle'] ?? 'Unknown Job'),
                    subtitle: Text(job['CompanyName'] ?? 'Unknown Company'),
                    trailing: Text(job['Status'] ?? 'Pending'),
                  );
                },
              );
  }

  Widget _buildNotificationSection() {
    return notifications.isEmpty
        ? const Center(child: Text('No notifications available.'))
        : ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: notifications.length,
            itemBuilder: (context, index) {
              final notification = notifications[index];
              return Card(
                elevation: 4.0,
                margin: const EdgeInsets.all(8.0),
                child: ListTile(
                  title: Text(notification['content']),
                  subtitle: Text('Date: ${notification['created_at']}'),
                  onTap: () {
                    // Navigate to job details or other relevant page
                  },
                ),
              );
            },
          );
  }
}
