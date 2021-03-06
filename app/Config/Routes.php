<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Home::notFound');
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// 路由配置
// 基本类
$routes->post('home', 'Home::index');
$routes->post('register', 'User::register');
$routes->post('login', 'Login::login');
$routes->post('logout', 'Login::logout');

// 用户类
$routes->get('user', 'User::userList');
$routes->get('user/getUserInfo', 'User::getUserInfo');
$routes->put('user', 'User::updateUserInfo');
$routes->put('user/changeStatus', 'User::changeStatus');
$routes->put('user/updateAdminInfo', 'User::updateAdminInfo');
$routes->put('user/normalUserList', 'User::normalUserList');

// 权限类
$routes->post('rule', 'Rule::addRule');
$routes->get('menu', 'Rule::getMenu');
$routes->get('rule', 'Rule::getRule');
$routes->get('role', 'Rule::getRole');
$routes->get('ruleById', 'Rule::getRuleByRoleId');
$routes->put('rule', 'Rule::changeRoleByRule');
$routes->delete('rule', 'Rule::delRule');
$routes->get('ruleDetail', 'Rule::ruleDetail');
$routes->put('editRule', 'Rule::editRule');

// 上传类
$routes->post('upload', 'Upload::upload');

// 房屋类
$routes->post('house', 'Data::insertHouse');
$routes->get('house', 'Data::getHouseList');
$routes->put('house', 'Data::updateHouseData');
$routes->get('houseInfo', 'Data::getInfoById');
$routes->get('home', 'Data::getHomeList');

// 系统类
$routes->get('getParameterInit', 'System::getParameterInit');
$routes->get('getParameterList', 'System::getParameterList');
$routes->get('getParameterDetail', 'System::getParameterDetail');
$routes->put('parameter', 'System::editParameter');
$routes->post('parameter', 'System::addParameter');
$routes->delete('parameter', 'System::deleteRecord');
$routes->get('carousel', 'System::getCarousel');
$routes->get('carouselInfo', 'System::getInfoCarouselById');
$routes->put('carousel', 'System::updateCarousel');
$routes->post('carousel', 'System::addCarousel');
$routes->get('carouselIndex', 'System::getIndexCarousel');

// 客户类
$routes->post('homeCustomer', 'HomeCustomer::add');
$routes->get('homeCustomer', 'HomeCustomer::getList');
$routes->get('homeCustomerDetail', 'HomeCustomer::detail');
$routes->put('homeCustomer', 'HomeCustomer::updateInfo');

// websocket类
$routes->get('bind', 'Websocket::bind');

// 短信类
$routes->post('sendSmsCode','System::sendSmsCode');
/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
