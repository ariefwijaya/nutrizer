import 'package:flutter/material.dart';
import 'package:nutrizer/widgets/brand_widget.dart';

class SplashScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).primaryColor,
        body: Center(
            child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: <Widget>[
            BrandLogo(),
            SizedBox(height: 8,),
            BrandName(),
            BrandTagLine(),
          ],
        )));
  }
}
