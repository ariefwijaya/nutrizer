import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';
import 'package:nutrizer/blocs/nutri_calc/nutri_calc_bloc.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/helper/assets_helper.dart';
import 'package:nutrizer/helper/common_helper.dart';
import 'package:nutrizer/helper/dialog_helper.dart';
import 'package:nutrizer/helper/height_slider_helper.dart';
import 'package:nutrizer/helper/weight_slider_helper.dart';
import 'package:nutrizer/models/nutrition_calc_model.dart';
import 'package:nutrizer/widgets/button_widget.dart';
import 'package:shimmer/shimmer.dart';

class NutritionCalcScreen extends StatefulWidget {
  final String screenTitle;
  const NutritionCalcScreen({Key key, this.screenTitle}) : super(key: key);

  @override
  _NutritionCalcScreenState createState() => _NutritionCalcScreenState();
}

class _NutritionCalcScreenState extends State<NutritionCalcScreen> {
  GenderType _gender;

  DateTime _selectedDate;
  int _age;
  double _weight;
  double _height;
  NutriFactorModel _activityFactor;
  NutriFactorModel _stressFactor;

  bool _isNotHealthy = false;

  List<NutriFactorModel> _activityFactorList;
  List<NutriFactorModel> _stressFactorList;

  double _resBmr;
  double _resFat;
  double _resEnergy;
  double _resCarbo;
  double _resProtein;

  final NutriCalcBloc _nutriCalcBloc = NutriCalcBloc()..add(NutriCalcStarted());
  final ScrollController _scrollController = ScrollController();

  @override
  Widget build(BuildContext context) {
    return BlocProvider<NutriCalcBloc>(
      create: (context) => _nutriCalcBloc,
      child: Scaffold(
        appBar: AppBar(
            actions: <Widget>[
              IconButton(
                tooltip: "Reset Input Data",
                icon: Icon(Icons.refresh),
                onPressed: () {
                  _nutriCalcBloc..add(NutriCalcStarted());
                },
              )
            ],
            title: Text(widget.screenTitle,
                style: FontStyleHelper.appBarTitle.copyWith(fontSize: 18))),
        body: SingleChildScrollView(
          controller: _scrollController,
          child: Container(
            width: double.infinity,
            margin: EdgeInsets.symmetric(vertical: 15),
            padding: EdgeInsets.symmetric(vertical: 20, horizontal: 16),
            color: Colors.white,
            child: Column(
                crossAxisAlignment: CrossAxisAlignment.center,
                children: <Widget>[
                  Text(
                    "Kebutuhan Gizi",
                    textAlign: TextAlign.center,
                    style: Theme.of(context)
                        .textTheme
                        .overline
                        .copyWith(fontSize: 14),
                  ),
                  SizedBox(height: 20),
                  _buildNutritionResult(),
                  SizedBox(height: 20),
                  Text(
                    "komposisi tubuh",
                    textAlign: TextAlign.center,
                    style: Theme.of(context)
                        .textTheme
                        .overline
                        .copyWith(fontSize: 14),
                  ),
                  SizedBox(height: 20),
                  _buildNutritionInput(),
                  SizedBox(height: 5),
                  BlocBuilder<NutriCalcBloc, NutriCalcState>(
                    bloc: _nutriCalcBloc,
                    builder: (context, subState) => subState
                                is NutriCalculatedLoading ||
                            subState is NutriCalcLoading
                        ? ButtonLoadingWidget()
                        : ButtonPrimaryWidget(
                            "Hitung Kebutuhan",
                            onPressed: _isCalculateEnabled()
                                ? () {
                                    final formData = NutriCalcFormModel(
                                      weight: _weight,
                                      height: _height,
                                      age: _age,
                                      gender: _gender == GenderType.male
                                          ? "M"
                                          : _gender == GenderType.female
                                              ? "F"
                                              : null,
                                      activityFactor: _activityFactor.factor,
                                      stressFactor: _stressFactor?.factor,
                                    );
                                    _nutriCalcBloc.add(
                                        NutriCalcCalculate(formData: formData));
                                  }
                                : null,
                          ),
                  )
                ]),
          ),
        ),
      ),
    );
  }

