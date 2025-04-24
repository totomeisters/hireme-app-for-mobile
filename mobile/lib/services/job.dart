import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:crypto/crypto.dart';
import 'package:path/path.dart' as path; // Add this import for basename

class JobService {
  static Future<Map> jobListings(
      String jobDesc, String salaMin, String salMax, String jobLoc) async {
    try {
      // API Link
      // var uri = Uri.parse('https://hireme-capstone.000webhostapp.com');
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Get date now
      DateTime now = DateTime.now();

      // Format Date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for request
      var requestBody = {
        "state": "state_job_listing",
        "job_desc": jobDesc,
        "salary_min": salaMin,
        "salary_max": salMax,
        "job_loc": jobLoc,
        'api_key': apiKey,
      };

      // Send http request
      http.Response response = await http.post(
        uri,
        body: requestBody,
      );

      // Decode Response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during login: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }

  static Future<Map> jobReq(String userId) async {
    try {
      // API Link
      // var uri = Uri.parse('https://hireme-capstone.000webhostapp.com');
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Get date now
      DateTime now = DateTime.now();

      // Format Date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for request
      var requestBody = {
        "state": "state_job_rec",
        "user_id": userId,
        'api_key': apiKey,
      };

      // Send http request
      http.Response response = await http.post(
        uri,
        body: requestBody,
      );

      // Decode Response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during login: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }

/*  static Future<Map<String, dynamic>> applyToJob(
      String userId, String resume, String jobId) async {
    try {
      // API Link
      // var uri = Uri.parse('https://hireme-capstone.000webhostapp.com');
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');
      // Get date now
      DateTime now = DateTime.now();

      // Format Date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for request
      var requestBody = {
        "state": "state_apply_job",
        "user_id": userId,
        "resume": resume,
        "job_id": jobId,
        'api_key': apiKey,
      };

      // Send http request
      http.Response response = await http.post(
        uri,
        body: requestBody,
      );

      // Decode Response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during login: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  } */

/*  static Future<Map<String, dynamic>> applyToJob(
      String userId, String resume, String jobId) async {
    try {
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');
      DateTime now = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);
      String apiKey =
          md5.convert(utf8.encode("hireme$formattedDate")).toString();

      var requestBody = {
        "state": "state_apply_job",
        "user_id": userId,
        "resume": resume,
        "job_id": jobId,
        'api_key': apiKey,
      };

      http.Response response = await http.post(uri, body: requestBody);
      print('Raw API Response: ${response.body}'); // Debugging

      final responseJson = utf8.decode(response.bodyBytes);
      if (responseJson.isEmpty) {
        return {'verdict': false, 'message': 'Empty response from server'};
      }
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during application submission: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  } */

  static Future<Map<String, dynamic>> applyToJob(
      String userId, String resume, String jobId,
      {bool isFile = false}) async {
    try {
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');
      DateTime now = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);
      String apiKey =
          md5.convert(utf8.encode("hireme$formattedDate")).toString();

      var requestBody = {
        "state": "state_apply_job",
        "user_id": userId,
        "job_id": jobId,
        'api_key': apiKey,
      };

      // Determine whether to send a file or a URL
      if (isFile) {
        requestBody["resumefile"] = resume; // For file picker uploads
      } else {
        requestBody["ResumeFilePath"] = resume; // For Google Drive links
      }

      http.Response response = await http.post(uri, body: requestBody);
      print('Raw API Response: ${response.body}');

      final responseJson = utf8.decode(response.bodyBytes);
      if (responseJson.isEmpty) {
        return {'verdict': false, 'message': 'Empty response from server'};
      }
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during application submission: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }

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

      final response = await http.post(uri, body: requestBody);
      print('Request Body: $requestBody');
      print('Raw API Response: ${response.body}');
      if (response.body.isEmpty) {
        return {'verdict': false, 'message': 'Empty response from server'};
      }
      return jsonDecode(utf8.decode(response.bodyBytes));
    } catch (e) {
      return {'verdict': false, 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> interviewList(String userId) async {
    try {
      // API Link
      // var uri = Uri.parse('https://hireme-capstone.000webhostapp.com');
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Get date now
      DateTime now = DateTime.now();

      // Format Date
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);

      // Key
      String key = "hireme$formattedDate";

      // Convert key to md5
      String apiKey = md5.convert(utf8.encode(key)).toString();

      // Fill up needed data for request
      var requestBody = {
        "state": "state_list_interview",
        "user_id": userId,
        "api_key": apiKey,
      };

      // Send http request
      http.Response response = await http.post(
        uri,
        body: requestBody,
      );

      // Decode Response
      final responseJson = utf8.decode(response.bodyBytes);
      return jsonDecode(responseJson);
    } catch (e) {
      print('Error during login: $e');
      return {'verdict': false, 'message': e.toString()};
    }
  }
}
