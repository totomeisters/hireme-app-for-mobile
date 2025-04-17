import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:hireme_app/pages/about.dart';
import 'package:hireme_app/pages/application_form.dart';
import 'package:hireme_app/pages/job_details.dart';
import 'package:hireme_app/pages/jobapplication.dart';
import 'package:hireme_app/pages/jobrecommended.dart';
import 'package:hireme_app/pages/notification.dart';
import 'package:hireme_app/pages/profile.dart';
import 'package:flutter_widget_from_html/flutter_widget_from_html.dart';
import '../services/job.dart';

class JobOpening extends StatefulWidget {
  const JobOpening({super.key, this.selectedJobId});

  final String? selectedJobId; // Optional job ID to navigate directly to job

  @override
  _JobOpeningState createState() => _JobOpeningState();
}

class _JobOpeningState extends State<JobOpening> {
  final TextEditingController _searchController = TextEditingController();

  int _selectedIndex = 1; // Set the initial index to Job Openings
  String _selectedSortOption = ''; // Store selected sort option
  String _selectedWorkType = ''; // Store selected work type filter
  List<String> _selectedSkills = []; // Store selected skill filters
  List<String> _selectedQualifications =
      []; // Store selected qualification filters
  TextEditingController _minSalaryController = TextEditingController();
  TextEditingController _maxSalaryController = TextEditingController();

  // List of available work types
  final List<String> _workTypes = [
    'Janitor/Cleaner',
    'Security Guard',
    'Chef/Cook',
    'Housekeeping',
    'Food Service Worker',
    'Driver',
    'Maintenance Worker',
    'Others'
  ];

  // List of available skills
  final List<String> _skills = [
    'Attention to detail',
    'Leadership',
    'Problem solving',
    'Adaptability',
    'Conflict resolution',
    'Customer service',
    'Multitasking',
    'Teamwork',
    'Cultural awareness'
  ];

  // List of available qualifications
  final List<String> _qualifications = [
    'High School Graduate',
    'College Graduate',
    '5 Months Work Experience',
    '1 Year Work Experience',
    '2 Years Work Experience',
    '3 Years Work Experience',
    'Certifications',
    'Specialized Training'
  ];

  List _jobList = [];
  List _filteredJobList = [];

  @override
  void initState() {
    super.initState();
    _fetchJobDetails();
  }

