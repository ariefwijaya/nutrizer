import 'package:nutrizer/dao/user_dao.dart';
import 'package:nutrizer/models/api_model.dart';
import 'package:nutrizer/models/form_model.dart';
import 'package:nutrizer/models/user_model.dart';
import 'package:nutrizer/repositories/user_repository.dart';

class UserDomain {
  final UserRepository _userRepository;
  final UserDao _userDao;

  UserDomain({UserRepository userRepository, UserDao userDao})
      : _userRepository = userRepository ?? UserRepository(),
        _userDao = userDao ?? UserDao();

  Future<UserModel> getCurrentSession() async {
    return await _userDao.getUser();
  }

  Future<void> logout() async {
    await _userDao.deleteUser();
  }

  Future<bool> addSession(UserModel userModel) async {
    return await _userDao.createUser(userModel);
  }

  Future<bool> updateSession(UserModel userModel) async {
    return await _userDao.updateUser(userModel);
  }

  Future<bool> isLoggedIn() async {
    //always 0, assume that only one user in app
    return await _userDao.checkUser();
  }

  Future<bool> loginByUsername(String username, String password) async {
    return await addSession(await _userRepository.login(username, password));
  }

  Future<bool> signupByEmail(
      {String email,
      String username,
      String password,
      String nickname,
      String birthday,
      String gender}) async {
    return await addSession(await _userRepository.signup(
        email: email,
        birthday: birthday,
        nickname: nickname,
        password: password,
        username: username,
        gender: gender));
  }

  Future<ApiModel> checkExistByusername(String username) async {
    return await _userRepository.checkExistByusername(username);
  }

  Future<ApiModel> requestResetPassword(String username) async {
    return await _userRepository.requestResetPassword(username);
  }

  Future<bool> updateUserProfile(FormEditProfileModel data) async {
    return await _userRepository.updateUserProfile(data);
  }

  Future<bool> changePassword(FormChangePasswordModel data) async {
    return await _userRepository.changePassword(data);
  }

  Future<bool> updateUserProfileBMI(double height, double weight) async {
    return await _userRepository.updateUserProfileBMI(height, weight);
  }

  Future<UserModel> getUserProfile() async {
    return await _userRepository.getUserProfile();
  }

  Future<BmiModel> getBMI() async {
    return await _userRepository.getBMI();
  }

  Future<ApiModel> getAppInfo() async {
    return await _userRepository.getAppInfo();
  }

  Future<bool> checkTokenValidation() async {
    return await _userRepository.checkTokenValidation();
  }
  
}
