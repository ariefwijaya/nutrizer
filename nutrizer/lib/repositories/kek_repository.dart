import 'package:nutrizer/helper/network_helper.dart';
import 'package:nutrizer/models/api_model.dart';
import 'package:nutrizer/models/kek_model.dart';

class KekRepository {
  final NetworkHelper _networkHelper = NetworkHelper();
  Future<List<KEKModel>> getKekList(int page) async {
    Map<String, dynamic> result =
        await _networkHelper.get("kek", body: {"page": page.toString()});
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return apiModel.data.map<KEKModel>((data) {
        return KEKModel.fromJson(data);
      }).toList();
    else
      throw (apiModel.message);
  }

   Future<KEKModel> getKekDetail(String id) async {
    Map<String, dynamic> result =
        await _networkHelper.get("kekDetail", body: {"id": id});
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return KEKModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  
}