  bool _isCalculateEnabled() {
    if (_weight == null ||
        _height == null ||
        _gender == GenderType.unknown ||
        _age == null ||
        _activityFactor == null) {
      return false;
    } else if (_isNotHealthy && _stressFactor == null) {
      return false;
    } else {
      return true;
    }
  }

  Widget _buildNutritionInput() {
    return BlocBuilder<NutriCalcBloc, NutriCalcState>(
      bloc: _nutriCalcBloc,
      buildWhen: (previous, current) =>
          current is NutriCalcSuccess ||
          current is NutriCalcFailure ||
          current is NutriCalcLoading ||
          current is NutriCalcRefreshSuccess,
      builder: (context, state) {
        bool isLoading = false;

        if (state is NutriCalcSuccess) {
          final stateData = state.data;
          _activityFactorList = stateData.activityFactor;
          _stressFactorList = stateData.stressFactor;

          _selectedDate = DateTime.now();
          _gender = GenderType.unknown;
          _age = null;
          _weight = null;
          _height = null;
          _activityFactor = null;
          _stressFactor = null;

           _resBmr = null;
          _resFat = null;
          _resEnergy = null;
          _resCarbo = null;
          _resProtein = null;

          _nutriCalcBloc.add(NutriCalcRefresh());
        }

        // if (state is NutriCalcResetSuccess) {
        //   _activityFactorList = [];
        //   _stressFactorList = [];

        //   _selectedDate = DateTime.now();
        //   _gender = GenderType.unknown;
        //   _age = null;
        //   _weight = null;
        //   _height = null;
        //   _activityFactor = null;
        //   _stressFactor = null;
        // }

        if (state is NutriCalcLoading) {
          isLoading = true;
        }

        return Column(
          children: <Widget>[
            Row(children: <Widget>[
              Expanded(
                  child: _builInputGender(
                      isLoading: isLoading,
                      val: _gender,
                      onTap: () async {
                        final resSelect = await _showGenderSelector(context);

                        if (resSelect != null) {
                          _gender = resSelect;
                          setState(() {
                            _activityFactor = null;
                            _stressFactor = null;
                          });
                        }
                      })),
              _buildInputDivider(),
              Expanded(
                  child: _buildInputText(
                      isLoading: isLoading,
                      label: "Usia",
                      val: _getStringInputVal(_age),
                      labelTop: "tahun",
                      onTap: () async {
                        final resDate = await showDatePicker(
                            context: context,
                            initialDate: _selectedDate ?? DateTime.now(),
                            firstDate: DateTime(1945),
                            lastDate: DateTime.now());

                        if (resDate != null) {
                          _age = CommonHelper.calculateAge(resDate);
                          _selectedDate = resDate;
                          setState(() {});
                        }
                      }))
            ]),
            SizedBox(height: 20),
            Row(children: <Widget>[
              Expanded(
                  child: _buildInputText(
                      isLoading: isLoading,
                      label: "Tinggi",
                      val: _getStringInputVal(_height?.toInt()),
                      labelTop: "cm",
                      onTap: () async {
                        final resSelect = await _showHeightSelect(
                            context, _height?.toInt(),
                            onChanged: (val) => Navigator.pop(context, val));

                        if (resSelect != null) {
                          setState(() {
                            _height = resSelect.toDouble();
                          });
                        }
                      })),
              _buildInputDivider(),
              Expanded(
                  child: _buildInputText(
                      isLoading: isLoading,
                      label: "Berat",
                      val: _getStringInputVal(_weight?.toInt()),
                      labelTop: "kg",
                      onTap: () async {
                        final resSelect = await _showWeightSelect(
                            context, _weight?.toInt(),
                            onChanged: (val) => Navigator.pop(context, val));

                        if (resSelect != null) {
                          setState(() {
                            _weight = resSelect.toDouble();
                          });
                        }
                      }))
            ]),
            SizedBox(height: 25),
            Text(
              "Sedang Tidak Sehat?",
              style: FontStyleHelper.formHeaderSubTitle
                  .copyWith(color: ColorPrimaryHelper.textLight),
            ),
            SizedBox(height: 5),
            Container(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: !isLoading
                  ? Switch(
                      value: _isNotHealthy,
                      onChanged: (value) {
                        setState(() {
                          _isNotHealthy = value;
                          _stressFactor = null;
                          _activityFactor = null;
                        });
                      },
                      activeTrackColor: ColorPrimaryHelper.lightPrimary,
                      activeColor: ColorPrimaryHelper.primary,
                    )
                  : Shimmer.fromColors(
                      child: Container(
                        margin: EdgeInsets.symmetric(vertical: 8),
                        decoration: BoxDecoration(
                          color: Colors.white,
                        ),
                        height: 24,
                      ),
                      baseColor: Colors.grey[200],
                      highlightColor: Colors.grey[100],
                    ),
            ),
            SizedBox(height: 25),
            Text(
              "Tingkat Aktivitas",
              style: FontStyleHelper.formHeaderSubTitle
                  .copyWith(color: ColorPrimaryHelper.textLight),
            ),
            SizedBox(height: 5),
            Container(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: !isLoading
                  ? DropdownButton<NutriFactorModel>(
                      items: _getActivityFactorOptions(),
                      disabledHint: Text("Tidak tersedia"),
                      hint: new Text('Pilih tingkat aktivitas'),
                      value: _activityFactor,
                      onChanged: (value) {
                        setState(() {
                          _activityFactor = value;
                        });
                      },
                      isExpanded: true,
                    )
                  : Shimmer.fromColors(
                      child: Container(
                        margin: EdgeInsets.symmetric(vertical: 8),
                        decoration: BoxDecoration(
                          color: Colors.white,
                        ),
                        height: 24,
                      ),
                      baseColor: Colors.grey[200],
                      highlightColor: Colors.grey[100],
                    ),
            ),
            SizedBox(height: 10),
            Text(
              "Tingkat Stress",
              style: FontStyleHelper.formHeaderSubTitle
                  .copyWith(color: ColorPrimaryHelper.textLight),
            ),
            SizedBox(height: 5),
            Container(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: !isLoading
                  ? DropdownButton<NutriFactorModel>(
                      disabledHint: Text("Tidak tersedia"),
                      items: _getStressFactorOptions(),
                      hint: new Text('Pilih tingkat stress'),
                      value: _stressFactor,
                      onChanged: (value) {
                        // print(value);
                        setState(() {
                          _stressFactor = value;
                        });
                      },
                      isExpanded: true,
                    )
                  : Shimmer.fromColors(
                      child: Container(
                        margin: EdgeInsets.symmetric(vertical: 8),
                        decoration: BoxDecoration(
                          color: Colors.white,
                        ),
                        height: 24,
                      ),
                      baseColor: Colors.grey[200],
                      highlightColor: Colors.grey[100],
                    ),
            ),
          ],
        );
      },
    );
  }

