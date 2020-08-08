import 'package:flutter/material.dart';
import 'package:flutter_pagewise/flutter_pagewise.dart';
import 'package:nutrizer/domain/nutrition_domain.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/models/nutrition_dict_model.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'package:nutrizer/widgets/common_widget.dart';

class NutritionDictFoodScreen extends StatelessWidget {
  final _nutritionDomain = NutritionDomain();
  final NutritionDictModel nutritionDictModel;
  NutritionDictFoodScreen({Key key, this.nutritionDictModel}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final _pageLoadController = PagewiseLoadController(
      pageSize: 6,
      pageFuture: (pageIndex) => _nutritionDomain.getNutriFoodCategory(
          nutritionDictModel.id, pageIndex),
    );

    return Scaffold(
      appBar: AppBar(
          title: Text(nutritionDictModel.name,
              style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
      body: Stack(
        children: <Widget>[
          RefreshIndicator(
            onRefresh: () async {
              _pageLoadController.reset();
            },
            child: PagewiseListView(
                pageLoadController: _pageLoadController,
                // pageSize: 6,
                padding: EdgeInsets.symmetric(horizontal: 0, vertical: 60),
                itemBuilder: (context, NutriCatModel entry, index) =>
                    NutritionCategoryCard(
                        title: entry.name,
                        child: Column(
                            children: entry.foodModel
                                .map<NutritionFoodCard>((foodData) {
                          return NutritionFoodCard(
                            foodName: foodData.name,
                            initialName: CommonHelper.getInitials(
                                string: foodData.name, limitTo: 1),
                            kkalVal: foodData.kkal,
                            isLoading: false,
                          );
                        }).toList()),
                        isLoading: false),
                // pageFuture: (pageIndex) => _nutritionDomain.getNutriFoodCategory(
                //     nutritionDictModel.id, pageIndex),
                retryBuilder: (context, callback) => PlaceholderWidget(
                      imagePath: AssetsHelper.error,
                      title: 'Ups, Error.',
                      subtitle:
                          "Terjadi sesuatu yang kesalahan. Atau coba cek koneksi internet kamu.",
                      onPressed: () => callback(),
                    ),
                noItemsFoundBuilder: (context) => PlaceholderWidget(
                      imagePath: AssetsHelper.notfound,
                      title: 'Maaf! Tidak ditemukan.',
                      subtitle: "Ga ada apa-apa nih di sini",
                    ),
                loadingBuilder: (context) => Column(
                    children: List<Widget>.generate(
                        2,
                        (int index) => NutritionCategoryCard(
                            child: Column(
                                children: List<Widget>.generate(
                                    3, (int idx) => NutritionFoodCard())))))),
          ),
          Positioned(
              top: 0,
              right: 0,
              left: 0,
              child: Container(
                padding: EdgeInsets.symmetric(horizontal: 14, vertical: 14),
                decoration: BoxDecoration(color: ColorPrimaryHelper.titleText),
                child:
                    Text("Makanan yang mengandung ${nutritionDictModel.name}."),
              ))
        ],
      ),
    );
  }
}
