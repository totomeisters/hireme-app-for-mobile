import 'dart:io';
import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:file_picker/file_picker.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:path_provider/path_provider.dart';
import '../services/job.dart';

class ApplicationForm extends StatefulWidget {
  final String jobTitle;
  final String jobId;

  const ApplicationForm(
      {super.key, required this.jobTitle, required this.jobId});

  @override
  _ApplicationFormState createState() => _ApplicationFormState();
}

class _ApplicationFormState extends State<ApplicationForm> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _gdriveLinkController = TextEditingController();
  PlatformFile? _pickedFile;
  File? _resumeFile;
  bool _isSubmitting = false;
  bool _isUrlValid = false;

  // Method to validate if the link is a valid and complete URL
  bool _isValidUrl(String url) {
    final Uri? uri = Uri.tryParse(url);
    return uri != null &&
        uri.isAbsolute &&
        (uri.scheme == 'http' || uri.scheme == 'https') &&
        uri.host.isNotEmpty;
  }

  // URL validation on text change
  void _validateUrl(String value) {
    setState(() {
      _isUrlValid = _isValidUrl(value);
    });
  }

  // File picker for resume
  Future<void> _pickFile() async {
    try {
      final result = await FilePicker.platform.pickFiles(
        type: FileType.custom,
        allowedExtensions: ['pdf', 'docx'],
      );

      if (result != null) {
        final file = result.files.first;
        if (!file.name.endsWith('.pdf') && !file.name.endsWith('.docx')) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('Only .pdf or .docx files are allowed.'),
              backgroundColor: Colors.red,
            ),
          );
          return;
        }

        // Create a file from the picked result
        if (file.path != null) {
          _resumeFile = File(file.path!);
        } else {
          // Handle web platform or case where path is null
          final bytes = file.bytes;
          if (bytes != null) {
            final tempDir = await getTemporaryDirectory();
            _resumeFile = File('${tempDir.path}/${file.name}');
            await _resumeFile!.writeAsBytes(bytes);
          }
        }

        setState(() {
          _pickedFile = file;
          _gdriveLinkController.clear();
          _isUrlValid = false;
        });
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Failed to pick file: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  /* Future<void> _submitApplication() async {
    setState(() {
      _isSubmitting = true;
    });

    final prefs = await SharedPreferences.getInstance();
    String? userId = prefs.getString('userid');

    Map<String, dynamic> response;

    if (_pickedFile != null && _resumeFile != null) {
      // Extract file path as a string
      String resumePath = _resumeFile!.path; // Use the non-nullable path
      // Submit with file path as a string
      response = await JobService.applyToJob(userId!, resumePath, widget.jobId);
    } else {
      // Submit with URL
      response = await JobService.applyToJob(
          userId!, _gdriveLinkController.text, widget.jobId);
    }

    setState(() {
      _isSubmitting = false;
    });

    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(response["verdict"] == true
            ? 'Application submitted!'
            : response["message"].isNotEmpty
                ? response["message"]
                : "No Data"),
        backgroundColor:
            response["verdict"] == true ? Colors.green : Colors.red,
      ),
    );
  } */
  Future<void> _submitApplication() async {
    setState(() {
      _isSubmitting = true;
    });

    final prefs = await SharedPreferences.getInstance();
    String? userId = prefs.getString('userid');

    try {
      Map<String, dynamic> response;

      if (_pickedFile != null && _resumeFile != null) {
        // Extract file path as a string
        String resumePath = _resumeFile!.path;
        // Submit with file picker
        response = await JobService.applyToJob(
          userId!,
          resumePath,
          widget.jobId,
          isFile: true,
        );
      } else {
        // Submit with Google Drive link
        response = await JobService.applyToJob(
          userId!,
          _gdriveLinkController.text,
          widget.jobId,
          isFile: false,
        );
      }

      setState(() {
        _isSubmitting = false;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(response["verdict"] == true
              ? 'Application submitted!'
              : response["message"].isNotEmpty
                  ? response["message"]
                  : "No Data"),
          backgroundColor:
              response["verdict"] == true ? Colors.green : Colors.red,
        ),
      );
    } catch (e) {
      setState(() {
        _isSubmitting = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Failed to submit application: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _confirmSubmission() {
    if (_pickedFile == null && !_isUrlValid) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Please provide either a file or a valid URL.'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    if (_pickedFile != null &&
        !_pickedFile!.name.endsWith('.pdf') &&
        !_pickedFile!.name.endsWith('.docx')) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Only .pdf or .docx files are allowed.'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Confirm Submission'),
          content:
              const Text('Are you sure you want to submit your application?'),
          actions: <Widget>[
            TextButton(
              child: const Text('Cancel'),
              onPressed: () => Navigator.of(context).pop(),
            ),
            TextButton(
              child: const Text('Submit'),
              onPressed: () {
                Navigator.of(context).pop();
                _submitApplication();
              },
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          'Application Form',
          style: GoogleFonts.roboto(
            textStyle: const TextStyle(
              color: Colors.white,
              fontWeight: FontWeight.bold,
            ),
          ),
        ),
        backgroundColor: Colors.blue,
        iconTheme: const IconThemeData(
          color: Colors.white,
        ),
      ),
      body: Stack(
        children: [
          // Background image
          Container(
            width: double.infinity,
            height: double.infinity,
            decoration: const BoxDecoration(
              image: DecorationImage(
                image: AssetImage("assets/images/backg.png"),
                fit: BoxFit.cover,
              ),
            ),
          ),
          // Form content
          Align(
            alignment: Alignment.topCenter,
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: SingleChildScrollView(
                child: Card(
                  elevation: 8,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Form(
                      key: _formKey,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Applying for: ${widget.jobTitle}',
                            style: GoogleFonts.roboto(
                              textStyle: const TextStyle(
                                fontSize: 20,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ),
                          const SizedBox(height: 20),

                          /// File Picker
                          ElevatedButton.icon(
                            onPressed: _pickFile,
                            icon: const Icon(Icons.attach_file),
                            label: const Text(
                                'Attach Resume (.pdf or .docx only)'),
                            style: ElevatedButton.styleFrom(
                              foregroundColor: Colors.white,
                              backgroundColor: Colors.orange,
                            ),
                          ),
                          if (_pickedFile != null)
                            Padding(
                              padding: const EdgeInsets.only(top: 10.0),
                              child: Text(
                                'Attached: ${_pickedFile!.name}',
                                style: TextStyle(
                                    color: Colors.grey[800], fontSize: 16),
                              ),
                            ),

                          const SizedBox(height: 20),

                          /// Message Between
                          const Center(
                            child: Text(
                              '— OR submit via link —',
                              style: TextStyle(
                                fontSize: 16,
                                fontWeight: FontWeight.w500,
                                color: Colors.black54,
                              ),
                            ),
                          ),

                          const SizedBox(height: 20),

                          /// Google Drive URL input
                          TextFormField(
                            controller: _gdriveLinkController,
                            decoration: InputDecoration(
                              labelText:
                                  'Paste a valid Google Drive or resume URL',
                              border: OutlineInputBorder(
                                borderRadius: BorderRadius.circular(10.0),
                              ),
                              filled: true,
                              fillColor: Colors.white,
                            ),
                            onChanged: (val) {
                              _validateUrl(val);
                              if (val.isNotEmpty && _pickedFile != null) {
                                setState(() => _pickedFile = null);
                              }
                            },
                          ),

                          const SizedBox(height: 30),

                          _isSubmitting
                              ? const Center(child: CircularProgressIndicator())
                              : SizedBox(
                                  width: double.infinity,
                                  child: ElevatedButton(
                                    onPressed: _confirmSubmission,
                                    style: ElevatedButton.styleFrom(
                                      foregroundColor: Colors.white,
                                      backgroundColor: Colors.blue,
                                      padding: const EdgeInsets.symmetric(
                                          vertical: 16.0),
                                    ),
                                    child: const Text('Submit Application'),
                                  ),
                                ),
                        ],
                      ),
                    ),
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
