import 'dart:io';

import 'package:flutter/material.dart';
import 'package:nutrizer/constant.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:share/share.dart';

class InviteScreen extends StatelessWidget {
  const InviteScreen({Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      child: Scaffold(
        appBar: AppBar(
          elevation: 0,
          title: Text("Undang Teman",
              style: FontStyleHelper.appBarTitle.copyWith(fontSize: 22)),
        ),
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: <Widget>[
            Container(
                margin: EdgeInsets.symmetric(horizontal: 50, vertical: 40),
                child: Image.asset(AssetsHelper.invite)),
            Container(
              margin: EdgeInsets.symmetric(horizontal: 30),
              child: Text(
                "Yuk, undang temanmu untuk menggunakan $appName juga !",
                textAlign: TextAlign.center,
                style: FontStyleHelper.formHeaderTitle.copyWith(
                  color: Theme.of(context).accentColor,
                  fontWeight: FontWeight.w900,
                ),
              ),
            ),
            SizedBox(height: 8),
            Container(
                margin: EdgeInsets.symmetric(horizontal: 30),
                child: Text(
                  "Bagikan dan undang temanmu dengan klik tombol berikut",
                  textAlign: TextAlign.center,
                  style: TextStyle(
                      color: ColorPrimaryHelper.textLight, fontSize: 14),
                )),
            SizedBox(height: 15),
            ButtonPrimaryWidget("Bagikan", onPressed: () {
              if (Platform.isAndroid) {
                Share.share(
                    'Yuk cobain aplikasi $appName sekarang!\n $androidStoreUrl');
              } else if (Platform.isIOS) {
                Share.share(
                    'Yuk cobain aplikasi $appName sekarang!\n $iosStoreUrl',
                    subject: '$appName Invitation');
              }else{
                DialogHelper.showSnackBar(context, "Fitur ini belum bisa digunakan di handphone kamu");
              }
            })
          ],
        ),
      ),
    );
  }
}
