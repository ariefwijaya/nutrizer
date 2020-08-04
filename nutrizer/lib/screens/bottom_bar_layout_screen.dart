import 'package:flutter/material.dart';
import 'package:nutrizer/helper/appstyle_helper.dart';
import 'package:nutrizer/screens/account_screen.dart';
import 'package:nutrizer/screens/home_screen.dart';
import 'package:nutrizer/screens/invite_screen.dart';

class BottomBarLayoutScreen extends StatefulWidget {
  @override
  _BottomBarLayoutScreenState createState() => _BottomBarLayoutScreenState();
}

class _BottomBarLayoutScreenState extends State<BottomBarLayoutScreen>
    with SingleTickerProviderStateMixin {
  int _selectedIndex = 0;
  TabController _pageController;

  final _listPage = <Widget>[HomeScreen(), InviteScreen(), AccountScreen()];

  @override
  void initState() {
    super.initState();
    _pageController = TabController(length: _listPage.length, vsync: this);
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

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
              .copyWith(color: Theme.of(context).primaryColor, fontSize: 12,
              fontWeight: FontWeight.normal),
          unselectedLabelStyle: FontStyleHelper.brandName
              .copyWith(color: Theme.of(context).dividerColor, fontSize: 12,fontWeight: FontWeight.normal),
          currentIndex: _selectedIndex,
          selectedItemColor: Theme.of(context).primaryColor,
          onTap: _onNavBarTapped,
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
      body: TabBarView(
        controller: _pageController,
        children: _listPage,

      ),
    );
  }

  void _onNavBarTapped(int index) {
    setState(() {
      _selectedIndex =index;
    });
    _pageController.animateTo(index,
        duration: Duration(milliseconds: 300), curve: Curves.easeInOut);
  }
}
