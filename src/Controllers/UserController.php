<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\User;

class UserController{

    public function registro(Request $request, Response $response){

        $parsedBody = $request->getParsedBody();
        $nombre = $parsedBody['nombre'];
        $email= $parsedBody['email'];
        $password= $parsedBody['clave'];
        $tipo = $parsedBody['tipo'];
        $legajo = $parsedBody['legajo'];
        $rta = array(
            'status'=>200,
            'mensaje'=>'Exito al guardar'       
        );
            $usuario = new User;
            $usuario->timestamps = false;
            $usuario->nombre = $nombre;
            $usuario->tipo_id = $tipo;
            $usuario->email = $email;
            $usuario->clave = $password;
            $usuario->legajo = $legajo;
            try{
                $usuario->save();
                
            }catch(\Exception $e){
                $rta['status'] = 303;
                $rta['mensaje'] = $e;
            }
                   
        $response->getBody()->write(json_encode($rta));
 
        return $response
            ->withHeader('Content-type', 'application/json');
    }

    public function login(Request $request, Response $response){
        $parsedBody = $request->getParsedBody();
        
        $email= $parsedBody['email'];
        $password= $parsedBody['clave'];
                       
        $rta =array(
            'status'=>303,
            'message'=>'Combinacion Incorrecta'
        );        
        $usuarioEncontrado = json_decode(User::whereRaw('email = ? AND clave = ?',array($email,$password))
                                ->join('tipos','Users.tipo_id','=','tipos.Id')
                                ->get());
    
        if(!empty($usuarioEncontrado)){
                      
            $loggedUser = new User;
            $loggedUser->nombre = $usuarioEncontrado[0]->nombre;
            $loggedUser->email = $usuarioEncontrado[0]->email;
            $loggedUser->tipo_id = $usuarioEncontrado[0]->tipo_id;
            $loggedUser->clave = $usuarioEncontrado[0]->clave;
            
            $key = 'usuario';
            $payload = json_encode($loggedUser);

            $rta['status'] = 200;
            $rta['message'] = JWT::encode($payload,$key);
        }
        $response->getBody()->write(json_encode($rta));
        return $response
                ->withHeader('Content-Type','application/json');
    }

    public function addMateria(Request $request, Response $response){

    }
}