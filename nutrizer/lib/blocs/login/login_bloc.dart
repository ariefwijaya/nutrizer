import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/blocs/authentication/authentication_bloc.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/models/api_model.dart';

part 'login_event.dart';
part 'login_state.dart';

class LoginBloc extends Bloc<LoginEvent, LoginState> {
  final UserDomain _userDomain;

  LoginBloc({UserDomain userDomain, AuthenticationBloc authenticationBloc})
      : _userDomain = userDomain ?? UserDomain();

  @override
  LoginState get initialState => LoginInitial();

  @override
  Stream<LoginState> mapEventToState(
    LoginEvent event,
  ) async* {
  
    if (event is LoginCheckedUsernameExist) {
      yield* _mapLoginCheckedUsernameExistToState(event.username);
    }

    if (event is LoginButtonPressed) {
      yield* _mapLoginButtonPressedToState(event.username, event.password);
    }

    if (event is LoginForgotPasswordRequested) {
      yield* _mapLoginForgotPasswordRequestedToState(event.username);
    }
  }

  Stream<LoginState> _mapLoginCheckedUsernameExistToState(String username) async* {
    try {
      yield LoginLoading();
      ApiModel resDomain = await _userDomain.checkExistByusername(username);
      if (resDomain.success) {
        if (resDomain.data) {
          yield LoginUserExisted();
        } else {
          yield LoginUserNotExisted();
        }
      } else {
        yield LoginFailure(error: resDomain.message);
      }
    } catch (error) {
      yield LoginFailure(error: error.toString());
    }
  }

  Stream<LoginState> _mapLoginButtonPressedToState(
      String username, String password) async* {
    try {
      yield LoginLoading();
      final bool isLoggedIn = await _userDomain.loginByUsername(username, password);
      if (isLoggedIn) {
        yield LoginSuccess();
      } else {
        yield LoginFailure(error: "Login Failed.");
      }
    } catch (error) {
      yield LoginFailure(error: error.toString());
    }
  }

  Stream<LoginState> _mapLoginForgotPasswordRequestedToState(
      String username) async* {
    try {
      yield LoginLoading();
      final ApiModel resDomain = await _userDomain.requestResetPassword(username);
      if (resDomain.success) {
        yield LoginForgotPasswordSuccess(message: resDomain.message);
      } else {
        yield LoginFailure(error: resDomain.message);
      }
    } catch (error) {
      yield LoginFailure(error: error.toString());
    }
  }
}
