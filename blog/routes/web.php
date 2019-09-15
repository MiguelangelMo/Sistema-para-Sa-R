<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    //->name('login');
});

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main'); //a traves del name(main) lo que hicimos pues apodarla main para route de LoginController

    Route::group(['middleware' => ['admnistrador']], function () {
        //Categorias
        Route::get('/categoria', 'CategoriaCotroller@index');
        Route::post('/categoria/registrar', 'CategoriaCotroller@store');
        Route::put('/categoria/actualizar', 'CategoriaCotroller@update');
        Route::put('/categoria/desactivar', 'CategoriaCotroller@desactivar');
        Route::put('/categoria/activar', 'CategoriaCotroller@activar');
        Route::get('/categoria/n@m1re_ca2egor1a', 'CategoriaCotroller@selectCategoria');

        //Articulos
        Route::get('/articulo', 'ArticuloController@index');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/ListarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf');

        //Personas / Clientes
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');

        //Proveedor
        Route::get('/proveedor', 'ProveedorController@index');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');

        //Roles
        Route::get('/roles', 'RolController@index');
        Route::get('/roles/seleccion_roles', 'RolController@selectRol');

        //Usuario
        Route::get('/usuario', 'UserController@index');
        Route::post('/usuario/registrar', 'UserController@store');
        Route::put('/usuario/actualizar', 'UserController@update');
        Route::put('/usuario/desactivar', 'UserController@desactivar');
        Route::put('/usuario/activar', 'UserController@activar');

        //Ingresos
        Route::get('/ingreso', 'IngresoController@index');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');

        //Ventas
        Route::get('/venta', 'VentaController@index');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/ventaPdf/{id}', 'VentaController@pdf');
    });

    Route::group(['middleware' => ['almacenista']], function () {
        //Categorias
        Route::get('/categoria', 'CategoriaCotroller@index');
        Route::post('/categoria/registrar', 'CategoriaCotroller@store');
        Route::put('/categoria/actualizar', 'CategoriaCotroller@update');
        Route::put('/categoria/desactivar', 'CategoriaCotroller@desactivar');
        Route::put('/categoria/activar', 'CategoriaCotroller@activar');
        Route::get('/categoria/n@m1re_ca2egor1a', 'CategoriaCotroller@selectCategoria');

        //Articulos
        Route::get('/articulo', 'ArticuloController@index');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/ListarArticulo', 'ArticuloController@listarArticulo');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/articulo/actualizar', 'ArticuloController@update');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::put('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');

        //Proveedor
        Route::get('/proveedor', 'ProveedorController@index');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');

        //Ingresos
        Route::get('/ingreso', 'IngresoController@index');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
    });

    Route::group(['middleware' => ['vendedor']], function () {
        //Personas / Clientes
        Route::get('/cliente', 'ClienteController@index');
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::put('/cliente/actualizar', 'ClienteController@update');

        //Ventas
        Route::get('/venta', 'VentaController@index');
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::post('/venta/registrar', 'VentaController@store');
        Route::put('/venta/desactivar', 'VentaController@desactivar');
        Route::get('/venta/ventaPdf/{id}', 'VentaController@pdf');

        //Articulo
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
    });
});

//Route::get('/home', 'HomeController@index')->name('home');
