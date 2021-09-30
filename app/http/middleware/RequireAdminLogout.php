<?php

namespace App\Http\Middleware;

use \App\Session\Admin\Login as SessAdmLog;

class RequireAdminLogout{

    public function handle($request, $next){

        if(SessAdmLog::isLogged()){

            $request->getRouter()->redirect('/admin');
        }
        return $next($request);
    }
}