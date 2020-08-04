import 'package:nutrizer/models/kek_model.dart';
import 'package:nutrizer/repositories/kek_repository.dart';

class KekDomain {
  final KekRepository _kekRepository = KekRepository();

  Future<List<KEKModel>> getKekList(int page) async {
    return await _kekRepository.getKekList(page);
  }

  Future<KEKModel> getKekDetail(String id) async {
    return await _kekRepository.getKekDetail(id);
  }
  
}
