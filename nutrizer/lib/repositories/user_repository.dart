import 'dart:async';

import 'package:nutrizer/helper/network_helper.dart';
import 'package:nutrizer/models/api_model.dart';
import 'package:nutrizer/models/form_model.dart';
import 'package:nutrizer/models/user_model.dart';

//for connecting to API / database
class UserRepository {
  final NetworkHelper _networkHelper;
  UserRepository({NetworkHelper networkHelper})
      : _networkHelper = networkHelper ?? NetworkHelper();

  Future<UserModel> login(String username, String password) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "login",
      body: {"username": username, "password": password},
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return UserModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<UserModel> signup(
      {String email,
      String username,
      String password,
      String nickname,
      String birthday,
      String gender}) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "signup",
      body: {
        "email": email,
        "username": username,
        "password": password,
        "nickname": nickname,
        "birthday": birthday,
        "gender": gender
      },
    );
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return UserModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<ApiModel> checkExistByusername(String username) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "user/checkExist",
      body: {"username": username},
    );

    return ApiModel.fromJson(result);
  }

  Future<ApiModel> requestResetPassword(String username) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "resetPassword",
      body: {"username": username},
    );

    return ApiModel.fromJson(result);
  }

  Future<bool> updateUserProfile(FormEditProfileModel user) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "user/updateProfile",
      body: user.toJson(),
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return true;
    else
      throw (apiModel.message);
  }

  Future<bool> changePassword(FormChangePasswordModel user) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "user/changePassword",
      body: user.toJson(),
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return true;
    else
      throw (apiModel.message);
  }

  Future<bool> updateUserProfileBMI(double height, double weight) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "user/updateBMI",
      body: {"weight": weight.toString(), "height": height.toString()},
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return true;
    else
      throw (apiModel.message);
  }

  Future<UserModel> getUserProfile() async {
    Map<String, dynamic> result = await _networkHelper.get(
      "user/profile",
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return UserModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<BmiModel> getBMI() async {
    Map<String, dynamic> result = await _networkHelper.get(
      "user/bmi",
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return BmiModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<bool> checkTokenValidation() async {
    Map<String, dynamic> result = await _networkHelper.get(
      "auth/validation",
    );
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return true;
    else
      return false;
  }

  Future<ApiModel> getAppInfo() async {
    Map<String, dynamic> result = await _networkHelper.get(
      "appInfo",
    );
    return ApiModel.fromJson(result);
  }
}
