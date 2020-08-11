import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:shimmer/shimmer.dart';

class InfoTileBMICardWidget extends StatelessWidget {
  final String titleText;
  final String assetPathIcon;
  final String infoText;
  final String infoUomText;
  final Color iconColor;
  final bool isLoading;

  InfoTileBMICardWidget(
      {this.titleText,
      this.assetPathIcon,
      this.infoText,
      this.infoUomText,
      this.iconColor,
      this.isLoading = false});

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
          !isLoading
              ? Text(
                  titleText,
                  style: TextStyle(
                      fontSize: 16, color: ColorPrimaryHelper.formLabel),
                )
              : Shimmer.fromColors(
                  child: Container(
                    decoration: BoxDecoration(color: Colors.white),
                    height: 18,
                    width: 100,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                ),
          SizedBox(
            height: 5,
          ),
          !isLoading
              ? Row(
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
              : Shimmer.fromColors(
                  child: Container(
                    decoration: BoxDecoration(color: Colors.white),
                    height: 30,
                    width: 100,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                ),
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
  final VoidCallback onTap;
  final bool isLoading;

  BMICardWidget(
      {this.weight,
      this.height,
      this.bmiValue,
      this.bmiScoreText,
      this.onTap,
      this.isLoading = true});

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
          onTap: onTap,
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
                            InfoTileBMICardWidget(
                              titleText: "Tinggi",
                              assetPathIcon: AssetsHelper.heightIcon,
                              iconColor: ColorPrimaryHelper.danger,
                              infoText: "$height",
                              infoUomText: "cm",
                              isLoading: isLoading,
                            ),
                            SizedBox(
                              height: 15,
                            ),
                            InfoTileBMICardWidget(
                              titleText: "Berat",
                              assetPathIcon: AssetsHelper.weightIcon,
                              iconColor: ColorPrimaryHelper.warning,
                              infoText: "$weight",
                              infoUomText: "kg",
                              isLoading: isLoading,
                            )
                          ],
                        ),
                        !isLoading
                            ? Container(
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
                                      style: FontStyleHelper.sectionTitleBMI
                                          .copyWith(
                                              color: Theme.of(context)
                                                  .primaryColor,
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
                            : Shimmer.fromColors(
                                child: Container(
                                  decoration: BoxDecoration(
                                      color: Colors.white,
                                      border: Border.all(
                                          color:
                                              ColorPrimaryHelper.lightPrimary,
                                          width: 4),
                                      shape: BoxShape.circle),
                                  height: 100,
                                  width: 100,
                                ),
                                baseColor: Colors.grey[200],
                                highlightColor: Colors.grey[100],
                              )
                      ],
                    ),
                  ),
                  Container(
                    margin: EdgeInsets.only(top: 10),
                    child: !isLoading
                        ? Text(
                            bmiScoreText,
                            style: FontStyleHelper.sectionDividerTitle.copyWith(
                              color: Theme.of(context).primaryColor,
                              fontSize: 18,
                            ),
                          )
                        : Shimmer.fromColors(
                            child: Container(
                              decoration: BoxDecoration(
                                color: Colors.white,
                              ),
                              height: 20,
                              width: double.infinity,
                            ),
                            baseColor: Colors.grey[200],
                            highlightColor: Colors.grey[100],
                          ),
                  )
                ],
              )),
        ),
      ),
    );
  }
}

class BannerCard extends StatelessWidget {
  const BannerCard(
      {Key key, this.title, this.subtitle, this.onTap, this.isLoading = false})
      : super(key: key);

  final String title, subtitle;
  final VoidCallback onTap;
  final bool isLoading;

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Card(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
          elevation: 6,
          shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.2),
          child: Stack(
            children: <Widget>[
              Positioned(
                  top: 0,
                  left: -10,
                  child: ClipRRect(
                    borderRadius: BorderRadius.only(
                        topLeft: Radius.circular(10),
                        bottomLeft: Radius.circular(10)),
                    child: Image.asset(AssetsHelper.seaweedBanner, width: 60),
                  )),
              Positioned(
                  bottom: 0,
                  right: -10,
                  child: RotatedBox(
                    quarterTurns: 2,
                    child: ClipRRect(
                      borderRadius: BorderRadius.only(
                          topLeft: Radius.circular(10),
                          bottomLeft: Radius.circular(10)),
                      child: Image.asset(AssetsHelper.seaweedBanner, width: 60),
                    ),
                  )),
              Row(
                children: <Widget>[
                  Expanded(
                      child: Container(
                    margin: EdgeInsets.symmetric(horizontal: 25, vertical: 25),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: <Widget>[
                        !isLoading
                            ? Text(title,
                                maxLines: 2,
                                style: TextStyle(
                                    color: Theme.of(context).primaryColor,
                                    fontSize: 18))
                            : Shimmer.fromColors(
                                child: Text("Loading...",
                                    style: TextStyle(
                                        color: Colors.white, fontSize: 22)),
                                baseColor: Colors.grey[300],
                                highlightColor: Colors.grey[100],
                              ),
                        SizedBox(height: 5),
                        subtitle != null && !isLoading
                            ? Text(subtitle,
                                textAlign: TextAlign.justify,
                                style: TextStyle(
                                    color: ColorPrimaryHelper.textLight,
                                    fontWeight: FontWeight.normal,
                                    fontSize: 14))
                            : Container()
                      ],
                    ),
                  ))
                ],
              ),
            ],
          )),
    );
  }
}

