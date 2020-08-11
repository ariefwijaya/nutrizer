import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/banner_domain.dart';
import 'package:nutrizer/models/banner_model.dart';

part 'banner_ads_event.dart';
part 'banner_ads_state.dart';

class BannerAdsBloc extends Bloc<BannerAdsEvent, BannerAdsState> {
  final BannerDomain bannerDomain = BannerDomain();

  BannerAdsBloc({BannerAdsState bannerAdsState}):super(bannerAdsState??BannerAdsInitial());

  @override
  Stream<BannerAdsState> mapEventToState(
    BannerAdsEvent event,
  ) async* {
    if (event is BannerAdsHomeFetch) {
      yield* _mapBannerAdsHomeFetchToState();
    }
  }

  Stream<BannerAdsState> _mapBannerAdsHomeFetchToState() async* {
    try {
      yield BannerAdsFetchLoading();
      final resBanner = await bannerDomain.getBannerAdsHome();
      yield BannerAdsFetchSuccess(bannerAdsModel: resBanner);
    } catch (error) {
      yield BannerAdsFetchFailure(error: error.toString());
    }
  }
}
