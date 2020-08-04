import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:nutrizer/blocs/authentication/authentication_bloc.dart';
import 'package:nutrizer/blocs/bmi/bmi_bloc.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/helper/height_slider_helper.dart';
import 'package:nutrizer/helper/weight_slider_helper.dart';
import 'package:nutrizer/widgets/appbar_widget.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/blocs/appbar/appbar_bloc.dart';
import 'package:nutrizer/blocs/profile/profile_bloc.dart';
import 'package:nutrizer/widgets/common_widget.dart';

class BMIUpdateScreen extends StatefulWidget {
  @override
  _BMIUpdateScreenState createState() => _BMIUpdateScreenState();
}

class _BMIUpdateScreenState extends State<BMIUpdateScreen> {
  final AppbarBloc _appbarBloc = AppbarBloc();

  ScrollController _controller = new ScrollController();
  final double targetElevation = 3;
  double _elevation = 0;
  int _currentHeight = 0;
  int _currentWeight = 0;
  bool _enableSubmit = false;
  int height = 170;
  int weight = 60;

  _BMIUpdateScreenState() {
    _controller = ScrollController();
    _controller.addListener(_scrollListener);
  }

  @override
  void initState() {
    super.initState();
  }

  @override
  void dispose() {
    _controller?.removeListener(_scrollListener);
    _controller?.dispose();
    _appbarBloc.close();
    super.dispose();
  }

