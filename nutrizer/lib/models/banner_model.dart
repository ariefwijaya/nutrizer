class BannerAdsModel {
  String id;
  String title;
  String subtitle;
  String linkUrl;

  BannerAdsModel({this.id, this.title, this.subtitle, this.linkUrl});

  BannerAdsModel.fromJson(Map<String, dynamic> json) {
    id = json['id'];
    title = json['title'];
    subtitle = json['subtitle'];
    linkUrl = json['linkUrl'];
  }

  Map<String, dynamic> toJson() {
    final Map<String, dynamic> data = new Map<String, dynamic>();
    data['id'] = this.id;
    data['title'] = this.title;
    data['subtitle'] = this.subtitle;
    data['linkUrl'] = this.linkUrl;
    return data;
  }
}