class MenuCard extends StatelessWidget {
  final String imagePath, menuTitle;
  final VoidCallback onPressed;
  const MenuCard({Key key, this.imagePath, this.menuTitle, this.onPressed})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        elevation: 6,
        shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.2),
        child: InkWell(
          borderRadius: BorderRadius.circular(12),
          onTap: onPressed,
          child: Container(
            width: 150,
            padding: const EdgeInsets.all(20.0),
            child: Column(
              children: <Widget>[
                Image.asset(
                  imagePath,
                ),
                SizedBox(
                  height: 10,
                ),
                Expanded(
                  child: Text(
                    menuTitle,
                    textAlign: TextAlign.center,
                    style: FontStyleHelper.brandName.copyWith(
                        color: ColorPrimaryHelper.titleText,
                        fontSize: 14,
                        fontWeight: FontWeight.normal),
                  ),
                )
              ],
            ),
          ),
        ));
  }
}

class KEKCard extends StatelessWidget {
  final String title, subtitle;
  final VoidCallback onPressed;
  final bool isLoading;
  const KEKCard(
      {Key key,
      this.title,
      this.subtitle,
      this.onPressed,
      this.isLoading = true})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(bottom: 10),
      child: Card(
        elevation: 7,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.3),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(10),
          child: Stack(
            children: <Widget>[
              !isLoading
                  ? ListTile(
                      contentPadding: const EdgeInsets.only(
                          top: 5.0, bottom: 5, right: 10, left: 30),
                      title: Text(title,
                      maxLines: 2,
                          style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: Theme.of(context).primaryColor)),
                      subtitle: subtitle != null
                          ? Container(
                              margin: EdgeInsets.only(top: 6),
                              child: Text(subtitle,
                                  style: TextStyle(
                                      fontSize: 14,
                                      fontWeight: FontWeight.normal,
                                      color: ColorPrimaryHelper.textLight)),
                            )
                          : Container(),
                      trailing: Icon(
                        Icons.chevron_right,
                        color: Theme.of(context).primaryColor,
                        size: 40,
                      ),
                      onTap: onPressed,
                    )
                  : Shimmer.fromColors(
                      child: ListTile(
                        contentPadding: const EdgeInsets.only(
                            top: 5.0, bottom: 5, right: 10, left: 30),
                        title: Container(
                            color: Colors.white,
                            height: 20,
                            width: double.infinity),
                        subtitle: Container(
                          margin: EdgeInsets.only(top: 10),
                          child: Container(
                              color: Colors.white,
                              height: 14,
                              width: double.infinity),
                        ),
                        trailing: Icon(
                          Icons.chevron_right,
                          color: Theme.of(context).primaryColor,
                          size: 40,
                        ),
                        onTap: isLoading ? null : onPressed,
                      ),
                      baseColor: Colors.grey[200],
                      highlightColor: Colors.grey[100],
                    ),
              Positioned(
                  left: 0,
                  top: 0,
                  bottom: 0,
                  child: !isLoading
                      ? Container(
                          width: 7,
                          color: ColorPrimaryHelper.sideList,
                        )
                      : Shimmer.fromColors(
                          child: Container(
                            width: 7,
                            color: Colors.white,
                          ),
                          baseColor: Colors.grey[200],
                          highlightColor: Colors.grey[100],
                        ))
            ],
          ),
        ),
      ),
    );
  }
}

