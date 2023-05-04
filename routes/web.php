<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/logout', 'HomeController@logout')->name('logout')->middleware('auth');

// Route::get('/user', 'HomeController@user')->name('user')->middleware('auth');
// Route::put('/user/update', 'HomeController@userUpdate')->name('user.update')->middleware('auth');
// Route::post('/user/js_viacep', 'HomeController@js_viacep')->name('user.js_viacep');


Route::middleware(['auth'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['namespace' => 'Painel'], function(){

        Route::get('/', 'PainelController@index')->name('painel');
        Route::post('/js_viacep', 'PainelController@js_viacep')->name('painel.js_viacep');

        Route::group(['namespace' => 'Gestao'], function(){

            Route::group(['namespace' => 'Dashboard'], function(){
                Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
            });

        });

        Route::group(['namespace' => 'Cadastro'], function(){

            Route::group(['namespace' => 'Usuario'], function(){
                Route::get('/usuario', 'UsuarioController@index')->name('usuario.index');
                Route::get('/usuario/create', 'UsuarioController@create')->name('usuario.create');
                Route::post('/usuario/store', 'UsuarioController@store')->name('usuario.store');
                Route::get('/usuario/{usuario}', 'UsuarioController@show')->name('usuario.show');
                Route::put('/usuario/{usuario}/update', 'UsuarioController@update')->name('usuario.update');
                Route::delete('/usuario/{usuario}/destroy', 'UsuarioController@destroy')->name('usuario.destroy');
            });

            Route::group(['namespace' => 'UsuarioLogado'], function(){
                Route::get('/usuario_logado/{user}', 'UsuarioLogadoController@show')->name('usuario_logado.show');
                Route::put('/usuario_logado/{user}/update', 'UsuarioLogadoController@update')->name('usuario_logado.update');
            });

            Route::group(['namespace' => 'Categoria'], function(){
                Route::get('/categoria', 'CategoriaController@index')->name('categoria.index');
                Route::get('/categoria/create', 'CategoriaController@create')->name('categoria.create');
                Route::post('/categoria/store', 'CategoriaController@store')->name('categoria.store');
                Route::get('/categoria/{categoria}', 'CategoriaController@show')->name('categoria.show');
                Route::put('/categoria/{categoria}/update', 'CategoriaController@update')->name('categoria.update');
                Route::delete('/categoria/{categoria}/destroy', 'CategoriaController@destroy')->name('categoria.destroy');
            });

            Route::group(['namespace' => 'Aliquota'], function(){
                Route::get('/aliquota', 'AliquotaController@index')->name('aliquota.index');
                Route::get('/aliquota/create', 'AliquotaController@create')->name('aliquota.create');
                Route::post('/aliquota/store', 'AliquotaController@store')->name('aliquota.store');
                Route::get('/aliquota/{aliquota}', 'AliquotaController@show')->name('aliquota.show');
                Route::put('/aliquota/{aliquota}/update', 'AliquotaController@update')->name('aliquota.update');
                Route::delete('/aliquota/{aliquota}/destroy', 'AliquotaController@destroy')->name('aliquota.destroy');
            });

            Route::group(['namespace' => 'Cliente'], function(){
                Route::get('/cliente', 'ClienteController@index')->name('cliente.index');
                Route::get('/cliente/create', 'ClienteController@create')->name('cliente.create');
                Route::post('/cliente/store', 'ClienteController@store')->name('cliente.store');
                Route::get('/cliente/{cliente}', 'ClienteController@show')->name('cliente.show');
                Route::put('/cliente/{cliente}/update', 'ClienteController@update')->name('cliente.update');
                Route::delete('/cliente/{cliente}/destroy', 'ClienteController@destroy')->name('cliente.destroy');
            });

            Route::group(['namespace' => 'FormaPagamento'], function(){
                Route::get('/forma_pagamento', 'FormaPagamentoController@index')->name('forma_pagamento.index');
                Route::get('/forma_pagamento/create', 'FormaPagamentoController@create')->name('forma_pagamento.create');
                Route::post('/forma_pagamento/store', 'FormaPagamentoController@store')->name('forma_pagamento.store');
                Route::get('/forma_pagamento/{forma_pagamento}', 'FormaPagamentoController@show')->name('forma_pagamento.show');
                Route::put('/forma_pagamento/{forma_pagamento}/update', 'FormaPagamentoController@update')->name('forma_pagamento.update');
                Route::delete('/forma_pagamento/{forma_pagamento}/destroy', 'FormaPagamentoController@destroy')->name('forma_pagamento.destroy');
            });

            Route::group(['namespace' => 'Fazenda'], function(){
                Route::get('/fazenda', 'FazendaController@index')->name('fazenda.index');
                Route::get('/fazenda/create', 'FazendaController@create')->name('fazenda.create');
                Route::post('/fazenda/store', 'FazendaController@store')->name('fazenda.store');
                Route::get('/fazenda/{fazenda}', 'FazendaController@show')->name('fazenda.show');
                Route::put('/fazenda/{fazenda}/update', 'FazendaController@update')->name('fazenda.update');
                Route::delete('/fazenda/{fazenda}/destroy', 'FazendaController@destroy')->name('fazenda.destroy');
            });

            Route::group(['namespace' => 'Empresa'], function(){
                Route::get('/empresa', 'EmpresaController@index')->name('empresa.index');
                Route::get('/empresa/create', 'EmpresaController@create')->name('empresa.create');
                Route::post('/empresa/store', 'EmpresaController@store')->name('empresa.store');
                Route::get('/empresa/{empresa}', 'EmpresaController@show')->name('empresa.show');
                Route::put('/empresa/{empresa}/update', 'EmpresaController@update')->name('empresa.update');
                Route::delete('/empresa/{empresa}/destroy', 'EmpresaController@destroy')->name('empresa.destroy');
            });

            Route::group(['namespace' => 'Produtor'], function(){
                Route::get('/produtor', 'ProdutorController@index')->name('produtor.index');
                Route::get('/produtor/create', 'ProdutorController@create')->name('produtor.create');
                Route::post('/produtor/store', 'ProdutorController@store')->name('produtor.store');
                Route::get('/produtor/{produtor}', 'ProdutorController@show')->name('produtor.show');
                Route::put('/produtor/{produtor}/update', 'ProdutorController@update')->name('produtor.update');
                Route::delete('/produtor/{produtor}/destroy', 'ProdutorController@destroy')->name('produtor.destroy');
            });

        });

        Route::group(['namespace' => 'Movimentacao'], function(){

            Route::group(['namespace' => 'Lancamento'], function(){
                Route::get('/lancamento', 'LancamentoController@index')->name('lancamento.index');
                Route::get('/lancamento/create', 'LancamentoController@create')->name('lancamento.create');
                Route::post('/lancamento/store_MG', 'LancamentoController@store_MG')->name('lancamento.store_MG');
                Route::post('/lancamento/store_MF', 'LancamentoController@store_MF')->name('lancamento.store_MF');
                Route::get('/lancamento/list', 'LancamentoController@list')->name('lancamento.list');
                //Route::post('/lancamento/list_destroy', 'LancamentoController@list_destroy')->name('lancamento.list_destroy');
                //Route::get('/lancamento/{lancamento}', 'LancamentoController@show')->name('lancamento.show');
                //Route::put('/lancamento/{lancamento}/update', 'LancamentoController@update')->name('lancamento.update');
                //Route::delete('/lancamento/{lancamento}/destroy', 'LancamentoController@destroy')->name('lancamento.destroy');
            });

        });

    });

});


Route::group(['namespace' => 'Guest'], function(){

    Route::group(['namespace' => 'ResetPassword'], function(){
        Route::get('/forget-password', 'ForgotPasswordController@getEmail')->name('forgot.password');
        Route::post('/forget-password', 'ForgotPasswordController@postEmail')->name('forgot.reset');

        Route::get('/reset-password/{token}', 'ResetPasswordController@getPassword')->name('reset.password');
        Route::post('/reset-password', 'ResetPasswordController@updatePassword')->name('reset');
    });

});


