part of 'banner_ads_bloc.dart';

abstract class BannerAdsState extends Equatable {
  const BannerAdsState();

  @override
  List<Object> get props => [];
}

class BannerAdsInitial extends BannerAdsState {}

class BannerAdsFetchLoading extends BannerAdsState {}

class BannerAdsFetchSuccess extends BannerAdsState {
  final BannerAdsModel bannerAdsModel;

  const BannerAdsFetchSuccess(
      {this.bannerAdsModel});

  @override
  List<Object> get props => [bannerAdsModel];
}

class BannerAdsFetchFailure extends BannerAdsState {
  final String error;

  const BannerAdsFetchFailure({this.error});

  @override
  List<Object> get props => [error];
}