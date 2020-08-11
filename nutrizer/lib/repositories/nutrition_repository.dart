import 'package:nutrizer/helper/network_helper.dart';
import 'package:nutrizer/models/api_model.dart';
import 'package:nutrizer/models/nutrition_calc_model.dart';
import 'package:nutrizer/models/nutrition_dict_model.dart';
import 'package:nutrizer/models/user_model.dart';

class NutritionRepository {
  final NetworkHelper _networkHelper = NetworkHelper();
  Future<List<NutritionDictModel>> getNutriDictList(int page) async {
    Map<String, dynamic> result = await _networkHelper
        .get("nutritionDict", body: {"page": page.toString()});
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return apiModel.data.map<NutritionDictModel>((data) {
        return NutritionDictModel.fromJson(data);
      }).toList();
    else
      throw (apiModel.message);
  }

  Future<List<NutriCatModel>> getNutriFoodCatByNutrition(
      String id, int page) async {
    Map<String, dynamic> result = await _networkHelper
        .get("nutritionFoodCat", body: {"id": id, "page": page.toString()});
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return apiModel.data.map<NutriCatModel>((data) {
        return NutriCatModel.fromJson(data);
      }).toList();
    else
      throw (apiModel.message);
  }

  Future<BmiModel> getCalculatedBMI(double weight, double height) async {
    Map<String, dynamic> result = await _networkHelper.post("calculateBMI",
        body: {"height": height.toString(), "weight": weight.toString()});
    ApiModel apiModel = ApiModel.fromJson(result);

    if (apiModel.success)
      return BmiModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<NutriCalcInitModel> getNutriCalcInitialData() async {
    Map<String, dynamic> result = await _networkHelper.get("nutritionCalcData");
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return NutriCalcInitModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<NutriCalcResultModel> getNutriCalculatedResult(
      NutriCalcFormModel formData) async {
    Map<String, dynamic> result = await _networkHelper
        .post("nutritionCalculated", body: formData.toJsonString());
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return NutriCalcResultModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<NutriCalcResultModel> getUserNutriCalculatedResult() async {
    Map<String, dynamic> result = await _networkHelper.get("user/nutrition");
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return NutriCalcResultModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }
}
