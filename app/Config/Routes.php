<?php

namespace Config;

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
$routes->setDefaultController('LoginController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('login', 'LoginController::index');
$routes->post('login/check', 'LoginController::check');
$routes->get('login/forgot_password', 'LoginController::forgot_password');
$routes->post('login/reset_password', 'LoginController::reset_password');
$routes->get('login/daftar', 'LoginController::daftar');
$routes->post('login/registrasi', 'LoginController::registrasi');

$routes->group('dashboard', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'DashboardController::index');
    $routes->get('profile', 'DashboardController::profile');
    $routes->get('password_edit', 'DashboardController::password_edit');
    $routes->put('password_update', 'DashboardController::password_update');
    $routes->get('logout', 'DashboardController::logout');

    $routes->get('pegawai', 'DashboardController::pegawai');
    $routes->post('pegawai_list', 'DashboardController::pegawai_list');
    $routes->get('pegawai_add', 'DashboardController::pegawai_add');
    $routes->post('pegawai_store', 'DashboardController::pegawai_store');
    $routes->get('pegawai_edit/(:any)', 'DashboardController::pegawai_edit/$1');
    $routes->put('pegawai_update', 'DashboardController::pegawai_update');
    $routes->delete('pegawai_delete', 'DashboardController::pegawai_delete');

    $routes->get('cabang', 'DashboardController::cabang');
    $routes->post('cabang_list', 'DashboardController::cabang_list');
    $routes->get('cabang_add', 'DashboardController::cabang_add');
    $routes->post('cabang_store', 'DashboardController::cabang_store');
    $routes->get('cabang_edit/(:any)', 'DashboardController::cabang_edit/$1');
    $routes->put('cabang_update', 'DashboardController::cabang_update');
    $routes->delete('cabang_delete', 'DashboardController::cabang_delete');

    $routes->get('kendaraan', 'DashboardController::kendaraan');
    $routes->post('kendaraan_list', 'DashboardController::kendaraan_list');
    $routes->get('kendaraan_add', 'DashboardController::kendaraan_add');
    $routes->post('kendaraan_store', 'DashboardController::kendaraan_store');
    $routes->get('kendaraan_edit/(:any)', 'DashboardController::kendaraan_edit/$1');
    $routes->put('kendaraan_update', 'DashboardController::kendaraan_update');
    $routes->delete('kendaraan_delete', 'DashboardController::kendaraan_delete');

    $routes->get('proyek', 'DashboardController::proyek');
    $routes->post('proyek_list', 'DashboardController::proyek_list');
    $routes->get('proyek_add', 'DashboardController::proyek_add');
    $routes->post('proyek_store', 'DashboardController::proyek_store');
    $routes->get('proyek_edit/(:any)', 'DashboardController::proyek_edit/$1');
    $routes->put('proyek_update', 'DashboardController::proyek_update');
    $routes->delete('proyek_delete', 'DashboardController::proyek_delete');

    $routes->get('kelola_kegiatan/(:any)', 'DashboardController::kelola_kegiatan/$1');
    $routes->post('kelola_kegiatan_list/(:any)', 'DashboardController::kelola_kegiatan_list/$1');
    $routes->get('kelola_kegiatan_add/(:any)', 'DashboardController::kelola_kegiatan_add/$1');
    $routes->post('kelola_kegiatan_store', 'DashboardController::kelola_kegiatan_store');
    $routes->get('kelola_kegiatan_edit/(:any)', 'DashboardController::kelola_kegiatan_edit/$1');
    $routes->put('kelola_kegiatan_update', 'DashboardController::kelola_kegiatan_update');
    $routes->delete('kelola_kegiatan_delete', 'DashboardController::kelola_kegiatan_delete');

    $routes->get('kegiatan', 'DashboardController::kegiatan');
    $routes->post('kegiatan_list', 'DashboardController::kegiatan_list');
    $routes->get('kegiatan_add', 'DashboardController::kegiatan_add');
    $routes->post('kegiatan_store', 'DashboardController::kegiatan_store');
    $routes->get('kegiatan_edit/(:any)', 'DashboardController::kegiatan_edit/$1');
    $routes->put('kegiatan_update', 'DashboardController::kegiatan_update');
    $routes->delete('kegiatan_delete', 'DashboardController::kegiatan_delete');

    $routes->get('users', 'DashboardController::users');
    $routes->post('users_list', 'DashboardController::users_list');
    $routes->get('users_add', 'DashboardController::users_add');
    $routes->post('users_store', 'DashboardController::users_store');
    $routes->get('users_edit/(:any)', 'DashboardController::users_edit/$1');
    $routes->put('users_update', 'DashboardController::users_update');
    $routes->delete('users_delete', 'DashboardController::users_delete');

    $routes->get('operasional', 'DashboardController::operasional');
    $routes->post('operasional_list', 'DashboardController::operasional_list');
    $routes->get('operasional_add', 'DashboardController::operasional_add');
    $routes->post('operasional_store', 'DashboardController::operasional_store');
    $routes->get('operasional_edit/(:any)', 'DashboardController::operasional_edit/$1');
    $routes->put('operasional_update', 'DashboardController::operasional_update');
    $routes->delete('operasional_delete', 'DashboardController::operasional_delete');

    $routes->get('transaksi', 'DashboardController::transaksi');
    $routes->post('transaksi_list', 'DashboardController::transaksi_list');
    $routes->get('transaksi_add', 'DashboardController::transaksi_add');
    $routes->post('transaksi_store', 'DashboardController::transaksi_store');
    $routes->get('transaksi_edit/(:any)', 'DashboardController::transaksi_edit/$1');
    $routes->put('transaksi_update', 'DashboardController::transaksi_update');
    $routes->delete('transaksi_delete', 'DashboardController::transaksi_delete');

    $routes->get('dana', 'DashboardController::dana');
    $routes->post('dana_list', 'DashboardController::dana_list');
    $routes->get('dana_add', 'DashboardController::dana_add');
    $routes->post('dana_store', 'DashboardController::dana_store');
    $routes->get('dana_edit/(:any)', 'DashboardController::dana_edit/$1');
    $routes->put('dana_update', 'DashboardController::dana_update');
    $routes->delete('dana_delete', 'DashboardController::dana_delete');

    $routes->get('dana_keluar', 'DashboardController::dana_keluar');
    $routes->post('dana_keluar_list', 'DashboardController::dana_keluar_list');
    $routes->get('dana_keluar_add', 'DashboardController::dana_keluar_add');
    $routes->post('dana_keluar_store', 'DashboardController::dana_keluar_store');
    $routes->get('dana_keluar_edit/(:any)', 'DashboardController::dana_keluar_edit/$1');
    $routes->put('dana_keluar_update', 'DashboardController::dana_keluar_update');
    $routes->delete('dana_keluar_delete', 'DashboardController::dana_keluar_delete');

    $routes->get('dokumentasi', 'DashboardController::dokumentasi');
    $routes->post('dokumentasi_list', 'DashboardController::dokumentasi_list');
    $routes->get('dokumentasi_add', 'DashboardController::dokumentasi_add');
    $routes->get('dokumentasi_read', 'DashboardController::dokumentasi_read');
    $routes->get('dokumentasi_getedit/(:any)', 'DashboardController::dokumentasi_getedit/$1');
    $routes->post('dokumentasi_store', 'DashboardController::dokumentasi_store');
    $routes->get('dokumentasi_edit/(:any)', 'DashboardController::dokumentasi_edit/$1');
    $routes->put('dokumentasi_update', 'DashboardController::dokumentasi_update');
    $routes->delete('dokumentasi_delete', 'DashboardController::dokumentasi_delete');

    $routes->get('penggunaan', 'DashboardController::penggunaan');
    $routes->post('penggunaan_list', 'DashboardController::penggunaan_list');
    $routes->get('penggunaan_add', 'DashboardController::penggunaan_add');
    $routes->post('penggunaan_store', 'DashboardController::penggunaan_store');
    $routes->get('penggunaan_edit/(:any)', 'DashboardController::penggunaan_edit/$1');
    $routes->put('penggunaan_update', 'DashboardController::penggunaan_update');
    $routes->delete('penggunaan_delete', 'DashboardController::penggunaan_delete');
    $routes->get('pemakaian_read', 'DashboardController::pemakaian_read');
    $routes->get('pemakaian_getedit/(:any)', 'DashboardController::pemakaian_getedit/$1');

    $routes->get('pemakaian_bbm', 'DashboardController::pemakaian_bbm');
    $routes->post('pemakaian_bbm_list', 'DashboardController::pemakaian_bbm_list');
    $routes->get('pemakaian_bbm_add', 'DashboardController::pemakaian_bbm_add');
    $routes->post('pemakaian_bbm_store', 'DashboardController::pemakaian_bbm_store');
    $routes->get('pemakaian_bbm_edit/(:any)', 'DashboardController::pemakaian_bbm_edit/$1');
    $routes->put('pemakaian_bbm_update', 'DashboardController::pemakaian_bbm_update');
    $routes->delete('pemakaian_bbm_delete', 'DashboardController::pemakaian_bbm_delete');
    $routes->get('pemakaian_bbm_read', 'DashboardController::pemakaian_bbm_read');
    $routes->get('pemakaian_bbm_getedit/(:any)', 'DashboardController::pemakaian_bbm_getedit/$1');

    $routes->get('material', 'DashboardController::material');
    $routes->post('material_list', 'DashboardController::material_list');
    $routes->get('material_add', 'DashboardController::material_add');
    $routes->post('material_store', 'DashboardController::material_store');
    $routes->get('material_edit/(:any)', 'DashboardController::material_edit/$1');
    $routes->put('material_update', 'DashboardController::material_update');
    $routes->delete('material_delete', 'DashboardController::material_delete');
    $routes->get('material_read', 'DashboardController::material_read');
    $routes->get('material_getedit/(:any)', 'DashboardController::material_getedit/$1');

    $routes->get('jenis_material', 'DashboardController::jenis_material');
    $routes->post('jenis_material_list', 'DashboardController::jenis_material_list');
    $routes->get('jenis_material_add', 'DashboardController::jenis_material_add');
    $routes->post('jenis_material_store', 'DashboardController::jenis_material_store');
    $routes->get('jenis_material_edit/(:any)', 'DashboardController::jenis_material_edit/$1');
    $routes->put('jenis_material_update', 'DashboardController::jenis_material_update');
    $routes->delete('jenis_material_delete', 'DashboardController::jenis_material_delete');
    $routes->get('jenis_material_read', 'DashboardController::jenis_material_read');
    $routes->get('jenis_material_getedit/(:any)', 'DashboardController::jenis_material_getedit/$1');

    $routes->get('bbm', 'DashboardController::bbm');
    $routes->post('bbm_list', 'DashboardController::bbm_list');
    $routes->get('bbm_add', 'DashboardController::bbm_add');
    $routes->post('bbm_store', 'DashboardController::bbm_store');
    $routes->get('bbm_edit/(:any)', 'DashboardController::bbm_edit/$1');
    $routes->put('bbm_update', 'DashboardController::bbm_update');
    $routes->delete('bbm_delete', 'DashboardController::bbm_delete');

    $routes->get('pegawai_lap', 'DashboardController::pegawai_lap');
    $routes->post('pegawai_lap_list', 'DashboardController::pegawai_lap_list');

    $routes->get('cabang_lap', 'DashboardController::cabang_lap');
    $routes->post('cabang_lap_list', 'DashboardController::cabang_lap_list');

    $routes->get('proyek_lap', 'DashboardController::proyek_lap');
    $routes->post('proyek_lap_list', 'DashboardController::proyek_lap_list');

    $routes->get('kendaraan_lap', 'DashboardController::kendaraan_lap');
    $routes->post('kendaraan_lap_list', 'DashboardController::kendaraan_lap_list');

    $routes->get('dokumentasi_lap', 'DashboardController::dokumentasi_lap');
    $routes->post('dokumentasi_lap_list/(:any)', 'DashboardController::dokumentasi_lap_list/$1');

    $routes->get('penggunaan_lap', 'DashboardController::penggunaan_lap');
    $routes->post('penggunaan_lap_list', 'DashboardController::penggunaan_lap_list');

    $routes->get('material_lap', 'DashboardController::material_lap');
    $routes->post('material_lap_list/(:any)', 'DashboardController::material_lap_list/$1');

    $routes->get('operasional_lap', 'DashboardController::operasional_lap');
    $routes->post('operasional_lap_list/(:any)', 'DashboardController::operasional_lap_list/$1');
    $routes->post('operasional_lap_list_foot/(:any)', 'DashboardController::operasional_lap_list_foot/$1');

    $routes->get('dana_lap', 'DashboardController::dana_lap');
    $routes->post('dana_lap_list/(:any)', 'DashboardController::dana_lap_list/$1');
    
    $routes->get('dana_keluar_lap', 'DashboardController::dana_keluar_lap');
    $routes->post('dana_keluar_lap_list/(:any)', 'DashboardController::dana_keluar_lap_list/$1');
    
    $routes->get('pemakaian_bbm_lap', 'DashboardController::pemakaian_bbm_lap');
    $routes->post('pemakaian_bbm_lap_list/(:any)', 'DashboardController::pemakaian_bbm_lap_list/$1');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}