  Future<void> _fetchJobDetails() async {
    try {
      final response = await JobService.jobListings('', '', '', '');

      if (response["verdict"] == true) {
        setState(() {
          _jobList = response["job_list"];
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
        break;
    }
  }

  // Combined filter method that handles search text, salary range, skills, qualifications, and work type
  void _applyFilters() {
    final filteredJobs = _jobList.where((job) {
      // Text search filter
      final jobTitle = job['JobTitle']?.toLowerCase() ?? '';
      final companyName = job['CompanyName']?.toLowerCase() ?? '';
      final jobLocation = job['JobLocation']?.toLowerCase() ?? '';
      final jobDescription = job['JobDescription']?.toLowerCase() ?? '';
      final workType = job['WorkType']?.toLowerCase() ?? '';
      final jobSkills = job['Skills']?.toLowerCase() ?? '';
      final jobQualifications = job['Qualifications']?.toLowerCase() ?? '';
      final searchQuery = _searchController.text.toLowerCase();

      bool matchesSearch = _searchController.text.isEmpty ||
          jobTitle.contains(searchQuery) ||
          companyName.contains(searchQuery) ||
          jobLocation.contains(searchQuery) ||
          jobDescription.contains(searchQuery);

      // Salary filter
      final minSalary = int.tryParse(_minSalaryController.text);
      final maxSalary = int.tryParse(_maxSalaryController.text);

      bool matchesSalary = true;
      if (minSalary != null && minSalary > 0) {
        final salaryMin = int.tryParse(job['SalaryMin'] ?? '0') ?? 0;
        matchesSalary = matchesSalary && salaryMin >= minSalary;
      }

      if (maxSalary != null && maxSalary > 0) {
        final salaryMax = int.tryParse(job['SalaryMax'] ?? '0') ?? 0;
        matchesSalary = matchesSalary && salaryMax <= maxSalary;
      }

      // Work type filter
      bool matchesWorkType = _selectedWorkType.isEmpty ||
          workType.toLowerCase() == _selectedWorkType.toLowerCase();

      // Skills filter - modified for multiple selection
      bool matchesSkills = _selectedSkills.isEmpty ||
          _selectedSkills
              .any((skill) => jobSkills.contains(skill.toLowerCase()));

      // Qualifications filter - modified for multiple selection
      bool matchesQualifications = _selectedQualifications.isEmpty ||
          _selectedQualifications
              .any((qual) => jobQualifications.contains(qual.toLowerCase()));

      return matchesSearch &&
          matchesSalary &&
          matchesWorkType &&
          matchesSkills &&
          matchesQualifications;
    }).toList();

    setState(() {
      _filteredJobList = filteredJobs;
    });
  }

  void _resetFilter() {
    // Reset the filter to show all jobs
    setState(() {
      _filteredJobList = _jobList; // Reset to original job list
      _minSalaryController.clear(); // Clear the salary filters
      _maxSalaryController.clear();
      _selectedWorkType = ''; // Clear work type filter
      _selectedSkills = []; // Clear skills filter
      _selectedQualifications = []; // Clear qualifications filter
      _searchController.clear(); // Clear search text
    });
  }

  // Minimalistic dropdown for skills selection
  Widget _buildSkillsDropdown(StateSetter setModalState) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Skills',
            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
        const SizedBox(height: 8),
        Container(
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey),
            borderRadius: BorderRadius.circular(8),
          ),
          child: ExpansionTile(
            title: Text(
              _selectedSkills.isEmpty
                  ? 'Select Skills'
                  : '${_selectedSkills.length} Skills Selected',
              style: TextStyle(
                color: _selectedSkills.isEmpty ? Colors.grey : Colors.blue,
                fontWeight: _selectedSkills.isEmpty
                    ? FontWeight.normal
                    : FontWeight.bold,
              ),
            ),
            children: [
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                child: Column(
                  children: _skills.map((skill) {
                    return CheckboxListTile(
                      title: Text(skill),
                      value: _selectedSkills.contains(skill),
                      dense: true,
                      contentPadding: EdgeInsets.zero,
                      controlAffinity: ListTileControlAffinity.leading,
                      onChanged: (bool? value) {
                        setModalState(() {
                          if (value == true) {
                            _selectedSkills.add(skill);
                          } else {
                            _selectedSkills.remove(skill);
                          }
                        });
                      },
                    );
                  }).toList(),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // Minimalistic dropdown for qualifications selection
  Widget _buildQualificationsDropdown(StateSetter setModalState) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text('Qualifications',
            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
        const SizedBox(height: 8),
        Container(
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey),
            borderRadius: BorderRadius.circular(8),
          ),
          child: ExpansionTile(
            title: Text(
              _selectedQualifications.isEmpty
                  ? 'Select Qualifications'
                  : '${_selectedQualifications.length} Qualifications Selected',
              style: TextStyle(
                color:
                    _selectedQualifications.isEmpty ? Colors.grey : Colors.blue,
                fontWeight: _selectedQualifications.isEmpty
                    ? FontWeight.normal
                    : FontWeight.bold,
              ),
            ),
            children: [
              Container(
                padding:
                    const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                child: Column(
                  children: _qualifications.map((qualification) {
                    return CheckboxListTile(
                      title: Text(qualification),
                      value: _selectedQualifications.contains(qualification),
                      dense: true,
                      contentPadding: EdgeInsets.zero,
                      controlAffinity: ListTileControlAffinity.leading,
                      onChanged: (bool? value) {
                        setModalState(() {
                          if (value == true) {
                            _selectedQualifications.add(qualification);
                          } else {
                            _selectedQualifications.remove(qualification);
                          }
                        });
                      },
                    );
                  }).toList(),
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }

  // Sorting options modal (pop-up)
  void _showSortFilterDialog() {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      builder: (BuildContext context) {
        return StatefulBuilder(
            builder: (BuildContext context, StateSetter setModalState) {
          return SingleChildScrollView(
            child: Container(
              padding:
                  const EdgeInsets.symmetric(vertical: 12.0, horizontal: 16.0),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.only(
                  topLeft: Radius.circular(20),
                  topRight: Radius.circular(20),
                ),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 8.0),
                    decoration: BoxDecoration(
                      color: Colors.blue,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Center(
                      child: Text(
                        'Sort & Filter',
                        style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  Text('Sort By',
                      style:
                          TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  const SizedBox(height: 8),
                  _buildSortOption(
                    title: 'Job Title',
                    onTap: () {
                      setModalState(() {
                        _selectedSortOption = 'Job Title';
                      });
                      setState(() {
                        _filteredJobList.sort((a, b) {
                          return a['JobTitle'].compareTo(b['JobTitle']);
                        });
                      });
                    },
                    isSelected: _selectedSortOption == 'Job Title',
                  ),
                  const SizedBox(height: 16),

                  // Minimalistic dropdown with checkboxes for skills
                  _buildSkillsDropdown(setModalState),

                  const SizedBox(height: 16),

                  // Minimalistic dropdown with checkboxes for qualifications
                  _buildQualificationsDropdown(setModalState),

                  const SizedBox(height: 16),
                  Text('Custom Salary Range',
                      style:
                          TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                  TextField(
                      controller: _minSalaryController,
                      keyboardType: TextInputType.number,
                      decoration:
                          const InputDecoration(labelText: 'Min Salary')),
                  const SizedBox(height: 8),
                  TextField(
                      controller: _maxSalaryController,
                      keyboardType: TextInputType.number,
                      decoration:
                          const InputDecoration(labelText: 'Max Salary')),
                  const SizedBox(height: 16),
                  // Center the "Apply Filters" button
                  Center(
                    child: ElevatedButton(
                      onPressed: () {
                        _applyFilters();
                        Navigator.pop(
                            context); // Close the modal after applying filter
                      },
                      child: const Text('Apply Filters'),
                      style: ElevatedButton.styleFrom(
                        foregroundColor: Colors.white,
                        backgroundColor: Colors.blue,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                        padding: const EdgeInsets.symmetric(
                            horizontal: 20,
                            vertical: 10), // Adjust padding for better look
                      ),
                    ),
                  ),
                  const SizedBox(height: 12),
                  // Center the "Reset" button
                  Center(
                    child: ElevatedButton(
                      onPressed: () {
                        setModalState(() {
                          _selectedSkills = [];
                          _selectedQualifications = [];
                          _minSalaryController.clear();
                          _maxSalaryController.clear();
                        });
                        _resetFilter();
                        Navigator.pop(
                            context); // Close the modal after resetting filter
                      },
                      child: const Text('Reset Filters'),
                      style: ElevatedButton.styleFrom(
                        foregroundColor: Colors.white,
                        backgroundColor: Colors.red, // Red color for reset
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                        padding: const EdgeInsets.symmetric(
                            horizontal: 20, vertical: 10),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          );
        });
      },
    );
  }

  // Helper method to build sort options with borders and centered text
  Widget _buildSortOption(
      {required String title,
      required VoidCallback onTap,
      required bool isSelected}) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12.0),
        margin: const EdgeInsets.only(bottom: 8.0),
        decoration: BoxDecoration(
          color: isSelected ? Colors.blue.withOpacity(0.1) : Colors.transparent,
          borderRadius: BorderRadius.circular(8.0),
          border: isSelected
              ? Border.all(color: Colors.blue, width: 2)
              : Border.all(color: Colors.grey, width: 1),
        ),
        child: Center(
          child: Text(
            title,
            style: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: isSelected ? Colors.blue : Colors.black,
            ),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset:
          true, // Ensures the screen resizes when the keyboard appears
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
              'Job Openings',
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
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_none, color: Colors.white),
            onPressed: () {
              // Navigate to the Notification page
              Navigator.push(
                context,
                MaterialPageRoute(
                    builder: (context) => const NotificationPage()),
              );
            },
          ),
        ],
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
              child: Row(
                children: [
                  Expanded(
                    child: TextField(
                      controller: _searchController,
                      decoration: InputDecoration(
                        hintText: 'Search',
                        prefixIcon:
                            const Icon(Icons.search, color: Colors.grey),
                        filled: true,
                        fillColor: Colors.white,
                        contentPadding:
                            const EdgeInsets.symmetric(vertical: 15.0),
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(30.0),
                          borderSide: BorderSide.none,
                        ),
                        hintStyle: const TextStyle(color: Colors.grey),
                        suffixIcon: _searchController.text.isNotEmpty
                            ? IconButton(
                                icon:
                                    const Icon(Icons.clear, color: Colors.grey),
                                onPressed: () {
                                  _searchController.clear();
                                  _applyFilters();
                                },
                              )
                            : null,
                      ),
                      onChanged: (query) {
                        _applyFilters();
                      },
                    ),
                  ),
                  const SizedBox(
                    width: 8, // Space between the search bar and sort icon
                  ),
                  // Filter chip to show selected work type filter
                  if (_selectedWorkType.isNotEmpty)
                    Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 8, vertical: 6),
                      margin: const EdgeInsets.only(right: 8),
                      decoration: BoxDecoration(
                        color: Colors.blue.withOpacity(0.2),
                        borderRadius: BorderRadius.circular(16),
                        border: Border.all(color: Colors.blue),
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            _selectedWorkType,
                            style: const TextStyle(
                              fontSize: 12,
                              color: Colors.blue,
                            ),
                          ),
                          const SizedBox(width: 4),
                          InkWell(
                            onTap: () {
                              setState(() {
                                _selectedWorkType = '';
                                _applyFilters();
                              });
                            },
                            child: const Icon(Icons.close,
                                size: 16, color: Colors.blue),
                          ),
                        ],
                      ),
                    ),
                  GestureDetector(
                    onTap: () {
                      // Show the sort options modal
                      _showSortFilterDialog();
                    },
                    child: const Icon(
                      Icons.filter_list,
                      color: Color.fromARGB(255, 0, 9, 15),
                      size: 30, // Adjust the size of the sort icon
                    ),
                  ),
                ],
              ),
            ),
            // Show active filters summary if filters are applied
            if (_selectedWorkType.isNotEmpty ||
                _minSalaryController.text.isNotEmpty ||
                _maxSalaryController.text.isNotEmpty ||
                _selectedSkills.isNotEmpty ||
                _selectedQualifications.isNotEmpty)
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16.0),
                child: Row(
                  children: [
                    const Text(
                      'Filters: ',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 14,
                      ),
                    ),
                    Expanded(
                      child: Text(
                        _buildFilterSummary(),
                        style: const TextStyle(
                          fontSize: 14,
                          color: Colors.blue,
                        ),
                        overflow: TextOverflow.ellipsis,
                      ),
                    ),
                    TextButton(
                      onPressed: () {
                        _resetFilter();
                      },
                      child: const Text('Clear All'),
                      style: TextButton.styleFrom(
                        minimumSize: const Size(0, 0),
                        padding: const EdgeInsets.symmetric(
                            horizontal: 8, vertical: 4),
                        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                      ),
                    )
                  ],
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

  // Helper method to build a summary of active filters
  String _buildFilterSummary() {
    List<String> activeSummaries = [];

    if (_selectedWorkType.isNotEmpty) {
      activeSummaries.add("Work Type: $_selectedWorkType");
    }

    // Modified for multiple skills
    if (_selectedSkills.isNotEmpty) {
      activeSummaries.add("Skills: ${_selectedSkills.length} selected");
    }

    // Modified for multiple qualifications
    if (_selectedQualifications.isNotEmpty) {
      activeSummaries
          .add("Qualifications: ${_selectedQualifications.length} selected");
    }

    if (_minSalaryController.text.isNotEmpty &&
        _maxSalaryController.text.isNotEmpty) {
      activeSummaries.add(
          "Salary: ₱${_minSalaryController.text} - ₱${_maxSalaryController.text}");
    } else if (_minSalaryController.text.isNotEmpty) {
      activeSummaries.add("Min Salary: ₱${_minSalaryController.text}");
    } else if (_maxSalaryController.text.isNotEmpty) {
      activeSummaries.add("Max Salary: ₱${_maxSalaryController.text}");
    }

    return activeSummaries.join(', ');
  }

  Widget _buildJobCard(Map<String, dynamic> job) {
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
            // Display the WorkType if available
            if (job['WorkType'] != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 10.0),
                child: Text(
                  'Work Type: ${job['WorkType'] ?? ''}',
                  style: GoogleFonts.roboto(
                    textStyle: TextStyle(
                      fontSize: 16,
                      color: Colors.grey[700],
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
              ),
            if (job['Skills'] != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 10.0),
                child: Text(
                  'Skills: ${job['Skills'] ?? ''}',
                  style: GoogleFonts.roboto(
                    textStyle: TextStyle(
                      fontSize: 16,
                      color: Colors.grey[700],
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
              ),
            if (job['Qualifications'] != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 10.0),
                child: Text(
                  'Qualifications: ${job['Qualifications'] ?? ''}',
                  style: GoogleFonts.roboto(
                    textStyle: TextStyle(
                      fontSize: 16,
                      color: Colors.grey[700],
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
              ),
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
              job['JobDescription']?.length > 100
                  ? '${job['JobDescription']?.substring(0, 100)}...'
                  : job['JobDescription'] ?? '',
              textStyle: GoogleFonts.roboto(
                textStyle: TextStyle(
                  fontSize: 16,
                  color: Colors.grey[800],
                ),
              ),
            ),
            if (job['JobDescription']?.length > 100)
              GestureDetector(
                onTap: () {
                  setState(() {
                    job['isExpanded'] = !(job['isExpanded'] ?? false);
                  });
                },
                child: Text(
                  job['isExpanded'] ?? false ? 'Read less' : 'Read more',
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
  }
}
