<?php

namespace App\Controllers;

use \Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Materia;
use App\Models\User;

class MateriaContoller{
    
    public function agregar(Request $request, Response $response){
        $key = 'usuario';
        $token = $request->getHeader('token');
        $usuario = json_decode(JWT::decode($token[0],$key,array('HS256')));
        $rta = array(
            'status'=>303,
            'message'=>'Tienes que tener permisos de admin para acceder'
        );
       
        if($usuario->tipo_id == 3){

            $parsedBody = $request->getParsedBody();
            $materia = $parsedBody['materia'];
            $cuatrimestre = $parsedBody['cuatrimestre'];
            $vacantes = $parsedBody['vacantes'];
            $profesorId = $parsedBody['profesor'];

            $registro = json_decode(User::where('id','=',$profesorId)->get());

            if(!empty($registro)){
                $rta['message'] = 'El registro no fue encontrado';
            }else if($registro[0]->tipo_id != 2){
                $rta['message'] = 'El Id seleccionado no es un profesor';
            }else{
                if(intval($vacantes)>0 && intval($cuatrimestre)>0){
                    $materia = new Materia;
                    $materia->materia =$materia;
                    $materia->cuatrimestre= $cuatrimestre;
                    $materia->vacantes = $vacantes;
                    $materia->profesor_id = $profesorId;
                    try{
                        $materia->save(); 
                        $rta['status'] = 200;                       
                    }catch(\Exception $e){
                        $rta['status'] = 303;
                        $rta['message'] = $e;
                    }
                }else{
                    $rta['message'] = 'Error en vacantes Y/O cuatrimestre';
                }
            }
        }
        $response->getBody()->write(json_encode($rta));

        return $response;
    }
    
    public function show(Request $request, Response $response,$args){
        $key = 'usuario';
        $token = $request->getHeader('token');
        $usuario = json_decode(JWT::decode($token[0],$key,array('HS256')));
        $idMateria = $args['id'];
        
        $rta = array(
            'status'=>303,
            'message'=>'Show message'
        );
        if(isset($idMateria)){
            $registro = json_decode(Materia::where('id','=',$idMateria)->get());
            if(!isEmpty($registro)){
                $rta['status'] = 200;
                $profesor = json_decode(User::where('id','=',$registro->profesor_id)->get());
                $datosMateria = $registro->materia . ' ' .
                                $registro->cuatrimestre . ' ' . 
                                $registro->vacantes . ' ' .
                                $profesor->nombre;
                $lista = json_decode(Inscripto::where('materia_id','=',$idMateria)->get());
                if($usuario->tipo_id == 1){
                    $rta['message'] = $datosMateria; 
                }else{
                    $rta['message'] = $datosMateria . $lista;
                }
            }else{
                $rta['message'] = 'La materia no existe';
            }

            $response->getBody()->write(json_encode($rta));
            return $response;
        }

       
    }

    public function asigProf(Request $request, Response $response, $args){
        $key = 'usuario';
        $token = $request->getHeader('token');
        $usuario = json_decode(JWT::decode($token[0],$key,array('HS256')));
        $rta = array(
            'status'=>303,
            'message'=>'Tienes que tener permisos de admin para acceder'
        );
       
        if($usuario->tipo_id == 3){
            $registro = json_decode(Materia::where('id','=',$idMateria)->get());
            $profesor = json_decode(User::where('id','=',$registro->profesor_id)->get());
            if(!isEmpty($registro) && $profesor->tipo_id == 2){
                $registro->profesor_id = $profesor->id;
                $rta['status'] = 200;
                $rta['message'] = 'El profesor es'.$profesor->id;
            }else{
                $rta['message'] = 'La materia Y/O el profesor no existen';
            }
        }

        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function anotarse(Request $request, Response $response, $args){
        $key = 'usuario';
        $token = $request->getHeader('token');
        $usuario = json_decode(JWT::decode($token[0],$key,array('HS256')));
        $rta = array(
            'status'=>303,
            'message'=>'Tienes que tener permisos de admin para acceder'
        );
       
        if($usuario->tipo_id == 1){
            $registro = json_decode(Materia::where('id','=',$idMateria)->get());
            if(!isEmpty($registro)){

                if($registro->vacantes>0){

                    $registro->vacantes =$registro->vacantes - 1; 
                    $inscripto = new Inscripto;
                    $incripto->alumno_id = $usuario->id;
                    $inscripto->materia_id = $registro->id;

                    $inscripto->save();
                    $registro->save();
                    $rta['status'] = 200;
                    $rta['message'] = 'El profesor es'.$profesor->id;

                }else{
                    $rta['message'] = 'No hay vacantes';
                }

            }else{
                $rta['message'] = 'La materia  no existe';
            }
        }

        $response->getBody()->write(json_encode($rta));
        return $response;
        }
    
    
        public function lista(Request $request, Response $response){

            $materia = json_decode(Materia::all());
            $rta = array(
                'status'=>303,
                'message'=>'Tienes que tener permisos de admin para acceder'
            );
 
            return $response->getBody()->write(json_encode($rta));           
        }
}
    



