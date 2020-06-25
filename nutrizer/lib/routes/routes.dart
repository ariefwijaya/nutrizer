import 'package:flutter/material.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/screens/bottom_bar_layout_screen.dart';
import 'package:nutrizer/screens/login_sceen.dart';
import 'package:nutrizer/screens/onboarding_screen.dart';
import 'package:nutrizer/screens/register_screen.dart';
import 'package:nutrizer/screens/splash_screen.dart';
import 'package:nutrizer/screens/forgot_password_screen.dart';
import 'package:page_transition/page_transition.dart';
import 'package:nutrizer/screens/bmi_user_screen.dart';

class Routes {
  static Route<dynamic> generateRoute(RouteSettings routeSettings) {
    switch (routeSettings.name) {
      case '/':
        return MaterialPageRoute(builder: (_) => OnBoardingScreen());
      case SplashScreenRouter:
        return MaterialPageRoute(builder: (_) => SplashScreen());
      case MenuRouter:
        return PageTransition(
            child: BottomBarLayoutScreen(),
            type: PageTransitionType.leftToRight,
            settings: routeSettings);
      case OnBoardingRouter:
        return MaterialPageRoute(builder: (_) => OnBoardingScreen());
       case LoginRouter:
        return MaterialPageRoute(builder: (_) => LoginScreen(),settings: routeSettings);
      case RegisterRouter:
        return MaterialPageRoute(builder: (_) => RegisterScreen(),settings: routeSettings);
      case InputBMIRouter:
        return MaterialPageRoute(builder: (_) => BMIUserScreen(),settings: routeSettings);
      case ForgotPasswordRouter:
        return MaterialPageRoute(builder: (_) => ForgotPasswordScreen(),settings: routeSettings);
      default:
        return MaterialPageRoute(
            builder: (_) => Scaffold(
                  body: Center(
                      child: Text('NOT FOUND')),
                ));
    }
  }
}

// Navigator.pushNamed(context, '/second', arguments: "arguments data");
// Navigator.pushReplacement(context, route);
