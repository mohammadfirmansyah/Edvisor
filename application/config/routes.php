<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
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
// Controller default yang akan dijalankan saat tidak ada controller yang ditentukan dalam URL
$route['default_controller'] = 'Controller';  // Menentukan controller default, biasanya Home atau Dashboard

// Route untuk root URL (home), yang mengarah ke method index() dari controller Controller
$route[''] = "Controller/index";  // URL root ('') akan memanggil method index() di Controller

// Route khusus untuk previewForm yang memiliki tiga parameter dinamis
$route['previewForm/(:any)/(:any)/(:any)'] = 'Controller/previewForm/$1/$2/$3';  
// Menangkap tiga parameter dinamis untuk method previewForm()

// Route untuk tiga segmen dalam URL, misalnya controller/method/parameter
$route['(:any)/(:any)/(:any)'] = "Controller/$1/$2/$3"; 
// Menangkap tiga segmen URL dan memetakan ke controller, method, dan parameter

// Route untuk dua segmen dalam URL, misalnya controller/method
$route['(:any)/(:any)'] = "Controller/$1/$2"; 
// Menangkap dua segmen URL dan memetakan ke controller dan method

// Route untuk satu segmen dalam URL, misalnya controller saja
$route['(:any)'] = "Controller/$1"; 
// Menangkap satu segmen URL dan memetakan ke controller

// Jika tidak ada route yang cocok, maka akan menampilkan halaman 404
$route['404_override'] = 'Controller/show_404';  
// Menangani rute yang tidak terdefinisi, akan memanggil method show_404() dari controller

// Menentukan apakah dash (-) dalam URI harus diterjemahkan menjadi underscore (_)
// Jika FALSE, dash tidak akan diganti menjadi underscore.
$route['translate_uri_dashes'] = FALSE;  
// Mengatur apakah dash pada URL akan diterjemahkan menjadi underscore dalam nama controller/method