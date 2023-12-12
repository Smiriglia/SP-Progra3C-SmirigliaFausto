<?php
require_once './models/class_usuario.php';

class UsuarioController extends Usuario
{
    public function CargarUno($request, $response, $args)
    {

        $parametros = $request->getParsedBody();
        $paqueteRespuesta = new PaqueteRespuesta();

        $usuario = Usuario::ObtenerUsuarioEmail($parametros['email']);
        if (isset($usuario))
            $paqueteRespuesta->SetError(["error" => "El email ya esta tomado"]);
        else
        {
            $nombre = $parametros['nombre'];
            $email = $parametros['email'];
            $clave = $parametros['clave'];
            $rol = $parametros['rol'];
            $usr = new Usuario();
            $usr->nombre = $nombre;
            $usr->email = $email;
            $usr->clave = $clave;
            $usr->rol = $rol;
            $usr->crearUsuario();
    
            $paqueteRespuesta->SetExito(["mensaje" => "Usuario creado con exito"]);

        }


        $payload = $paqueteRespuesta->GenerarRespuesta();
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $parametros = $request->getQueryParams();
        $id = $parametros['id'];
        $usuario = Usuario::obtenerUsuario($id);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::obtenerUsuario($parametros['id']);
        if(isset($parametros['nombre'])){
            $usuario->nombre = $parametros['nombre'];
        }
        if(isset($parametros['clave'])){
            $usuario->clave = $parametros['clave'];
        }
        if(isset($parametros['email'])){
            $usuario->email = $parametros['email'];
        }
        if(isset($parametros['rol'])){
            $usuario->rol = $parametros['rol'];
        }
        if(isset($parametros['estado'])){
            $usuario->estado = $parametros['estado'];
        }
        Usuario::modificarUsuario($usuario);
        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = Usuario::obtenerUsuario($parametros['id']);
        Usuario::borrarUsuario($usuario);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    

    
}
