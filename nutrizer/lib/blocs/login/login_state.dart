part of 'login_bloc.dart';

abstract class LoginState extends Equatable {
  const LoginState();
  @override
  List<Object> get props => [];
}

class LoginInitial extends LoginState {}

class LoginLoading extends LoginState {}

class LoginSuccess extends LoginState {}

class LoginFailure extends LoginState {
  final String error;

  const LoginFailure({this.error});

  @override
  List<Object> get props => [error];
}

class LoginUserExisted extends LoginState {}

class LoginUserNotExisted extends LoginState {}

class LoginForgotPasswordSuccess extends LoginState {
   final String message;

  const LoginForgotPasswordSuccess({this.message});

  @override
  List<Object> get props => [message];
}