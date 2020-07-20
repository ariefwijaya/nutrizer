import 'dart:async';

import 'package:meta/meta.dart';
import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/models/user_model.dart';

part 'authentication_event.dart';
part 'authentication_state.dart';

class AuthenticationBloc
    extends Bloc<AuthenticationEvent, AuthenticationState> {
  final UserDomain _userDomain;

  AuthenticationBloc({UserDomain userDomain})
      : _userDomain = userDomain ?? UserDomain();

  @override
  AuthenticationState get initialState => AuthenticationInitialState();

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
    try {
      await Future.delayed(Duration(seconds: 2));
      final isSignedIn = await _userDomain.isLoggedIn();
      if (isSignedIn) {
        UserModel userModel = await _userDomain.getCurrentSession();
        if (userModel.weight == null || userModel.height == null) {
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
      if (userModel.weight == null || userModel.height == null) {
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
