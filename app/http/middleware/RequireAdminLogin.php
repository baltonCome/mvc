<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessAdmLog;

class RequireAdminLogin{

    public function handle($request, $next){

        if(!SessAdmLog::isLogged()){

            $request->getRouter()->redirect('/admin/login');
        }
        return $next($request);
    }
}