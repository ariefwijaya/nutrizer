import 'package:flutter/material.dart';
import 'package:nutrizer/widgets/header_widget.dart';

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
    body: SingleChildScrollView(
        child: Container(
      child: Column(
    children: <Widget>[
      HeaderHomeWidget(
        greetingText: "Halo, Yura",
        sectionText: "Indeks Massa Tubuh",
        height: 170,
        weight: 60,
        bmiValue: 21.2,
        bmiScoreText: "Normal",
      ),
      Container(
        height: 100,
      )
    ],
        )),
      ));
  }
}
