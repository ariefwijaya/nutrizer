part of 'signup_bloc.dart';

abstract class SignupEvent extends Equatable {
  const SignupEvent();
}

class SignupEmailButtonPressed extends SignupEvent {
  final String email;
  final String username;
  final String password;

  const SignupEmailButtonPressed({
    this.email,
    this.username,
    this.password,
  });

  @override
  List<Object> get props => [email, username, password];
}