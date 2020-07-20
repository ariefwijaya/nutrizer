import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/bmi/bmi_bloc.dart';
import 'package:nutrizer/widgets/header_widget.dart';

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<BmiBloc>(
          create: (context) => BmiBloc()..add(BmiStarted()),
        )
      ],
      child: Scaffold(
          body: SingleChildScrollView(
        child: Container(
            child: Column(
          children: <Widget>[
            BlocBuilder<BmiBloc, BmiState>(
              builder: (context, state) {
                int weight = 0;
                int height = 0;
                double bmi = 0;
                String bmiText = "Unknown";

                if (state is BmiSuccess) {
                  weight = state.weight.toInt();
                  height = state.height.toInt();
                  bmi = state.bmiValue;
                }

                return HeaderHomeWidget(
                  padding: EdgeInsets.only(top: 30),
                  greetingText: "Halo, Yura",
                  sectionText: "Indeks Massa Tubuh",
                  height: weight,
                  weight: height,
                  bmiValue: bmi,
                  bmiScoreText: bmiText,
                );
              },
            ),
            Container(
              height: 100,
            )
          ],
        )),
      )),
    );
  }
}
