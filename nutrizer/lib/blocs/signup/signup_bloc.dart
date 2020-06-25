import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';

part 'signup_event.dart';
part 'signup_state.dart';

class SignupBloc extends Bloc<SignupEvent, SignupState> {
  final UserDomain _userDomain;
  
  SignupBloc({UserDomain userDomain}): _userDomain = userDomain ?? UserDomain();

  @override
  SignupState get initialState => SignupInitial();

  @override
  Stream<SignupState> mapEventToState(
    SignupEvent event,
  ) async* {
 
    if (event is SignupEmailButtonPressed) {
      yield* _mapSignupEmailButtonPressedToState(event.email, event.password,event.username);
    }
  }

  Stream<SignupState> _mapSignupEmailButtonPressedToState(
      String email,String password,String username) async* {
    try {
      yield SignupLoading();
      bool signUpSuccess = await _userDomain.signupByEmail(email, username, password);
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

