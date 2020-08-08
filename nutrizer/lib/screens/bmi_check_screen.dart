import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:nutrizer/blocs/bmi/bmi_bloc.dart';
import 'package:nutrizer/blocs/profile/profile_bloc.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/helper/height_slider_helper.dart';
import 'package:nutrizer/helper/weight_slider_helper.dart';
import 'package:nutrizer/models/user_model.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:nutrizer/widgets/card_widget.dart';
import 'package:shimmer/shimmer.dart';

class BMICheckScreen extends StatefulWidget {
  final String screenTitle;

  BMICheckScreen({this.screenTitle});
  @override
  _BMICheckScreenState createState() => _BMICheckScreenState();
}

class _BMICheckScreenState extends State<BMICheckScreen> {
  
  String _heightText = "-";
  String _weightText = "-";
  String _bmiText = "-";
  String _bmiScoreText = "Hasil Kategori";
  int _bmiScoreVal;

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<BmiBloc>(
          create: (context) => BmiBloc(),
        ),
      ],
      child: Scaffold(
        appBar: AppBar(
            title: Text(widget.screenTitle,
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
        body: BlocConsumer<BmiBloc, BmiState>(
          listener: (context, state) {
            if (state is BmiFailure) {
              DialogHelper.showSnackBar(context, state.error,
                  type: SnackBarType.Error);
            }
          },
          builder: (context, state) {
            Color colorScore = ColorPrimaryHelper.accent;
            bool isLoading = false;
            if (state is BmiSuccess) {
              final bmiData = state.bmiModel;
              _heightText = bmiData.height.toString();
              _weightText = bmiData.weight.toString();
              _bmiText = bmiData.bmi.toStringAsFixed(2);
              _bmiScoreText = bmiData.bmiText;
              _bmiScoreVal = bmiData.bmiRank;

              if (_bmiScoreVal != null) {
                if (_bmiScoreVal < 1) {
                  colorScore = ColorPrimaryHelper.danger;
                } else if (_bmiScoreVal > 1) {
                  colorScore = ColorPrimaryHelper.warning;
                } else if (_bmiScoreVal == 1) {
                  colorScore = ColorPrimaryHelper.primary;
                } else {
                  colorScore = ColorPrimaryHelper.accent;
                }
              }
            }

            if (state is BmiFailure) {
              _bmiScoreVal = null;
              colorScore = ColorPrimaryHelper.accent;
              _bmiScoreText = "Perhitungan gagal";
            }

            if (state is BmiLoading) {
              isLoading = true;
            }

            return ListView(
              children: <Widget>[
                Container(
                  padding: EdgeInsets.symmetric(horizontal: 14, vertical: 14),
                  decoration:
                      BoxDecoration(color: ColorPrimaryHelper.textLight),
                  child: Text(
                      "Masukkan Tinggi dan berat badan untuk melakukan perhitungan Indeks Massa Tubuh (IMT)"),
                ),
                Container(
                  padding: EdgeInsets.all(10),
                  child: Card(
                    elevation: 7,
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(10)),
                    shadowColor: ColorPrimaryHelper.shadow.withOpacity(0.3),
                    child: InkWell(
                      borderRadius: BorderRadius.circular(10),
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Row(
                          children: <Widget>[
                            Expanded(
                              child: InfoTileBMICardWidget(
                                titleText: "Tinggi",
                                assetPathIcon: AssetsHelper.heightIcon,
                                iconColor: ColorPrimaryHelper.danger,
                                infoText: _heightText,
                                infoUomText: "cm",
                                isLoading: isLoading,
                              ),
                            ),
                            SizedBox(
                              width: 10,
                            ),
                            Expanded(
                              child: InfoTileBMICardWidget(
                                titleText: "Berat",
                                assetPathIcon: AssetsHelper.weightIcon,
                                iconColor: ColorPrimaryHelper.warning,
                                infoText: _weightText,
                                infoUomText: "kg",
                                isLoading: isLoading,
                              ),
                            )
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
                Card(
                  elevation: 1,
                  margin: EdgeInsets.zero,
                  child: Padding(
                    padding: const EdgeInsets.all(15.0),
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: <Widget>[
                        ConstrainedBox(
                          constraints:
                              BoxConstraints(minHeight: 80, minWidth: 90),
                          child: Card(
                            elevation: 2,
                            shape: RoundedRectangleBorder(
                                borderRadius: BorderRadius.circular(10)),
                            child: Padding(
                              padding: const EdgeInsets.all(8.0),
                              child: Column(
                                mainAxisSize: MainAxisSize.min,
                                children: <Widget>[
                                  !isLoading
                                      ? Text(
                                          _bmiText,
                                          style: FontStyleHelper.sectionTitleBMI
                                              .copyWith(
                                                  color: Theme.of(context)
                                                      .primaryColor,
                                                  fontSize: 20),
                                        )
                                      : Shimmer.fromColors(
                                          child: Container(
                                            margin: EdgeInsets.only(
                                                bottom: 8, top: 5),
                                            decoration: BoxDecoration(
                                              color: Colors.white,
                                            ),
                                            height: 20,
                                            width: 60,
                                          ),
                                          baseColor: Colors.grey[200],
                                          highlightColor: Colors.grey[100],
                                        ),
                                  !isLoading
                                      ? Text(
                                          "IMT",
                                          style: TextStyle(
                                              fontSize: 16,
                                              color:
                                                  ColorPrimaryHelper.formLabel),
                                        )
                                      : Shimmer.fromColors(
                                          child: Container(
                                            margin: EdgeInsets.only(bottom: 8),
                                            decoration: BoxDecoration(
                                              color: Colors.white,
                                            ),
                                            height: 20,
                                            width: 60,
                                          ),
                                          baseColor: Colors.grey[200],
                                          highlightColor: Colors.grey[100],
                                        ),
                                ],
                              ),
                            ),
                          ),
                        ),
                        SizedBox(width: 20),
                        Expanded(
                          child: !isLoading
                              ? Text(
                                  _bmiScoreText,
                                  style: FontStyleHelper.sectionDividerTitle
                                      .copyWith(
                                    color: colorScore,
                                    fontSize: 18,
                                  ),
                                )
                              : Shimmer.fromColors(
                                  child: Container(
                                      margin: EdgeInsets.only(bottom: 8),
                                      decoration: BoxDecoration(
                                        color: Colors.white,
                                      ),
                                      height: 20,
                                      width: double.infinity),
                                  baseColor: Colors.grey[200],
                                  highlightColor: Colors.grey[100],
                                ),
                        ),
                      ],
                    ),
                  ),
                ),
                Container(
                  height: 48,
                  margin: EdgeInsets.symmetric(horizontal: 20, vertical: 20),
                  child: !isLoading
                      ? RaisedButton.icon(
                          onPressed: () async {
                            final resSubmitBMI =
                                await showModalBottomSheet<BmiModel>(
                                    isScrollControlled: true,
                                    context: context,
                                    builder: (context) => _buildModalBottom());

                            if (resSubmitBMI is BmiModel) {
                              BlocProvider.of<BmiBloc>(context).add(
                                  BMICalculate(
                                      height: resSubmitBMI.height,
                                      weight: resSubmitBMI.weight));
                            } else {
                              DialogHelper.showSnackBar(context,
                                  "Masukkan Tinggi dan Berat untuk memulai perhitungan",
                                  type: SnackBarType.Info);
                            }
                          },
                          icon: Icon(
                            FontAwesomeIcons.solidEdit,
                            color: Colors.white,
                          ),
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(40)),
                          color: Theme.of(context).primaryColor,
                          label: Text("Hitung IMT",
                              style: TextStyle(
                                  fontWeight: FontWeight.w800,
                                  fontSize: 18,
                                  color: Theme.of(context).canvasColor)),
                        )
                      : RaisedButton(
                          onPressed: null,
                          disabledColor: Theme.of(context).primaryColor,
                          child: CircularProgressIndicator(),
                          color: Theme.of(context).primaryColor,
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(40))),
                )
              ],
            );
          },
        ),
      ),
    );
  }

  Widget _buildModalBottom() {
    return DraggableScrollableSheet(
        expand: true,
        initialChildSize: 1, // half screen on load
        maxChildSize: 1, // full screen on scroll
        minChildSize: 0.9,
        // expand: true,
        builder: (BuildContext context, ScrollController scrollController) =>
            Container(
                margin: EdgeInsets.symmetric(vertical: 20, horizontal: 20),
                child: SingleChildScrollView(
                    controller: scrollController,
                    child: Column(
                      children: <Widget>[
                        Container(
                          padding: EdgeInsets.symmetric(vertical: 10),
                          margin: EdgeInsets.only(top: 20, bottom: 20),
                          height: 10,
                          width: 30,
                          decoration: BoxDecoration(
                              border: Border.symmetric(
                                  vertical: BorderSide(
                                      color: ColorPrimaryHelper.sideList))),
                        ),
                        _BMIFormField(onSaved: (height, weight) {
                          final result = BmiModel(
                              height: height.toDouble(),
                              weight: weight.toDouble());
                          Navigator.pop<BmiModel>(context, result);
                        }),
                      ],
                    ))));
  }
}

