import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/login/login_bloc.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/widgets/header_widget.dart';
import 'package:nutrizer/widgets/textfield_widget.dart';

class ForgotPasswordScreen extends StatefulWidget {
  @override
  _ForgotPasswordScreenState createState() => _ForgotPasswordScreenState();
}

class _ForgotPasswordScreenState extends State<ForgotPasswordScreen> {
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  String _username;
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
            listenWhen: (prevState, currentState) {
              if (prevState is LoginLoading) {
                Navigator.pop(context);
              }
              return true;
            },
            listener: (context, state) {
              if (state is LoginLoading) {
                DialogHelper.showLoadingDialog(context); //invoking login
              } else if (state is LoginForgotPasswordSuccess) {
                DialogHelper.showSnackBar(context, "${state.message}",
                    type: SnackBarType.Success,
                    onClosed: (reason) => Navigator.pop(context));
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
                          titleText: "Lupa Password?",
                          subTitleText: "",
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
                                ],
                              ),
                            )),
                        Container(
                          child: Builder(
                            builder: (context) => ButtonPrimaryWidget(
                              "Reset Password",
                              onPressed: () {
                                if (_formKey.currentState.validate()) {
                                  _formKey.currentState.save();
                                  // Logging in the user w/ Firebas
                                  BlocProvider.of<LoginBloc>(context).add(
                                      LoginForgotPasswordRequested(
                                          username: _username));
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
                ],
              ),
            ),
          ),
        ));
  }
}
