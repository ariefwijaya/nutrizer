part of 'theme_bloc.dart';

abstract class ThemeEvent extends Equatable {
  // Passing class fields in a list to the Equatable super class
  ThemeEvent();

  @override
  List<Object> get props => [];
}

class ThemeChanged extends ThemeEvent {
  final ThemeMode themeMode;

  ThemeChanged({
    @required this.themeMode,
  });

  @override
  List<Object> get props => [themeMode];
}