class _BMIFormField extends StatelessWidget {
  _BMIFormField({Key key, this.onSaved}) : super(key: key);
  final Function(int heightVal, int weightVal) onSaved;

  @override
  Widget build(BuildContext context) {
    int height = 120;
    int weight = 40;

    bool isChanged = false;

    bool _isSubmitEnable() {
      return isChanged;
    }

    return BlocProvider<ProfileBloc>(
      create: (context) => ProfileBloc(),
      child: Column(
        children: <Widget>[
          BlocBuilder<ProfileBloc, ProfileState>(
            builder: (context, state) {
              if (state is ProfileLoading) {
                return ButtonLoadingWidget();
              }

              return ButtonPrimaryWidget(
                "Masukkan",
                onPressed: _isSubmitEnable()
                    ? (() {
                        if (onSaved != null) {
                          onSaved(height, weight);
                        }
                      })
                    : null,
              );
            },
          ),
          _SectionTitleBMIWidget(
            leftTitleText: "Tinggi",
            leftTitleonPressed: () {},
            rightTitleText: "cm",
          ),
          Container(
            height: 350,
            margin: EdgeInsets.only(top: 10, right: 20, left: 20),
            child: BlocBuilder<ProfileBloc, ProfileState>(
              buildWhen: (previous, current) =>
                  current is ProfileBMIHeightChanged ? true : false,
              builder: (context, ProfileState state) => HeightSliderHelper(
                  height: state is ProfileBMIHeightChanged
                      ? state.height.toInt()
                      : height,
                  minHeight: 100,
                  maxHeight: 200,
                  stepHeight: 10,
                  currentHeightTextColor: Theme.of(context).primaryColor,
                  personImagePath: AssetsHelper.assetPathPerson,
                  onChange: (val) {
                    height = val;
                    isChanged = true;
                    BlocProvider.of<ProfileBloc>(context)
                        .add(ProfileChangedBMIHeight(height: val.toDouble()));
                  }),
            ),
          ),
          _SectionTitleBMIWidget(
            leftTitleText: "Berat",
            leftTitleonPressed: () {},
            rightTitleText: "kg",
          ),
          Container(
            margin: EdgeInsets.symmetric(horizontal: 40, vertical: 10),
            child: BlocBuilder<ProfileBloc, ProfileState>(
              buildWhen: (previous, current) =>
                  current is ProfileBMIWeightChanged ? true : false,
              builder: (context, ProfileState state) => WeightSliderHelper(
                  weight: state is ProfileBMIWeightChanged
                      ? state.weight.toInt()
                      : weight,
                  minWeight: 40,
                  maxWeight: 120,
                  onChange: (val) {
                    weight = val;
                    isChanged = true;
                    BlocProvider.of<ProfileBloc>(context)
                        .add(ProfileChangedBMIWeight(weight: val.toDouble()));
                  }),
            ),
          ),
          SizedBox(
            height: 20,
          )
        ],
      ),
    );
  }
}

class _SectionTitleBMIWidget extends StatelessWidget {
  final String leftTitleText;
  final Function leftTitleonPressed;
  final String rightTitleText;

  _SectionTitleBMIWidget(
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
