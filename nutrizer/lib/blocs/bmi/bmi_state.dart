part of 'bmi_bloc.dart';

abstract class BmiState extends Equatable {
  const BmiState();
  @override
  List<Object> get props => [];
}

class BmiInitial extends BmiState {}

class BmiLoading extends BmiState {}

class BmiSuccess extends BmiState {
  final BmiModel bmiModel;

  const BmiSuccess(
      {this.bmiModel});

  @override
  List<Object> get props => [bmiModel];
}

class BmiFailure extends BmiState {
  final String error;

  const BmiFailure({this.error});

  @override
  List<Object> get props => [error];
}
