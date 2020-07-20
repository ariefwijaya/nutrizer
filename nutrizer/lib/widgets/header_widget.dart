import 'package:flutter/material.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'brand_widget.dart';
import 'section_divider_widget.dart';

class HeaderFormWidget extends StatelessWidget {
  final String titleText;
  final String subTitleText;

  HeaderFormWidget({@required this.titleText, this.subTitleText});

  @override
  Widget build(BuildContext context) {
    return Container(
      child: Column(
        children: <Widget>[
          Container(
            margin: EdgeInsets.symmetric(vertical: 3),
            child: Text(
              titleText,
              style: Theme.of(context)
                  .textTheme
                  .headline1
                  .copyWith(color: Theme.of(context).primaryColor),
            ),
          ),
          Text(
            subTitleText != null ? subTitleText : "",
            style: Theme.of(context).textTheme.subtitle1,
          )
        ],
      ),
    );
  }
}

class HeaderHomeWidget extends StatelessWidget {
  final int weight;
  final int height;
  final double bmiValue;
  final String bmiScoreText;
  final String greetingText;
  final String sectionText;
  final EdgeInsets padding;

  HeaderHomeWidget(
      {this.greetingText,
      this.sectionText,
      this.weight,
      this.height,
      this.bmiScoreText,
      this.bmiValue,
      this.padding});
  @override
  Widget build(BuildContext context) {
    return Stack(
      children: <Widget>[
        ClipPath(
          clipper: HeaderClipper(),
          child: Container(
            height: 250,
            padding: padding,
            color: Theme.of(context).primaryColor,
            child: Container(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: Column(
                children: <Widget>[
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: <Widget>[
                      Text(
                        greetingText,
                        style: TextStyle(fontSize: 22),
                      ),
                      BrandLogo(width: 25, padding: EdgeInsets.all(10))
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
        Container(
          padding: padding,
          child: Stack(
            children: <Widget>[
              Container(
                alignment: Alignment.topRight,
                margin: EdgeInsets.only(top: 50, right: 25),
                padding: EdgeInsets.symmetric(horizontal: 20),
                child: SectionDividerWidget(
                  sectionText,
                  titleColor: Theme.of(context).secondaryHeaderColor,
                ),
              ),
              Container(
                  alignment: Alignment.topRight,
                  margin: EdgeInsets.only(top: 50, right: 25),
                  padding: EdgeInsets.symmetric(horizontal: 20),
                  child: Image.asset(
                    AssetsHelper.seaweedSmall,
                    width: 60,
                  )),
              Container(
                  margin: EdgeInsets.only(top: 90),
                  padding: EdgeInsets.symmetric(horizontal: 20),
                  child: BMICardWidget(
                    height: height,
                    bmiValue: bmiValue,
                    weight: weight,
                    bmiScoreText: bmiScoreText,
                  )),
            ],
          ),
        )
      ],
    );
  }
}

class HeaderClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    var path = Path();
    path.lineTo(0, size.height - 50);
    path.quadraticBezierTo(
        size.width / 2, size.height, size.width, size.height - 50);
    path.lineTo(size.width, 0);
    path.close();
    return path;
  }

  @override
  bool shouldReclip(CustomClipper<Path> oldClipper) {
    return false;
  }
}
