class ApiModel {
  bool success;
  String errorCode;
  String message;
  dynamic data;

  ApiModel({this.success, this.errorCode, this.message, this.data});

  ApiModel.fromJson(Map<String, dynamic> json) {
    success = json['success'];
    errorCode = json['error_code'];
    message = json['message'];
    data = json['data'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['success'] = this.success;
    data['error_code'] = this.errorCode;
    data['message'] = this.message;
    data['data'] = this.data;
    return data;
  }
}
