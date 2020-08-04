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
}
