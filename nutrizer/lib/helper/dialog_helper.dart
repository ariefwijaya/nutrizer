import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';

enum SnackBarType { Error, Success, Info, Warning }

class DialogHelper {
  static Future<void> showLoadingDialog(BuildContext context,) async {
    return showDialog<void>(
        context: context,
        barrierDismissible: true,
        builder: (BuildContext context) {
          return WillPopScope(
              onWillPop: () async => false,
              child: SimpleDialog(
                  elevation: 5,
                  contentPadding:
                      EdgeInsets.symmetric(horizontal: 10, vertical: 20),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(15)),
                  children: <Widget>[
                    Center(
                      child: Row(
                          mainAxisSize: MainAxisSize.min,
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              "Loading...",
                              style: TextStyle(
                                  fontSize: 18,
                                  color: Theme.of(context).primaryColor),
                            )
                          ]),
                    ),
                  ]));
        });
  }

  static Future<bool> showAlertDialog(BuildContext context,
      {@required String content, Function onYes, Function onNo}) async {
    return await showDialog<bool>(
          context: context,
          builder: (context) => AlertDialog(
            content: Container(
                padding: EdgeInsets.only(top: 10),
                child: Text(
                  '$content',
                  textAlign: TextAlign.center,
                  style: TextStyle(height: 1.5),
                )),
            actions: <Widget>[
              OutlineButton(
                onPressed: onNo,
                child: Text('No'),
              ),
              FlatButton(
                onPressed: onYes,
                child: Text(
                  'Yes',
                  style: TextStyle(fontWeight: FontWeight.w700),
                ),
                color: Theme.of(context).primaryColor,
              ),
            ],
          ),
        ) ??
        false;
  }

  static void showSnackBar(BuildContext context, String text,
      {SnackBarType type, Function(SnackBarClosedReason) onClosed}) {
    Widget iconBar = Container();
    final danger = ColorPrimaryHelper.danger;
    final success = ColorPrimaryHelper.primary;
    final warning = ColorPrimaryHelper.warning;

    if (type == SnackBarType.Error) {
      iconBar = Icon(
        Icons.remove_circle,
        color: danger,
      );
    } else if (type == SnackBarType.Success) {
      iconBar = Icon(
        Icons.check_circle,
        color: success,
      );
    } else if (type == SnackBarType.Warning) {
      iconBar = Icon(
        Icons.warning,
        color: warning,
      );
    } else {
      iconBar = Icon(
        Icons.info,
        color: Theme.of(context).accentColor,
      );
    }

    Scaffold.of(context)
      ..hideCurrentSnackBar()
      ..showSnackBar(
        SnackBar(
          duration: Duration(seconds: 2),
          // behavior: SnackBarBehavior.floating,
          content: Row(
            mainAxisSize: MainAxisSize.min,
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [Expanded(child: Text('$text')), iconBar],
          ),
          // backgroundColor: Colors.red,
        ),
      ).closed.then((SnackBarClosedReason reason) {
        if (onClosed != null) onClosed(reason);
      });
  }
}
