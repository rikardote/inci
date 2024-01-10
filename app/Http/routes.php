<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('pdf', 'PdfController@invoice');
Route::get('borrados', 'TestController@test');


Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::resource('employees', 'EmployeesController');

    Route::get('employees/{num_empleado}/destroy', [
        'uses' => 'EmployeesController@destroy',
        'as' => 'employees.destroy'
    ]);
    Route::PATCH('employees/{num_empleado}/editar', [
        'uses' => 'EmployeesController@capt_update',
        'as' => 'employees.capt_update'
    ]);


    Route::resource('deparments', 'DeparmentsController');
    Route::get('deparments/{code}/destroy', [
        'uses' => 'DeparmentsController@destroy',
        'as' => 'deparments.destroy'
    ]);
    Route::post('employees/search', [
        'uses' => 'SearchEmpleados2Controller@index',
        'as' => 'employees.search'
    ]);

    Route::resource('codigosdeincidencias', 'CodigosDeIncidenciasController');
    Route::get('codigosdeincidencias/{code}/destroy', [
        'uses' => 'CodigosDeIncidenciasController@destroy',
        'as' => 'codigosdeincidencias.destroy'
    ]);
    Route::resource('horarios', 'HorariosController');
    Route::get('horarios/{id}/destroy', [
        'uses' => 'HorariosController@destroy',
        'as' => 'horarios.destroy'
    ]);
    Route::resource('puestos', 'PuestosController');
    Route::get('puestos/{id}/destroy', [
        'uses' => 'PuestosController@destroy',
        'as' => 'puestos.destroy'
    ]);

    Route::resource('periodos', 'PeriodosController');
    Route::get('periodos/{id}/destroy', [
        'uses' => 'PeriodosController@destroy',
        'as' => 'periodos.destroy'
    ]);

    Route::resource('qnas', 'QnasController');
    Route::get('qnas/{id}/destroy', [
        'uses' => 'QnasController@destroy',
        'as' => 'qnas.destroy'
    ]);
    Route::get('qnas/{id}/condicion', [
        'uses' => 'QnasController@condicion',
        'as' => 'qnas.condicion'
    ]);


    Route::resource('users', 'UsersController');
    Route::get('users/{id}/destroy', [
        'uses' => 'UsersController@destroy',
        'as' => 'user.destroy'
    ]);
    Route::get('users/{id}/change', [
        'uses' => 'UsersController@change',
        'as' => 'user.change'
    ]);
    Route::patch('users/{id}/change', [
        'uses' => 'UsersController@change_store',
        'as' => 'user.change.store'
    ]);

    Route::post('incidencias/search', [
        'uses' => 'SearchEmpleadosController@index',
        'as' => 'empleados.search'
    ]);
    Route::get('incidencias/{num_empleado}/{qna_id}', [
        'uses' => 'IncidenciasController@create',
        'as' => 'admin.incidencias.create'
    ]);
    Route::post('/incidencias', [
        'uses' => 'IncidenciasController@store',
        'as' => 'admin.incidencias.store'
    ]);

    Route::get('/incidencias/{num_empleado}', [
        'uses' => 'IncidenciasController@show',
        'as' => 'admin.incidencias.show'
    ]);


    Route::get('/', 'HomeController@index');

    Route::get('/home', [
        'uses' => 'HomeController@index',
        'as' => 'home.index'
    ]);
    Route::get('/dashboard', [
        'uses' => 'DashboardController@index',
        'as' => 'dashboard.index'
    ]);

    ///REPORTES
    Route::get('/reporte/general/', [
        'uses' => 'ReportsController@general',
        'as' => 'reports.general'
    ]);
    Route::get('/reporte/diario/', [
        'uses' => 'ReportsController@diario',
        'as' => 'reports.diario'
    ]);
    Route::post('/reporte/diario/', [
        'uses' => 'ReportsController@diario_post',
        'as' => 'reports.diario.post'
    ]);
    Route::post('/reporte/general/{dpto_code}', [
        'uses' => 'ReportsController@general_show',
        'as' => 'reports.general.show'
    ]);
    Route::get('/reporte/empleado/', [
        'uses' => 'ReportsController@empleado',
        'as' => 'reports.general.empleado'
    ]);
    Route::post('reporte/search', [
        'uses' => 'SearchReporteEmpleadosController@index',
        'as' => 'reportes.empleado.search'
    ]);
    Route::post('/reporte/general/{dpto_code}', [
        'uses' => 'ReportsController@general_show',
        'as' => 'reports.general.show'
    ]);
    Route::post('/reporte/empleado/{num_empleado}', [
        'uses' => 'ReportsController@empleado_show',
        'as' => 'reports.empleado.show'
    ]);
    Route::post('/reporte/empleado_qna/{num_empleado}', [
        'uses' => 'ReportsController@empleado_qna',
        'as' => 'reports.empleado.qna_show'
    ]);
    Route::get('/reporte/sin-derecho/', [
        'uses' => 'ReportsController@sinDerecho',
        'as' => 'reports.sinderecho'
    ]);
    Route::post('/reporte/sin-derecho-dpto/{dpto}', [
        'uses' => 'ReportsController@getsinDerecho',
        'as' => 'reports.sinderecho_show'
    ]);
    Route::get('/reporte/sin-derecho-dpto/{fecha_inicial}/{fecha_final}/{num_empleado}/show', [
        'uses' => 'ReportsController@show_incidenciasEmpleados',
        'as' => 'reports.show_incidenciasEmpleados'
    ]);
    Route::get('/reporte/pdf/{qna_id}/{dpto}', [
        'uses' => 'ReportsController@reporte_pdf',
        'as' => 'reporte.pdf'
    ]);
    Route::get('/reporte/pdf/{qna_id}/{dpto}/diario', [
        'uses' => 'ReportsController@reporte_pdf_diario',
        'as' => 'reporte.pdf.diario'
    ]);
    Route::get('/reporte/licencias', [
        'uses' => 'ReportsController@licencias',
        'as' => 'reports.licencias'
    ]);
    Route::post('/reporte/licencias', [
        'uses' => 'ReportsController@postLicencias',
        'as' => 'reports.licencias_exp'
    ]);
    Route::get('/reporte/licencias-pdf/{fecha_inicial}/{fecha_final}', [
        'uses' => 'ReportsController@reporte_licencias_pdf',
        'as' => 'reporte.licencias_pdf'
    ]);
    Route::get('/reporte/ausentismo', [
        'uses' => 'ReportsController@ausentismo',
        'as' => 'reports.ausentismo'
    ]);
    Route::post('reporte/ausentismo/show', [
        'uses' => 'ReportsController@ausentismo_centro',
        'as' => 'reporte.ausentismo.centro'
    ]);
    Route::get('reporte/ausentismo/getincidenciasbycode/{code}/{fecha_inicial}/{fecha_final}/{dpto_id}', [
        'uses' => 'ReportsController@ausentismo_incidencias',
        'as' => 'reporte.ausentismo.incidencias'
    ]);
    Route::get('/reporte/pendientes', [
        'uses' => 'ReportsController@pendientes',
        'as' => 'reports.pendientes'
    ]);
    Route::post('/reporte/pendientes/{dpto_code}', [
        'uses' => 'ReportsController@pendientes_show',
        'as' => 'reports.pendientes.show'
    ]);
    Route::get('/reporte/pendientes/{qna_id}/{dpto}/{pendiente_id}', [
        'uses' => 'ReportsController@pendientes_id',
        'as' => 'reports.pendientes.id'
    ]);

    Route::get('/reporte/estadistica', [
        'uses' => 'ReportsController@estadistica',
        'as' => 'reports.estadistica'
    ]);
    Route::get('/reporte/validacion_aguinaldo', [
        'uses' => 'ReportsController@val_aguinaldo',
        'as' => 'reports.val_aguinaldo'
    ]);
    Route::get('/reporte/validacion_aguinaldo_pdf', [
        'uses' => 'ReportsController@val_aguinaldo_pdf',
        'as' => 'reports.val_aguinaldopdf'
    ]);

    Route::post('/reporte/estadistica/show', [
        'uses' => 'ReportsController@estadistica_concepto',
        'as' => 'reports.estadistica_concepto'
    ]);
    Route::get('/reporte/empleado/pdf/{num_empleado}/{fecha_inicio}/{fecha_final}', [
        'uses' => 'ReportsController@reporte_kardex_pdf',
        'as' => 'reporte.kardex.pdf'
    ]);
    Route::get('/reporte/empleado/kardex/{num_empleado}/', [
        'uses' => 'ReportsController@reporte_kardex_todo',
        'as' => 'reporte.kardex.todo'
    ]);


    Route::get('/reporte/sinderecho/pdf/{dpto}/{fecha_inicio}/{fecha_final}', [
        'uses' => 'ReportsController@getsinDerechoPDF',
        'as' => 'reporte.sinderecho.pdf'
    ]);
    Route::get('/reporte/exceso-de-incapacidades/', [
        'uses' => 'ReportsController@exceso_incapacidades',
        'as' => 'reports.exceso_incapacidades'
    ]);
    Route::get('/reporte/estadistica/incidencia', [
        'uses' => 'ReportsController@estadistica_por_incidencia',
        'as' => 'reports.por_incidencia'
    ]);
    Route::post('/reporte/estadistica/incidencia_show', [
        'uses' => 'ReportsController@estadistica_por_incidencia_show',
        'as' => 'reports.por_incidencia_show'
    ]);
    Route::get('/reporte/estadistica/pdf/{dpto}/{fecha_inicial}/{fecha_final}/{code}', [
        'uses' => 'ReportsController@estadistica_por_incidencia_pdf',
        'as' => 'reports.estadistica_por_incidencia.pdf'
    ]);
    Route::get('/reporte/inasistencias', [
        'uses' => 'ReportsController@inasistencias',
        'as' => 'reports.inasistencias'
    ]);
    Route::post('/reporte/inasistencias', [
        'uses' => 'ReportsController@inasistencias_get',
        'as' => 'reports.inasistencias.get'
    ]);
    Route::get('/reporte/vacaciones', [
        'uses' => 'ReportsController@vacaciones',
        'as' => 'reports.vacaciones'
    ]);
    Route::post('reporte/vacaciones/search', [
        'uses' => 'SearchReporteEmpleadosController@vacaciones',
        'as' => 'reportes.vacaciones.search'
    ]);
        ///END-REPORTES

        ///INICIO-CAPTURA
    Route::get('/capturar', [
        'uses' => 'CapturarController@index',
        'as' => 'admin.capturar.index'
    ]);
    Route::get('/capturar/{qna_id}/centro', [
        'uses' => 'CapturarController@centro',
        'as' => 'admin.capturar.centro'
    ]);
    Route::get('/capturar/{qna_id}/{dpto_id}/centro', [
        'uses' => 'CapturarController@capturar_centro',
        'as' => 'admin.capturar.capturar_centro'
    ]);
    Route::get('/capturar/{qna_id}/{dpto_id}/{grupo}/grupo', [
        'uses' => 'CapturarController@grupo',
        'as' => 'admin.capturar.grupo'
    ]);
    Route::get('/capturar/{incidencia_id}/capturado', [
        'uses' => 'CapturarController@capturado',
        'as' => 'admin.capturar.capturado'
    ]);
        ///END-CAPTURA

    Route::resource('incidencias', 'IncidenciasController');
    Route::post('incidencias/capturar', [
        'uses' => 'IncidenciasController@store',
        'as' => 'incidencias.capturar'
    ]);
    Route::get('incidencias/{token}/{num_empleado}/{qna_id}/destroy', [
        'uses' => 'IncidenciasController@destroy',
        'as' => 'incidencias.destroy'
    ]);
    Route::get('incidencias_del/{token}/{num_empleado}/{qna_id}/destroy', [
        'uses' => 'IncidenciasController@incidencias_del',
        'as' => 'incidencias_del.destroy'
    ]);
    Route::post('incidencias/search', [
        'uses' => 'SearchEmpleadosController@index',
        'as' => 'empleados.search'
    ]);
    Route::get('incidencias/{num_empleado}/{qna_id}', [
        'uses' => 'IncidenciasController@create',
        'as' => 'admin.incidencias.create'
    ]);
    Route::post('incidencias/{empleado_id}/{qna_id}', [
        'uses' => 'IncidenciasController@store',
        'as' => 'admin.incidencias.store'
    ]);
    Route::get('incidencias/comment/{empleado_id}/create', [
        'uses' => 'IncidenciasController@comentario_create',
        'as' => 'incidencias.create.comment'
    ]);

    Route::put('incidencias/comment/{empleado_id}/update', [
        'uses' => 'IncidenciasController@comentario_update',
        'as' => 'incidencias.update.comment'
    ]);
    Route::post('incidencias/comment/{empleado_id}/store', [
        'uses' => 'IncidenciasController@comentario_store',
        'as' => 'incidencias.store.comment'
    ]);

    Route::get('ausentismo', [
        'uses' => 'AusentismoController@index',
        'as' => 'admin.ausentismo'
    ]);
    Route::post('ausentismo/delegacion', [
        'uses' => 'AusentismoController@delegacion',
        'as' => 'admin.ausentismo.delegacion'
    ]);
    Route::post('ausentismo/centro', [
        'uses' => 'AusentismoController@centro',
        'as' => 'admin.ausentismo.centro'
    ]);
    Route::post('ausentismo/empleado', [
        'uses' => 'AusentismoController@empleado',
        'as' => 'admin.ausentismo.empleado'
    ]);

    Route::get('fechadecierre', [
        'uses' => 'FechadecierreController@create',
        'as' => 'fechadecierre.create'
    ]);
    Route::put('fechadecierre', [
        'uses' => 'FechadecierreController@update',
        'as' => 'fechadecierre.update'
    ]);

    Route::get('getdata', [
        'uses' => 'EmployeesController@autocomplete',
        'as' => 'employee.autocomplete'
    ]);
    Route::get('getdoctors', [
        'uses' => 'EmployeesController@autocomplete_medicos',
        'as' => 'employee.autocomplete2'
    ]);
    Route::get('logs', [
        'uses' => 'IncidenciasController@logs',
        'as' => 'logs.index'
    ]);

    /* B IO M E T R I C O  */
    Route::get('biometrico', [
        'uses' => 'BiometricosController@index',
        'as' => 'biometrico.index'
    ]);
    Route::get('biometrico_ver', [
        'uses' => 'BiometricosController@show',
        'as' => 'biometrico.show'
    ]);
    Route::get('biometrico_getchecadas', [
        'uses' => 'BiometricosController@get_checadas',
        'as' => 'biometrico.get_checadas'
    ]);
    Route::post('biometrico_buscar/', [
        'uses' => 'BiometricosController@buscar',
        'as' => 'biometrico.buscar'
    ]);
    Route::POST('import', 'ImportController@import');

    Route::get('biometrico_capturar/{num_empleado}/{fecha}', [
        'uses' => 'BiometricosController@biometrico_capturar',
        'as' => 'biometrico.capturar'
    ]);
    Route::get('biometrico_pdf/{qna}/{dpto}', [
        'uses' => 'BiometricosController@biometrico_pdf',
        'as' => 'biometrico.pdf'
    ]);
    Route::get('ver_registros_biometricos/', [
        'uses' => 'BiometricosController@biometrico_ver_registros',
        'as' => 'biometrico.ver_registros'
    ]);

    /* C O V I D - 1 9 */
    Route::get('covid', [
        'uses' => 'CovidController@index',
        'as' => 'covid.index'
    ]);
    Route::post('covid/show/{dpto}', [
        'uses' => 'CovidController@show',
        'as' => 'covid.show'
    ]);
    Route::get('covid/reporte/{qna_id}/{dpto}', [
        'uses' => 'CovidController@reporte_pdf',
        'as' => 'covid.reporte.pdf'
    ]);
    Route::get('covid/todos', [
        'uses' => 'CovidController@todos',
        'as' => 'covid.todos'
    ]);

    /* PAGINA DE MANTENIMIENTO */
    Route::get('mantenimiento/', [
        'uses' => 'MantenimientoController@index',
        'as' => 'mantenimiento.index'
    ]);
    Route::get('mantenimiento/show/', [
        'uses' => 'MantenimientoController@show',
        'as' => 'mantenimiento.show'
    ]);
    Route::get('mantenimiento/state', [
        'uses' => 'MantenimientoController@state',
        'as' => 'mantenimiento.state'
    ]);

        /* P R I M A  D O M I N I C A L */
        Route::get('/reporte/prima_dominical', [
            'uses' => 'ReportsController@prima_dominical',
            'as' => 'reports.prima_dominical'
        ]);
        Route::post('/reporte/prima_dominical/show/{dpto}', [
            'uses' => 'ReportsController@prima_dominical_show',
            'as' => 'prima_dominical.show'
        ]);
        Route::get('reporte/prima_dominical/reporte/{qna_id}/{dpto}', [
            'uses' => 'ReportsController@prima_dominical_pdf',
            'as' => 'prima_dominical.reporte.pdf'
        ]);


/* biometrico */
    Route::get('biometrico/conectar', [
        'uses' => 'BiometricosController@conectar',
        'as' => 'biometrico.conectar'
    ]);
    Route::get('biometrico/show_checadas', [
        'uses' => 'BiometricosController@show_checadas',
        'as' => 'biometrico.show_checadas'
    ]);
    //asignar checadas
    Route::get('biometrico/asignar_checadas', [
        'uses' => 'BiometricosController@asignar',
        'as' => 'biometrico.asignar'
    ]);
    Route::post('biometrico/asignar_view', [
        'uses' => 'BiometricosController@asignar_post',
        'as' => 'biometrico.asignar_post'
    ]);


});
