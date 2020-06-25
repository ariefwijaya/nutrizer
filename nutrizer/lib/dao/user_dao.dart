import 'package:hive/hive.dart';
import 'package:nutrizer/helper/hive_helper.dart';
import 'package:nutrizer/models/user_model.dart';

class UserDao {
  final HiveHelper dbProvider;
  UserDao() : dbProvider = HiveHelper(HiveBox.user);

  String _userKey = "user1";

  Future<bool> createUser(UserModel user) async {
    final Box db = await dbProvider.dataBox;
    await db.put(_userKey,user.toJson());
    return true;
  }

  Future<void> deleteUser() async {
    final Box db = await dbProvider.dataBox;
    await db.deleteFromDisk();
  }

  Future<bool> checkUser() async {
    final Box db = await dbProvider.dataBox;
    return db.isNotEmpty;
  }

  Future<UserModel> getUser() async {
    final db = await dbProvider.dataBox;
    if (db.isNotEmpty) {
      final maps = db.get(_userKey);
      Map<String, dynamic> castData = Map<String, dynamic>.from(maps);
      return UserModel.fromJson(castData);
    } else {
      return null;
    }
  }

  Future<bool> updateUser(UserModel user) async {
    final Box db = await dbProvider.dataBox;
    await db.put(_userKey, user.toJson());
    return true;
  }
}
