import 'package:flutter/material.dart';

class ButtonOnBoardingWidget extends StatelessWidget {
  final String label;
  final Function onPressed;

  ButtonOnBoardingWidget(
    this.label, {
    this.onPressed,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: EdgeInsets.symmetric(vertical: 15, horizontal: 10),
      child: RaisedButton(
        elevation: 3,
        onPressed: onPressed,
        padding: EdgeInsets.symmetric(vertical: 15.0, horizontal: 25),
        child: Text(label,
            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
        shape: RoundedRectangleBorder(
            borderRadius: new BorderRadius.circular(30.0)),
      ),
    );
  }
}

class ButtonPrimaryWidget extends StatelessWidget {
  final String label;
  final Function onPressed;
  final EdgeInsetsGeometry margin;
  final Color color;

  ButtonPrimaryWidget(this.label, {this.onPressed, this.margin,this.color, Key key})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: margin != null
          ? margin
          : EdgeInsets.symmetric(vertical: 15, horizontal: 70),
      width: double.infinity,
      child: RaisedButton(
        elevation: 3,
        onPressed: onPressed,
        padding: EdgeInsets.symmetric(vertical: 12.0, horizontal: 15),
        child: Text(label,
            style: TextStyle(
                fontWeight: FontWeight.w800,
                fontSize: 18,
                color: Theme.of(context).canvasColor)),
        color: color!=null?color:Theme.of(context).primaryColor,
        shape: RoundedRectangleBorder(
            borderRadius: new BorderRadius.circular(30.0)),
      ),
    );
  }
}

class ButtonLoadingWidget extends StatelessWidget {
  final EdgeInsetsGeometry margin;

  ButtonLoadingWidget({this.margin, Key key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: margin != null
          ? margin
          : EdgeInsets.symmetric(vertical: 15, horizontal: 70),
      width: double.infinity,
      child: RaisedButton(
        elevation: 3,
        onPressed: null,
        disabledColor: Theme.of(context).primaryColor,
        padding: EdgeInsets.symmetric(vertical: 12.0, horizontal: 15),
        child: CircularProgressIndicator(),
        color: Theme.of(context).primaryColor,
        shape: RoundedRectangleBorder(
            borderRadius: new BorderRadius.circular(30.0)),
      ),
    );
  }
}
