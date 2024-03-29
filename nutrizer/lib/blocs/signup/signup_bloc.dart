import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';

part 'signup_event.dart';
part 'signup_state.dart';

class SignupBloc extends Bloc<SignupEvent, SignupState> {
  final UserDomain _userDomain;

  SignupBloc({UserDomain userDomain, SignupState signupState})
      : _userDomain = userDomain ?? UserDomain(),
        super(signupState??SignupInitial());

  @override
  Stream<SignupState> mapEventToState(
    SignupEvent event,
  ) async* {
    if (event is SignupEmailButtonPressed) {
      yield* _mapSignupEmailButtonPressedToState(event);
    }
  }

  Stream<SignupState> _mapSignupEmailButtonPressedToState(
      SignupEmailButtonPressed event) async* {
    try {
      yield SignupLoading();
      bool signUpSuccess = await _userDomain.signupByEmail(
          email: event.email,
          birthday: event.birthday,
          nickname: event.nickname,
          password: event.password,
          username: event.username,
          gender: event.gender);
      if (signUpSuccess) {
        yield SignupSuccess();
      } else {
        yield SignupFailure("Failed to signup. Please try again");
      }
    } catch (error) {
      yield SignupFailure(error.toString());
    }
  }
}
