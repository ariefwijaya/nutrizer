import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/kek_domain.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/models/kek_model.dart';

part 'kek_event.dart';
part 'kek_state.dart';

class KekBloc extends Bloc<KekEvent, KekState> {
  KekDomain _kekDomain = KekDomain();

  KekBloc({KekState kekState}) : super(kekState ?? KekInitial());

  @override
  Stream<KekState> mapEventToState(
    KekEvent event,
  ) async* {
    if (event is KekFetchList) {
      yield* _mapKekFetchListToState(event);
    }

    if (event is KekFetchDetail) {
      yield* _mapKekFetchDetailToState(event);
    }
  }

  Stream<KekState> _mapKekFetchListToState(KekFetchList event) async* {
    try {
      yield KekLoading();
      if (await ConnectionHelper.isOnline()) {
        final resKek = await _kekDomain.getKekList(event.offset);
        yield KekSuccess(kekModel: resKek);
      } else {
        yield KekOffline();
      }
    } catch (error) {
      yield KekFailure(error: error.toString());
    }
  }

  Stream<KekState> _mapKekFetchDetailToState(KekFetchDetail event) async* {
    try {
      yield KekDetailLoading();
      if (await ConnectionHelper.isOnline()) {
        final resKek = await _kekDomain.getKekDetail(event.id);
        yield KekDetailSuccess(kekModel: resKek);
      } else {
        yield KekDetailOffline();
      }
    } catch (error) {
      yield KekDetailFailure(error: error.toString());
    }
  }
}
