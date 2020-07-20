import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';

class _InfoTileBMICardWidget extends StatelessWidget {
  final String titleText;
  final String assetPathIcon;
  final String infoText;
  final String infoUomText;
  final Color iconColor;

  _InfoTileBMICardWidget(
      {this.titleText,
      this.assetPathIcon,
      this.infoText,
      this.infoUomText,
      this.iconColor});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(left: 10),
      decoration: BoxDecoration(
          border: Border(left: BorderSide(width: 3, color: iconColor))),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: <Widget>[
          Text(
            titleText,
            style: TextStyle(fontSize: 16, color: ColorPrimaryHelper.formLabel),
          ),
          SizedBox(
            height: 5,
          ),
          Row(
            children: <Widget>[
              Image.asset(
                assetPathIcon,
                color: iconColor,
                height: 18,
              ),
              SizedBox(
                width: 5,
              ),
              Text(
                infoText,
                style: FontStyleHelper.sectionTitleBMI.copyWith(
                    color: Theme.of(context).accentColor, fontSize: 20),
              ),
              SizedBox(
                width: 5,
              ),
              Text(
                infoUomText,
                style: TextStyle(
                    fontSize: 16, color: ColorPrimaryHelper.formLabel),
              ),
            ],
          )
        ],
      ),
    );
  }
}

class BMICardWidget extends StatelessWidget {
  final int weight;
  final int height;
  final double bmiValue;
  final String bmiScoreText;

  BMICardWidget({this.weight, this.height, this.bmiValue, this.bmiScoreText});

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
        color: ColorPrimaryHelper.secondary,
        borderRadius: BorderRadius.circular(10),
        boxShadow: [
          BoxShadow(
              color: ColorPrimaryHelper.lightShadow,
              blurRadius: 12,
              offset: Offset(0, 2))
        ],
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(10),
          onTap: () {},
          child: Container(
              padding:
                  EdgeInsets.only(left: 20, top: 20, bottom: 15, right: 20),
              decoration:
                  BoxDecoration(borderRadius: BorderRadius.circular(10)),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                children: <Widget>[
                  Container(
                    padding: EdgeInsets.only(bottom: 10),
                    decoration: BoxDecoration(
                        border: Border(
                            bottom: BorderSide(
                                color: Theme.of(context).dividerColor,
                                width: 3))),
                    child: Row(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: <Widget>[
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: <Widget>[
                            _InfoTileBMICardWidget(
                              titleText: "Tinggi",
                              assetPathIcon: AssetsHelper.heightIcon,
                              iconColor: ColorPrimaryHelper.danger,
                              infoText: "$height",
                              infoUomText: "cm",
                            ),
                            SizedBox(
                              height: 15,
                            ),
                            _InfoTileBMICardWidget(
                              titleText: "Berat",
                              assetPathIcon: AssetsHelper.weightIcon,
                              iconColor: ColorPrimaryHelper.warning,
                              infoText: "$weight",
                              infoUomText: "kg",
                            )
                          ],
                        ),
                        Container(
                          decoration: BoxDecoration(
                              border: Border.all(
                                  color: ColorPrimaryHelper.lightPrimary,
                                  width: 4),
                              shape: BoxShape.circle),
                          padding: EdgeInsets.all(25),
                          child: Column(
                            mainAxisSize: MainAxisSize.min,
                            children: <Widget>[
                              Text(
                                "$bmiValue",
                                style: FontStyleHelper.sectionTitleBMI.copyWith(
                                    color: Theme.of(context).primaryColor,
                                    fontSize: 20),
                              ),
                              Text(
                                "IMT",
                                style: TextStyle(
                                    fontSize: 16,
                                    color: ColorPrimaryHelper.formLabel),
                              ),
                            ],
                          ),
                        )
                      ],
                    ),
                  ),
                  Container(
                    margin: EdgeInsets.only(top: 10),
                    child: Text(
                      bmiScoreText,
                      style: FontStyleHelper.sectionDividerTitle.copyWith(
                        color: Theme.of(context).primaryColor,
                        fontSize: 18,
                      ),
                    ),
                  )
                ],
              )),
        ),
      ),
    );
  }
}
