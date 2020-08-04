class MenuModel {
  String name;
  String image;
  String route;

  MenuModel({this.name, this.image, this.route});

  MenuModel.fromJson(Map<String, dynamic> json) {
    name = json['name'];
    image = json['image'];
    route = json['route'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['name'] = this.name;
    data['image'] = this.image;
    data['route'] = this.route;
    return data;
  }
}
