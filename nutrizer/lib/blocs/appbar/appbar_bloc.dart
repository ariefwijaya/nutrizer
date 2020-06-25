import 'dart:async';

import 'package:bloc/bloc.dart';
import 'package:equatable/equatable.dart';
import 'package:meta/meta.dart';

part 'appbar_event.dart';
part 'appbar_state.dart';

class AppbarBloc extends Bloc<AppbarEvent, AppbarState> {
  @override
  AppbarState get initialState => AppbarState(elevation: 0);

  @override
  Stream<AppbarState> mapEventToState(
    AppbarEvent event,
  ) async* {
    if (event is AppbarStarted) {
      yield AppbarState(elevation: event.elevation);
    }

    if (event is AppbarChanged) {
      yield AppbarState(elevation: event.elevation);
    }
  }
}
