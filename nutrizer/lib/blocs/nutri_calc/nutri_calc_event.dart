part of 'nutri_calc_bloc.dart';

abstract class NutriCalcEvent extends Equatable {
  const NutriCalcEvent();
   @override
  List<Object> get props => [];
}

class NutriCalcStarted extends NutriCalcEvent {

}

class NutriCalcRefresh extends NutriCalcEvent{}

class NutriCalcCalculate extends NutriCalcEvent {
  final NutriCalcFormModel formData;
  const NutriCalcCalculate({this.formData});
   @override
  List<Object> get props => [formData];
}