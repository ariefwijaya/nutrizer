import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/screens/home_screen.dart';

class BottomBarLayoutScreen extends StatefulWidget {
  @override
  _BottomBarLayoutScreenState createState() => _BottomBarLayoutScreenState();
}

class _BottomBarLayoutScreenState extends State<BottomBarLayoutScreen> {
  int _currentIndex = 0;

  List<Widget> _widgets = <Widget>[
    HomeScreen(),
    Container(),
    Container(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).backgroundColor,
      bottomNavigationBar: Container(
        decoration: BoxDecoration(boxShadow: [
          BoxShadow(color: ColorPrimaryHelper.lightShadow, blurRadius: 30)
        ]),
        child: BottomNavigationBar(
          backgroundColor: ColorPrimaryHelper.secondary,
          selectedIconTheme:
              IconThemeData(color: Theme.of(context).primaryColor),
          iconSize: 25,
          unselectedItemColor: ColorPrimaryHelper.disabledIcon,
          selectedLabelStyle: FontStyleHelper.brandName
              .copyWith(color: Theme.of(context).primaryColor, fontSize: 12),
          unselectedLabelStyle: FontStyleHelper.brandName
              .copyWith(color: Theme.of(context).dividerColor, fontSize: 12),
          currentIndex: _currentIndex,
          selectedItemColor: Theme.of(context).primaryColor,
          onTap: (value) {
            setState(() {
              _currentIndex = value;
            });
          },
          items: <BottomNavigationBarItem>[
            BottomNavigationBarItem(
                icon: Icon(Icons.home), title: Text('BERANDA')),
            BottomNavigationBarItem(
                icon: Icon(Icons.move_to_inbox), title: Text('UNDANG')),
            BottomNavigationBarItem(
                icon: Icon(Icons.person), title: Text('AKUN')),
          ],
        ),
      ),
      body: _widgets.elementAt(_currentIndex),
    );
  }
}
