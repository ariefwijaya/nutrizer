part of 'authentication_bloc.dart';

abstract class AuthenticationState extends Equatable {
  @override
  List<Object> get props => [];
}

class AuthenticationInitialState extends AuthenticationState {}

class AuthenticationUnauthenticatedState extends AuthenticationState {}

class AuthenticationAuthenticatedState extends AuthenticationState {
  final UserModel user;

  AuthenticationAuthenticatedState({@required this.user});

  @override
  List<Object> get props => [user];
}

class AuthenticationAuthenticatedNotCompletedState extends AuthenticationState {
  final UserModel user;

  AuthenticationAuthenticatedNotCompletedState({@required this.user});

  @override
  List<Object> get props => [user];
}

class AuthenticationFailureState extends AuthenticationState {
  final String message;

  AuthenticationFailureState({@required this.message});

  @override
  List<Object> get props => [message];

  @override
  String toString() => 'AuthenticationFailure { error: $message }';
}
