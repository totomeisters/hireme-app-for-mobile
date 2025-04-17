import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:crypto/crypto.dart';

class SampleService {
  static Future<Map> logIn(String username, String password) async {
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
        'state': 'state_log_in',
        'username': username,
        'password': password,
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
      return {'status': 'error', 'message': e.toString()};
    }
  }

/*  static Future<Map> otpVerification(String userId, String otp) async {
    try {
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      DateTime now = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);
      String key = "hireme$formattedDate";
      String apiKey = md5.convert(utf8.encode(key)).toString();

      var requestBody = {
        "state": "state_otp_verification", // <-- Change state name
        "user_id": userId,
        "otp": otp,
        'api_key': apiKey,
      };

      print("Sending OTP verification request: $requestBody"); // Debugging log

      http.Response response = await http.post(uri, body: requestBody);
      final responseJson = utf8.decode(response.bodyBytes);
      var jsonResponse = jsonDecode(responseJson);

      print("OTP verification response: $jsonResponse"); // Debugging log

      return jsonResponse;
    } catch (e) {
      print('Error during OTP verification: $e');
      return {'status': 'error', 'message': e.toString()};
    }
  } */ // LUMANG OTP VERIFICATION

  static Future<Map> otpVerification(String userId, String otp) async {
    try {
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      DateTime now = DateTime.now();
      String formattedDate = DateFormat('yyyy-MM-dd').format(now);
      String key = "hireme$formattedDate";
      String apiKey = md5.convert(utf8.encode(key)).toString();

      var requestBody = {
        "state": "state_verify_user", // ✅ Correct state
        "user_id": userId,
        "otp": otp,
        "api_key": apiKey,
      };

      http.Response response = await http.post(uri, body: requestBody);
      final responseJson = utf8.decode(response.bodyBytes);
      var jsonResponse = jsonDecode(responseJson);

      // ✅ Validate OTP response
      if (jsonResponse["verdict"] == false) {
        String message = jsonResponse.containsKey("message")
            ? jsonResponse["message"]
            : "Invalid OTP. Please try again.";

        return {"status": "error", "message": message};
      }

      // ✅ If OTP is correct, return success response
      return jsonResponse;
    } catch (e) {
      print('Error during OTP verification: $e');
      return {'status': 'error', 'message': e.toString()};
    }
  }

  static Future<Map> reg(
      String firstname,
      String lastname,
      String birthdate,
      String contactNumber,
      String address,
      String username,
      String password,
      String email) async {
    try {
      // API Link
      // var uri = Uri.parse('http://hireme-capstone.000webhostapp.com');
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
        'state': 'state_register',
        'email': email,
        'username': username,
        'password': password,
        'first_name': firstname,
        'last_name': lastname,
        'birthdate': birthdate,
        'address': address,
        'contact_number': contactNumber,
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
      print('Error during registration: $e');
      return {'status': 'error', 'message': e.toString()};
    }
  }

  static Future<Map> logInAndFetchDetails(
      String username, String password) async {
    try {
      // Log in the user
      Map loginResponse = await logIn(username, password);

      // Check if login was successful
      if (loginResponse['status'] == 'success') {
        // Extract job seeker ID from login response
        String jobSeekerId = loginResponse['job_seeker_id'];
        String apiKey = loginResponse['api_key'];

        // Fetch job seeker details
        Map jobSeekerDetails =
            await JobSeekerService.getJobSeekerDetails(jobSeekerId, apiKey);

        // Return job seeker details
        return jobSeekerDetails;
      } else {
        // Return login response if login failed
        return loginResponse;
      }
    } catch (e) {
      print('Error during login and fetch details: $e');
      return {'status': 'error', 'message': e.toString()};
    }
  }

  static resendOtp(String userID) {}
}

class JobSeekerService {
  static Future<Map> getJobSeekerDetails(
      String jobSeekerId, String apiKey) async {
    try {
      // API Link
      // var uri = Uri.parse('https://hireme-capstone.000webhostapp.com');
      var uri = Uri.parse('https://hireme-app.online/hireme/api.php');

      // Fill up needed data for request
      var requestBody = {
        'state': 'state_get_job_seeker_details',
        'job_seeker_id': jobSeekerId,
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
      print('Error during fetching job seeker details: $e');
      return {'status': 'error', 'message': e.toString()};
    }
  }
}
