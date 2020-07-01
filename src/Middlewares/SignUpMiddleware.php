<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

class SignUpMiddleware
{
    
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $request PSR-7 request
     * @param  RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $parsedBody = $request->getParsedBody(); 
        
        $valida = isset($parsedBody['nombre']) && 
            isset($parsedBody['clave']) && 
            isset($parsedBody['tipo']) &&
            isset($parsedBody['email']) &&
            isset($parsedBody['legajo']) &&
            intval($parsedBody['legajo'])>=1000 &&
            intval($parsedBody['legajo'])<=2000 ;

        try{
            if($valida){
                $res = new Response();
                $res->getBody()->write((String)$handler->handle($request)->getBody());
                return $res;
            }else{
                $res = new Response();
                throw new \Slim\Exception\HttpForbiddenException($request);               
            } 

        }catch(\Exception $e){
            $res->getBody()->write(json_encode(array('status'=>303,'message'=>'Revise los datos de registro')));
            return $res->withStatus(303); 
        }

    }
}
