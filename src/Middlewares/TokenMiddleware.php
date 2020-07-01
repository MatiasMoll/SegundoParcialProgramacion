<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

class TokenMiddlware
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
        $token = $request->getHeader('token');
        try{
            if($token){
                $res = new Response();
                $res->getBody()->write((string)($handler->handle($request))->getBody());
                return $res;
            }else{
                $res = new Response();
                throw new \Slim\Exception\HttpForbiddenException($request);
            } 
        }catch(\Exception $e){
            $res = new Response();
            $res->getBody()->write($e);
            return $res;
        }       
       
    }

}
