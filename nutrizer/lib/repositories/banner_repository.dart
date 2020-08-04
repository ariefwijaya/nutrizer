import 'package:nutrizer/helper/network_helper.dart';
import 'package:nutrizer/models/api_model.dart';
import 'package:nutrizer/models/banner_model.dart';

class BannerRepository {
  final NetworkHelper _networkHelper = NetworkHelper();
   Future<BannerAdsModel> getBannerAdsHome() async {
    Map<String, dynamic> result = await _networkHelper.get(
      "banner/home",
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return BannerAdsModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }
}