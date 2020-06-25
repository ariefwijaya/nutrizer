class UserModel {
  String id;
  String nickname;
  String username;
  String privilege;
  String email;
  String birthday;
  double height;
  double weight;
  double bmi;
  String token;

  UserModel(
      {this.id,
      this.nickname,
      this.username,
      this.privilege,
      this.email,
      this.birthday,
      this.height,
      this.weight,
      this.bmi,
      this.token});

  UserModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    nickname = json['nickname'];
    username = json['username'];
    privilege = json['privilege'];
    email = json['email'];
    birthday = json['birthday'];
    height = json['height']?.toDouble();
    weight = json['weight']?.toDouble();
    bmi = json['bmi']?.toDouble();
    token = json['token'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['nickname'] = this.nickname;
    data['username'] = this.username;
    data['privilege'] = this.privilege;
    data['email'] = this.email;
    data['birthday'] = this.birthday;
    data['height'] = this.height;
    data['weight'] = this.weight;
    data['bmi'] = this.bmi;
    data['token'] = this.token;
    return data;
  }
}
