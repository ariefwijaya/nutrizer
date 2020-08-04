import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';

class NutritionCalcScreen extends StatelessWidget {
  final String screenTitle;
  const NutritionCalcScreen({Key key,this.screenTitle}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
            title: Text(screenTitle,
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 20))),
    );
  }
}