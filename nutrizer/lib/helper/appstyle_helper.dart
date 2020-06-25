import 'package:flutter/material.dart';

abstract class ColorHelper {}

class ColorPrimaryHelper extends ColorHelper {
  ///Main color of the app
  static final primary = Color(0xff97c14b);

  ///Light version of main color
  static final lightPrimary = Color(0xffc5dc9b);

  ///Secondary color of button and for hyperlink
  static final accent = Color(0xff292d27);

  ///Inverse primary and base color. such as container dan title
  static final secondary = Color(0xffffffff);

  ///Background as based color for each page
  static final background = Color(0xfffafafa);

  ///Shadow color for widget
  static final shadow = Color(0x84292d27);

  ///Shadow but less
  static final lightShadow = Color(0x18000000);

  ///Used for any divider or border
  static final divider = Color(0xfff0f0f0);

  ///Used for title in header page and section
  static final titleText = Color(0xff525a4e);

  ///Used for makeover list tile, lies in side left/right
  static final sideList = Color(0xff807f7b);

  ///Label color in form field
  static final formLabel = Color(0xffc1c1c1);

  ///Subtitle and light color for text
  static final textLight = Color(0xff9ba497);

  ///Icon color especially on menu button when it is not active
  static final disabledIcon = Color(0xffb7b7b7);

  ///Danger, quit or error color
  static final danger = Color(0xffc15e4b);

  ///Warning color
  static final warning = Color(0xffdad332);
}

class FontStyleHelper {
  static String _lilitaOne = "LilitaOne";
  static String _quickSand = "Quicksand";

  ///Brand Name Text Style and color
  static final brandName = TextStyle(
    fontSize: 28,
    fontFamily: _lilitaOne,
  );

  static final tagLine = TextStyle(
      fontSize: 18, fontFamily: _quickSand, fontWeight: FontWeight.w600);

  static final bigButtonText = TextStyle(
      fontSize: 18, fontFamily: _quickSand, fontWeight: FontWeight.w900);

  static final formHeaderTitle = TextStyle(
      fontSize: 18, fontFamily: _quickSand, fontWeight: FontWeight.w900);
  static final formHeaderSubTitle = TextStyle(
      fontSize: 18, fontFamily: _quickSand, fontWeight: FontWeight.w600);

  static final appBarTitle = TextStyle(
      fontSize: 24, fontFamily: _quickSand, fontWeight: FontWeight.w900);

  static final sectionTitleBMI = TextStyle(
      fontSize: 28, fontFamily: _quickSand, fontWeight: FontWeight.w600);

  static final sectionDividerTitle =
      TextStyle(fontSize: 18, fontFamily: _quickSand);
}
