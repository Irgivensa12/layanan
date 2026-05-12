<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['index'] = 'Controller/index';
$route['logout'] = 'Controller/logout';

$route['menu'] = 'Controller/menu';
$route['tiket_sim'] = 'Controller/tiket_sim';
$route['tiket_sarpras'] = 'Controller/tiket_sarpras';

$route['input'] = 'Controller/input';
$route['tambah_tiket_sarpras'] = 'Controller/tambah_tiket_sarpras';

$route['tambah_tiket_sim'] = 'Controller/tambah_tiket_sim';
$route['dashboard'] = 'Controller/dashboard';
$route['admin_sim'] = 'Controller/admin_sim';
$route['admin_sarpras'] = 'Controller/admin_sarpras';
$route['laporan_sim'] = 'Controller/laporan_sim';
$route['laporan_sarpras'] = 'Controller/laporan_sarpras';
