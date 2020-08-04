class KEKModel {
  String id;
  String title;
  String subtitle;
  String content;

  KEKModel({this.id, this.title, this.subtitle,this.content});

  KEKModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    title = json['title'];
    subtitle = json['subtitle'];
    content = json['content'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['title'] = this.title;
    data['subtitle'] = this.subtitle;
    data['content'] = this.content;
    return data;
  }
}
