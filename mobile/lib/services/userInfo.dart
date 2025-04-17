import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:intl/intl.dart';
import 'package:crypto/crypto.dart';

class UserService {
  static Future<Map> profile(String userid) async {
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
        'state': 'state_user_profile',
        'user_id': userid,
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
}
