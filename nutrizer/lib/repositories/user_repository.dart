import 'dart:async';

import 'package:nutrizer/constant.dart';
import 'package:nutrizer/helper/network_helper.dart';
import 'package:nutrizer/models/api_model.dart';
import 'package:nutrizer/models/user_model.dart';

//for connecting to API / database
class UserRepository {
  final NetworkHelper _networkHelper;
  UserRepository({NetworkHelper networkHelper})
      : _networkHelper = networkHelper ?? NetworkHelper();

  Future<UserModel> login(String username, String password) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "${baseUrl}login",
      body: {"username": username, "password": password},
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return UserModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<UserModel> signup(
      String email, String username, String password) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "${baseUrl}signup",
      body: {"email": email, "username": username, "password": password},
    );
    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return UserModel.fromJson(apiModel.data);
    else
      throw (apiModel.message);
  }

  Future<ApiModel> checkExistByusername(String username) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "${baseUrl}user/checkExist",
      body: {"username": username},
    );

    return ApiModel.fromJson(result);
  }

  Future<ApiModel> requestResetPassword(String username) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "${baseUrl}resetPassword",
      body: {"username": username},
    );

    return ApiModel.fromJson(result);
  }

  Future<bool> updateUserProfile(UserModel user) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "${baseUrl}user/updateProfile",
      body: user.toJson(),
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return true;
    else
      throw (apiModel.message);
  }

  Future<double> updateUserProfileBMI(double height, double weight) async {
    Map<String, dynamic> result = await _networkHelper.post(
      "${baseUrl}user/updateBMI",
      body: {"weight": weight.toString(), "height": height.toString()},
    );

    ApiModel apiModel = ApiModel.fromJson(result);
    if (apiModel.success)
      return apiModel.data?.toDouble();
    else
      throw (apiModel.message);
  }
}
