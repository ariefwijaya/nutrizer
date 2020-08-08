import 'dart:async';

import 'package:meta/meta.dart';
import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/models/user_model.dart';
import 'package:package_info/package_info.dart';

part 'authentication_event.dart';
part 'authentication_state.dart';

class AuthenticationBloc
    extends Bloc<AuthenticationEvent, AuthenticationState> {
  final UserDomain _userDomain;

  AuthenticationBloc(
      {UserDomain userDomain, AuthenticationState authenticationState})
      : _userDomain = userDomain ?? UserDomain(),
        super(authenticationState ?? AuthenticationInitialState());

  @override
  Stream<AuthenticationState> mapEventToState(
    AuthenticationEvent event,
  ) async* {
    if (event is AuthenticationStartedEvent) {
      yield* _mapAppStartedToState();
    } else if (event is AuthenticationLoggedInEvent) {
      yield* _mapLoggedInToState();
    } else if (event is AuthenticationLoggedOutEvent) {
      yield* _mapLoggedOutToState();
    }
  }

  Stream<AuthenticationState> _mapAppStartedToState() async* {
    bool needUpdate = false;
    bool forceUpdate = false;
    String messageUpdate = "";
    try {
      final resAppInfo = await _userDomain.getAppInfo();
      if (!resAppInfo.success) throw resAppInfo.message;
      final resAppInfoData = resAppInfo.data;

      forceUpdate = resAppInfoData['forceUpdate'];

      final resPackageInfo = PackageInfo(
          appName: resAppInfoData['appName'],
          buildNumber: resAppInfoData['buildNumber'],
          packageName: resAppInfoData['packageName'],
          version: resAppInfoData['version']);

      final PackageInfo packageInfo = await PackageInfo.fromPlatform();

      if (resPackageInfo.version != packageInfo.version) {
        messageUpdate ="Ada versi terbaru lagi nih versi ${resPackageInfo.version}, yakin lebih kece. Update dong^^. Kamu sekarang lagi pakai versi ${packageInfo.version}";
        needUpdate = true;
      }
    } catch (e) {
      print("Check App Version Error: $e");
    }

    if (needUpdate && forceUpdate) {
      yield AppForceUpdate(message: messageUpdate);
      return;
    } else {
      if (needUpdate) {
        yield AppNeedUpdate(message: messageUpdate);
      }
    }

    try {
      final isSignedIn = await _userDomain.isLoggedIn();
      if (isSignedIn) {
        UserModel userModel = await _userDomain.getCurrentSession();

        try {
          bool tokenStatus = await _userDomain.checkTokenValidation();
          if (!tokenStatus) {
            yield AuthenticationExpiredState(
                message:
                    "Session Expired. Or already Logged in another device. Logout automatically");
          }
        } catch (e) {
          print(e);
        }

        if (userModel.weight == null ||
            userModel.height == null ||
            userModel.weight == 0 ||
            userModel.height == 0) {
          yield AuthenticationAuthenticatedNotCompletedState(user: userModel);
        } else {
          yield AuthenticationAuthenticatedState(user: userModel);
        }
      } else {
        yield AuthenticationUnauthenticatedState();
      }
    } catch (e) {
      yield AuthenticationFailureState(message: e.toString());
    }
  }

  Stream<AuthenticationState> _mapLoggedInToState() async* {
    try {
      UserModel userModel = await _userDomain.getCurrentSession();
      if (userModel.weight == null ||
          userModel.height == null ||
          userModel.weight == 0 ||
          userModel.height == 0) {
        yield AuthenticationAuthenticatedNotCompletedState(user: userModel);
      } else {
        yield AuthenticationAuthenticatedState(user: userModel);
      }
    } catch (e) {
      yield AuthenticationFailureState(message: e.toString());
    }
  }

  Stream<AuthenticationState> _mapLoggedOutToState() async* {
    try {
      await _userDomain.logout();
      yield AuthenticationUnauthenticatedState();
    } catch (e) {
      yield AuthenticationFailureState(message: e.toString());
    }
  }
}