  Widget _buildNutritionResult() {
    return BlocConsumer<NutriCalcBloc, NutriCalcState>(
      listener: (context, state) {
        if (state is NutriCalculatedFailure) {
          DialogHelper.showSnackBar(context, state.error,
              type: SnackBarType.Error);
        }

        if (state is NutriCalcFailure) {
          DialogHelper.showSnackBar(context, state.error,
              type: SnackBarType.Error);
        }
        if (state is NutriCalculatedSuccess) {
          DialogHelper.showSnackBar(context, "Perhitungan Gizi Berhasil.",
              type: SnackBarType.Success);
          _scrollController.animateTo(
            0.0,
            curve: Curves.easeOut,
            duration: const Duration(milliseconds: 300),
          );
        }
      },
      bloc: _nutriCalcBloc,
      // buildWhen: (previous, current) =>
      //     current is NutriCalculatedSuccess ||
      //     current is NutriCalculatedFailure ||
      //     current is NutriCalculatedLoading,
      builder: (subContext, state) {
        bool isLoading = false;

        if (state is NutriCalculatedSuccess) {
          final stateData = state.data;
          _resBmr = stateData.bmr;
          _resFat = stateData.fat;
          _resEnergy = stateData.energy;
          _resCarbo = stateData.carbo;
          _resProtein = stateData.protein;
        }

        if (state is NutriCalculatedLoading || state is NutriCalcLoading) {
          isLoading = true;
        }

        if (state is NutriCalculatedFailure) {
          _resBmr = null;
          _resFat = null;
          _resEnergy = null;
          _resCarbo = null;
          _resProtein = null;
        }

        return Column(
          children: <Widget>[
            Row(
              children: <Widget>[
                Expanded(
                  child: _buildCardResult(
                      isLoading: isLoading,
                      label: "BEE",
                      subLabel: "kkal",
                      text: _getStringInputVal(_resBmr, fixed: 2)),
                ),
                SizedBox(width: 15),
                Expanded(
                  child: _buildCardResult(
                      isLoading: isLoading,
                      label: "Energi",
                      subLabel: "kkal",
                      text: _getStringInputVal(_resEnergy, fixed: 2)),
                ),
              ],
            ),
            SizedBox(height: 15),
            Row(
              children: <Widget>[
                Expanded(
                  child: _buildCardResult(
                      isLoading: isLoading,
                      label: "Lemak",
                      subLabel: "gram",
                      text: _getStringInputVal(_resFat, fixed: 2)),
                ),
                SizedBox(width: 15),
                Expanded(
                  child: _buildCardResult(
                      isLoading: isLoading,
                      label: "Karbohidrat",
                      subLabel: "gram",
                      text: _getStringInputVal(_resCarbo, fixed: 2)),
                ),
                SizedBox(width: 15),
                Expanded(
                  child: _buildCardResult(
                      isLoading: isLoading,
                      label: "Protein",
                      subLabel: "gram",
                      text: _getStringInputVal(_resProtein, fixed: 2)),
                ),
              ],
            ),
          ],
        );
      },
    );
  }

