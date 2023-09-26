import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_html/flutter_html.dart';
import 'package:flutter_html/style.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:nutrizer/blocs/kek/kek_bloc.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/models/kek_model.dart';
import 'package:nutrizer/widgets/common_widget.dart';

class KEKDetailScreen extends StatefulWidget {
  final KEKModel kekModel;
  const KEKDetailScreen({Key key, this.kekModel}) : super(key: key);

  @override
  _KEKDetailScreenState createState() => _KEKDetailScreenState();
}

class _KEKDetailScreenState extends State<KEKDetailScreen> {
  @override
  Widget build(BuildContext context) {
    return BlocProvider<KekBloc>(
      create: (context) => KekBloc()..add(KekFetchDetail(id: widget.kekModel.id)),
      child: Scaffold(
        appBar: AppBar(
            title: Text(widget.kekModel.title,
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
        body: BlocBuilder<KekBloc, KekState>(builder: (context, state) {
          if (state is KekDetailFailure) {
            return PlaceholderWidget(
              imagePath: AssetsHelper.error,
              title: 'Ups, Error.',
              subtitle: "Terjadi sesuatu yang kesalahan",
              onPressed: () => BlocProvider.of<KekBloc>(context)
                  .add(KekFetchDetail(id: widget.kekModel.id)),
            );
          }

          if(state is KekDetailOffline){
            return PlaceholderWidget(
              imagePath: AssetsHelper.offline,
              title: 'Kamu Sedang Offline',
              subtitle: "Coba cek koneksi internet kamu. Butuh internet untuk akses konten ini.",
              onPressed: () => BlocProvider.of<KekBloc>(context)
                  .add(KekFetchDetail(id: widget.kekModel.id)),
            );
          }

          if (state is KekDetailSuccess) {
            if (state.kekModel.content == null) {
              return PlaceholderWidget(
                imagePath: AssetsHelper.notfound,
                title: 'Konten kosong',
                subtitle: "Belum ada info nih yang bisa ditampilkan ke kamu.",
              );
            }

            return SingleChildScrollView(
                          child: Card(
                  elevation: 6,
                  shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.2),
                  margin: EdgeInsets.only(top: 20),
                  child: SizedBox(
                    width: double.infinity,
                    child: _buildHtmlContent(state.kekModel.content))),
            );
          }

          return Center(child: CircularProgressIndicator());
        }),
      ),
    );
  }

  Widget _buildHtmlContent(String html) {
    return Html(
      data: html,
      
      onLinkTap: (url) {
        CommonHelper.launchURLInApp(url);
      },
      style: {
        "body": Style(
            margin: EdgeInsets.all(16),
            color: ColorPrimaryHelper.accent,
            fontWeight: FontWeight.normal),
      },
      onImageTap: (src) {
        Fluttertoast.showToast(msg: "Fitur ini belum tersedia");
      },
    );
  }
}
