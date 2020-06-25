part of 'theme_bloc.dart';

class ThemeState extends Equatable {
  final ThemeMode themeMode;

  ThemeState({
    @required this.themeMode,
  });

  @override
  List<Object> get props => [themeMode];
}
