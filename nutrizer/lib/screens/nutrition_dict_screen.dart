import 'package:flutter/material.dart';
import 'package:flutter_pagewise/flutter_pagewise.dart';
import 'package:nutrizer/domain/nutrition_domain.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'package:nutrizer/widgets/common_widget.dart';

class NutritionDictScreen extends StatelessWidget {
  final _nutritionDomain = NutritionDomain();
  final String screenTitle;
  NutritionDictScreen({Key key, this.screenTitle}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final _pageLoadController = PagewiseLoadController(
      pageSize: 6,
      pageFuture: (pageIndex) => _nutritionDomain.getNutriDictList(pageIndex),
    );

    return Scaffold(
      appBar: AppBar(
          title: Text(screenTitle,
              style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
      body: Stack(
        children: <Widget>[
          RefreshIndicator(
            onRefresh: () async {
              _pageLoadController.reset();
            },
            child: PagewiseListView(
                // pageSize: 6,
                pageLoadController: _pageLoadController,
                padding: EdgeInsets.symmetric(horizontal: 10, vertical: 85),
                itemBuilder: (context, entry, index) {
                  return NutritionDictCard(
                      title: entry.name,
                      imageUrl: entry.imageUrl,
                      isLoading: false,
                      onPressed: () {
                        Navigator.pushNamed(context, RoutesPath.nutriDictFood,
                            arguments: entry);
                      });
                },
                // pageFuture: (pageIndex) =>
                //     _nutritionDomain.getNutriDictList(pageIndex),
                retryBuilder: (context, callback) => PlaceholderWidget(
                      imagePath: AssetsHelper.error,
                      title: 'Ups, Error.',
                      subtitle: "Terjadi sesuatu yang kesalahan. Atau coba cek koneksi internet kamu.",
                      onPressed: () => callback(),
                    ),
                noItemsFoundBuilder: (context) => PlaceholderWidget(
                      imagePath: AssetsHelper.notfound,
                      title: 'Maaf! Tidak ditemukan.',
                      subtitle: "Ga ada apa-apa nih di sini",
                    ),
                loadingBuilder: (context) => Column(
                      children: <Widget>[
                        NutritionDictCard(),
                        NutritionDictCard(),
                        NutritionDictCard(),
                        NutritionDictCard()
                      ],
                    )),
          ),
          Positioned(
              top: 0,
              right: 0,
              left: 0,
              child: Container(
                padding: EdgeInsets.symmetric(horizontal: 14, vertical: 14),
                decoration:
                    BoxDecoration(color: Theme.of(context).primaryColor),
                child: Row(children: <Widget>[
                  Image.asset(
                    AssetsHelper.nutrition,
                    height: 40,
                  ),
                  SizedBox(width: 16),
                  Expanded(
                      child: Text(
                          "Nutrisi penting yang dibutuhkan oleh tubuh setiap hari"))
                ]),
              ))
        ],
      ),
    );
  }
}
