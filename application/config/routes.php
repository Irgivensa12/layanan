<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['index'] = 'Controller/index';
$route['logout'] = 'Controller/logout';

$route['menu'] = 'Controller/menu';

$route['input'] = 'Controller/input';
$route['tiket_sim'] = 'Controller/tiket_sim';
$route['tiket_sarpras'] = 'Controller/tiket_sarpras';
$route['tambah_tiket_sarpras'] = 'Controller/tambah_tiket_sarpras';
