import 'package:flutter/material.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/screens/about_app_screen.dart';
import 'package:nutrizer/screens/bmi_update_screen.dart';
import 'package:nutrizer/screens/bottom_bar_layout_screen.dart';
import 'package:nutrizer/screens/change_password_screen.dart';
import 'package:nutrizer/screens/edit_profile_screen.dart';
import 'package:nutrizer/screens/kek_detail_screen.dart';
import 'package:nutrizer/screens/kek_screen.dart';
import 'package:nutrizer/screens/login_sceen.dart';
import 'package:nutrizer/screens/nutrition_calc_screen.dart';
import 'package:nutrizer/screens/nutrition_dict_food_screen.dart';
import 'package:nutrizer/screens/nutrition_dict_screen.dart';
import 'package:nutrizer/screens/onboarding_screen.dart';
import 'package:nutrizer/screens/register_screen.dart';
import 'package:nutrizer/screens/splash_screen.dart';
import 'package:nutrizer/screens/forgot_password_screen.dart';
import 'package:nutrizer/widgets/common_widget.dart';
import 'package:page_transition/page_transition.dart';
import 'package:nutrizer/screens/bmi_user_screen.dart';

class Routes {
  static Route<dynamic> generateRoute(RouteSettings routeSettings) {
    switch (routeSettings.name) {
      case '/':
        return MaterialPageRoute(builder: (_) => OnBoardingScreen());
      case RoutesPath.splashScreen:
        return MaterialPageRoute(builder: (_) => SplashScreen());
      case RoutesPath.menu:
        return PageTransition(
            child: BottomBarLayoutScreen(),
            type: PageTransitionType.leftToRight,
            settings: routeSettings);
      case RoutesPath.onBoarding:
        return MaterialPageRoute(builder: (_) => OnBoardingScreen());
      case RoutesPath.login:
        return MaterialPageRoute(
            builder: (_) => LoginScreen(), settings: routeSettings);
      case RoutesPath.register:
        return MaterialPageRoute(
            builder: (_) => RegisterScreen(), settings: routeSettings);
      case RoutesPath.inputBMI:
        return MaterialPageRoute(
            builder: (_) => BMIUserScreen(), settings: routeSettings);
      case RoutesPath.forgotPassword:
        return MaterialPageRoute(
            builder: (_) => ForgotPasswordScreen(), settings: routeSettings);
      case RoutesPath.editProfile:
        return MaterialPageRoute(
            builder: (_) => EditProfileScreen(), settings: routeSettings);
      case RoutesPath.changePassword:
        return MaterialPageRoute(
            builder: (_) => ChangePasswordScreen(), settings: routeSettings);
      case RoutesPath.aboutApp:
        return MaterialPageRoute(
            builder: (_) => AboutAppScreen(), settings: routeSettings);
      case RoutesPath.updateBMI:
        return MaterialPageRoute(
            builder: (_) => BMIUpdateScreen(), settings: routeSettings);
      case RoutesPath.kek:
        return MaterialPageRoute(
            builder: (_) => KekScreen(screenTitle: routeSettings.arguments),
            settings: routeSettings);
      case RoutesPath.nutriCalc:
        return MaterialPageRoute(
            builder: (_) =>
                NutritionCalcScreen(screenTitle: routeSettings.arguments),
            settings: routeSettings);
      case RoutesPath.nutriDict:
        return MaterialPageRoute(
            builder: (_) =>
                NutritionDictScreen(screenTitle: routeSettings.arguments),
            settings: routeSettings);
      case RoutesPath.kekDetail:
        return MaterialPageRoute(
            builder: (_) => KEKDetailScreen(kekModel: routeSettings.arguments),
            settings: routeSettings);
      case RoutesPath.nutriDictFood:
        return MaterialPageRoute(
            builder: (_) => NutritionDictFoodScreen(
                nutritionDictModel: routeSettings.arguments),
            settings: routeSettings);

      default:
        return MaterialPageRoute(
            builder: (_) => Scaffold(
                  appBar: AppBar(
                    elevation: 0,
                  ),
                  body: PlaceholderWidget(
                    title: "Are you lost?",
                    subtitle: "We can't find the place for you.",
                    imagePath: AssetsHelper.notfound,
                    onPressed: null,
                  ),
                ));
    }
  }
}

// Navigator.pushNamed(context, '/second', arguments: "arguments data");
// Navigator.pushReplacement(context, route);
