import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:hireme_app/pages/jobrecommended.dart';
import 'package:hireme_app/pages/profile.dart';
import 'package:hireme_app/pages/jobopening.dart';
import 'package:hireme_app/pages/about.dart';
import 'package:hireme_app/services/job.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import 'package:shimmer/shimmer.dart';

class JobApplication extends StatefulWidget {
  const JobApplication({super.key});

  @override
  _JobApplicationState createState() => _JobApplicationState();
}

class _JobApplicationState extends State<JobApplication> {
  List appliedJobs = [];
  List interviewList = [];
  List filteredAppliedJobs = [];
  List filteredInterviewList = [];
  bool isLoading = true;
  int _selectedIndex = 2;
  String selectedFilter = "Newest"; // Default filter

  @override
  void initState() {
    super.initState();
    fetchData();
  }

  Future<void> fetchData() async {
    await appliedJobsData();
    await interviewListData();
    setState(() {
      isLoading = false;
      applyFilters(); // Apply filters after fetching data
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
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content:
              Text('An unexpected error occurred while fetching applied jobs.'),
          duration: Duration(seconds: 3),
          backgroundColor: Colors.red,
        ),
      );
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
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Information Retrieved.'),
            duration: Duration(seconds: 3),
            backgroundColor: Colors.green,
          ),
        );
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
      debugPrint("Unexpected error while fetching interview list: $e");
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text(
              'An unexpected error occurred while fetching interview list.'),
          duration: Duration(seconds: 3),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _onItemTapped(int index) {
    HapticFeedback.lightImpact();
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

  void applyFilters() {
    setState(() {
      if (selectedFilter == "Newest") {
        // Sort by date descending
        filteredAppliedJobs = [...appliedJobs]..sort((a, b) =>
            DateTime.parse(b['ApplicationDate'])
                .compareTo(DateTime.parse(a['ApplicationDate'])));
        filteredInterviewList = [...interviewList]..sort((a, b) =>
            DateTime.parse(b['InterviewDate'])
                .compareTo(DateTime.parse(a['InterviewDate'])));
      } else if (selectedFilter == "Status") {
        // Sort by status alphabetically
        filteredAppliedJobs = [...appliedJobs]..sort((a, b) =>
            (a['Status'] ?? '')
                .toString()
                .compareTo((b['Status'] ?? '').toString()));
        filteredInterviewList = [
          ...interviewList
        ]; // No specific status for interviews
      }
    });
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
          actions: [
            PopupMenuButton<String>(
              onSelected: (value) {
                setState(() {
                  selectedFilter = value;
                  applyFilters(); // Reapply filters whenever the filter changes
                });
              },
              itemBuilder: (context) => [
                const PopupMenuItem(
                  value: "Newest",
                  child: Text('Newest'),
                ),
                const PopupMenuItem(
                  value: "Status",
                  child: Text('Status'),
                ),
              ],
              icon: const Icon(Icons.filter_alt),
            ),
          ],
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
        body: Container(
          decoration: const BoxDecoration(
            image: DecorationImage(
              image: AssetImage("assets/images/backg.png"),
              fit: BoxFit.cover,
            ),
          ),
          child: Container(
            color: Colors.black.withOpacity(0.2),
            child: TabBarView(
              children: [
                _buildJobList(filteredAppliedJobs, 'No Jobs Applied', false),
                _buildJobList(
                    filteredInterviewList, 'No Interviews Scheduled', true),
              ],
            ),
          ),
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
    return RefreshIndicator(
      onRefresh: fetchData,
      child: isLoading
          ? _buildShimmer()
          : jobs.isEmpty
              ? _buildEmptyState(emptyMessage)
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: jobs.length,
                  itemBuilder: (_, index) {
                    final job = jobs[index];
                    return JobCard(job: job, isInterviewTab: isInterviewTab);
                  },
                ),
    );
  }

  Widget _buildEmptyState(String message) {
    return ListView(
      children: [
        SizedBox(height: MediaQuery.of(context).size.height * 0.2),
        Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.work_off, size: 100, color: Colors.grey[400]),
            const SizedBox(height: 10),
            Text(
              message,
              style: GoogleFonts.roboto(fontSize: 18, color: Colors.white),
            ),
            const SizedBox(height: 20),
            ElevatedButton(
              onPressed: () {
                Navigator.push(context,
                    MaterialPageRoute(builder: (_) => const JobOpening()));
              },
              child: const Text('Explore Jobs'),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildShimmer() {
    return ListView.builder(
      padding: const EdgeInsets.all(16),
      itemCount: 5,
      itemBuilder: (_, __) => Shimmer.fromColors(
        baseColor: Colors.grey[300]!,
        highlightColor: Colors.grey[100]!,
        child: Card(
          margin: const EdgeInsets.symmetric(vertical: 10),
          child: Container(height: 150, width: double.infinity),
        ),
      ),
    );
  }
}

class JobCard extends StatefulWidget {
  final Map job;
  final bool isInterviewTab;

  const JobCard({super.key, required this.job, required this.isInterviewTab});

  @override
  _JobCardState createState() => _JobCardState();
}

class _JobCardState extends State<JobCard> {
  bool _isDescriptionExpanded = false;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      elevation: 8,
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (widget.job['JobTitle'] != null)
              Text(widget.job['JobTitle'],
                  style: GoogleFonts.roboto(
                      fontSize: 20, fontWeight: FontWeight.bold)),
            const SizedBox(height: 5),
            if (widget.job['CompanyName'] != null)
              Text(widget.job['CompanyName'],
                  style: GoogleFonts.roboto(
                      color: Colors.grey[700], fontSize: 16)),
            const SizedBox(height: 10),
            AnimatedCrossFade(
              duration: const Duration(milliseconds: 300),
              crossFadeState: _isDescriptionExpanded
                  ? CrossFadeState.showSecond
                  : CrossFadeState.showFirst,
              firstChild: HtmlWidget(
                (widget.job['JobDescription'] ?? '').toString().substring(
                        0,
                        (widget.job['JobDescription']?.length ?? 0)
                            .clamp(0, 100)) +
                    '...',
                textStyle:
                    GoogleFonts.roboto(fontSize: 16, color: Colors.grey[800]),
              ),
              secondChild: HtmlWidget(
                widget.job['JobDescription'] ?? '',
                textStyle:
                    GoogleFonts.roboto(fontSize: 16, color: Colors.grey[800]),
              ),
            ),
            if ((widget.job['JobDescription']?.length ?? 0) > 100)
              TextButton(
                onPressed: () => setState(
                    () => _isDescriptionExpanded = !_isDescriptionExpanded),
                child: Text(_isDescriptionExpanded ? "Read less" : "Read more"),
              ),
            const SizedBox(height: 10),
            if (widget.isInterviewTab && widget.job['InterviewDate'] != null)
              Text('Interview Date: ${widget.job['InterviewDate']}',
                  style: GoogleFonts.roboto(fontSize: 16)),
            if (!widget.isInterviewTab && widget.job['Status'] != null)
              Container(
                margin: const EdgeInsets.only(top: 8),
                padding:
                    const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
                decoration: BoxDecoration(
                  color: _getStatusColor(widget.job['Status']),
                  borderRadius: BorderRadius.circular(20),
                ),
                child: Text(
                  widget.job['Status'].toString().toUpperCase(),
                  style: const TextStyle(
                      color: Colors.white,
                      fontSize: 14,
                      fontWeight: FontWeight.bold),
                ),
              ),
            const SizedBox(height: 10),
            if (widget.job['ApplicationDate'] != null)
              Text('Applied on: ${_formatDate(widget.job['ApplicationDate'])}',
                  style: GoogleFonts.roboto(fontSize: 14)),
          ],
        ),
      ),
    );
  }

  String _formatDate(String dateTimeString) {
    try {
      DateTime parsedDate = DateTime.parse(dateTimeString);
      return "${parsedDate.year}-${parsedDate.month.toString().padLeft(2, '0')}-${parsedDate.day.toString().padLeft(2, '0')}";
    } catch (_) {
      return dateTimeString;
    }
  }

  Color _getStatusColor(String? status) {
    switch (status?.toLowerCase()) {
      case 'pending':
        return Colors.orange;
      case 'rejected':
        return Colors.red;
      case 'verified':
        return Colors.green;
      default:
        return Colors.grey;
    }
  }
}

void main() {
  runApp(const MaterialApp(
      home: JobApplication(), debugShowCheckedModeBanner: false));
}
