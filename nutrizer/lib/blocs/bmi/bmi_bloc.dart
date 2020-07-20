import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/models/user_model.dart';

part 'bmi_event.dart';
part 'bmi_state.dart';

class BmiBloc extends Bloc<BmiEvent, BmiState> {
  UserDomain _userDomain = UserDomain();

  @override
  BmiState get initialState => BmiInitial();

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
      UserModel userProfile = await _userDomain.getUserProfile();
      yield BmiSuccess(
        height: userProfile.height,
        weight: userProfile.weight,
        bmiValue: userProfile.bmi,
      );
    } catch (error) {
      print(error);
      yield BmiFailure(error: error.toString());
    }
  }
}
