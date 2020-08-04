import 'package:flutter/material.dart';
import 'package:flutter_pagewise/flutter_pagewise.dart';
import 'package:nutrizer/domain/kek_domain.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/models/kek_model.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'package:nutrizer/widgets/common_widget.dart';

class KekScreen extends StatelessWidget {
  final _kekDomain = KekDomain();
  final String screenTitle;
  KekScreen({Key key, this.screenTitle}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
            title: Text(screenTitle,
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
        body: PagewiseListView(
            pageSize: 6,
            padding: EdgeInsets.symmetric(horizontal: 10, vertical: 10),
            itemBuilder: (context, entry, index) {
              return KEKCard(
                  title: entry.title,
                  subtitle: entry.subtitle,
                  isLoading: false,
                  onPressed: () {
                    Navigator.pushNamed(context, RoutesPath.kekDetail,
                        arguments: entry);
                  });
            },
            pageFuture: (pageIndex) => _kekDomain.getKekList(pageIndex),
            retryBuilder: (context, callback) => PlaceholderWidget(
                  imagePath: AssetsHelper.error,
                  title: 'Ups, Error.',
                  subtitle: "Terjadi sesuatu yang kesalahan",
                  onPressed: () => callback(),
                ),
            noItemsFoundBuilder: (context) => PlaceholderWidget(
                  imagePath: AssetsHelper.notfound,
                  title: 'Maaf! Tidak ditemukan.',
                  subtitle: "Ga ada apa-apa nih di sini",
                ),
            loadingBuilder: (context) => Column(
                  children: <Widget>[
                    KEKCard(),
                    KEKCard(),
                    KEKCard(),
                    KEKCard(),
                  ],
                )));
  }
}
