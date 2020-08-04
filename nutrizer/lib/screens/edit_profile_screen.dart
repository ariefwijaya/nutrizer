import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:nutrizer/blocs/profile/profile_bloc.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/models/form_model.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/widgets/common_widget.dart';
import 'package:nutrizer/widgets/textfield_widget.dart';

class EditProfileScreen extends StatefulWidget {
  const EditProfileScreen({Key key}) : super(key: key);

  @override
  _EditProfileScreenState createState() => _EditProfileScreenState();
}

class _EditProfileScreenState extends State<EditProfileScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  String _nickName;
  String _email;
  bool submitEnabled = false;
  ProfileBloc profileFetchBloc = ProfileBloc();

  @override
  Widget build(BuildContext context) {
    return BlocProvider<ProfileBloc>(
      create: (context) => profileFetchBloc..add(ProfileUserFetch()),
      child: Scaffold(
        appBar: AppBar(
            title: Text("Ubah Profile",
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 20))),
        body: BlocBuilder<ProfileBloc, ProfileState>(
          buildWhen: (previous, current) =>
              current is ProfileFetchSuccess ||
              current is ProfileFetchLoading ||
              current is ProfileFetchFailure,
          builder: (context, state) {
            if (state is ProfileFetchFailure) {
              return PlaceholderWidget(
                imagePath: AssetsHelper.error,
                title: "Failed to get Data",
                subtitle: "Something error happened. Please try again.",
                onPressed: () {
                  profileFetchBloc.add(ProfileUserFetch());
                },
              );
            }

            if (state is ProfileFetchSuccess) {
              return Container(
                margin: EdgeInsets.only(top: 20),
                padding: EdgeInsets.only(bottom: 20),
                decoration: BoxDecoration(color: Colors.white, boxShadow: [
                  BoxShadow(
                      color: ColorPrimaryHelper.lightShadow,
                      blurRadius: 5,
                      offset: Offset(0, 3))
                ]),
                child: Form(
                  key: _formKey,
                  onChanged: () => setState(() {
                    submitEnabled = true;
                  }),
                  child: Column(
                    mainAxisSize: MainAxisSize.min,
                    children: <Widget>[
                      TextFormV2Widget(
                        labelText: "Nama",
                        onSaved: (input) => _nickName = input,
                        initialValue: state.userModel.nickname,
                        validator: (input) =>
                            input.isNotEmpty ? null : "Masukkan Nama Lengkap",
                      ),
                      TextFormV2Widget(
                        labelText: "Email",
                        onSaved: (input) => _email = input,
                        validator: (input) =>
                            input.isNotEmpty && !input.contains("@")
                                ? 'Format email tidak benar'
                                : null,
                        initialValue: state.userModel.email,
                      ),
                      _buildAccountMenu(
                          menuName: "Ubah Password",
                          onTap: () {
                            Navigator.pushNamed(
                                context, RoutesPath.changePassword);
                          }),
                      SizedBox(height: 10),
                      BlocConsumer<ProfileBloc, ProfileState>(
                        listener: (context, subState) {
                          if (subState is ProfileSuccess) {
                            submitEnabled = false;
                            Fluttertoast.showToast(
                                msg: "Data berhasil diubah.",
                                backgroundColor: ColorPrimaryHelper.primary);
                          }

                          if (subState is ProfileFailure) {
                            Fluttertoast.showToast(
                                msg: subState.error,
                                backgroundColor: ColorPrimaryHelper.danger);
                          }
                        },
                        buildWhen: (previous, current) =>
                            current is ProfileSuccess ||
                            current is ProfileLoading ||
                            current is ProfileFailure,
                        builder: (subContext, subState) {
                          if (subState is ProfileLoading) {
                            return ButtonLoadingWidget();
                          }
                          return ButtonPrimaryWidget(
                            "Simpan",
                            onPressed:
                                submitEnabled ? () => _onSubmit(context) : null,
                          );
                        },
                      )
                    ],
                  ),
                ),
              );
            }
            return Center(child: CircularProgressIndicator());
          },
        ),
      ),
    );
  }

  void _onSubmit(BuildContext context) {
    if (_formKey.currentState.validate()) {
      _formKey.currentState.save();

      _showConfirmDialog(context);
    }
  }

  void _showConfirmDialog(BuildContext context) {
    showModalBottomSheet(
        context: context,
        builder: (context) => ModalBottomCard(
              imagePath: AssetsHelper.confirmation,
              title: "Anda yakin?",
              subtitle: "Perubahan yang anda buat akan tersimpan",
              labelButton: "Simpan",
              onButtonTap: () {
                Navigator.pop(context);
                profileFetchBloc.add(ProfileUpdateUser(
                    formData: FormEditProfileModel(
                        email: _email, nickname: _nickName)));
              },
            ));
  }

  Widget _buildAccountMenu(
      {String menuName, VoidCallback onTap, IconData iconData}) {
    return Column(
      children: <Widget>[
        ListTile(
          title: Text(
            menuName,
            style: TextStyle(fontWeight: FontWeight.bold),
          ),
          trailing: Icon(Icons.keyboard_arrow_right),
          onTap: onTap,
        ),
        Divider(
          thickness: 2,
          color: ColorPrimaryHelper.disabledIcon,
        )
      ],
    );
  }
}
