<?php
/**
 * Routes - all standard routes are defined here.
 *
 * Nova Framework
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.2
 */

/** Create alias for Router. */
use Core\Router;
use Helpers\Hooks;

/** Get the Router instance. */
$router = Router::getInstance();

/** Define static routes. */

// Default Routing
Router::any('', 'App\Controllers\Welcome@index');
Router::any('subpage', 'App\Controllers\Welcome@subPage');
Router::any('admin/(:any)(/(:any)(/(:any)(/(:any))))', 'App\Controllers\Demo@test');
/** End default routes */

/** Auth Routing **/
Router::any('Register', 'App\Controllers\Auth@register');
Router::any('activate/username/(:any)/key/(:any)', 'App\Controllers\Auth@activate');
Router::any('Forgot-Password', 'App\Controllers\Auth@forgotPassword');
Router::any('resetpassword/username/(:any)/key/(:any)', 'App\Controllers\Auth@resetPassword');
Router::any('Resend-Activation-Email', 'App\Controllers\Auth@resendActivation');

Router::any('Login', 'App\Controllers\Auth@login');
Router::any('Logout', 'App\Controllers\Auth@logout');
Router::any('Settings', 'App\Controllers\Auth@settings');
Router::any('Change-Email', 'App\Controllers\Auth@changeEmail');
Router::any('Change-Password', 'App\Controllers\Auth@changePassword');

Router::any('Edit-Profile','App\Controllers\Members@editProfile');
Router::any('Privacy-Settings','App\Controllers\Members@privacy');
Router::any('Account-Settings','App\Controllers\Members@account');
/** End Auth Routing **/

/** Live Checks */
Router::any('LiveCheckEmail', 'App\Controllers\LiveCheck@emailCheck');
Router::any('LiveCheckUserName', 'App\Controllers\LiveCheck@userNameCheck');
/** End Live Checks **/

/** Member Routing **/
Router::any('Members','App\Controllers\Members@members');
Router::any('Members/(:any)','App\Controllers\Members@members');
Router::any('Members/(:any)/(:any)','App\Controllers\Members@members');
Router::any('Online-Members','App\Controllers\Members@online');
Router::any('Profile/(:any)','App\Controllers\Members@viewProfile');
/** End Member Routing **/

/** Admin Panel Routing **/
Router::any('AdminPanel', 'App\Controllers\AdminPanel@Dashboard');
Router::any('AdminPanel-Users', 'App\Controllers\AdminPanel@Users');
Router::any('AdminPanel-Users/(:any)', 'App\Controllers\AdminPanel@Users');
Router::any('AdminPanel-Users/(:any)/(:any)', 'App\Controllers\AdminPanel@Users');
Router::any('AdminPanel-User/(:any)', 'App\Controllers\AdminPanel@User');
Router::any('AdminPanel-Groups', 'App\Controllers\AdminPanel@Groups');
Router::any('AdminPanel-Group/(:any)', 'App\Controllers\AdminPanel@Group');
Router::any('AdminPanel-MassEmail', 'App\Controllers\AdminPanel@MassEmail');
/** End Admin Panel Routing **/

/** Module routes. */
$hooks = Hooks::get();
$hooks->run('routes');
/** End Module routes. */

/** If no route found. */
Router::error('Core\Error@index');

/** Execute matched routes. */
$router->dispatch();
