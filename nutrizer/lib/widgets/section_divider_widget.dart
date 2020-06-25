import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';

class SectionDividerWidget extends StatelessWidget {
  final EdgeInsets padding;
  final EdgeInsets margin;
  final String titleText;
  final Color titleColor;
  final Widget rightWidget;

  SectionDividerWidget(this.titleText,{this.padding,this.margin,this.titleColor,this.rightWidget});
  @override
  Widget build(BuildContext context) {
    return Container(
      padding:padding,
      margin: margin,
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: <Widget>[
          Text(
            titleText,
            style: FontStyleHelper.sectionDividerTitle.copyWith(
             color:titleColor!=null?titleColor: ColorPrimaryHelper.titleText
            ),
          ),
          rightWidget!=null?rightWidget:Container()
        ],
      ),
    );
  }
}
