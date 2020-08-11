import 'dart:async';
import 'dart:math';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/nutrition_domain.dart';
import 'package:nutrizer/domain/user_domain.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/models/user_model.dart';

part 'bmi_event.dart';
part 'bmi_state.dart';

class BmiBloc extends Bloc<BmiEvent, BmiState> {
  UserDomain _userDomain = UserDomain();
  NutritionDomain _nutritionDomain = NutritionDomain();

  BmiBloc({BmiState bmiState}) : super(bmiState ?? BmiInitial());

  @override
  Stream<BmiState> mapEventToState(
    BmiEvent event,
  ) async* {
    if (event is BmiStarted) {
      yield* _mapBmiStartedToState();
    }

    if (event is BMICalculate) {
      yield* _mapBMICalculateToState(event);
    }
  }

  Stream<BmiState> _mapBmiStartedToState() async* {
    try {
      yield BmiLoading();
      BmiModel bmi = await _userDomain.getBMI();
      yield BmiSuccess(
        bmiModel: bmi,
      );
    } catch (error) {
      yield BmiFailure(error: error.toString());
    }
  }

  Stream<BmiState> _mapBMICalculateToState(BMICalculate ev) async* {
    try {
      yield BmiLoading();
      if (ev.height == null || ev.weight == null) throw "Failed to Calculate";

      if (await ConnectionHelper.isOnline()) {
        BmiModel bmi = await _nutritionDomain.getCalculatedBMI(ev.weight, ev.height);
        yield BmiSuccess(bmiModel: bmi);
      } else {
        double bmiValue = getBmiValue(ev.weight, ev.height);
        String bmiScore = getBmiScore(bmiValue);
        int bmiRank = getBmiScoreVal(bmiValue);
        BmiModel bmi = BmiModel(
            height: ev.height,
            weight: ev.weight,
            bmi: bmiValue,
            bmiRank: bmiRank,
            bmiText: bmiScore);

        yield BmiSuccess(bmiModel: bmi);
      }
    } catch (error) {
      yield BmiFailure(error: error.toString());
    }
  }

  double getBmiValue(double weight, double height) {
    if (weight == null || height == null) return null;
    return weight / pow(height / 100, 2);
  }

  String getBmiScore(double bmi) {
    if (bmi < 18.5) {
      return "Berat badan kurang";
    } else if (bmi >= 18.5 && bmi <= 24.9) {
      return "Berat badan ideal";
    } else if (bmi >= 25.0 && bmi <= 29.9) {
      return "Berat badan lebih";
    } else if (bmi >= 30.0 && bmi <= 39.9) {
      return "Gemuk";
    } else if (bmi > 40.0) {
      return "Obesitas";
    } else {
      return "Unknown";
    }
  }

  int getBmiScoreVal(double bmi) {
    if (bmi < 18.5) {
      return 0;
    } else if (bmi >= 18.5 && bmi <= 24.9) {
      return 1;
    } else if (bmi >= 25.0 && bmi <= 29.9) {
      return 2;
    } else if (bmi >= 30.0 && bmi <= 39.9) {
      return 3;
    } else if (bmi > 40.0) {
      return 4;
    } else {
      return null;
    }
  }
}
