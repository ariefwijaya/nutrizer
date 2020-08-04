import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:nutrizer/domain/kek_domain.dart';
import 'package:nutrizer/models/kek_model.dart';

part 'kek_event.dart';
part 'kek_state.dart';

class KekBloc extends Bloc<KekEvent, KekState> {
  KekDomain _kekDomain = KekDomain();

  KekBloc({KekState kekState}) : super(kekState);

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
      final resKek = await _kekDomain.getKekList(event.offset);
      yield KekSuccess(kekModel: resKek);
    } catch (error) {
      yield KekFailure(error: error.toString());
    }
  }

  Stream<KekState> _mapKekFetchDetailToState(KekFetchDetail event) async* {
    try {
      yield KekDetailLoading();
      final resKek = await _kekDomain.getKekDetail(event.id);
      yield KekDetailSuccess(kekModel: resKek);
    } catch (error) {
      yield KekDetailFailure(error: error.toString());
    }
  }
}
