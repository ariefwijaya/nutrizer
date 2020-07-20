part of 'bmi_bloc.dart';

abstract class BmiEvent extends Equatable {
  const BmiEvent();
  @override
  List<Object> get props => [];
}

class BmiStarted extends BmiEvent {}