class NutritionDictCard extends StatelessWidget {
  final String title, imageUrl;
  final VoidCallback onPressed;
  final bool isLoading;
  const NutritionDictCard(
      {Key key,
      this.title,
      this.imageUrl,
      this.onPressed,
      this.isLoading = true})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(bottom: 10),
      child: Card(
        elevation: 7,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
        shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.3),
        child: ClipRRect(
          borderRadius: BorderRadius.circular(10),
          child: !isLoading
              ? ListTile(
                  contentPadding: const EdgeInsets.only(
                      top: 16.0, bottom: 16, right: 16, left: 16),
                  title: Text(title,
                      maxLines: 2,
                      overflow: TextOverflow.ellipsis,
                      style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Theme.of(context).accentColor)),
                  trailing: imageUrl != null
                      ? CachedNetworkImage(
                          imageUrl: imageUrl,
                          imageBuilder: (context, imageProvider) => Container(
                                width: 60,
                                height: 60,
                                decoration: BoxDecoration(
                                    color: ColorPrimaryHelper.divider,
                                    borderRadius: BorderRadius.circular(10),
                                    image:
                                        DecorationImage(image: imageProvider)),
                              ),
                          errorWidget: (context, url, error) => Container(
                                width: 60,
                                height: 60,
                                decoration: BoxDecoration(
                                  color: ColorPrimaryHelper.divider,
                                  borderRadius: BorderRadius.circular(10),
                                ),
                                child: Icon(Icons.image),
                              ),
                          progressIndicatorBuilder: (context, url, progress) =>
                              Shimmer.fromColors(
                                child: Container(
                                  width: 60,
                                  height: 60,
                                  color: Colors.white,
                                ),
                                baseColor: Colors.grey[200],
                                highlightColor: Colors.grey[100],
                              ))
                      : Container(
                          width: 60,
                          height: 60,
                          decoration: BoxDecoration(
                            color: ColorPrimaryHelper.divider,
                            borderRadius: BorderRadius.circular(10),
                          ),
                          child: Icon(Icons.image),
                        ),
                  onTap: onPressed,
                )
              : Shimmer.fromColors(
                  child: ListTile(
                    contentPadding: const EdgeInsets.only(
                        top: 16.0, bottom: 16, right: 16, left: 16),
                    title: Container(
                        margin: EdgeInsets.only(right: 10),
                        color: Colors.white,
                        height: 22,
                        width: double.infinity),
                    trailing: Container(
                        width: 60,
                        height: 60,
                        decoration: BoxDecoration(
                          color: Colors.white,
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Icon(Icons.image)),
                    onTap: isLoading ? null : onPressed,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                ),
        ),
      ),
    );
  }
}

class NutritionCategoryCard extends StatelessWidget {
  final String title;
  final bool isLoading;
  final Widget child;
  const NutritionCategoryCard(
      {Key key, this.title, this.child, this.isLoading = true})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(bottom: 10),
      child: Card(
          elevation: 6,
          shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.2),
          child: Container(
            padding: EdgeInsets.symmetric(horizontal: 16, vertical: 10),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: <Widget>[
                !isLoading
                    ? Text(
                        title,
                        style: TextStyle(
                            fontSize: 18,
                            color: Theme.of(context).primaryColor),
                      )
                    : Shimmer.fromColors(
                        child: Container(
                          margin: EdgeInsets.only(bottom: 5),
                          width: double.infinity,
                          height: 18,
                          color: Colors.white,
                        ),
                        baseColor: Colors.grey[200],
                        highlightColor: Colors.grey[100],
                      ),
                child != null ? child : Container()
              ],
            ),
          )),
    );
  }
}

class NutritionFoodCard extends StatelessWidget {
  final String foodName, initialName, kkalVal;
  final bool isLoading;
  const NutritionFoodCard(
      {Key key,
      this.foodName,
      this.initialName,
      this.isLoading = true,
      this.kkalVal})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      decoration: BoxDecoration(
          border: Border(
              bottom: BorderSide(color: ColorPrimaryHelper.divider, width: 2))),
      margin: EdgeInsets.only(bottom: 5),
      padding: EdgeInsets.only(bottom: 10, top: 5),
      child: Row(children: <Widget>[
        !isLoading
            ? CircleAvatar(
                backgroundColor: Theme.of(context).primaryColor,
                radius: 15,
                child: Text(
                  initialName,
                  style: TextStyle(
                      color: Colors.white, fontWeight: FontWeight.w500),
                ))
            : Shimmer.fromColors(
                child: CircleAvatar(
                  backgroundColor: Colors.white,
                  radius: 15,
                ),
                baseColor: Colors.grey[200],
                highlightColor: Colors.grey[100],
              ),
        SizedBox(width: 15),
        Expanded(
          child: !isLoading
              ? Text(foodName,
                  style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.normal,
                      color: ColorPrimaryHelper.accent))
              : Shimmer.fromColors(
                  child: Container(
                    color: Colors.white,
                    width: double.infinity,
                    height: 18,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                ),
        ),
        SizedBox(width: 15),
        !isLoading
            ? (kkalVal != null
                ? Text(
                    "$kkalVal kkal",
                    style: TextStyle(
                        color: ColorPrimaryHelper.warning, fontSize: 12),
                  )
                : Container())
            : Shimmer.fromColors(
                child: Container(
                  color: Colors.white,
                  width: 60,
                  height: 14,
                ),
                baseColor: Colors.grey[200],
                highlightColor: Colors.grey[100],
              )
      ]),
    );
  }
}
