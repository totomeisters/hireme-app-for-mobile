import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:crypto/crypto.dart';
import 'package:path/path.dart' as path; // Add this import for basename
import 'package:http_parser/http_parser.dart'; // Needed for multipart requests

class JobService {
  // Fetch job listings
  static Future<Map> jobListings(
      String jobDesc, String salaMin, String salMax, String jobLoc) async {
    try {
      // API Link
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Get the current date
      DateTime now = DateTime.now();

      // Format the date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for the request
      var requestBody = {
        "state": "state_job_listing",
        "job_desc": jobDesc,
        "salary_min": salaMin,
        "salary_max": salMax,
        "job_loc": jobLoc,
        'api_key': apiKey,
      };

      // Send HTTP request
      http.Response response = await http.post(
        uri,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: requestBody,
      );

      // Decode the response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during job listing: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }

  // Fetch job recommendations
  static Future<Map> jobReq(String userId) async {
    try {
      // API Link
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Get the current date
      DateTime now = DateTime.now();

      // Format the date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for the request
      var requestBody = {
        "state": "state_job_rec",
        "user_id": userId,
        'api_key': apiKey,
      };

      // Send HTTP request
      http.Response response = await http.post(
        uri,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: requestBody,
      );

      // Decode the response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during job request: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }

  // Apply to a job
  static Future<Map<String, dynamic>> applyToJob(
      String userId, String resume, String jobId,
      {bool isFile = false}) async {
    try {
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');
      DateTime now = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);
      String apiKey =
          md5.convert(utf8.encode("hireme$formattedDate")).toString();

      // Create multipart request
      var request = http.MultipartRequest("POST", uri)
        ..fields['state'] = 'state_apply_job'
        ..fields['user_id'] = userId
        ..fields['job_id'] = jobId
        ..fields['api_key'] = apiKey;

      if (isFile) {
        // Attach file if provided
        var file = File(resume);
        request.files.add(
          http.MultipartFile.fromBytes(
            'resumefile',
            file.readAsBytesSync(),
            filename: path.basename(file.path),
            contentType: MediaType('application', 'octet-stream'),
          ),
        );
      } else {
        // Use Google Drive link if provided
        request.fields['ResumeFilePath'] = resume;
      }

      // Send request and get response
      var response = await request.send();

      // Parse response
      var responseBody = await response.stream.bytesToString();
      print('Raw API Response: $responseBody');

      if (responseBody.isEmpty) {
        return {'verdict': false, 'message': 'Empty response from server'};
      }

      // Decode the outer JSON response
      var decodedResponse = jsonDecode(responseBody);

      // Check if the response contains a "messages" field
      if (decodedResponse['messages'] != null &&
          decodedResponse['messages'] is List) {
        // Parse the first message in the "messages" array
        var innerMessage = decodedResponse['messages'][0];
        var innerDecoded = jsonDecode(innerMessage);

        // Return the inner message verdict and message
        return {
          'verdict': innerDecoded['verdict'],
          'message': innerDecoded['message'],
        };
      }

      // If no nested messages, return the original response
      return decodedResponse;
    } catch (e) {
      print('Error during application submission: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }

  // Fetch jobs the user has applied to
  static Future<Map<String, dynamic>> jobApplied(String userId) async {
    try {
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');
      DateTime now = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);
      String apiKey =
          md5.convert(utf8.encode("hireme$formattedDate")).toString();

      var requestBody = {
        "state": "state_list_job_application",
        "user_id": userId,
        "api_key": apiKey,
      };

      final response = await http.post(
        uri,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: requestBody,
      );
      print('Request Body: $requestBody');
      print('Raw API Response: ${response.body}');
      print('Raw Body: ${response.body}');

      if (response.body.isEmpty) {
        return {'verdict': false, 'message': 'Empty response from server'};
      }
      return jsonDecode(utf8.decode(response.bodyBytes));
    } catch (e) {
      return {'verdict': false, 'message': e.toString()};
    }
  }

  // Fetch interview list for the user
  static Future<Map<String, dynamic>> interviewList(String userId) async {
    try {
      // API Link
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Get the current date
      DateTime now = DateTime.now();

      // Format the date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for the request
      var requestBody = {
        "state": "state_list_interview",
        "user_id": userId,
        "api_key": apiKey,
      };

      // Send HTTP request
      http.Response response = await http.post(
        uri,
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: requestBody,
      );

      // Decode the response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during interview list fetch: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }
}
