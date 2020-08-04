import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/models/user_model.dart';

part 'bmi_event.dart';
part 'bmi_state.dart';

class BmiBloc extends Bloc<BmiEvent, BmiState> {
  UserDomain _userDomain = UserDomain();

  BmiBloc({BmiState bmiState}) :super(bmiState);

  @override
  Stream<BmiState> mapEventToState(
    BmiEvent event,
  ) async* {
    if (event is BmiStarted) {
      yield* _mapBmiStartedToState();
    }
  }

  Stream<BmiState> _mapBmiStartedToState() async* {
    try {
      yield BmiLoading();
      BmiModel bmi = await _userDomain.getBMI();
      yield BmiSuccess(
        height: bmi.height,
        weight: bmi.weight,
        bmiValue: bmi.bmi,
        bmiScoreText: bmi.bmiText
      );
    } catch (error) {
      yield BmiFailure(error: error.toString());
    }
  }
}
