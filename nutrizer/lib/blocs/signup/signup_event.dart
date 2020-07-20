part of 'signup_bloc.dart';

abstract class SignupEvent extends Equatable {
  const SignupEvent();
}

class SignupEmailButtonPressed extends SignupEvent {
  final String email;
  final String username;
  final String password;
  final String birthday;
  final String nickname;

  const SignupEmailButtonPressed(
      {this.email, this.username, this.password, this.birthday, this.nickname});

  @override
  List<Object> get props => [email, username, password, birthday, nickname];
}
