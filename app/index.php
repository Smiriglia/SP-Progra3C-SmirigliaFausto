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
require_once("./middlewares\autenticador_usuarios.php");
require_once("./middlewares\autenticador_reservas.php");

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
    ->add(\AutenticadorUsuario::class.':VerificarParametrosCrearCliente');

    $group->post('/consultar', \ClienteController::class . ':ConsultarCliente')
    ->add(\AutenticadorUsuario::class.':VerificarParametrosConsultarCliente');

    $group->post('/modificar', \ClienteController::class . ':Insertar')
    ->add(\AutenticadorUsuario::class.':VerificarParametrosConsultarCliente');

    $group->delete('/eliminar', \ClienteController::class . ':EliminarCliente')
    ->add(\AutenticadorUsuario::class.':VerificarParametrosEliminarCliente');

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
});

$app->run();