  Future<int> _showWeightSelect(BuildContext context, int curWeight,
      {Function(int) onChanged}) {
    int weight = curWeight ?? 40;
    return showModalBottomSheet(
      context: context,
      builder: (context) => Container(
        margin: EdgeInsets.symmetric(horizontal: 30, vertical: 20),
        child: StatefulBuilder(
          builder: (BuildContext context, setState) => Column(
            mainAxisSize: MainAxisSize.min,
            children: <Widget>[
              WeightSliderHelper(
                  weight: weight,
                  minWeight: 40,
                  maxWeight: 120,
                  onChange: (val) {
                    setState(() {
                      weight = val;
                    });
                  }),
              ButtonPrimaryWidget("Ubah", onPressed: () {
                if (onChanged != null) onChanged(weight);
              })
            ],
          ),
        ),
      ),
    );
  }

  Future<int> _showHeightSelect(BuildContext subContext, int curHeight,
      {Function(int) onChanged}) {
    int height = curHeight ?? 110;

    return showModalBottomSheet(
      isScrollControlled: true,
      context: subContext,
      builder: (subContext) => Container(
        margin: EdgeInsets.symmetric(horizontal: 30, vertical: 20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: <Widget>[
            Container(
              padding: EdgeInsets.symmetric(vertical: 10),
              margin: EdgeInsets.only(top: 20, bottom: 20),
              height: 10,
              width: 30,
              decoration: BoxDecoration(
                  border: Border.symmetric(
                      vertical:
                          BorderSide(color: ColorPrimaryHelper.sideList))),
            ),
            Expanded(
              child: StatefulBuilder(
                builder: (BuildContext context, setState) => HeightSliderHelper(
                    height: height,
                    minHeight: 100,
                    maxHeight: 200,
                    stepHeight: 10,
                    currentHeightTextColor: Theme.of(context).primaryColor,
                    personImagePath: AssetsHelper.assetPathPerson,
                    onChange: (val) {
                      setState(() {
                        height = val;
                      });
                    }),
              ),
            ),
            ButtonPrimaryWidget("Ubah", onPressed: () {
              if (onChanged != null) onChanged(height);
            })
          ],
        ),
      ),
    );
  }

