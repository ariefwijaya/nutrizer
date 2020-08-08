import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';

class FooterFormWidget extends StatelessWidget {
  final String titleText;
  final String linkText;
  final Function onPressed;

  FooterFormWidget({@required this.titleText, this.linkText, this.onPressed});
  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(top: 20),
      decoration: BoxDecoration(
          // gradient: LinearGradient(
          //     begin: Alignment.bottomCenter,
          //     end: Alignment.topCenter,
          //     colors: [
          //   ColorPrimaryHelper.textLight,
          //   ColorPrimaryHelper.textLight.withOpacity(0.1)
          // ])
          ),
      child: ClipPath(
          clipper: FooterFormClipper(),
          child: Container(
            padding: EdgeInsets.only(left: 10, bottom: 20, right: 10),
            alignment: Alignment.bottomCenter,
            height: 65,
            decoration: BoxDecoration(
              color: Theme.of(context).primaryColor,
            ),
            child: RichText(
                text: TextSpan(
                    style: TextStyle(
                      fontSize: 16
                    ),
                    children: [
                  TextSpan(
                      text: titleText,
                      style: TextStyle(
                        color: Theme.of(context).scaffoldBackgroundColor,
                      )),
                  TextSpan(text: "  "),
                  linkText != null
                      ? TextSpan(
                          text: linkText,
                          style: TextStyle(
                            color:Theme.of(context).accentColor,
                            decoration: TextDecoration.underline,
                          ),
                          recognizer: TapGestureRecognizer()..onTap = onPressed)
                      : Container()
                ])),
          )),
    );
  }
}

class FooterFormClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    var path = Path();
    path.lineTo(0, 0);
    path.lineTo(0, 10);
    path.quadraticBezierTo(size.width / 4, 0, size.width / 2, 0);
    path.quadraticBezierTo(size.width - size.width / 4, 0, size.width, 10);
    path.lineTo(size.width, size.height);
    path.lineTo(0, size.height);
    path.close();
    return path;
  }

  @override
  bool shouldReclip(CustomClipper<Path> oldClipper) {
    return false;
  }
}
