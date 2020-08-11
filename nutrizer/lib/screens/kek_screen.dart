import 'package:flutter/material.dart';
import 'package:flutter_pagewise/flutter_pagewise.dart';
import 'package:nutrizer/domain/kek_domain.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'package:nutrizer/widgets/common_widget.dart';

class KekScreen extends StatefulWidget {
  final String screenTitle;
  KekScreen({Key key, this.screenTitle}) : super(key: key);

  @override
  _KekScreenState createState() => _KekScreenState();
}

class _KekScreenState extends State<KekScreen> {
  final _kekDomain = KekDomain();
  bool isOnline;

void _checkOffline(){
  isOnline=null;
  setState(() {
    
  });
  ConnectionHelper.isOnline().then((value) {
      setState(() {
        isOnline = value;
      });
    }).catchError((onError) {
      print(onError);
    });
}
  @override
  void initState() {
    super.initState();
    _checkOffline();
  }

  @override
  Widget build(BuildContext context) {
    final _pageLoadController = PagewiseLoadController(
        pageSize: 6,
        pageFuture: (pageIndex) => _kekDomain.getKekList(pageIndex));

    return Scaffold(
        appBar: AppBar(
            title: Text(widget.screenTitle,
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
        body: isOnline == null
            ? Center(child: CircularProgressIndicator())
            : !isOnline
                ? PlaceholderWidget(
                    imagePath: AssetsHelper.offline,
                    title: 'Kamu Sedang Offline',
                    subtitle:
                        "Coba cek koneksi internet kamu. Butuh internet untuk akses konten ini.",
                    onPressed: () => _checkOffline(),
                  )
                : RefreshIndicator(
                    onRefresh: () async {
                      _pageLoadController.reset();
                    },
                    child: PagewiseListView(
                        // pageSize: 6,
                        pageLoadController: _pageLoadController,
                        padding:
                            EdgeInsets.symmetric(horizontal: 10, vertical: 10),
                        itemBuilder: (context, entry, index) {
                          return KEKCard(
                              title: entry.title,
                              subtitle: entry.subtitle,
                              isLoading: false,
                              onPressed: () {
                                Navigator.pushNamed(
                                    context, RoutesPath.kekDetail,
                                    arguments: entry);
                              });
                        },

                        // pageFuture: (pageIndex) => _kekDomain.getKekList(pageIndex),
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
                                KEKCard(),
                                KEKCard(),
                                KEKCard(),
                                KEKCard(),
                              ],
                            )),
                  ));
  }
}
