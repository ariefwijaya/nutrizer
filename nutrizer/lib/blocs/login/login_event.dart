part of 'login_bloc.dart';

abstract class LoginEvent extends Equatable {
  const LoginEvent();
}

class LoginCheckedUsernameExist extends LoginEvent {
  final String username;

  const LoginCheckedUsernameExist({this.username});

  @override
  List<Object> get props => [username];
}

class LoginButtonPressed extends LoginEvent {
  final String username;
  final String password;

  const LoginButtonPressed({
    this.username,this.password
  });

  @override
  List<Object> get props => [username,password];
}


class LoginForgotPasswordRequested extends LoginEvent {
  final String username;

  const LoginForgotPasswordRequested({
    this.username
  });

  @override
  List<Object> get props => [username];
}