<?php
namespace FLA\Common\Middleware;

use FLA\Common\BusinessObject\BusinessFunction\user\FindUserByToken;
use FLA\Common\BusinessObject\BusinessFunction\user\ValTokenIsExists;

class VerifyRequestMiddleware extends AbstractMiddleware
{
    protected function beforeRequest($request)
    {

        $token = $request->header('FLA-TOKEN');
        $roleLogin = $request->header('roleLogin');

        if(($token!=null && $token!='')) {
            $valTokenIsExists = new ValTokenIsExists();
            $valTokenIsExists->execute(['token'=>$token]);

            $findUserByToken = new FindUserByToken();
            $user = $findUserByToken->execute([
                "token" => $token
            ]);

            $request["userLoginId"] = $user->user_id;
            $request["roleLoginId"] = $roleLogin;

        } else {
            return redirect('/login');
        }
    }

    protected function afterRequest($request)
    {

    }
}