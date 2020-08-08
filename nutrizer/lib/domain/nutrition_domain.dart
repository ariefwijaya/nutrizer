import 'package:nutrizer/models/nutrition_calc_model.dart';
import 'package:nutrizer/models/nutrition_dict_model.dart';
import 'package:nutrizer/models/user_model.dart';
import 'package:nutrizer/repositories/nutrition_repository.dart';

class NutritionDomain {
  final NutritionRepository _nutritionRepository = NutritionRepository();

  Future<List<NutritionDictModel>> getNutriDictList(int page) async {
    return await _nutritionRepository.getNutriDictList(page);
  }

  Future<List<NutriCatModel>> getNutriFoodCategory(String id, int page) async {
    return await _nutritionRepository.getNutriFoodCatByNutrition(id, page);
  }

  Future<BmiModel> getCalculatedBMI(double weight, double height) async {
    return await _nutritionRepository.getCalculatedBMI(weight, height);
  }

  Future<NutriCalcInitModel> getNutriCalcInitialData() async {
    return await _nutritionRepository.getNutriCalcInitialData();
  }

  Future<NutriCalcResultModel> getNutriCalculatedResult(NutriCalcFormModel formData) async {
    return await _nutritionRepository.getNutriCalculatedResult(formData);
  }

  Future<NutriCalcResultModel> getUserNutriCalculatedResult(NutriCalcFormModel formData) async {
    return await _nutritionRepository.getNutriCalculatedResult(formData);
  }
  
}
