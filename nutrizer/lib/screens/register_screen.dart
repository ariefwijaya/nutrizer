import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/authentication/authentication_bloc.dart';
import 'package:nutrizer/blocs/signup/signup_bloc.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/widgets/footer_widget.dart';
import 'package:nutrizer/widgets/header_widget.dart';
import 'package:nutrizer/widgets/textfield_widget.dart';

class RegisterScreen extends StatefulWidget {
  @override
  _RegisterScreenState createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  final TextEditingController _passwordController = TextEditingController();
  String _username;
  String _password;
  String _email;
  String _birthday;
  String _nickname;

  @override
  Widget build(BuildContext context) {
    return WillPopScope(
      onWillPop: () => DialogHelper.showAlertDialog(context,
          content: "Batal mendaftar?", onNo: () {
        Navigator.of(context).pop(false);
      }, onYes: () {
        Navigator.of(context).pop(true);
      }),
      child: Scaffold(
          appBar: AppBar(
            backgroundColor: Theme.of(context).scaffoldBackgroundColor,
            elevation: 0,
          ),
          body: BlocProvider<SignupBloc>(
              create: (context) => SignupBloc(),
              child: BlocListener<SignupBloc, SignupState>(
                condition: (prevState, currentState) {
                  if (prevState is SignupLoading) {
                    Navigator.pop(context);
                  }
                  return true;
                },
                listener: (context, state) {
                  if (state is SignupLoading) {
                    DialogHelper.showLoadingDialog(context);
                  } else if (state is SignupSuccess) {
                    //dont remove the root page, since its contain authentication bloc instance
                    Navigator.popUntil(context, (route) => route.isFirst);
                    BlocProvider.of<AuthenticationBloc>(context)
                        .add(AuthenticationLoggedInEvent());
                  } else if (state is SignupFailure) {
                    DialogHelper.showSnackBar(context, state.error,
                        type: SnackBarType.Error);
                  }
                },
                child: Container(
                  height: MediaQuery.of(context).size.height,
                  child: Stack(
                    children: <Widget>[
                      Positioned(
                          top: 0,
                          left: 0,
                          child: Image.asset(
                            AssetsHelper.leavesDecor1,
                            colorBlendMode: BlendMode.dstOut,
                            color: Theme.of(context)
                                .backgroundColor
                                .withOpacity(0.5),
                          )),
                      Positioned(
                          top: 0,
                          right: 0,
                          child: Image.asset(
                            AssetsHelper.leavesDecor2,
                            colorBlendMode: BlendMode.dstOut,
                            color: Theme.of(context)
                                .backgroundColor
                                .withOpacity(0.5),
                          )),
                      SingleChildScrollView(
                        child: Column(
                          children: <Widget>[
                            HeaderFormWidget(
                              titleText: "Buat Akun",
                            ),
                            Form(
                                key: _formKey,
                                child: Container(
                                  margin: EdgeInsets.symmetric(horizontal: 30),
                                  child: Column(
                                    children: <Widget>[
                                      TextFormWidget(
                                        labelText: "Username",
                                        icon: Icons.person,
                                        onSaved: (input) => _username = input,
                                        validator: (input) => RegExp(
                                                    r'^[a-zA-Z0-9_]+$')
                                                .hasMatch(input)
                                            ? null
                                            : "Hanya diperbolehkan huruf, angka dan underscore",
                                      ),
                                      SizedBox(height: 5),
                                      TextFormWidget(
                                        labelText: "Nama Lengkap",
                                        icon: Icons.people,
                                        onSaved: (input) => _nickname = input,
                                        validator: (input) => input.isNotEmpty
                                            ? null
                                            : "Masukkan Nama Lengkap",
                                      ),
                                      SizedBox(height: 5),
                                      TextFormWidget(
                                        labelText: "Email",
                                        icon: Icons.email,
                                        keyboardType:
                                            TextInputType.emailAddress,
                                        onSaved: (input) => _email = input,
                                        validator: (input) =>
                                            !input.contains("@")
                                                ? 'Format email tidak benar'
                                                : null,
                                      ),
                                      SizedBox(height: 4),
                                      TextDateFormWidget(
                                        labelText: "Tanggal Lahir",
                                        icon: Icons.date_range,
                                        onSaved: (input) => _birthday = input,
                                        validator: (input) => input.isEmpty
                                            ? 'Silahkan masukkan Tanggal Lahir'
                                            : null,
                                      ),
                                      SizedBox(height: 4),
                                      TextPasswordFormWidget(
                                        labelText: "Password",
                                        controller: _passwordController,
                                        icon: Icons.lock,
                                        onSaved: (input) => _password = input,
                                        validator: (input) => input.isEmpty
                                            ? 'Silahkan masukkan Password'
                                            : null,
                                      ),
                                      SizedBox(height: 5),
                                      TextPasswordFormWidget(
                                          labelText: "Ulangi Password",
                                          icon: Icons.lock_outline,
                                          validator: (input) =>
                                              input != _passwordController.text
                                                  ? 'Password tidak sama'
                                                  : null),
                                    ],
                                  ),
                                )),
                            SizedBox(
                              height: 20,
                            ),
                            Builder(
                              builder: (context) => ButtonPrimaryWidget(
                                "Daftar",
                                onPressed: () {
                                  if (_formKey.currentState.validate()) {
                                    _formKey.currentState.save();
                                    // Logging in the user w/ Firebas
                                    BlocProvider.of<SignupBloc>(context).add(
                                        SignupEmailButtonPressed(
                                            email: _email,
                                            username: _username,
                                            password: _password));
                                  }
                                },
                              ),
                            ),
                            SizedBox(
                              height: 100,
                            )
                          ],
                        ),
                      ),
                      Positioned(
                        bottom: 0,
                        left: 0,
                        right: 0,
                        child: FooterFormWidget(
                          titleText: "Sudah punya akun?",
                          linkText: "Login Sekarang",
                          onPressed: () {
                            Navigator.pushNamedAndRemoveUntil(
                                context, LoginRouter, (route) => route.isFirst);
                          },
                        ),
                      ),
                    ],
                  ),
                ),
              ))),
    );
  }
}
