import 'package:hive/hive.dart';

class HiveBox {
  static final String user = 'user';
  static final String cache = 'cache';
}

class HiveHelper {
  Box _box;
  String _boxName;
  HiveHelper(String boxName)
      : _boxName = boxName;
  // static final HiveHelper dbProvider = HiveHelper();

  Future<Box> get dataBox async {
    if (!Hive.isBoxOpen(_boxName)) {
      _box = await Hive.openBox(_boxName);
    }else{
      _box = Hive.box(_boxName);
    }
    return _box;
  }

}
