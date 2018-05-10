<?php
namespace FLA\Common\Middleware;

use FLA\Common\BusinessObject\BusinessFunction\user\IsTokenExists;

class VerifyUserLoggedMiddleware extends AbstractMiddleware
{
    protected function beforeRequest($request)
    {
        $tokenCookie = isset($_COOKIE['FLA-TOKEN'])?$_COOKIE['FLA-TOKEN']:'';

        if($tokenCookie!=null && $tokenCookie!='') {
            $isTokenExists = new IsTokenExists();
            $resultIsTokenExists = $isTokenExists->execute(['token'=>$tokenCookie]);
            if(!$resultIsTokenExists['exists']) {

                if (isset($_SERVER['HTTP_COOKIE'])) {
                    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
                    foreach($cookies as $cookie) {
                        $parts = explode('=', $cookie);
                        $name = trim($parts[0]);
                        setcookie($name, '', time()-1000);
                        setcookie($name, '', time()-1000, '/');
                    }
                }

                return redirect('/login');
            }
        } else {
            return redirect('/login');
        }
    }

    protected function afterRequest($request)
    {

    }

}