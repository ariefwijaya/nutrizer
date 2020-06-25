import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hive/hive.dart';
import 'package:hive_flutter/hive_flutter.dart';
import 'package:nutrizer/blocs/authentication/authentication_bloc.dart';
import 'package:nutrizer/blocs/theme/theme_bloc.dart';
import 'package:nutrizer/routes/routes.dart';
import 'package:nutrizer/screens/bottom_bar_layout_screen.dart';
import 'package:nutrizer/screens/onboarding_screen.dart';
import 'package:nutrizer/screens/splash_screen.dart';
import 'package:nutrizer/screens/bmi_user_screen.dart';

//For debugging purpose only
class SimpleBlocDelegate extends BlocDelegate {
  @override
  void onEvent(Bloc bloc, Object event) {
    super.onEvent(bloc, event);
    // print(event);
  }

  @override
  void onTransition(Bloc bloc, Transition transition) {
    super.onTransition(bloc, transition);
    print(transition);
  }

  @override
  void onError(Bloc bloc, Object error, StackTrace stacktrace) {
    super.onError(bloc, error, stacktrace);
    print(error);
  }
}

void main() async {
  await Hive.initFlutter();
  //For debugging purpose only
  BlocSupervisor.delegate = SimpleBlocDelegate();
  runApp(MyApp());
}

class MyApp extends StatefulWidget {
  // This widget is the root of your application.
  @override
  _MyAppState createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  @override
  void dispose() {
    Hive.close();
    super.dispose();
  }

  @override
  void initState() {
    // Hive.registerAdapter(UserDaoModelAdapter());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<ThemeBloc>(
          create: (BuildContext context) => ThemeBloc(),
        ),
        BlocProvider<AuthenticationBloc>(
          create: (BuildContext context) =>
              AuthenticationBloc()..add(AuthenticationStartedEvent()),
        ),
      ],
      child: BlocBuilder<ThemeBloc, ThemeState>(
        builder: _buildWithTheme,
      ),
    );
  }

  Widget _buildWithTheme(BuildContext context, ThemeState state) {
    return MaterialApp(
      title: 'Nutrizer',
      home: BlocBuilder<AuthenticationBloc, AuthenticationState>(
          builder: (BuildContext context, AuthenticationState state) {
        if (state is AuthenticationInitialState) {
          return SplashScreen();
        }
        if (state is AuthenticationAuthenticatedState) {
          return BottomBarLayoutScreen();
        }
        if (state is AuthenticationAuthenticatedNotCompletedState) {
          return BMIUserScreen();
        }
        return OnBoardingScreen();
      }),
      themeMode: state.themeMode,
      theme: ThemeData(
          scaffoldBackgroundColor: Color(0xfffafafa),
          backgroundColor: Color(0xfffafafa),
          dialogBackgroundColor: Color(0xffffffff),
          accentColor: Color(0xff292d27),
          primaryColor: Color(0xff97c14b),
          primarySwatch: MaterialColor(0xff97c14b, {
            50: Color.fromRGBO(151, 193, 75, .1),
            100: Color.fromRGBO(151, 193, 75, .2),
            200: Color.fromRGBO(151, 193, 75, .3),
            300: Color.fromRGBO(151, 193, 75, .4),
            400: Color.fromRGBO(151, 193, 75, .5),
            500: Color.fromRGBO(151, 193, 75, .6),
            600: Color.fromRGBO(151, 193, 75, .7),
            700: Color.fromRGBO(151, 193, 75, .8),
            800: Color.fromRGBO(151, 193, 75, .9),
            900: Color.fromRGBO(151, 193, 75, 1),
          }),
          dividerColor: Color(0xfff0f0f0),
          fontFamily: "Quicksand",
          cardColor: Color(0xffffffff),
          errorColor: Color(0xffc15e4b),
          dividerTheme: DividerThemeData(
            color: Color(0xfff0f0f0),
          ),
          buttonTheme: ButtonThemeData(
              buttonColor: Color(0xff292d27),
              textTheme: ButtonTextTheme.primary,
              colorScheme: Theme.of(context).colorScheme.copyWith(
                    primary: Color(0xff292d27),
                  )),
          dialogTheme: DialogTheme(
              titleTextStyle: TextStyle(
            fontSize: 16,
          )),
          canvasColor: Color(0xffffffff),
          secondaryHeaderColor: Color(0xfff0f0f0),
          textTheme: TextTheme(
            caption: TextStyle(
              fontSize: 16,
            ),
            button: TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
            ),
            bodyText1: TextStyle(
              fontSize: 12,
            ),
            bodyText2: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 16,
                color: Color(0xffffffff)),
            headline2:
                TextStyle(fontFamily: "LilitaOne", fontSize: 24), //brand name
            headline1: TextStyle(
                fontSize: 22,
                fontWeight: FontWeight.bold,
                color: Color(0xff525a4e)),
          ),
          appBarTheme: AppBarTheme(
              color: Color(0xffffffff),
              textTheme: TextTheme(
                  headline6: TextStyle(
                      fontWeight: FontWeight.w800,
                      color: Color(0xff292d27),
                      fontSize: 20)))),
      onGenerateRoute: Routes.generateRoute,
    );
  }
}
