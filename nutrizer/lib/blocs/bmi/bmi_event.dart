part of 'bmi_bloc.dart';

abstract class BmiEvent extends Equatable {
  const BmiEvent();
  @override
  List<Object> get props => [];
}

class BmiStarted extends BmiEvent {}

class BMICalculate extends BmiEvent {
  final double weight,height;
  BMICalculate({this.weight,this.height});
  @override
  List<Object> get props => [weight,height];
}