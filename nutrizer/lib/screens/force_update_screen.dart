import 'package:flutter/material.dart';
import 'package:nutrizer/constant.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/widgets/brand_widget.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:store_redirect/store_redirect.dart';

class ForceUpdateScreen extends StatefulWidget {
  final String message;
  const ForceUpdateScreen({Key key, this.message}) : super(key: key);

  @override
  _ForceUpdateScreenState createState() => _ForceUpdateScreenState();
}

class _ForceUpdateScreenState extends State<ForceUpdateScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).primaryColor,
        iconTheme: IconThemeData(color: Colors.white),
        title: Text("Update Versi Terbaru",
            style: FontStyleHelper.appBarTitle
                .copyWith(fontSize: 20, color: Colors.white)),
      ),
      body: Stack(
        children: <Widget>[
          Container(
              height: 230,
              width: double.infinity,
              color: Theme.of(context).primaryColor),
          SingleChildScrollView(
            child: Container(
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
                    ),
                    Card(
                      elevation: 10,
                      child: Padding(
                        padding: const EdgeInsets.all(12.0),
                        child: Text(widget.message,
                            style: TextStyle(
                                fontSize: 14,
                                color: ColorPrimaryHelper.danger)),
                      ),
                    ),
                    ButtonPrimaryWidget(
                      "UPDATE",
                      onPressed: () {
                        StoreRedirect.redirect(
                            androidAppId: androidAppId, iOSAppId: iosAppId);
                      },
                    )
                  ],
                )),
          )
        ],
      ),
    );
  }
}
