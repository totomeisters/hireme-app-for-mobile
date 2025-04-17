import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:hireme_app/pages/about.dart';
import 'package:hireme_app/pages/application_form.dart';
import 'package:hireme_app/pages/job_details.dart';
import 'package:hireme_app/pages/jobapplication.dart';
import 'package:hireme_app/pages/jobopening.dart';
import 'package:hireme_app/pages/profile.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';

import '../services/job.dart';

class Jobrecommended extends StatefulWidget {
  const Jobrecommended({super.key});

  @override
  _JobrecommendedState createState() => _JobrecommendedState();
}

class _JobrecommendedState extends State<Jobrecommended> {
  final TextEditingController _searchController = TextEditingController();
  List _jobList = [];
  List _filteredJobList = [];
  int _selectedIndex = 4;

  @override
  void initState() {
    super.initState();
    _fetchJobDetails();
  }

  Future<void> _fetchJobDetails() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      String? userId = prefs.getString('userid');
      if (userId == null) return;

      final response = await JobService.jobReq(userId);

      if (response["verdict"] == true) {
        setState(() {
          _jobList = response["job_list"] ?? [];
          _filteredJobList = _jobList;
        });
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(response["message"] ?? 'Can\'t connect to REST.'),
            duration: const Duration(seconds: 3),
            backgroundColor: Colors.red,
          ),
        );
      }
    } catch (error) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(error.toString()),
          duration: const Duration(seconds: 3),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });

    switch (index) {
      case 0:
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const Profile()),
        );
        break;
      case 1:
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const JobOpening()),
        );
        break;
      case 2:
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const JobApplication()),
        );
        break;
      case 3:
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const About()),
        );
        break;
      case 4:
        Navigator.push(
          context,
          MaterialPageRoute(builder: (context) => const Jobrecommended()),
        );
    }
  }

  void _filterJobs(String query) {
    final searchQuery = query.toLowerCase();
    final filteredJobs = _jobList.where((job) {
      final jobTitle = job['JobTitle']?.toLowerCase() ?? '';
      final companyName = job['CompanyName']?.toLowerCase() ?? '';
      final jobLocation = job['JobLocation']?.toLowerCase() ?? '';
      final jobDescription = job['JobDescription']?.toLowerCase() ?? '';

      return jobTitle.contains(searchQuery) ||
          companyName.contains(searchQuery) ||
          jobLocation.contains(searchQuery) ||
          jobDescription.contains(searchQuery);
    }).toList();

    setState(() {
      _filteredJobList = filteredJobs;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        title: Row(
          children: [
            Image.asset(
              'assets/images/hireme_logo1.png', // Path to your logo image
              height: 40, // Adjust the height as needed
            ),
            const SizedBox(width: 15), // Space between the logo and the title
            Text(
              'Job Recommended',
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
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: TextField(
                controller: _searchController,
                decoration: InputDecoration(
                  hintText: 'Search',
                  prefixIcon: const Icon(Icons.search, color: Colors.grey),
                  filled: true,
                  fillColor: Colors.white,
                  contentPadding: const EdgeInsets.symmetric(vertical: 15.0),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(30.0),
                    borderSide: BorderSide.none,
                  ),
                  hintStyle: const TextStyle(color: Colors.grey),
                  suffixIcon: _searchController.text.isNotEmpty
                      ? IconButton(
                          icon: const Icon(Icons.clear, color: Colors.grey),
                          onPressed: () {
                            _searchController.clear();
                            _filterJobs('');
                          },
                        )
                      : null,
                ),
                onChanged: _filterJobs,
              ),
            ),
            Expanded(
              child: _filteredJobList.isEmpty
                  ? Center(
                      child: Text(
                        'No results as of now,\nbut will keep you posted!',
                        style: GoogleFonts.roboto(
                          textStyle: TextStyle(
                            fontSize: 18,
                            color: Colors.grey[700],
                          ),
                        ),
                        textAlign: TextAlign.center,
                      ),
                    )
                  : ListView.builder(
                      padding: const EdgeInsets.all(16.0),
                      itemCount: _filteredJobList.length,
                      itemBuilder: (context, index) {
                        final job = _filteredJobList[index];
                        return _buildJobCard(job);
                      },
                    ),
            ),
          ],
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
            icon: Icon(Icons.recommend),
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

  Widget _buildJobCard(Map<String, dynamic> job) {
    bool _isDescriptionExpanded = false;

    return StatefulBuilder(
      builder: (BuildContext context, StateSetter setState) {
        return Card(
          elevation: 8.0,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(15.0),
          ),
          child: Container(
            width: 300,
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  job['JobTitle'] ?? '',
                  style: GoogleFonts.roboto(
                    textStyle: const TextStyle(
                      fontSize: 24,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  job['CompanyName'] ?? '',
                  style: GoogleFonts.roboto(
                    textStyle: TextStyle(
                      fontSize: 18,
                      color: Colors.grey[700],
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  'Location: ${job['JobLocation'] ?? ''}',
                  style: GoogleFonts.roboto(
                    textStyle: TextStyle(
                      fontSize: 18,
                      color: Colors.grey[700],
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                Text(
                  'Salary: ₱${job['SalaryMin'] ?? ''} - ₱${job['SalaryMax'] ?? ''}',
                  style: GoogleFonts.roboto(
                    textStyle: TextStyle(
                      fontSize: 18,
                      color: Colors.green[700],
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                HtmlWidget(
                  _isDescriptionExpanded
                      ? job['JobDescription'] ?? ''
                      : (job['JobDescription']?.length ?? 0) > 100
                          ? '${job['JobDescription']!.substring(0, 100)}...'
                          : job['JobDescription'] ?? '',
                ),
                if ((job['JobDescription']?.length ?? 0) > 100)
                  GestureDetector(
                    onTap: () {
                      setState(() {
                        _isDescriptionExpanded = !_isDescriptionExpanded;
                      });
                    },
                    child: Text(
                      _isDescriptionExpanded ? 'Read less' : 'Read more',
                      style: const TextStyle(
                        color: Colors.blue,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                const SizedBox(height: 20),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                  children: [
                    ElevatedButton.icon(
                      onPressed: () {
                        if (!mounted) return;
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                              builder: (context) => JobDetails(job: job)),
                        );
                      },
                      icon: const Icon(Icons.visibility),
                      label: const Text('View Job'),
                      style: ElevatedButton.styleFrom(
                        foregroundColor: Colors.white,
                        backgroundColor: Colors.blue,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12.0),
                        ),
                      ),
                    ),
                    ElevatedButton.icon(
                      onPressed: () {
                        if (!mounted) return;
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                              builder: (context) => ApplicationForm(
                                  jobTitle: job['JobTitle'] ?? "No Data",
                                  jobId: job['JobID'] ?? "No Data")),
                        );
                      },
                      icon: const Icon(Icons.send),
                      label: const Text('Apply'),
                      style: ElevatedButton.styleFrom(
                        foregroundColor: Colors.white,
                        backgroundColor: Colors.green,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12.0),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        );
      },
    );
  }
}
