part of 'bmi_bloc.dart';

abstract class BmiState extends Equatable {
  const BmiState();
  @override
  List<Object> get props => [];
}

class BmiInitial extends BmiState {}

class BmiLoading extends BmiState {}

class BmiSuccess extends BmiState {
  final double height;
  final double weight;
  final double bmiValue;
  final String bmiScoreText;

  const BmiSuccess(
      {this.height, this.weight, this.bmiScoreText, this.bmiValue});

  @override
  List<Object> get props => [height, weight, bmiScoreText, bmiValue];
}

class BmiFailure extends BmiState {
  final String error;

  const BmiFailure({this.error});

  @override
  List<Object> get props => [error];
}
