<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';
require_once("./controllers/reserva_controller.php");
require_once("./controllers/cliente_controller.php");
require_once("./controllers/usuario_controller.php");
require_once("./controllers/log_accesos_controller.php");
require_once("./controllers/log_transacciones_controller.php");
require_once("./middlewares\autenticador_usuarios.php");
require_once("./middlewares\autenticador_clientes.php");
require_once("./middlewares\autenticador_reservas.php");
require_once("./middlewares\logger.php");
require_once("./middlewares\log_middleware.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();


// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();


// Routes
$app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("GET => Bienvenido!!! a SlimFramework");
    return $response;
});

$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->post('/crear', \ClienteController::class . ':Insertar')
    ->add(\AutenticadorCliente::class.':VerificarParametrosCrearCliente')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
    ->add(\Logger::class.':ValidarSesionIniciada');

    $group->post('/consultar', \ClienteController::class . ':ConsultarCliente')
    ->add(\AutenticadorCliente::class.':VerificarParametrosConsultarCliente');

    $group->post('/modificar', \ClienteController::class . ':Insertar')
    ->add(\AutenticadorCliente::class.':VerificarParametrosConsultarCliente');

    $group->delete('/eliminar', \ClienteController::class . ':EliminarCliente')
    ->add(\AutenticadorCliente::class.':VerificarParametrosEliminarCliente')
    ->add(\AutenticadorUsuario::class.':ValidarPermisosDeRol')
    ->add(\Logger::class.':ValidarSesionIniciada');

});

$app->group('/reserva', function (RouteCollectorProxy $group) {
    $group->post('/crear', \ReservaController::class . ':InsertarReserva')
    ->add(\AutenticadorReserva::class.':VerificarParametrosCrearReserva');

    $group->post('/cancelar', \ReservaController::class . ':CancelarReserva')
    ->add(\AutenticadorReserva::class.':VerificarParametrosCancelarReserva');

    $group->post('/ajustar', \ReservaController::class . ':AjustarReserva')
    ->add(\AutenticadorReserva::class.':VerificarParametrosAjustarReserva');

    $group->group("/consulta", function (RouteCollectorProxy $group) {
        $group->get('[/]', \ReservaController::class . ':ListarTipoHabitacion');

        $group->get('/importeTotal', \ReservaController::class . ':CalcularTotalReservas')
        ->add(\AutenticadorReserva::class.':VerificarParametroTipoHabitacion');

        $group->get('/clienteActivas', \ClienteController::class . ':ObtenerReservasActivas')
        ->add(\AutenticadorReserva::class.':VerificarParametrosConsultarUsuario');

        $group->get('/fechasActivas', \ReservaController::class . ':ListarEntreFechas')
        ->add(\AutenticadorReserva::class.':VerificarParametrosConsultarFechas');

        $group->get('/tipoClienteImporteCancelado', \ReservaController::class . ':CalcularTotalCancelado')
        ->add(\AutenticadorReserva::class.':VerificarParametroTipoCliente');

        $group->get('/clienteCanceladas', \ClienteController::class . ':ObtenerCancelacionesCliente')
        ->add(\AutenticadorReserva::class.':VerificarParametrosConsultarUsuario');

        $group->get('/fechasCanceladas', \ReservaController::class . ':ListarEntreFechasCancelado')
        ->add(\AutenticadorReserva::class.':VerificarParametrosConsultarFechas');

        $group->get('/tipoClienteCancelado', \ClienteController::class . ':ObtenerCancelacionesTipoCliente')
        ->add(\AutenticadorReserva::class.':VerificarParametroTipoCliente');

        $group->get('/cliente', \ClienteController::class . ':ObtenerReservas')
        ->add(\AutenticadorReserva::class.':VerificarParametrosConsultarUsuario');

        $group->get('/modalidadPago', \ReservaController::class . ':ListarModalidadPago')
        ->add(\AutenticadorReserva::class.':VerificarParametroModalidadPago');

    });
})
->add(\AutenticadorUsuario::class.':ValidarPermisosDeRolClienteRecepcionista')
->add(\Logger::class.':ValidarSesionIniciada');

$app->group("/usuario", function (RouteCollectorProxy $group) {
    $group->post('/crear', \UsuarioController::class . ':CargarUno')
    ->add(\AutenticadorUsuario::class.':ValidarCamposAlta');

    $group->group('/sesion', function (RouteCollectorProxy $group) {
        $group->post('[/]', \Logger::class.'::Loguear')
        ->add(\AutenticadorUsuario::class.':ValidarCamposLogIn');
        $group->get('[/]', \Logger::class.':Salir');
    })
    ->add(\Logger::class.':LimpiarCoockieUsuario');
});

$app->group("/log", function (RouteCollectorProxy $group) {
    $group->get('/acceso', \LogAccesosController::class . ':ExportarPDF');
    $group->get('/transaccion', \LogTransaccionesController::class . ':ExportarCSV');
});

$app->add(\LogMiddleware::class.':LogTransaccion');
$app->add(\LogMiddleware::class.':LogAcceso');
$app->run();
