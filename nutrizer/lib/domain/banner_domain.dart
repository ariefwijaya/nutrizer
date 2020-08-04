import 'package:nutrizer/models/banner_model.dart';
import 'package:nutrizer/repositories/banner_repository.dart';

class BannerDomain {
  final BannerRepository _bannerRepository = BannerRepository();

  Future<BannerAdsModel> getBannerAdsHome() async {
    return await _bannerRepository.getBannerAdsHome();
  }
}
