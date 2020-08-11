<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'pagesRoute/index';
$route['404_override'] = 'pagesRoute/error_404';
$route['translate_uri_dashes'] = TRUE;

//route web admin
$route['dashboard'] = 'pagesRoute/dashboard';
$route['login'] = 'pagesRoute/login';
$route['logout'] = 'pagesRoute/logout';
$route['dashboard'] = 'pagesRoute/dashboard';
$route['manage/user'] = 'pagesRoute/manage_user';
$route['manage/news'] = 'pagesRoute/manage_kek';
$route['manage/nutrition'] = 'pagesRoute/manage_nutrition';
$route['manage/foodCategory'] = 'pagesRoute/manage_food_cat';
$route['manage/food'] = 'pagesRoute/manage_food';
$route['profile/update'] = 'pagesRoute/setting_profile';
$route['setting/mobile'] = 'pagesRoute/setting_mobile';
$route['home'] = 'pagesRoute/index';

//api for web
$route['loader/images/(:any)/(:any)'] = 'load/getImage/$1/$2';
$route['loader/thumb/(:any)/(:any)'] = 'load/getImage/$1_thumb/$2';
$route['webapi/login'] = 'admin/loginUser';

$route['webapi/users'] = 'webapi/user/getGridRows';
$route['webapi/users/excel'] = 'webapi/user/getGridRowsExcel';
$route['webapi/user/get/(:any)'] = 'webapi/user/getById/$1';
$route['webapi/user/add'] = 'webapi/user/createData';
$route['webapi/user/edit'] = 'webapi/user/editData';
$route['webapi/user/delete'] = 'webapi/user/deleteData';
$route['webapi/users/search'] = 'webapi/user/getListData';

//info covid / keks
$route['webapi/keks'] = 'webapi/kek/getGridRows';
$route['webapi/keks/excel'] = 'webapi/kek/getGridRowsExcel';
$route['webapi/kek/get/(:any)'] = 'webapi/kek/getById/$1';
$route['webapi/kek/add'] = 'webapi/kek/createData';
$route['webapi/kek/edit'] = 'webapi/kek/editData';
$route['webapi/kek/delete'] = 'webapi/kek/deleteData';
$route['webapi/keks/search'] = 'webapi/kek/getListData';

//Nutrition
$route['webapi/nutritions'] = 'webapi/nutrition/getGridRows';
$route['webapi/nutritions/excel'] = 'webapi/nutrition/getGridRowsExcel';
$route['webapi/nutrition/get/(:any)'] = 'webapi/nutrition/getById/$1';
$route['webapi/nutrition/add'] = 'webapi/nutrition/createData';
$route['webapi/nutrition/edit'] = 'webapi/nutrition/editData';
$route['webapi/nutrition/delete'] = 'webapi/nutrition/deleteData';
$route['webapi/nutritions/search'] = 'webapi/nutrition/getListData';
$route['webapi/nutritionFoodCat/(:any)'] = 'webapi/nutrition/getMemberByParentId/$1';
$route['webapi/nutritionFoodCatAdd'] = 'webapi/nutrition/addMember';
$route['webapi/nutritionFoodCatDelete'] = 'webapi/nutrition/removeMember';

//Food Category
$route['webapi/foodCategories'] = 'webapi/foodCategory/getGridRows';
$route['webapi/foodCategories/excel'] = 'webapi/foodCategory/getGridRowsExcel';
$route['webapi/foodCategory/get/(:any)'] = 'webapi/foodCategory/getById/$1';
$route['webapi/foodCategory/add'] = 'webapi/foodCategory/createData';
$route['webapi/foodCategory/edit'] = 'webapi/foodCategory/editData';
$route['webapi/foodCategory/delete'] = 'webapi/foodCategory/deleteData';
$route['webapi/foodCategory/search'] = 'webapi/foodCategory/getListData';
$route['webapi/foodCategoryFood/(:any)'] = 'webapi/foodCategory/getMemberByParentId/$1';
$route['webapi/foodCategoryFoodAdd'] = 'webapi/foodCategory/addMember';
$route['webapi/foodCategoryFoodDelete'] = 'webapi/foodCategory/removeMember';

$route['webapi/foods'] = 'webapi/food/getGridRows';
$route['webapi/foods/excel'] = 'webapi/food/getGridRowsExcel';
$route['webapi/food/get/(:any)'] = 'webapi/food/getById/$1';
$route['webapi/food/add'] = 'webapi/food/createData';
$route['webapi/food/edit'] = 'webapi/food/editData';
$route['webapi/food/delete'] = 'webapi/food/deleteData';
$route['webapi/food/search'] = 'webapi/food/getListData';


$route['webapi/profile/get'] = 'admin/getDataProfile';
$route['webapi/profile/edit'] = 'admin/editDataProfile';

$route['webapi/banner'] = 'admin/getBannerNews';
$route['webapi/banner/update'] = 'admin/updateBannerNews';


//for mobile apps
$route['api/appInfo'] = 'api/getAppInfo';
$route['api/user/checkExist'] = 'api/checkUserExist';
$route['api/signup'] = 'api/signup';
$route['api/login'] = 'api/login';
$route['api/resetPassword'] = 'api/resetPassword';
$route['api/user/updateBMI'] = 'api/updateUserBMI';
$route['api/user/updateProfile'] = 'api/updateUserProfile';
$route['api/user/profile'] = 'api/userInfo';
$route['api/user/bmi'] = 'api/userbmi';
$route['api/user/nutrition'] = 'api/userNutrition';
$route['api/user/changePassword'] = 'api/changeUserPassword';
$route['api/auth/validation'] = 'api/validateUserSession';
$route['api/banner/home'] = 'api/bannerHome';
$route['api/kek'] = 'api/kekList';
$route['api/kekDetail'] = 'api/kekDetail';
$route['api/nutritionDict'] = 'api/nutritionDictList';
$route['api/nutritionFoodCat'] = 'api/nutritionFoodCatList';
$route['api/calculateBMI'] = 'api/calculateBMIData';
$route['api/nutritionCalcData'] = 'api/nutritionCalcData';
$route['api/nutritionCalculated'] = 'api/nutritionCalculatedResult';