  void _scrollListener() {
    double newElevation = _controller.offset > 1 ? targetElevation : 0;
    if (_elevation != newElevation) {
      _elevation = newElevation;
      _appbarBloc.add(AppbarChanged(elevation: _elevation));
    }
  }

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<AppbarBloc>(
          create: (context) => _appbarBloc,
        ),
        BlocProvider<ProfileBloc>(
          create: (context) => ProfileBloc(),
        ),
        BlocProvider<BmiBloc>(
          create: (context) => BmiBloc()..add(BmiStarted()),
        ),
      ],
      child: Scaffold(
        backgroundColor: ColorPrimaryHelper.secondary,
        appBar: PreferredSize(
          preferredSize: Size.fromHeight(kToolbarHeight),
          child: BlocBuilder<AppbarBloc, AppbarState>(
              bloc: _appbarBloc,
              builder: (context, AppbarState state) => AppbarWidget(
                    title: "Tentang Kamu",
                    elevation: state.elevation,
                    centerTitle: true,
                  )),
        ),
        body: BlocConsumer<BmiBloc, BmiState>(
          listener: (context, state) {},
          builder: (bmiContext, bmiState) {
            if (bmiState is BmiFailure) {
              return PlaceholderWidget(
                imagePath: AssetsHelper.error,
                title: "Failed to get Data",
                subtitle: "Something error happened. Please try again.",
                onPressed: () {
                  BlocProvider.of<BmiBloc>(context).add(BmiStarted());
                },
              );
            }
            if (bmiState is BmiSuccess) {
              _currentHeight = bmiState.height.toInt();
              _currentWeight = bmiState.weight.toInt();

              height = _currentHeight;
              weight = _currentWeight;

              return BlocListener<ProfileBloc, ProfileState>(
                listener: (context, state) {
                  if (state is ProfileLoading) {
                    // DialogHelper.showLoadingDialog(context); //invoking login
                  } else if (state is ProfileSuccess) {
                    Navigator.pop(context);
                    BlocProvider.of<AuthenticationBloc>(context)
                        .add(AuthenticationLoggedInEvent());
                  } else if (state is ProfileFailure) {
                    DialogHelper.showSnackBar(context, state.error,
                        type: SnackBarType.Error);
                  }
                },
                child: SingleChildScrollView(
                  controller: _controller,
                  child: Column(
                    children: <Widget>[
                      SectionTitleBMIWidget(
                        leftTitleText: "Tinggi",
                        leftTitleonPressed: () {},
                        rightTitleText: "cm",
                      ),
                      Container(
                        height: 400,
                        margin: EdgeInsets.only(top: 10, right: 20, left: 20),
                        child: BlocBuilder<ProfileBloc, ProfileState>(
                          buildWhen: (previous, current) =>
                              current is ProfileBMIHeightChanged ? true : false,
                          builder: (context, ProfileState state) =>
                              HeightSliderHelper(
                                  height: state is ProfileBMIHeightChanged
                                      ? state.height.toInt()
                                      : height,
                                  minHeight: 100,
                                  maxHeight: 200,
                                  stepHeight: 10,
                                  currentHeightTextColor:
                                      Theme.of(context).primaryColor,
                                  personImagePath: AssetsHelper.assetPathPerson,
                                  onChange: (val) {
                                    height = val;
                                    BlocProvider.of<ProfileBloc>(context).add(
                                        ProfileChangedBMIHeight(
                                            height: val.toDouble()));
                                  }),
                        ),
                      ),
                      SectionTitleBMIWidget(
                        leftTitleText: "Berat",
                        leftTitleonPressed: () {},
                        rightTitleText: "kg",
                      ),
                      Container(
                        margin:
                            EdgeInsets.symmetric(horizontal: 80, vertical: 10),
                        child: BlocBuilder<ProfileBloc, ProfileState>(
                          buildWhen: (previous, current) =>
                              current is ProfileBMIWeightChanged ? true : false,
                          builder: (context, ProfileState state) =>
                              WeightSliderHelper(
                                  weight: state is ProfileBMIWeightChanged
                                      ? state.weight.toInt()
                                      : weight,
                                  minWeight: 40,
                                  maxWeight: 120,
                                  onChange: (val) {
                                    weight = val;
                                    BlocProvider.of<ProfileBloc>(context).add(
                                        ProfileChangedBMIWeight(
                                            weight: val.toDouble()));
                                  }),
                        ),
                      ),
                      BlocBuilder<ProfileBloc, ProfileState>(
                        builder: (context, state) {
                          if (state is ProfileLoading) {
                            return ButtonLoadingWidget();
                          }

                          return ButtonPrimaryWidget(
                            "Simpan",
                            onPressed: _isSubmitEnable()
                                ? (() {
                                    BlocProvider.of<ProfileBloc>(context).add(
                                        ProfileUpdateBMI(
                                            weight: weight.toDouble(),
                                            height: height.toDouble()));
                                  })
                                : null,
                          );
                        },
                      ),
                      SizedBox(
                        height: 20,
                      )
                    ],
                  ),
                ),
              );
            }

            return Center(
              child: CircularProgressIndicator(),
            );
          },
        ),
      ),
    );
  }

  bool _isSubmitEnable() {
    return !(weight == _currentWeight && height == _currentHeight);
  }
}

class SectionTitleBMIWidget extends StatelessWidget {
  final String leftTitleText;
  final Function leftTitleonPressed;
  final String rightTitleText;

  SectionTitleBMIWidget(
      {this.leftTitleText, this.leftTitleonPressed, this.rightTitleText, key})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: 30),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: <Widget>[
          Container(
            margin: EdgeInsets.symmetric(vertical: 5),
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(7),
              color: ColorPrimaryHelper.secondary,
              boxShadow: [
                BoxShadow(
                    color: ColorPrimaryHelper.lightShadow,
                    blurRadius: 3,
                    offset: Offset(0, 0)),
              ],
            ),
            child: Material(
              color: Colors.transparent,
              child: InkWell(
                borderRadius: BorderRadius.circular(7),
                onTap: leftTitleonPressed,
                child: Container(
                  padding: EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                  child: Text(
                    leftTitleText,
                    style: FontStyleHelper.formHeaderSubTitle
                        .copyWith(color: Theme.of(context).accentColor),
                  ),
                ),
              ),
            ),
          ),
          Text(
            rightTitleText,
            style: FontStyleHelper.sectionTitleBMI
                .copyWith(color: Theme.of(context).primaryColor),
          )
        ],
      ),
    );
  }
}
