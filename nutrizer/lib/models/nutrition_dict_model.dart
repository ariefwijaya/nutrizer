class NutritionDictModel {
  String id;
  String name;
  String imageUrl;

  NutritionDictModel({this.id, this.name, this.imageUrl});

  NutritionDictModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    name = json['name'];
    imageUrl = json['imageUrl'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['name'] = this.name;
    data['imageUrl'] = this.imageUrl;
    return data;
  }
}


class NutriCatModel {
  String id;
  String name;
  String imageUrl;
  List<FoodModel> foodModel;

  NutriCatModel({this.id, this.name, this.imageUrl, this.foodModel});

  NutriCatModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    name = json['name'];
    imageUrl = json['imageUrl'];
    if (json['foods'] != null) {
      foodModel = new List<FoodModel>();
      json['foods'].forEach((v) {
        foodModel.add(new FoodModel.fromJson(v));
      });
    }
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['name'] = this.name;
    data['imageUrl'] = this.imageUrl;
    if (this.foodModel != null) {
      data['foods'] = this.foodModel.map((v) => v.toJson()).toList();
    }
    return data;
  }
}

class FoodModel {
  String id;
  String name;
  String kkal;
  String imageUrl;

  FoodModel({this.id, this.name, this.kkal, this.imageUrl});

  FoodModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    name = json['name'];
    kkal = json['kkal'];
    imageUrl = json['imageUrl'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['name'] = this.name;
    data['kkal'] = this.kkal;
    data['imageUrl'] = this.imageUrl;
    return data;
  }
}
