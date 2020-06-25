import 'package:flutter/material.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/brand_widget.dart';
import 'package:nutrizer/widgets/button_widget.dart';

class OnBoardingScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).primaryColor,
      body: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            Container(
                margin: EdgeInsets.all(20),
                child: Image.asset(
                  AssetsHelper.onBoardingFront,
                )),
            BrandName(),
            BrandTagLine(),
            SizedBox(
              height: 5,
            ),
            BrandDescription(),
            SizedBox(
              height: 20,
            ),
            ButtonOnBoardingWidget(
              "Lanjutkan",
              onPressed: () {
                Navigator.pushNamed(context, LoginRouter);
              },
            )
          ],
        ),
      ),
    );
  }
}
