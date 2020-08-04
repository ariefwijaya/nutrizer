import 'package:nutrizer/models/nutrition_dict_model.dart';
import 'package:nutrizer/repositories/nutrition_repository.dart';

class NutritionDomain {
  final NutritionRepository _nutritionRepository = NutritionRepository();

  Future<List<NutritionDictModel>> getNutriDictList(int page) async {
    return await _nutritionRepository.getNutriDictList(page);
  }

  Future<List<NutriCatModel>> getNutriFoodCategory(String id,int page) async {
    return await _nutritionRepository.getNutriFoodCatByNutrition(id,page);
  }
}
