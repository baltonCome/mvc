<?php

namespace App\Http\Middleware;


class Maintenance{

    public function handle($request, $next){

        if(getenv('MAINTENANCE')=='true'){
            
            throw new \Exception("Page in Maintenance, we will be back soon... Come back later.",500);
        }
        return $next($request);
    }
}