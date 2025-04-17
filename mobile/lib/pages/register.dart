import 'package:flutter/material.dart';
import 'package:hireme_app/pages/email_verification.dart'; // Import the EmailVerification page
import 'package:google_fonts/google_fonts.dart';
import 'package:hireme_app/services/auth.dart'; // Import the RegisterLogin class
import 'package:intl/intl.dart'; // Import for DateFormat
import 'package:hireme_app/pages/login.dart'; // Import the Login page

class Register extends StatelessWidget {
  const Register({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: Scaffold(
        body: Stack(
          children: [
            Container(
              decoration: const BoxDecoration(
                image: DecorationImage(
                  image: AssetImage("assets/images/backg.png"),
                  fit: BoxFit.cover,
                ),
              ),
            ),
            const Center(
              child: SingleChildScrollView(
                padding: EdgeInsets.all(20.0),
                child: RegisterForm(),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class RegisterForm extends StatefulWidget {
  const RegisterForm({super.key});

  @override
  _RegisterFormState createState() => _RegisterFormState();
}

class _RegisterFormState extends State<RegisterForm> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _birthdateController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _contactNumberController =
      TextEditingController();
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _usernameController =
      TextEditingController(); // Changed from _emailController
  final TextEditingController _passwordController = TextEditingController();
  String passwordStrength = "";

  @override
  void dispose() {
    _birthdateController.dispose();
    _emailController.dispose();
    _firstNameController.dispose();
    _lastNameController.dispose();
    _contactNumberController.dispose();
    _addressController.dispose();
    _usernameController.dispose(); // Changed from _emailController
    _passwordController.dispose();
    super.dispose();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
      builder: (BuildContext context, Widget? child) {
        return Theme(
          data: ThemeData.light().copyWith(
            primaryColor: Colors.blueAccent,
            hintColor: Colors.blueAccent,
            colorScheme: const ColorScheme.light(primary: Colors.blueAccent),
            buttonTheme:
                const ButtonThemeData(textTheme: ButtonTextTheme.primary),
          ),
          child: child!,
        );
      },
    );
    if (picked != null) {
      setState(() {
        _birthdateController.text = DateFormat('yyyy-MM-dd').format(picked);
      });
    }
  }

  Future<void> _register() async {
    if (_formKey.currentState!.validate()) {
      String firstName = _firstNameController.text;
      String lastName = _lastNameController.text;
      String email = _emailController.text;
      String birthdate = _birthdateController.text;
      String contactNumber = _contactNumberController.text;
      String address = _addressController.text;
      String username = _usernameController.text; // Changed from email
      String password = _passwordController.text;

      try {
        final response = await SampleService.reg(
          firstName,
          lastName,
          birthdate,
          contactNumber,
          address,
          username, // Changed from email
          password,
          email,
        );

        print('Response: $response'); // Debugging statement

        if (response["verdict"] == true) {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) =>
                  EmailVerification(userID: response["user_id"]),
            ),
          );
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(response["message"] ?? 'Registration successful.'),
              duration: const Duration(seconds: 3),
              backgroundColor: Colors.green,
            ),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text(response["message"] ??
                  'Registration failed. Please try again.'),
              duration: const Duration(seconds: 3),
              backgroundColor: Colors.red,
            ),
          );
        }
      } catch (e) {
        print('Error: $e'); // Debugging statement
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error: ${e.toString()}'),
            duration: const Duration(seconds: 3),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  void _navigateToLogin() {
    Navigator.push(
      context,
      MaterialPageRoute(
          builder: (context) => const Login(
                title: 'Welcome to login',
              )),
    );
  }

  String? _validateContactNumber(String? value) {
    if (value == null || value.isEmpty) {
      return 'Please enter your contact number';
    }
    final RegExp contactRegExp = RegExp(r'^[0-9]{11}$');
    if (!contactRegExp.hasMatch(value)) {
      return 'Please enter a valid 11-digit contact number';
    }
    return null; // Return null if validation passes
  }

  void _checkPasswordStrength(String password) {
    if (password.isEmpty) {
      setState(() {
        passwordStrength = '';
      });
      return;
    }

    final bool hasUpper = password.contains(RegExp(r'[A-Z]'));
    final bool hasLower = password.contains(RegExp(r'[a-z]'));
    final bool hasDigit = password.contains(RegExp(r'[0-9]'));
    final bool hasSpecial =
        password.contains(RegExp(r'[!@#$%^&*(),.?":{}|<>]'));
    final bool hasMinLength = password.length >= 8;

    if (hasUpper && hasLower && hasDigit && hasSpecial && hasMinLength) {
      setState(() {
        passwordStrength = 'Strong';
      });
    } else if (password.length >= 6) {
      setState(() {
        passwordStrength = 'Medium';
      });
    } else {
      setState(() {
        passwordStrength =
            'Weak password. You might want to reconsider this choice later for better security ';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Form(
      key: _formKey,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        mainAxisAlignment: MainAxisAlignment.center,
        children: <Widget>[
          const SizedBox(height: 30),
          _buildTextField(
            controller: _firstNameController,
            labelText: 'First Name',
            icon: Icons.person,
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _lastNameController,
            labelText: 'Last Name',
            icon: Icons.person,
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _birthdateController,
            labelText: 'Birthdate',
            icon: Icons.calendar_today,
            readOnly: true,
            onTap: () => _selectDate(context),
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _contactNumberController,
            labelText: 'Contact Number',
            icon: Icons.phone,
            keyboardType: TextInputType.phone,
            validator: _validateContactNumber,
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _emailController,
            labelText: 'Email',
            icon: Icons.email,
            keyboardType: TextInputType.emailAddress,
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _addressController,
            labelText: 'Permanent Address',
            icon: Icons.home,
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _usernameController,
            labelText: 'Username',
            icon: Icons.person_outline,
          ),
          const SizedBox(height: 20),
          _buildTextField(
            controller: _passwordController,
            labelText: 'Password',
            icon: Icons.lock,
            obscureText: true,
            onChanged: _checkPasswordStrength,
          ),
          if (passwordStrength.isNotEmpty)
            Text(
              'Password Strength: $passwordStrength',
              style: TextStyle(
                color: passwordStrength == 'Strong'
                    ? Colors.green
                    : (passwordStrength == 'Medium'
                        ? Colors.orange
                        : Colors.red),
              ),
            ),
          const SizedBox(height: 30),
          ElevatedButton(
            onPressed: _register,
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(vertical: 15),
              backgroundColor: Colors.blueAccent,
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(10.0),
              ),
            ),
            child: Text(
              'Register',
              style: GoogleFonts.roboto(fontSize: 20, color: Colors.white),
            ),
          ),
          const SizedBox(height: 10),
          TextButton(
            onPressed: _navigateToLogin,
            child: Text(
              'Back to Login',
              style: GoogleFonts.roboto(
                color: Colors.blue,
                fontSize: 20,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTextField({
    required TextEditingController controller,
    required String labelText,
    required IconData icon,
    bool readOnly = false,
    TextInputType keyboardType = TextInputType.text,
    void Function()? onTap,
    bool obscureText = false,
    String? Function(String?)? validator, // Correct type here
    void Function(String)? onChanged,
  }) {
    return TextFormField(
      controller: controller,
      decoration: InputDecoration(
        labelText: labelText,
        prefixIcon: Icon(icon, color: Colors.blueAccent),
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(10.0),
        ),
        filled: true,
        fillColor: Colors.white.withOpacity(0.8),
      ),
      readOnly: readOnly,
      onTap: onTap,
      keyboardType: keyboardType,
      obscureText: obscureText,
      validator: validator, // Correct type here
      onChanged: onChanged,
    );
  }
}

void main() {
  runApp(const Register());
}
