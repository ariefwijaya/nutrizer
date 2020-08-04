import 'package:flutter/material.dart';
import 'package:nutrizer/constant.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/widgets/brand_widget.dart';
import 'package:package_info/package_info.dart';

class AboutAppScreen extends StatefulWidget {
  const AboutAppScreen({Key key}) : super(key: key);

  @override
  _AboutAppScreenState createState() => _AboutAppScreenState();
}

class _AboutAppScreenState extends State<AboutAppScreen> {
  String _appName = "";
  String _packageName = "";
  String _version = "";
  String _buildNumber = "";

  @override
  void initState() {
    super.initState();

    PackageInfo.fromPlatform().then((PackageInfo packageInfo) {
      _appName = packageInfo.appName;
      _packageName = packageInfo.packageName;
      _version = packageInfo.version;
      _buildNumber = packageInfo.buildNumber;
      setState(() {});
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).primaryColor,
        iconTheme: IconThemeData(color: Colors.white),
        title: Text("Tentang Aplikasi",
            style: FontStyleHelper.appBarTitle
                .copyWith(fontSize: 20, color: Colors.white)),
      ),
      body: Stack(
        children: <Widget>[
          Container(
              height: 230,
              width: double.infinity,
              color: Theme.of(context).primaryColor),
          Container(
              margin: EdgeInsets.symmetric(horizontal: 30, vertical: 30),
              child: Column(
                children: <Widget>[
                  Stack(
                    alignment: Alignment.center,
                    children: <Widget>[
                      Image.asset(AssetsHelper.aboutApp),
                      BrandLogo()
                    ],
                  ),
                  Text("Version $_version",
                      style: TextStyle(
                          fontSize: 14, color: ColorPrimaryHelper.titleText)),
                  SizedBox(height: 8),
                  Text(appName,
                      textAlign: TextAlign.center,
                      style: Theme.of(context)
                          .textTheme
                          .headline2
                          .copyWith(color: Theme.of(context).primaryColor)),
                  SizedBox(height: 5),
                  Text(
                    appTagLine,
                    textAlign: TextAlign.center,
                    style: Theme.of(context)
                        .textTheme
                        .bodyText2
                        .copyWith(color: Theme.of(context).accentColor),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(vertical: 8.0),
                    child: Text(
                      appDescription,
                      textAlign: TextAlign.center,
                      style: TextStyle(
                          color: Theme.of(context).hintColor, fontSize: 16),
                    ),
                  )
                ],
              ))
        ],
      ),
    );
  }
}
