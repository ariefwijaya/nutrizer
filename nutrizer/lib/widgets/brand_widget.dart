import 'package:flutter/material.dart';
import 'package:nutrizer/constant.dart';
import 'package:nutrizer/helper/assets_helper.dart';

class BrandLogoWithName extends StatelessWidget {
  final double width;

  BrandLogoWithName({this.width = 60});
  @override
  Widget build(BuildContext context) {
    return Container(
      child: Column(
        children: <Widget>[
          Image.asset(
            AssetsHelper.assetPathAppLogo,
            width: width,
            fit: BoxFit.fitWidth,
            alignment: Alignment.center,
          ),
          Text(
            appName,
            textAlign: TextAlign.center,
            style: Theme.of(context).textTheme.headline2,
          ),
        ],
      ),
      padding: EdgeInsets.all(30),
      decoration: new BoxDecoration(
          color: Theme.of(context).canvasColor,
          shape: BoxShape.circle,
          border: Border.all(color: Theme.of(context).dividerColor, width: 2)),
    );
  }
}

class BrandLogo extends StatelessWidget {
  final double width;
  final EdgeInsetsGeometry padding;
  final EdgeInsetsGeometry margin;

  BrandLogo({this.width = 70, this.padding, this.margin});
  @override
  Widget build(BuildContext context) {
    return Container(
      child: Image.asset(
        AssetsHelper.assetPathAppLogo,
        width: width,
        fit: BoxFit.fitWidth,
        alignment: Alignment.center,
      ),
      margin: margin != null ? margin : EdgeInsets.all(5),
      decoration: new BoxDecoration(boxShadow: [
        BoxShadow(
          color: Colors.black26,
          blurRadius: 8,
        )
      ]),
    );
  }
}

class BrandName extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5.0),
      child: Text(
        appName,
        textAlign: TextAlign.center,
        style: Theme.of(context)
            .textTheme
            .headline2
            .copyWith(color: Theme.of(context).secondaryHeaderColor),
      ),
    );
  }
}

class BrandTagLine extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5.0),
      child: Text(
        appTagLine,
        textAlign: TextAlign.center,
        style: Theme.of(context).textTheme.bodyText2,
      ),
    );
  }
}

class BrandDescription extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 5.0, horizontal: 30),
      child: Text(
        appDescription,
        textAlign: TextAlign.center,
        style: TextStyle(
            color: Theme.of(context).secondaryHeaderColor, fontSize: 16),
      ),
    );
  }
}
