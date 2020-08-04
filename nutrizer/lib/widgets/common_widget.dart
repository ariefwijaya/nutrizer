import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/widgets/button_widget.dart';

class PlaceholderWidget extends StatelessWidget {
  const PlaceholderWidget(
      {Key key,
      this.imagePath,
      this.title,
      this.subtitle,
      this.onPressed,
      this.buttonText = "Retry"})
      : super(key: key);

  final String imagePath, title, subtitle;
  final VoidCallback onPressed;
  final String buttonText;

  @override
  Widget build(BuildContext context) {
    return Container(
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.center,
        children: <Widget>[
          Container(
              margin: EdgeInsets.symmetric(horizontal: 50, vertical: 40),
              child: Image.asset(imagePath)),
          Container(
            margin: EdgeInsets.symmetric(horizontal: 30),
            child: Text(
              title,
              textAlign: TextAlign.center,
              style: FontStyleHelper.formHeaderTitle.copyWith(
                color: Theme.of(context).accentColor,
                fontWeight: FontWeight.w900,
              ),
            ),
          ),
          SizedBox(height: 8),
          Container(
              margin: EdgeInsets.symmetric(horizontal: 30),
              child: Text(
                subtitle,
                textAlign: TextAlign.center,
                style: TextStyle(
                    color: ColorPrimaryHelper.textLight, fontSize: 14),
              )),
          SizedBox(height: 15),
          onPressed != null
              ? ButtonPrimaryWidget(buttonText, onPressed: onPressed)
              : Container()
        ],
      ),
    );
  }
}

class ModalBottomCard extends StatelessWidget {
  const ModalBottomCard({Key key, this.imagePath,this.title, this.subtitle,this.onButtonTap,
  this.labelButton}) : super(key: key);

  final String imagePath,title,subtitle,labelButton;
  final Function onButtonTap;
  @override
  Widget build(BuildContext context) {
    return Container(
        child: Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      mainAxisSize: MainAxisSize.min,
      children: <Widget>[
        Align(
          alignment: Alignment.centerLeft,
          child: IconButton(
            onPressed: () => Navigator.pop(context),
            icon: Icon(Icons.close),
          ),
        ),
        Expanded(
          child: Container(
              width: 200,
              height: 200,
              decoration: BoxDecoration(
                  image: DecorationImage(
                      fit: BoxFit.contain,
                      image: AssetImage(imagePath)))),
        ),
        Text(
          title,
          style: FontStyleHelper.formHeaderTitle
              .copyWith(fontSize: 20, color: Theme.of(context).accentColor),
        ),
        (subtitle!=null)?
        Container(
          margin: EdgeInsets.only(top: 5),
          child: Text(
            subtitle,
            style: FontStyleHelper.formHeaderSubTitle
                .copyWith(color: Theme.of(context).disabledColor, fontSize: 16),
          ),
        ):Container(),
        Container(
          margin: EdgeInsets.only(left: 20, right: 20, bottom: 20, top: 10),
          child: ButtonPrimaryWidget(labelButton,
              onPressed: onButtonTap, margin: EdgeInsets.all(5)),
        )
      ],
    ));
  }
}
