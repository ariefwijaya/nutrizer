part of 'banner_ads_bloc.dart';

abstract class BannerAdsEvent extends Equatable {
  const BannerAdsEvent();
  @override
  List<Object> get props => [];
}

class BannerAdsHomeFetch extends BannerAdsEvent {

}