<?php

use App\Http\Controllers\ApiControllers\ReciboController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiControllers\InvoiceSerieController;
/*Rutas login*/

Route::post('/login', 'AuthController@login')->name('login');
Route::post('/logout', 'AuthController@logout');
Route::post('change-password', 'AuthController@changePassword');
// START cambios para recordar contraseña con envio de una nueva por mail 
Route::post('recover-password', 'AuthController@recoverPassword');
// END cambios para recordar contraseña con envio de una nueva por mail 

Route::group(['middleware' => ['auth:sanctum', 'twofactor']], function () {
  Route::get('get-passwords', 'PasswordController@getPasswords');
  Route::get('get-password/{id}', 'PasswordController@getPassword');
  Route::post('save-password', 'PasswordController@savePassword');
  Route::get('delete-password/{id}', 'PasswordController@deletePassword');
});

Route::get('saveCuentaContableAllClientes', 'ClienteController@saveCuentaContableAllClientes');
Route::post('duplicate-facturas', 'FacturaController@DupilicateFacturasRepetidas');
// Route::get('saveCuentaContableAllClientes', 'ClienteController@saveCuentaContableAllClientes');
// Route::post('duplicate-facturas', 'FacturaController@DupilicateFacturasRepetidas');

Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::apiResource('invoice-series', InvoiceSerieController::class);
  Route::post('factura/mails', 'FacturaController@SendLoteEmail');
  Route::get('factura/mails/body', 'FacturaController@getMailBody');

  Route::get('print-emitidas/{fecha_inicio}/{fecha_fin}', 'FacturaController@printEmitidas');
  Route::get('print-recibidas/{fecha_inicio}/{fecha_fin}', 'FacturaRecibidasController@printRecibidas');
  Route::post('get-password-token', 'PasswordController@signToken');
  Route::post('verificar-codigo', 'PasswordController@verificarCodigo');

  Route::get('/index-app/{idUser}', 'AppController@main');
  /*Ruta para obtener las provincias*/
  Route::get('get-provincias', 'ProvinciaController@getProvincias');


  // START cambios para cientes con Pais
  /*Ruta para obtener paises*/
  Route::get('get-paises', 'ProvinciaController@getPaises');
  // END cambios para cientes con Pais

  /*Rutas Cliente*/
  Route::get('get-clientes', 'ClienteController@getClientes'); // Sin user_id en la URL
  Route::get('get-clientes/{user_id}', 'ClienteController@getClientes'); // Mantener compatibilidad
  Route::get('get-cliente-by-id/{cliente_id}', 'ClienteController@getClienteByid');
  Route::post('save-cliente', 'ClienteController@saveCliente');
  Route::get('delete-cliente/{cliente_id}', 'ClienteController@deleteCliente');
  Route::get('get-last-id', 'ClienteController@getLastId');
  Route::get('clientes/export', 'ClienteController@exportClientes');
  Route::post('clientes/import', 'ClienteController@importClientes');

  /*Ruta de formas pago*/
  Route::get('get-formas-pago', 'ClienteController@formasPago');

  /*Rutas Cliente Historial*/
  Route::post('save-cliente-historial/{cliente_id}', 'ClienteController@saveHistorial');
  Route::get('delete-cliente-historial/{historial_id}', 'ClienteController@deleteHistorial');

  /*Rutas Usuarios*/
  Route::get('get-usuarios', 'UsuarioController@getUsuarios');
  Route::get('get-usuario-by-id', 'UsuarioController@getUsuarioByid'); // Sin user_id en la URL
  Route::get('get-usuario-by-id/{usuario_id}', 'UsuarioController@getUsuarioByid'); // Mantener compatibilidad
  Route::post('save-usuario', 'UsuarioController@saveUsuario');
  Route::post('update-usuario', 'UsuarioController@updateUsuario'); // Sin id en la URL
  Route::post('update-usuario/{id}', 'UsuarioController@updateUsuario'); // Mantener compatibilidad
  Route::get('get-metodos-pago', 'UsuarioController@getMetodosPago');
  Route::get('delete-usuario/{usuario_id}', 'UsuarioController@deleteUsuario');
  Route::get('get-methods-form', 'UsuarioController@getMethodsForm');
  Route::get('get-email-usuario/{usuario_id}', 'UsuarioController@getEmailUsuario');
  Route::post('reset-employee-password/{usuario_id}', 'UsuarioController@resetEmployeePassword');
  Route::get('get-gestores', 'UsuarioController@getGestores');
  Route::get('get-usuarios-clientes', 'UsuarioController@getClientes'); // Cambiado el nombre para evitar conflicto con get-clientes de ClienteController

  /*Rutas Gestor*/
  Route::get('gestor/clientes', 'GestorController@getClientesAsociados');
  Route::post('gestor/cambiar-contexto', 'GestorController@cambiarContexto');

  /*Rutas Proveedor*/
  // IMPORTANTE: La ruta sin parámetros debe estar ANTES de la que tiene parámetros
  Route::get('get-proveedores', 'ProveedorController@getProveedores'); // Sin user_id en la URL
  Route::get('get-proveedores/{user_id}', 'ProveedorController@getProveedores'); // Mantener compatibilidad (debe estar después)
  Route::get('get-proveedor-by-id/{proveedor_id}', 'ProveedorController@getProveedorByid');
  Route::post('save-proveedor', 'ProveedorController@saveProveedor');
  Route::get('delete-proveedor/{proveedor_id}', 'ProveedorController@deleteProveedor');
  Route::get('get-last-proveedor-id', 'ProveedorController@getLastId');
  Route::get('proveedor-comisiones/{proveedor_id}', 'ProveedorComisionController@index');
  Route::post('proveedor-comisiones', 'ProveedorComisionController@store');
  Route::post('proveedor-comisiones-update/{id}', 'ProveedorComisionController@update');
  Route::get('delete-proveedor-comision/{id}', 'ProveedorComisionController@destroy');
  /*Rutas Servicio*/
  Route::get('get-servicios', 'ServicioController@getServicios');
  Route::get('get-servicios/{user_id}', 'ServicioController@getServicios');
  Route::get('get-servicio-by-id/{servicio_id}', 'ServicioController@getServicioByid');
  Route::post('save-servicio', 'ServicioController@saveServicio');
  Route::get('delete-servicio/{id}', 'ServicioController@deleteServicio');
  Route::get('servicio/numero/{venta}', 'ServicioController@getLastNumber');
  /*Rutas Albaranes*/
  Route::get('get-albaranes/{user_id}', 'AlbaranController@getAlbaranes');
  Route::get('get-albaran-by-id/{albaran_id}', 'AlbaranController@getAlbaranById');
  Route::post('save-albaran', 'AlbaranController@saveAlbaran');
  Route::post('update-albaran/{id}', 'AlbaranController@updatelbaran');

  Route::get('get-albaranes-enviados', 'AlbaranController@getnviados'); // Sin user_id en la URL
  Route::get('get-albaranes-enviados/{user_id}', 'AlbaranController@getnviados'); // Mantener compatibilidad
  Route::get('get-albaranes-enviados-show/{albaranId}', 'AlbaranController@albaranEnvidoShow');
  Route::get('preview-albaran/{albaranId}', 'AlbaranController@previewAlbaran');


  Route::post('save-albaran-enviado', 'AlbaranController@albaranesEnviadosF');
  Route::post('save-albaran-recibido', 'AlbaranController@albaranesRecibido');
  Route::post('delete-albaran-enviados/{albaran_id}', 'AlbaranController@deleteAlbaranEnviado');
  Route::post('unificar-albaranes', 'AlbaranController@unificarAlbaranes');
  Route::post('unificar-y-convertir-factura', 'AlbaranController@unificarYConvertirAFactura');

  Route::post('save-factura-albaran', 'AlbaranController@generarFactura');
  Route::post('save-nota-albaran', 'AlbaranController@generarNota');

  Route::post('update-albaran-enviados/{id}', 'AlbaranController@updatealbaranesEnviadosF');
  Route::post('update-albaran-enviados-factura/{id}', 'AlbaranController@updateFactAlbaran');

  Route::get('delete-albaran/{albaran_id}', 'AlbaranController@deleteAlbaran');

  /*Rutas Recibo*/
  Route::get('get-recibos', 'PresupuestoController@getRecibos'); // Sin user_id en la URL
  Route::get('get-recibos/{user_id}', 'PresupuestoController@getRecibos'); // Mantener compatibilidad
  Route::post('save-recibo/{tipo}/{convertir_factura}', 'ReciboController@saveRecibo'); //
  Route::get('get-recibo-by-id/{recibo_id}', 'ReciboController@getReciboById');
  Route::get('recibos/{recibo}/regenerar-factura-pdf', 'ReciboController@regenerarFacturaPdf');
  Route::get('preview-recibo/{reciboId}', 'ReciboController@previewRecibo');
  Route::get('delete-recibo/{recibo_id}', 'PresupuestoController@deletePresupuesto');
  Route::get('cambiar-anio-fiscal', 'ReciboController@CambiarAnioFiscal');

  Route::get('remove-contabilizado/{elemento}/{idServicio}/{idRecibo}', 'ReciboController@removeContabilizado');
  Route::get('get-recibo-by-name/{elemento}', 'ReciboController@getReciboByName');


  /*Rutas factura*/
  Route::post('print-factura', 'FacturaController@print');

  Route::get('get-facturas', 'FacturaController@getFacturas');
  Route::get('get-facturas/{user_id}', 'FacturaController@getFacturas');
  Route::get('get-facturas-proforma', 'FacturaController@getFacturasProforma'); // proforma
  Route::get('get-facturas-proforma/{user_id}', 'FacturaController@getFacturasProforma'); // proforma
  Route::get('delete-factura/{factura_id}', 'FacturaController@deleteFactura');
  Route::get('delete-factura-proforma/{factura_id}', 'FacturaController@deleteFacturaProforma'); // proforma
  Route::get('facturas-recibidas', 'FacturaRecibidasController@index');
  Route::get('facturas-recibidas-show/{idFac}', 'FacturaRecibidasController@show');
  Route::get('facturas-recibidas-pdf/{id}', 'FacturaRecibidasController@pdf');
  Route::post('facturas-recibidas', 'FacturaRecibidasController@store');
  Route::post('facturas-recibidas-update/{idFac}', 'FacturaRecibidasController@update');
  Route::post('facturas-recibidas-delete/{idFactRec}', 'FacturaRecibidasController@destroy');
  Route::get('get-data-albaranes/{cliente_id}', 'FacturaController@getDataAlbaranes');
  Route::post('duplicar-factura-recibida', 'FacturaRecibidasController@duplicarFactura');

  Route::get('get-retencion', 'FacturaRecibidasController@getRetencion');

  /* Liquidaciones (módulo independiente de facturas recibidas) */
  Route::get('liquidaciones', 'LiquidacionesController@index');
  Route::get('liquidaciones-siguiente-numero', 'LiquidacionesController@siguienteNumero');
  Route::get('liquidaciones-show/{idFac}', 'LiquidacionesController@show');
  Route::post('liquidaciones', 'LiquidacionesController@store');
  Route::post('liquidaciones-update/{idFac}', 'LiquidacionesController@update');
  Route::post('liquidaciones-delete/{idLiq}', 'LiquidacionesController@destroy');
  Route::post('duplicar-liquidacion', 'LiquidacionesController@duplicarLiquidacion');
  Route::post('liquidaciones-factura-comisiones', 'LiquidacionesController@crearFacturaComisiones');

  Route::post('export-excel', 'FacturaController@exportExcel');

  /*Rutas Notas*/
  Route::get('get-notas', 'NotaController@getNotas'); // Sin user_id en la URL
  Route::get('get-notas/{user_id}', 'NotaController@getNotas'); // Mantener compatibilidad
  Route::get('delete-nota/{recibo_id}', 'NotaController@deleteNota');
  Route::post('print-notas', 'NotaController@print');
  Route::post('nota/mails', 'NotaController@SendLoteEmail');

  /*Rutas Parte Trabajo*/
  Route::get('get-parte-trabajo/{user_id}', 'ParteTrabajoController@getParteTrabajo');
  Route::get('get-presupuestos-for-parte-trabajo/{user_id}', 'ParteTrabajoController@getPrespuestos');
  Route::get('get-presupuesto-asociado/{recibo_id}', 'ParteTrabajoController@getPresupuestoAsociado');
  Route::get('delete-parte-trabajo/{recibo_id}', 'ParteTrabajoController@deleteParteTrabajo');

  /*Rutas Ingreso*/
  Route::get('get-ingresos/{user_id?}', 'IngresoController@getIngresos');
  Route::get('get-ingreso-by-id/{ingreso_id}', 'IngresoController@getIngresoById');
  Route::post('save-ingreso', 'IngresoController@saveIngreso');
  Route::get('delete-ingreso/{ingreso_id}', 'IngresoController@deleteIngreso');

  /*Rutas Gastos*/
  Route::get('get-gastos/{user_id?}', 'GastosController@index');
  Route::get('get-gasto-by-id/{gasto_id}', 'GastosController@getGastoById');
  Route::post('save-gasto', 'GastosController@store');
  Route::put('update-gasto', 'GastosController@update');
  Route::get('delete-gasto/{gasto_id}', 'GastosController@destroy');

  // CRUD Apuntes contables
  Route::get('/get-apuntes', 'ApunteContableController@getApuntes');
  Route::get('/get-cuentas', 'ApunteContableController@getCuentas');
  Route::get('/get-tipos-apunte', 'ApunteContableController@getTipos');
  Route::get('/get-apunte-predefinido', 'ApunteContableController@getPrdefinidos');
  Route::get('/get-asiento/{id}', 'ApunteContableController@getAsiento');
  Route::post('/save-asiento', 'ApunteContableController@saveAsiento');
  Route::delete('/delete-asiento/{id}', 'ApunteContableController@deleteAsiento');
  Route::delete('/delete-asiento-linea/{id}', 'ApunteContableController@deleteAsientoLinea');
  Route::get('/get-facturas-cliente/{contacto_id}', 'ApunteContableController@getFacturasCliente');
  Route::get('/get-facturas-proveedor/{contacto_id}', 'ApunteContableController@getFacturasProveedor');
  Route::get('/get-cuentas/{prefix}', 'ApunteContableController@getCuentasByPrefix');
  Route::get('/get-iva', 'ApunteContableController@getIva');
  Route::get('/get-categoria-cuenta-contable', 'ApunteContableController@getCategoriasCuentas');

  // Reporte IVA
  Route::get('/get-reporte-iva/{year}', 'ReporteIvaController@index');
  Route::get('/get-anios-iva', 'ReporteIvaController@selectAnio');

  /*Rutas estadisticas*/
  Route::get('get-ingreso-bruto/{user_id}', 'EstadisticasController@getIngresoBruto');

  /*Rutas Morosos*/
  Route::get('get-morosos/{user_id?}', 'MorososController@getMorosos');
  Route::get('get-factura-pendiente-info', 'MorososController@getFacturaPendienteInfo');

  /*Rutas Email*/
  Route::post('send-email', 'MailController@sendEmail');

  /*Lote facturas*/
  Route::get('get-lote-facturas/{user_id?}/{desde?}/{hasta?}/{tipo?}', 'LoteController@getFacturasByRango');
  Route::post('enviar-lote-facturas/{user_id?}', 'LoteController@enviarFacturas');
  Route::post('upload-image-editor', 'ReciboController@uploadImageEditor');
  Route::get('lote-facturas-get-url-download-zips', 'LoteController@getStorageUrlsEnviadasRecibidasYTodas');

  /*Rutas Gestor Documental*/
  Route::get('get-documentos/{user_id}', 'GestorDocumentalController@getDocumentos');
  Route::post('delete-documentos', 'GestorDocumentalController@deleteDocument');
  Route::post('create-folder', 'GestorDocumentalController@createFolder');
  Route::post('save-documents', 'GestorDocumentalController@saveDocuments');
  Route::post('download-document/{user_id}', 'GestorDocumentalController@downloadDocuments');

  Route::get('gestor-admin', 'GestorDocumentalController@adminGestionDocumental');
  Route::post('view-document', 'GestorDocumentalController@viewDocument');

  Route::get('get-tasks/{user_id}', 'GestorTareasController@index');

  Route::post('new-drag/{user_id}', 'GestorTareasController@newDrag');
  Route::put('update-drag/{drag_id}', 'GestorTareasController@updateDrag');
  Route::delete('delete-drag/{drag_id}', 'GestorTareasController@deleteDrag');

  Route::post('save-tasks/', 'GestorTareasController@store');
  Route::put('update-tasks/{tarea_id}', 'GestorTareasController@update');
  Route::put('update-tasks-status/{tarea_id}', 'GestorTareasController@updateStatus');
  Route::delete('delete-tasks/{tarea_id}', 'GestorTareasController@destroy');

  /*Rutas Tipos Gastos*/
  Route::get('get-tipos-gasto/{user_id?}', 'TiposGastoController@getTiposGasto');
  Route::post('save-tipos-gasto', 'TiposGastoController@saveTiposGasto');
  Route::get('delete-tipos-gasto/{tipos_gasto_id}', 'TiposGastoController@deleteTiposGasto');

  // Rutas específicas de system-params deben ir ANTES del apiResource
  Route::post("system-params/update-bulk", "SystemParamController@saveBulk");
  Route::post("system-params/upload-image", "SystemParamController@uploadImage");
  Route::get("system-params/by-name/{name}", "SystemParamController@getByParamName");
  Route::get("system-params/albaran-templates", "SystemParamController@getAvailableAlbaranTemplates");

  // apiResource debe ir al final para no capturar las rutas específicas
  Route::apiResource("system-params", "SystemParamController");
});

// funcion auxiliar para importar documentos de excel a la db
Route::post('import-from-excel', 'ApunteContableController@importFromExcel');
Route::get('preview-pdf', [ReciboController::class, 'previewPdf']);

//Route::get('get-cliente-by-id/{cliente_id}', 'ClienteController@getClienteByid')->middleware(['auth:sanctum', 'hasrole:1,2']);
//Route::get('get-desplegables', 'ClienteController@getDesplegables');
//Route::post('save-cliente', 'ClienteController@saveCliente')->middleware(['auth:sanctum', 'hasrole:1,2']);
//Route::get('delete-cliente/{cliente_id}', 'ClienteController@deleteCliente')->middleware(['auth:sanctum', 'hasrole:1,2']);

// Route::get('preview-pdf/{recibo_id}', 'ReciboController@previewPdf');
