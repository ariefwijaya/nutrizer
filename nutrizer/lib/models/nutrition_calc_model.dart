class NutriCalcResultModel {
  double bmr;
  double fat;
  double energy;
  double carbo;
  double protein;

  NutriCalcResultModel(
      {this.bmr, this.fat, this.energy, this.carbo, this.protein});

  NutriCalcResultModel.fromJson(Map<String, dynamic> json) {

    bmr =  json['bmr']?.toDouble();
    fat = json['fat']?.toDouble();
    energy = json['energy']?.toDouble();
    carbo = json['carbo']?.toDouble();
    protein = json['protein']?.toDouble();
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['bmr'] = this.bmr;
    data['fat'] = this.fat;
    data['energy'] = this.energy;
    data['carbo'] = this.carbo;
    data['protein'] = this.protein;
    return data;
  }
}

class NutriCalcFormModel {
  String gender;
  int age;
  double weight;
  double height;
  double activityFactor;
  double stressFactor;

  NutriCalcFormModel(
      {this.gender,
      this.age,
      this.weight,
      this.height,
      this.activityFactor,
      this.stressFactor});

  NutriCalcFormModel.fromJson(Map<String, dynamic> json) {
    gender = json['gender'];
    age = json['age'];
    weight = json['weight'];
    height = json['height'];
    activityFactor = json['activityFactor'];
    stressFactor = json['stressFactor'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['gender'] = this.gender;
    data['age'] = this.age;
    data['weight'] = this.weight;
    data['height'] = this.height;
    data['activityFactor'] = this.activityFactor;
    data['stressFactor'] = this.stressFactor;
    return data;
  }

   Map<String, String> toJsonString() {
    final Map<String, String> data = new Map<String, String>();
    data['gender'] = this.gender.toString();
    data['age'] = this.age.toString();
    data['weight'] = this.weight.toString();
    data['height'] = this.height.toString();
    data['activityFactor'] = this.activityFactor.toString();
    if(this.stressFactor!=null)
    data['stressFactor'] = this.stressFactor.toString();
    return data;
  }
}


class NutriCalcInitModel {
  List<NutriFactorModel> activityFactor;
  List<NutriFactorModel> stressFactor;

  NutriCalcInitModel({this.activityFactor, this.stressFactor});

  NutriCalcInitModel.fromJson(Map<String, dynamic> json) {
    if (json['activityFactor'] != null) {
      activityFactor = new List<NutriFactorModel>();
      json['activityFactor'].forEach((v) {
        activityFactor.add(new NutriFactorModel.fromJson(v));
      });
    }

    if (json['stressFactor'] != null) {
      stressFactor = new List<NutriFactorModel>();
      json['stressFactor'].forEach((v) {
        stressFactor.add(new NutriFactorModel.fromJson(v));
      });
    }
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    if (this.activityFactor != null) {
      data['activityFactor'] =
          this.activityFactor.map((v) => v.toJson()).toList();
    }

    if (this.stressFactor != null) {
      data['stressFactor'] = this.stressFactor.map((v) => v.toJson()).toList();
    }
    return data;
  }
}

class NutriFactorModel {
  String id;
  String title;
  double factor;
  String gender;
  bool healthy;

  NutriFactorModel({this.id, this.title, this.factor, this.gender,this.healthy});

  NutriFactorModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    title = json['title'];
    factor = json['factor'] is int && json['factor']!=null ? json['factor'].toDouble(): json['factor'];
    gender = json['gender'];
    healthy = json['healthy'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['title'] = this.title;
    data['factor'] = this.factor;
    data['gender'] = this.gender;
    data['healthy'] = this.healthy;
    return data;
  }
}
