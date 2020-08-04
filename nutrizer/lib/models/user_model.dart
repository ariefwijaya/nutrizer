class UserModel {
  String nickname;
  String username;
  String email;
  String birthday;
  double height;
  double weight;
  double bmi;
  String lastLoginTime;
  String lastLoginFrom;
  String token;

  UserModel(
      {this.nickname,
      this.username,
      this.email,
      this.birthday,
      this.height,
      this.weight,
      this.bmi,
      this.lastLoginTime,
      this.lastLoginFrom});

  UserModel.fromJson(Map<String, dynamic> json) {
    nickname = json['nickname'];
    username = json['username'];
    email = json['email'];
    birthday = json['birthday'];
    height = json['height'] != null ? json['height'].toDouble() : 0.0;
    weight = json['weight'] != null ? json['weight'].toDouble() : 0.0;
    bmi = json['bmi'];
    lastLoginTime = json['lastLoginTime'];
    lastLoginFrom = json['lastLoginFrom'];
    token = json['token'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['nickname'] = this.nickname;
    data['username'] = this.username;
    data['email'] = this.email;
    data['birthday'] = this.birthday;
    data['height'] = this.height;
    data['weight'] = this.weight;
    data['bmi'] = this.bmi;
    data['lastLoginTime'] = this.lastLoginTime;
    data['lastLoginFrom'] = this.lastLoginFrom;
    data['token'] = this.token;
    return data;
  }
}

class BmiModel {
  double height;
  double weight;
  double bmi;
  String bmiText;

  BmiModel(
      {
      this.height,
      this.weight,
      this.bmi,
      this.bmiText
      });

  BmiModel.fromJson(Map<String, dynamic> json) {
    height = json['height'] != null ? json['height'].toDouble() : 0.0;
    weight = json['weight'] != null ? json['weight'].toDouble() : 0.0;
    bmi = json['bmi'];
    bmiText = json['bmiText'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['height'] = this.height;
    data['weight'] = this.weight;
    data['bmi'] = this.bmi;
    data['bmiText'] = this.bmiText;
    return data;
  }
}
