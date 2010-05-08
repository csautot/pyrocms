<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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
| 	www.your-site.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://www.codeigniter.com/user_guide/general/routing.html
*/

// Maintain admin routes
$route['blog/admin(:any)?'] = 'admin$1';

$route['blog/rss/all.rss'] = "rss/index";
$route['blog/rss/(:any).rss'] = "rss/category/$1";

$route['blog/category/(:any)'] = 'blog/category/$1';
$route['blog/archive/(:num)/(:num)'] = 'blog/archive/$1/$2';

$route['blog/(:any)'] = 'blog/view/$1';

$route['news/(:num)/(:num)/(:any)'] = "blog/view/$3";
$route['news/page/(:num)'] = "blog/index/$1";

$route['news/rss/all.rss'] = "rss/index";
$route['news/rss/(:any).rss'] = "rss/category/$1";

?>