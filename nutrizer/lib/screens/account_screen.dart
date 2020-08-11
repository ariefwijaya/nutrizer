import 'dart:async';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/authentication/authentication_bloc.dart';
import 'package:nutrizer/blocs/profile/profile_bloc.dart';
import 'package:nutrizer/constant.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:shimmer/shimmer.dart';
import 'package:store_redirect/store_redirect.dart';

class AccountScreen extends StatefulWidget {
  const AccountScreen({Key key}) : super(key: key);

  @override
  _AccountScreenState createState() => _AccountScreenState();
}

class _AccountScreenState extends State<AccountScreen> {
  Completer<void> _refreshCompleter;

  @override
  void initState() {
    super.initState();
    _refreshCompleter = Completer<void>();
  }

  @override
  Widget build(BuildContext context) {
    return BlocProvider<ProfileBloc>(
      create: (context) => ProfileBloc()..add(ProfileUserFetch()),
      child: Scaffold(
        body:   BlocConsumer<ProfileBloc, ProfileState>(
                  listener: (context, state) {
                if (state is ProfileFetchSuccess ||
                    state is ProfileFetchFailure) {
                  _refreshCompleter?.complete();
                  _refreshCompleter = Completer();
                }
              }, builder: (context, state) {
                Widget profileInfoWidget;
                  if (state is ProfileFetchSuccess) {
                    profileInfoWidget= _buildProfileInfo(
                        name: state.userModel.nickname,
                        email: state.userModel.email,
                        isLoading: false);
                  }

                  else if (state is ProfileFetchFailure) {
                    profileInfoWidget= _buildProfileInfo(
                      name: "Nick Name",
                      email: "Email",
                    );
                  }else{
 profileInfoWidget= _buildProfileInfo();
                  }

                 


                return RefreshIndicator(
            onRefresh: () {
              BlocProvider.of<ProfileBloc>(context).add(
                ProfileUserFetch()
              );
              return _refreshCompleter.future;
            },
            child: ListView(
              children: <Widget>[
              profileInfoWidget,
                SizedBox(height: 25),
                Container(
                    padding: EdgeInsets.symmetric(horizontal: 25),
                    decoration: BoxDecoration(color: Colors.white, boxShadow: [
                      BoxShadow(
                          color: ColorPrimaryHelper.lightShadow,
                          blurRadius: 5,
                          offset: Offset(0, 3))
                    ]),
                    child: Column(
                      children: <Widget>[
                        Builder(builder: (context) {
                          return _buildAccountMenu(
                              iconData: Icons.account_circle,
                              menuName: "Ubah Profil",
                              onTap: () async {
                                await Navigator.pushNamed(
                                    context, RoutesPath.editProfile);
                                BlocProvider.of<ProfileBloc>(context)
                                    .add(ProfileUserFetch());
                              });
                        }),
                        _buildDivider(),
                        _buildAccountMenu(
                            iconData: Icons.info_outline,
                            menuName: "Tentang Aplikasi",
                            onTap: () {
                              Navigator.pushNamed(context, RoutesPath.aboutApp);
                            }),
                        _buildDivider(),
                        _buildAccountMenu(
                            iconData: Icons.star,
                            menuName: "Kasih Bintang",
                            onTap: () {
                              StoreRedirect.redirect(
                                  androidAppId: androidAppId, iOSAppId: iosAppId);
                            }),
                        _buildDivider(),
                        ButtonPrimaryWidget(
                          "Logout",
                          margin:
                              EdgeInsets.symmetric(vertical: 30, horizontal: 30),
                          color: ColorPrimaryHelper.danger,
                          onPressed: () {
                            BlocProvider.of<AuthenticationBloc>(context)
                                .add(AuthenticationLoggedOutEvent());
                          },
                        )
                      ],
                    )),
              ],
            ),
          );
              },
        ),
      ),
    );
  }

  Widget _buildAccountMenu(
      {String menuName, VoidCallback onTap, IconData iconData}) {
    return ListTile(
      leading: Icon(
        iconData,
        size: 30,
      ),
      title: Text(
        menuName,
        style: TextStyle(fontWeight: FontWeight.bold),
      ),
      trailing: Icon(Icons.keyboard_arrow_right),
      onTap: onTap,
    );
  }

  Widget _buildDivider() {
    return Divider(
      height: 10,
      thickness: 2,
      indent: 70,
    );
  }

  Widget _buildProfileInfo(
      {String imageUrl, String name, String email, bool isLoading = true}) {
    return Container(
      decoration: BoxDecoration(color: Colors.white, boxShadow: [
        BoxShadow(
            color: ColorPrimaryHelper.lightShadow,
            blurRadius: 5,
            offset: Offset(0, 3))
      ]),
      padding: EdgeInsets.symmetric(horizontal: 30, vertical: 25),
      child: Column(
        children: <Widget>[
          isLoading
              ? Shimmer.fromColors(
                  baseColor: Colors.grey,
                  highlightColor: Colors.grey.shade200,
                  enabled: isLoading,
                  child: CircleAvatar(
                    radius: 45,
                    backgroundColor: Colors.white,
                  ))
              : (imageUrl == null
                  ? CircleAvatar(
                      radius: 45,
                      child: isLoading
                          ? Container()
                          : Text(
                              CommonHelper.getInitials(
                                  string: name, limitTo: 2),
                              style: FontStyleHelper.appBarTitle,
                            ),
                    )
                  : CircleAvatar(
                      radius: 45,
                      backgroundImage: CachedNetworkImageProvider(imageUrl),
                    )),
          SizedBox(height: 10),
          !isLoading
              ? Text(name,
                  style: FontStyleHelper.formHeaderTitle
                      .copyWith(color: ColorPrimaryHelper.accent, fontSize: 16))
              : Shimmer.fromColors(
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                  child: Container(
                      height: 14, width: double.infinity, color: Colors.white),
                ),
          SizedBox(height: 5),
          !isLoading
              ? Text(email,
                  style: FontStyleHelper.formHeaderSubTitle.copyWith(
                      color: ColorPrimaryHelper.textLight, fontSize: 14))
              : Shimmer.fromColors(
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                  child: Container(
                      height: 12, width: double.infinity, color: Colors.white),
                )
        ],
      ),
    );
  }
}
