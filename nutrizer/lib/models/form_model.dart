class FormEditProfileModel {
  String nickname;
  String email;

  FormEditProfileModel({this.nickname, this.email});

  FormEditProfileModel.fromJson(Map<String, dynamic> json) {
    nickname = json['nickname'];
    email = json['email'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['nickname'] = this.nickname;
    data['email'] = this.email;
    return data;
  }
}


class FormChangePasswordModel {
  String oldPassword;
  String newPassword;

  FormChangePasswordModel({this.oldPassword, this.newPassword});

  FormChangePasswordModel.fromJson(Map<String, dynamic> json) {
    oldPassword = json['oldPassword'];
    newPassword = json['newPassword'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['oldPassword'] = this.oldPassword;
    data['newPassword'] = this.newPassword;
    return data;
  }
}

