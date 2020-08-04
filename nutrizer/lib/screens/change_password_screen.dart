import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:fluttertoast/fluttertoast.dart';
import 'package:nutrizer/blocs/profile/profile_bloc.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/models/form_model.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/widgets/common_widget.dart';
import 'package:nutrizer/widgets/textfield_widget.dart';

class ChangePasswordScreen extends StatefulWidget {
  @override
  _ChangePasswordScreenState createState() => _ChangePasswordScreenState();
}

class _ChangePasswordScreenState extends State<ChangePasswordScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _passwordController = TextEditingController();
  String _oldPassword, _newPassword;
  bool submitEnabled = false;

  @override
  Widget build(BuildContext context) {
    return BlocProvider<ProfileBloc>(
      create: (context) => ProfileBloc(),
      child: Scaffold(
        appBar: AppBar(
            title: Text("Ubah Password",
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 20))),
        body: Container(
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
                TextPasswordFormV2Widget(
                  labelText: "Password Lama",
                  onSaved: (input) => _oldPassword = input,
                  validator: (input) => input.isEmpty
                      ? 'Silahkan masukkan Password saat ini'
                      : null,
                ),
                TextPasswordFormV2Widget(
                  labelText: "Password",
                  hintText: "Diisi jika ingin mengganti password",
                  controller: _passwordController,
                  onSaved: (input) => _newPassword = input,
                  validator: (input) =>
                      input.isEmpty ? 'Silahkan masukkan Password' : null,
                ),
                TextPasswordFormV2Widget(
                    labelText: "Ulangi Password",
                    validator: (input) => input != _passwordController.text
                        ? 'Password tidak sama'
                        : null),
                SizedBox(height: 10),
                BlocConsumer<ProfileBloc, ProfileState>(
                  listener: (context, subState) {
                    if (subState is ProfileSuccess) {
                      submitEnabled = false;

                      _formKey.currentState.reset();
                      _passwordController.clear();
  
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
                  builder: (subContext, subState) {
                    if (subState is ProfileLoading) {
                      return ButtonLoadingWidget();
                    }
                    return ButtonPrimaryWidget(
                      "Ubah Password",
                      onPressed:
                          submitEnabled ? () => _onSubmit(subContext) : null,
                    );
                  },
                ),
               
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _onSubmit(BuildContext subContext) {
    if (_formKey.currentState.validate()) {
      _formKey.currentState.save();

      _showConfirmDialog(subContext);
    }
  }

  void _showConfirmDialog(BuildContext subContext) {
    showModalBottomSheet(
        context: subContext,
        builder: (context) => ModalBottomCard(
              imagePath: AssetsHelper.confirmation,
              title: "Anda yakin?",
              subtitle: "Update password",
              labelButton: "Ubah Password",
              onButtonTap: () {
                Navigator.pop(context);
                BlocProvider.of<ProfileBloc>(subContext).add(ProfileChangePassword(
                    formData: FormChangePasswordModel(
                        oldPassword: _oldPassword, newPassword: _newPassword)));
              },
            ));
  }
}
