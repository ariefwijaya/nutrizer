part of 'appbar_bloc.dart';

abstract class AppbarEvent extends Equatable {
  const AppbarEvent();
  @override
  List<Object> get props => [];
}

class AppbarStarted extends AppbarEvent {
  final double elevation;

  AppbarStarted({
    @required this.elevation,
  });

  @override
  List<Object> get props => [elevation];
}

class AppbarChanged extends AppbarEvent {
  final double elevation;

  AppbarChanged({
    @required this.elevation,
  });

  @override
  List<Object> get props => [elevation];
}
