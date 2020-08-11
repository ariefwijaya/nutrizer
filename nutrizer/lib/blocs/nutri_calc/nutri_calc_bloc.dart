import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/nutrition_domain.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/models/nutrition_calc_model.dart';

part 'nutri_calc_event.dart';
part 'nutri_calc_state.dart';

///Nutrition Calculator Bloc
class NutriCalcBloc extends Bloc<NutriCalcEvent, NutriCalcState> {
  NutriCalcBloc() : super(NutriCalcInitial());

  final NutritionDomain _nutritionDomain = NutritionDomain();

  @override
  Stream<NutriCalcState> mapEventToState(
    NutriCalcEvent event,
  ) async* {
    if (event is NutriCalcStarted) {
      yield* _mapNutriCalcStartedToState(event);
    }

    if (event is NutriCalcRefresh) {
      yield NutriCalcRefreshSuccess();
    }

    if(event is NutriCalcCalculate){
       yield* _mapNutriCalcCalculateToState(event);

    }
  }

  Stream<NutriCalcState> _mapNutriCalcStartedToState(
      NutriCalcStarted event) async* {
    try {
      yield NutriCalcLoading();
      if (await ConnectionHelper.isOnline()) {
        final nutriFactor = await _nutritionDomain.getNutriCalcInitialData();
        yield NutriCalcSuccess(data: nutriFactor);
      } else {
        yield NutriCalcSuccess(data: _getNutriFactorList());
      }
    } catch (error) {
      print(error);
      yield NutriCalcFailure(
          error: "Terjadi kesalahan ketika insialisasi data");
    }
  }

  Stream<NutriCalcState> _mapNutriCalcCalculateToState(
      NutriCalcCalculate event) async* {
    try {
      yield NutriCalculatedLoading();
      if (await ConnectionHelper.isOnline()) {
        final result = await _nutritionDomain.getNutriCalculatedResult(event.formData);
        yield NutriCalculatedSuccess(data:result );
      } else {

        final result = NutriCalcResultModel();
        yield NutriCalculatedSuccess(data:result);
      }
    } catch (error) {
      yield NutriCalculatedFailure(
          error: "Terjadi kesalahan ketika melakukan perhitungan");
    }
  }

  NutriCalcInitModel _getNutriFactorList() {
    List<NutriFactorModel> activityFactor = [
      NutriFactorModel(id: "1", title: "Sangat Ringan/Bedrest", factor: 1.30,gender: "M",healthy: true),
      NutriFactorModel(id: "2", title: "Ringan", factor: 1.65,gender: "M",healthy: true),
      NutriFactorModel(id: "3", title: "Sedang", factor: 1.76,gender: "M",healthy: true),
      NutriFactorModel(id: "4", title: "Berat", factor: 2.10,gender: "M",healthy: true),
      NutriFactorModel(id: "5", title: "Sangat Ringan/Bedrest", factor: 1.30,gender: "F",healthy: true),
      NutriFactorModel(id: "6", title: "Ringan", factor: 1.55,gender: "F",healthy: true),
      NutriFactorModel(id: "7", title: "Sedang", factor: 1.70,gender: "F",healthy: true),
      NutriFactorModel(id: "8", title: "Berat", factor: 2.00,gender: "F",healthy: true),
       NutriFactorModel(id: "9", title: "Istirahat di Bed", factor: 1.2,healthy: false),
      NutriFactorModel(id: "10", title: "Tidak terikat di bed", factor: 1.3,healthy: false)
    ];

    List<NutriFactorModel> stressFactor = [
      NutriFactorModel(id: "1", title: "Tidak ada stress, gizi baik", factor: 1.3),
      NutriFactorModel(id: "2", title: "Stress ringan : radang salcerna, kanker, bedah elektif", factor: 1.4),
      NutriFactorModel(id: "3", title: "Stress sedang : sepsis, bedahtulang, luka bakar", factor: 1.5),
      NutriFactorModel(id: "4", title: "Stress berat : trauma multipel, bedah multisistem", factor: 1.6),
      NutriFactorModel(id: "5", title: "Stress sangat berat : CKB, luka bakar dan sepsis", factor: 1.7),
       NutriFactorModel(id: "6", title: "Luka bakar sangat berat", factor: 2.1),
    ];

    return NutriCalcInitModel(activityFactor: activityFactor,stressFactor: stressFactor);
  }
}
