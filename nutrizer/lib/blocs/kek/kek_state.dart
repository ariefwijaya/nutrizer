part of 'kek_bloc.dart';

abstract class KekState extends Equatable {
  const KekState();

  @override
  List<Object> get props => [];
}

class KekInitial extends KekState {}

class KekLoading extends KekState {}

class KekSuccess extends KekState {
  final List<KEKModel> kekModel;
  
  const KekSuccess(
      {this.kekModel});

  @override
  List<Object> get props => [kekModel];
}

class KekFailure extends KekState {
  final String error;

  const KekFailure({this.error});

  @override
  List<Object> get props => [error];
}

class KekOffline extends KekState {}


class KekDetailLoading extends KekState {}

class KekDetailSuccess extends KekState {
  final KEKModel kekModel;
  
  const KekDetailSuccess(
      {this.kekModel});

  @override
  List<Object> get props => [kekModel];
}

class KekDetailFailure extends KekState {
  final String error;

  const KekDetailFailure({this.error});

  @override
  List<Object> get props => [error];
}

class KekDetailOffline extends KekState {}