import 'package:flutter/material.dart';

class AppbarWidget extends StatelessWidget implements PreferredSizeWidget {
  final String title;
  final bool centerTitle;
  final double elevation;
  final List<Widget> actions;

  const AppbarWidget(
      {Key key, this.title, this.centerTitle, this.elevation, this.actions})
      : super(key: key);

  @override
  Size get preferredSize => new Size.fromHeight(kToolbarHeight);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: Text(title,style: TextStyle(fontSize: 18),),
      elevation: elevation,
      centerTitle: centerTitle,
      actions:actions,
    );
  }
}


class AppbarColorWidget extends StatelessWidget implements PreferredSizeWidget {
  final String title;
  final bool centerTitle;
  final double elevation;
  final List<Widget> actions;

  const AppbarColorWidget(
      {Key key, this.title, this.centerTitle, this.elevation, this.actions})
      : super(key: key);

  @override
  Size get preferredSize => new Size.fromHeight(kToolbarHeight);

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: Text(title,),
      backgroundColor: Theme.of(context).primaryColor,
      elevation: elevation,
      centerTitle: centerTitle,
      actions:actions
    );
  }
}
