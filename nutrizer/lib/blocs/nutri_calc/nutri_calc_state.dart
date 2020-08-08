part of 'nutri_calc_bloc.dart';

abstract class NutriCalcState extends Equatable {
  const NutriCalcState();
  @override
  List<Object> get props => [];
}

class NutriCalcInitial extends NutriCalcState {}

class NutriCalcLoading extends NutriCalcState {}

class NutriCalcSuccess extends NutriCalcState {
  final NutriCalcInitModel data;
  const NutriCalcSuccess({this.data});

  @override
  List<Object> get props => [data];
}

class NutriCalcRefreshSuccess extends NutriCalcState{
  
}

class NutriCalcFailure extends NutriCalcState {
  final String error;

  const NutriCalcFailure({this.error});

  @override
  List<Object> get props => [error];
}

class NutriCalculatedLoading extends NutriCalcState {}

class NutriCalculatedSuccess extends NutriCalcState {
  final NutriCalcResultModel data;
  const NutriCalculatedSuccess({this.data});

  @override
  List<Object> get props => [data];
}

class NutriCalculatedFailure extends NutriCalcState {
  final String error;

  const NutriCalculatedFailure({this.error});

  @override
  List<Object> get props => [error];
}
