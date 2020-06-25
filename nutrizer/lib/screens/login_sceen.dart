import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/authentication/authentication_bloc.dart';
import 'package:nutrizer/blocs/login/login_bloc.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/routes/router_const.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/widgets/footer_widget.dart';
import 'package:nutrizer/widgets/header_widget.dart';
import 'package:nutrizer/widgets/textfield_widget.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  String _username;
  String _password;
  @override
  Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          backgroundColor: Theme.of(context).scaffoldBackgroundColor,
          elevation: 0,
        ),
        body: BlocProvider<LoginBloc>(
          create: (context) => LoginBloc(),
          child: BlocListener<LoginBloc, LoginState>(
            condition: (prevState, currentState) {
              if (prevState is LoginLoading) {
                Navigator.pop(context);
              }
              return true;
            },
            listener: (context, state) {
              if (state is LoginLoading) {
                DialogHelper.showLoadingDialog(context); //invoking login
              } else if (state is LoginSuccess) {
                Navigator.popUntil(context, (route) => route.isFirst);
                BlocProvider.of<AuthenticationBloc>(context)
                    .add(AuthenticationLoggedInEvent());
              } else if (state is LoginFailure) {
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
                        color:
                            Theme.of(context).backgroundColor.withOpacity(0.5),
                      )),
                  Positioned(
                      top: 0,
                      right: 0,
                      child: Image.asset(
                        AssetsHelper.leavesDecor2,
                        colorBlendMode: BlendMode.dstOut,
                        color:
                            Theme.of(context).backgroundColor.withOpacity(0.5),
                      )),
                  SingleChildScrollView(
                    child: Column(
                      children: <Widget>[
                        HeaderFormWidget(
                          titleText: "Halo",
                          subTitleText: "Silahkan login",
                        ),
                        SizedBox(
                          height: 20,
                        ),
                        Form(
                            key: _formKey,
                            child: Container(
                              margin: EdgeInsets.symmetric(horizontal: 40),
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
                                        : "Only alphanumeric and underscore are allowed",
                                  ),
                                  SizedBox(height: 10),
                                  TextPasswordFormWidget(
                                    labelText: "Password",
                                    icon: Icons.lock,
                                    onSaved: (input) => _password = input,
                                    validator: (input) => input.isEmpty
                                        ? 'Please enter password'
                                        : null,
                                  ),
                                  Container(
                                    margin: EdgeInsets.symmetric(vertical: 3.0),
                                    alignment: Alignment.centerRight,
                                    child: FlatButton(
                                      onPressed: () {
                                        Navigator.pushNamed(context,ForgotPasswordRouter);
                                      },
                                      child: Text(
                                        "Lupa Password?",
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            )),
                        Container(
                          child: Builder(
                            builder: (context) => ButtonPrimaryWidget(
                              "Login",
                              onPressed: () {
                                if (_formKey.currentState.validate()) {
                                  _formKey.currentState.save();
                                  // Logging in the user w/ Firebas
                                  BlocProvider.of<LoginBloc>(context).add(
                                      LoginButtonPressed(
                                          username: _username,
                                          password: _password));
                                }
                              },
                            ),
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
                      titleText: "Belum punya akun?",
                      linkText: "Daftar Sekarang",
                      onPressed: () {
                        Navigator.pushNamedAndRemoveUntil(
                            context, RegisterRouter, (route) => route.isFirst);
                      },
                    ),
                  )
                ],
              ),
            ),
          ),
        ));
  }
}
