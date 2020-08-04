import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/banner_ads/banner_ads_bloc.dart';
import 'package:nutrizer/blocs/bmi/bmi_bloc.dart';
import 'package:nutrizer/blocs/profile/profile_bloc.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/models/menu_model.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/brand_widget.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'package:nutrizer/widgets/header_widget.dart';
import 'package:nutrizer/widgets/section_divider_widget.dart';

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final _listMenu = [
      MenuModel(
          image: AssetsHelper.sleep,
          name: "Kekurangan Energi Kronis",
          route: RoutesPath.kek),
      MenuModel(
          image: AssetsHelper.book,
          name: "Kamus Nutrisi",
          route: RoutesPath.nutriDict),
    ];

    return MultiBlocProvider(
      providers: [
        BlocProvider<BmiBloc>(
          create: (context) => BmiBloc()..add(BmiStarted()),
        ),
        BlocProvider<ProfileBloc>(
          create: (context) => ProfileBloc()..add(ProfileUserFetch()),
        ),
        BlocProvider<BannerAdsBloc>(
          create: (context) => BannerAdsBloc()..add(BannerAdsHomeFetch()),
        )
      ],
      child: Scaffold(
          appBar: AppBar(
            backgroundColor: Theme.of(context).primaryColor,
            elevation: 0,
            title: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                BlocBuilder<ProfileBloc, ProfileState>(
                  builder: (context, state) {
                    String name = "";
                    if (state is ProfileFetchSuccess) {
                      name = state.firstName;
                    }
                    return Text(
                      "Hi, $name",
                      style: TextStyle(fontSize: 20, color: Colors.white),
                    );
                  },
                ),
                BrandLogo(width: 25, padding: EdgeInsets.all(10))
              ],
            ),
          ),
          body: Container(
              child: Stack(
            children: <Widget>[
              ClipPath(
                clipper: HeaderClipper(),
                child: Container(
                  height: 250,
                  // padding: padding,
                  color: Theme.of(context).primaryColor,
                ),
              ),
              CustomScrollView(
                slivers: <Widget>[
                  SliverToBoxAdapter(
                    child: Container(
                      padding: EdgeInsets.only(bottom: 15),
                      child: BlocBuilder<BmiBloc, BmiState>(
                        builder: (context, state) {
                          if (state is BmiFailure) {
                            return HeaderHomeWidget(
                              padding: EdgeInsets.only(top: 30),
                              sectionText: "Indeks Massa Tubuh",
                              height: 0,
                              weight: 0,
                              bmiValue: 0,
                              bmiScoreText: "Failed. Retry",
                              onTap: () {
                                BlocProvider.of<BmiBloc>(context)
                                    .add(BmiStarted());
                              },
                            );
                          }

                          if (state is BmiSuccess) {
                            return HeaderHomeWidget(
                              padding: EdgeInsets.only(top: 30),
                              sectionText: "Indeks Massa Tubuh",
                              height: state.weight.toInt(),
                              weight: state.height.toInt(),
                              bmiValue: state.bmiValue,
                              bmiScoreText: state.bmiScoreText,
                              onTap: () async {
                                await Navigator.pushNamed(
                                    context, RoutesPath.updateBMI);
                                BlocProvider.of<BmiBloc>(context)
                                    .add(BmiStarted());
                              },
                            );
                          }

                          return HeaderHomeWidget(
                            padding: EdgeInsets.only(top: 30),
                            sectionText: "Indeks Massa Tubuh",
                            isLoading: true,
                          );
                        },
                      ),
                    ),
                  ),
                  SliverToBoxAdapter(
                    child: BlocBuilder<BannerAdsBloc, BannerAdsState>(
                      builder: (context, state) {
                        if (state is BannerAdsFetchSuccess) {
                          final banner = state.bannerAdsModel;
                          return Container(
                              margin: EdgeInsets.only(bottom: 10),
                              padding: EdgeInsets.symmetric(horizontal: 16),
                              child: BannerCard(
                                isLoading: false,
                                title: banner.title,
                                subtitle: banner.subtitle,
                                onTap: banner.linkUrl != null
                                    ? () => CommonHelper.launchURLInApp(
                                        banner.linkUrl)
                                    : null,
                              ));
                        }

                        return Container();
                      },
                    ),
                  ),
                  SliverToBoxAdapter(
                    child: SectionDividerWidget(
                      "Menu Utama",
                      titleColor: Theme.of(context).accentColor,
                      padding: EdgeInsets.symmetric(horizontal: 20),
                    ),
                  ),
                  SliverPadding(
                    padding: EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                    sliver: SliverGrid(
                      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                        crossAxisCount: 2,
                        childAspectRatio: 150 / 170,
                        crossAxisSpacing: 10,
                        mainAxisSpacing: 10,
                      ),
                      delegate: SliverChildBuilderDelegate(
                        (BuildContext context, int index) {
                          final _menu = _listMenu[index];
                          return MenuCard(
                            menuTitle: _menu.name,
                            imagePath: _menu.image,
                            onPressed: () {
                              Navigator.pushNamed(context, _menu.route,
                                  arguments: _menu.name);
                            },
                          );
                        },
                        childCount: _listMenu.length,
                      ),
                    ),
                  ),
                ],
              ),
            ],
          ))),
    );
  }
}
