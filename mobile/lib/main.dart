import 'dart:io';

import 'package:flutter/material.dart';
import 'package:animated_splash_screen/animated_splash_screen.dart';
import 'package:hireme_app/pages/login.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';

import 'global/httpoverride.dart';

Future<void> main() async {
  await dotenv.load(fileName: "assets/.env");
  HttpOverrides.global = MyHttpOverrides();
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  // This widget is the root of your application.
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
        debugShowCheckedModeBanner: false,
        home: AnimatedSplashScreen(
          splash: 'assets/images/hireme_logo1.png',
          splashIconSize: double.infinity,
          nextScreen: const Login(title: 'Login'),
          splashTransition: SplashTransition.scaleTransition,
        ));
  }
}
