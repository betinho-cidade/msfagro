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
        Route::post('/js_menu_aberto', 'PainelController@js_menu_aberto')->name('painel.js_menu_aberto');
        Route::post('/js_cnpj', 'PainelController@js_cnpj')->name('painel.js_cnpj');

        Route::get('/notificacao_cliente', 'PainelController@notificacao')->name('painel.notificacao');

        Route::group(['namespace' => 'Gestao'], function(){

            Route::group(['namespace' => 'Dashboard'], function(){
                Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
            });

            Route::group(['namespace' => 'Relatorio'], function(){
                Route::get('/gestao/relatorio', 'RelatorioController@index')->name('relatorio_gestao.index');
                Route::get('/gestao/relatorio/search', 'RelatorioController@search')->name('relatorio_gestao.search');
                Route::get('/gestao/relatorio/excell', 'RelatorioController@excell')->name('relatorio_gestao.excell');
                Route::get('/gestao/relatorio/pdf', 'RelatorioController@pdf')->name('relatorio_gestao.pdf');
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
                Route::post('/fazenda/{fazenda}/geomaps', 'FazendaController@geomaps')->name('fazenda.geomaps');
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

            Route::group(['namespace' => 'Googlemap'], function(){
                Route::get('/googlemap', 'GooglemapController@index')->name('googlemap.index');
                Route::get('/googlemap/list', 'GooglemapController@list')->name('googlemap.list');
                Route::get('/googlemap/{googlemap}', 'GooglemapController@show')->name('googlemap.show');
                Route::put('/googlemap/{googlemap}/update', 'GooglemapController@update')->name('googlemap.update');
            });            

            Route::group(['namespace' => 'Notificacao'], function(){
                Route::get('/notificacao', 'NotificacaoController@index')->name('notificacao.index');
                Route::get('/notificacao/create', 'NotificacaoController@create')->name('notificacao.create');
                Route::post('/notificacao/store', 'NotificacaoController@store')->name('notificacao.store');
                Route::get('/notificacao/{notificacao}', 'NotificacaoController@show')->name('notificacao.show');
                Route::put('/notificacao/{notificacao}/update', 'NotificacaoController@update')->name('notificacao.update');
                Route::get('/notificacao/{notificacao}/cliente_create', 'NotificacaoController@cliente_create')->name('notificacao.cliente_create');
                Route::put('/notificacao/{notificacao}/cliente_store', 'NotificacaoController@cliente_store')->name('notificacao.cliente_store');    
                Route::delete('/notificacao/{notificacao}/cliente_destroy/{cliente_notificacao}', 'NotificacaoController@cliente_destroy')->name('notificacao.cliente_destroy');  
                Route::delete('/notificacao/{notificacao}/destroy', 'NotificacaoController@destroy')->name('notificacao.destroy');
            });         
            
            Route::group(['namespace' => 'Lucro'], function(){
                Route::get('/lucro', 'LucroController@index')->name('lucro.index');
                Route::get('/lucro/create', 'LucroController@create')->name('lucro.create');
                Route::post('/lucro/store', 'LucroController@store')->name('lucro.store');
                Route::post('/lucro/refreshList', 'LucroController@refreshList')->name('lucro.refreshList');
                Route::get('/lucro/search', 'LucroController@search')->name('lucro.search');
                Route::get('/lucro/{lucro}', 'LucroController@show')->name('lucro.show');
                Route::put('/lucro/{lucro}/update', 'LucroController@update')->name('lucro.update');
                Route::delete('/lucro/{lucro}/destroy', 'LucroController@destroy')->name('lucro.destroy');
                Route::get('/lucro/{lucro}/download', 'LucroController@download')->name('lucro.download');
            });            

        });

        Route::group(['namespace' => 'Lancamento'], function(){

            Route::get('/lancamento', 'LancamentoController@index')->name('lancamento.index');
            Route::post('/lancamento/refreshList', 'LancamentoController@refreshList')->name('lancamento.refreshList');

            Route::group(['namespace' => 'Efetivo'], function(){
                Route::get('/efetivo', 'EfetivoController@index')->name('efetivo.index');
                Route::get('/efetivo/create', 'EfetivoController@create')->name('efetivo.create');
                Route::post('/efetivo/store', 'EfetivoController@store')->name('efetivo.store');
                Route::get('/efetivo/list', 'EfetivoController@list')->name('efetivo.list');
                Route::post('/efetivo/destroy_list', 'EfetivoController@destroy_list')->name('efetivo.destroy_list');
                Route::get('/efetivo/{efetivo}', 'EfetivoController@show')->name('efetivo.show');
                Route::put('/efetivo/{efetivo}/update', 'EfetivoController@update')->name('efetivo.update');
                Route::delete('/efetivo/{efetivo}/destroy', 'EfetivoController@destroy')->name('efetivo.destroy');
                Route::get('/efetivo/{efetivo}/download', 'EfetivoController@download')->name('efetivo.download');
            });

            Route::group(['namespace' => 'Movimentacao'], function(){
                Route::get('/movimentacao', 'MovimentacaoController@index')->name('movimentacao.index');
                Route::get('/movimentacao/create', 'MovimentacaoController@create')->name('movimentacao.create');
                Route::post('/movimentacao/store', 'MovimentacaoController@store')->name('movimentacao.store');
                Route::get('/movimentacao/list', 'MovimentacaoController@list')->name('movimentacao.list');
                Route::post('/movimentacao/destroy_list', 'MovimentacaoController@destroy_list')->name('movimentacao.destroy_list');
                Route::get('/movimentacao/{movimentacao}', 'MovimentacaoController@show')->name('movimentacao.show');
                Route::put('/movimentacao/{movimentacao}/update', 'MovimentacaoController@update')->name('movimentacao.update');
                Route::delete('/movimentacao/{movimentacao}/destroy', 'MovimentacaoController@destroy')->name('movimentacao.destroy');
                Route::get('/movimentacao/{movimentacao}/download', 'MovimentacaoController@download')->name('movimentacao.download');
            });

        });

        Route::group(['namespace' => 'Financeiro'], function(){

            Route::get('/financeiro', 'FinanceiroController@index')->name('financeiro.index');
            Route::get('/financeiro/list', 'FinanceiroController@list')->name('financeiro.list');
            Route::get('/financeiro/search', 'FinanceiroController@search')->name('financeiro.search');
        });

        Route::group(['namespace' => 'Relatorio'], function(){

            Route::get('/relatorio', 'RelatorioController@index')->name('relatorio.index');
            Route::get('/relatorio/search', 'RelatorioController@search')->name('relatorio.search');
            Route::get('/relatorio/excell', 'RelatorioController@excell')->name('relatorio.excell');
            Route::get('/relatorio/pdf', 'RelatorioController@pdf')->name('relatorio.pdf');
            Route::get('/relatorio/geomaps', 'RelatorioController@geomaps')->name('relatorio.geomaps');
        });        

    });

});


Route::group(['namespace' => 'Guest'], function(){

    Route::get('/download', 'DownloadController@download')->name('download');

    Route::group(['namespace' => 'ResetPassword'], function(){
        Route::get('/forget-password', 'ForgotPasswordController@getEmail')->name('forgot.password');
        Route::post('/forget-password', 'ForgotPasswordController@postEmail')->name('forgot.reset');

        Route::get('/reset-password/{token}', 'ResetPasswordController@getPassword')->name('reset.password');
        Route::post('/reset-password', 'ResetPasswordController@updatePassword')->name('reset');
    });

});