  Future<GenderType> _showGenderSelector(BuildContext context) {
    return showModalBottomSheet(
        context: context,
        builder: (context) => Container(
              margin: EdgeInsets.symmetric(vertical: 30, horizontal: 20),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: <Widget>[
                  _buildGenderSelectorCard(
                      assets: AssetsHelper.male,
                      val: GenderType.male,
                      label: "Laki-laki",
                      onChanged: (val) {
                        Navigator.pop(context, val);
                      }),
                  _buildGenderSelectorCard(
                      assets: AssetsHelper.female,
                      label: "Wanita",
                      val: GenderType.female,
                      onChanged: (val) {
                        Navigator.pop(context, val);
                      })
                ],
              ),
            ));
  }

  List<DropdownMenuItem<NutriFactorModel>> _getActivityFactorOptions() {
    if (_activityFactorList == null) return [];
    return _activityFactorList
        .where((element) {
          if (_isNotHealthy)
            return element.healthy == false;
          else {
            if (_gender == GenderType.male)
              return element.gender == "M";
            else if (_gender == GenderType.female)
              return element.gender == "F";
            else
              return true;
          }
        })
        .map<DropdownMenuItem<NutriFactorModel>>((e) => DropdownMenuItem(
              child: Text(e.title),
              value: e,
            ))
        .toList();
  }

  List<DropdownMenuItem<NutriFactorModel>> _getStressFactorOptions() {
    if (_stressFactorList == null) return [];
    return _stressFactorList
        .where((element) {
          if (_isNotHealthy)
            return true;
          else
            return false;
        })
        .map<DropdownMenuItem<NutriFactorModel>>((e) => DropdownMenuItem(
              child: Text(e.title),
              value: e,
            ))
        .toList();
  }

  String _getStringInputVal(val, {int fixed = 0}) {
    if (val == null) {
      return "-";
    }
    return val.toStringAsFixed(fixed);
  }

  Widget _buildGenderSelectorCard(
      {String assets,
      GenderType val,
      String label,
      Function(GenderType) onChanged}) {
    return GestureDetector(
      onTap: () {
        if (onChanged != null) onChanged(val);
      },
      child: Container(
        margin: const EdgeInsets.all(8.0),
        padding: const EdgeInsets.all(8.0),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: <Widget>[
            Text(
              label,
              style: TextStyle(color: ColorPrimaryHelper.formLabel),
            ),
            SizedBox(height: 10),
            Card(
              elevation: 10,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(50)),
              child: Padding(
                padding: const EdgeInsets.all(8.0),
                child: Image.asset(
                  assets,
                  width: 80,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCardResult(
      {String label, String text, String subLabel, bool isLoading}) {
    isLoading = isLoading ?? true;
    return Container(
      decoration: BoxDecoration(
        boxShadow: [
          BoxShadow(
              color: ColorPrimaryHelper.lightShadow,
              blurRadius: 6,
              offset: Offset(0, 2))
        ],
        color: Colors.white,
        borderRadius: BorderRadius.circular(15),
        // border: Border.all(color: ColorPrimaryHelper.lightPrimary, width: 3),
      ),
      padding: EdgeInsets.all(15),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: <Widget>[
          !isLoading
              ? Text(
                  label,
                  style: TextStyle(
                      fontWeight: FontWeight.normal,
                      fontSize: 12,
                      // letterSpacing: 1.5,
                      color: ColorPrimaryHelper.formLabel),
                )
              : Shimmer.fromColors(
                  child: Container(
                    margin: EdgeInsets.only(bottom: 8),
                    decoration: BoxDecoration(
                      color: Colors.white,
                    ),
                    height: 12,
                    width: double.infinity,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                ),
          SizedBox(height: 4),
          !isLoading
              ? Text(
                  text,
                  style: FontStyleHelper.sectionTitleBMI.copyWith(
                      color: Theme.of(context).primaryColor, fontSize: 20),
                )
              : Shimmer.fromColors(
                  child: Container(
                    margin: EdgeInsets.only(bottom: 8),
                    decoration: BoxDecoration(
                      color: Colors.white,
                    ),
                    height: 22,
                    width: double.infinity,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                ),
          SizedBox(height: 4),
          !isLoading
              ? Text(
                  subLabel,
                  style: TextStyle(
                      fontWeight: FontWeight.normal,
                      fontSize: 14,
                      color: ColorPrimaryHelper.titleText),
                )
              : Shimmer.fromColors(
                  child: Container(
                    margin: EdgeInsets.only(bottom: 8),
                    decoration: BoxDecoration(
                      color: Colors.white,
                    ),
                    height: 14,
                    width: double.infinity,
                  ),
                  baseColor: Colors.grey[200],
                  highlightColor: Colors.grey[100],
                )
        ],
      ),
    );
  }

  Widget _buildInputDivider() {
    return Container(
      width: 2,
      height: 100,
      color: ColorPrimaryHelper.divider,
    );
  }

  Widget _buildInputText(
      {String val,
      String label,
      String labelTop,
      VoidCallback onTap,
      bool isLoading}) {
    isLoading = isLoading ?? true;
    return GestureDetector(
      onTap: !isLoading ? onTap : null,
      child: Container(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: <Widget>[
            Text(
              labelTop,
              style: TextStyle(
                  color: ColorPrimaryHelper.textLight,
                  fontSize: 12,
                  letterSpacing: 2),
            ),
            Container(
              height: 60,
              child: !isLoading
                  ? Text(val,
                      textAlign: TextAlign.center,
                      overflow: TextOverflow.fade,
                      style: TextStyle(
                          color: ColorPrimaryHelper.titleText,
                          fontWeight: FontWeight.bold,
                          fontSize: 45))
                  : Shimmer.fromColors(
                      child: Container(
                        margin: EdgeInsets.all(16),
                        decoration: BoxDecoration(
                          color: Colors.white,
                        ),
                        height: 45,
                        width: double.infinity,
                      ),
                      baseColor: Colors.grey[200],
                      highlightColor: Colors.grey[100],
                    ),
            ),
            SizedBox(height: 8),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: <Widget>[
                Text(
                  label,
                  style: TextStyle(
                      color: ColorPrimaryHelper.titleText,
                      fontWeight: FontWeight.normal),
                ),
                SizedBox(width: 5),
                Icon(
                  FontAwesomeIcons.solidEdit,
                  color: ColorPrimaryHelper.titleText,
                  size: 14,
                )
              ],
            )
          ],
        ),
      ),
    );
  }

  Widget _builInputGender(
      {GenderType val, VoidCallback onTap, bool isLoading}) {
    Widget _select;
    String _selectText;

    isLoading = isLoading ?? true;

    if (val == GenderType.male) {
      _select = Image.asset(
        AssetsHelper.male,
        width: 80,
      );

      _selectText = "Laki-laki";
    } else if (val == GenderType.female) {
      _select = Image.asset(
        AssetsHelper.female,
        width: 80,
      );
      _selectText = "Wanita";
    } else {
      _select = CircleAvatar(
          radius: 35,
          child: Icon(
            FontAwesomeIcons.question,
            color: Colors.white,
          ),
          backgroundColor: ColorPrimaryHelper.primary);
      _selectText = "Jenis Kelamin";
    }

    return GestureDetector(
      onTap: !isLoading ? onTap : null,
      child: Container(
          padding: EdgeInsets.all(10),
          child: Column(
            children: <Widget>[
              !isLoading
                  ? _select
                  : Shimmer.fromColors(
                      child: CircleAvatar(
                          radius: 35, backgroundColor: Colors.white),
                      baseColor: Colors.grey[200],
                      highlightColor: Colors.grey[100],
                    ),
              SizedBox(height: 8),
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: <Widget>[
                  Text(
                    _selectText,
                    style: TextStyle(
                        color: ColorPrimaryHelper.titleText,
                        fontWeight: FontWeight.normal),
                  ),
                  SizedBox(width: 5),
                  Icon(
                    FontAwesomeIcons.solidEdit,
                    color: ColorPrimaryHelper.titleText,
                    size: 14,
                  )
                ],
              )
            ],
          )),
    );
  }
}

enum GenderType { female, male, unknown }
