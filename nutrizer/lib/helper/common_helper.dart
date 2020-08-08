import 'dart:io';

import 'package:connectivity/connectivity.dart';
import 'package:url_launcher/url_launcher.dart' as urlLauncher;

class ConnectionHelper {
  static Future<bool> isOnline() async {
    var connectivityResult = await (Connectivity().checkConnectivity());
    if (connectivityResult == ConnectivityResult.none) {
      return false;
    } else {
      try {
        final result = await InternetAddress.lookup('google.com');
        if (result.isNotEmpty && result[0].rawAddress.isNotEmpty) {
          return true;
        }
      } on SocketException catch (_) {
        return false;
      }
    }

    return false;
  }
}

class CommonHelper {
  static String getInitials({String string, int limitTo}) {
    var buffer = StringBuffer();
    var split = string.split(' ');

    if (limitTo > split.length) {
      limitTo = split.length;
    }

    for (var i = 0; i < (limitTo ?? split.length); i++) {
      buffer.write(split[i][0]);
    }

    return buffer.toString();
  }

  static Future<void> launchURLInApp(String url) async {
    if (await urlLauncher.canLaunch(url)) {
      await urlLauncher.launch(url, enableJavaScript: true, forceWebView: true);
    } else {
      print('Could not launch $url');
    }
  }

  static int calculateAge(DateTime birthDate) {
    DateTime currentDate = DateTime.now();
    int age = currentDate.year - birthDate.year;
    int month1 = currentDate.month;
    int month2 = birthDate.month;
    if (month2 > month1) {
      age--;
    } else if (month1 == month2) {
      int day1 = currentDate.day;
      int day2 = birthDate.day;
      if (day2 > day1) {
        age--;
      }
    }
    return age;
  }
}
