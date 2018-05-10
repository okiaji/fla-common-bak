<?php
namespace FLA\Controllers\Common;

use FLA\Common\BusinessObject\BusinessFunction\user\GetUserInfoByToken;
use FLA\Common\BusinessObject\BusinessTransaction\user\AuthUserLogin;
use FLA\Common\BusinessObject\BusinessTransaction\user\DestroyUserLogin;
use FLA\Common\CommonConstant;
use FLA\Core\BaseControllers;
use FLA\Core\CoreException;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class AuthController extends BaseControllers
{

    public function login(Request $request)
    {
        try {
            $agent = new Agent();
            $device = $agent->platform()." ".$agent->version($agent->platform());
            $browser = $agent->browser()." ".$agent->version($agent->browser());

            $input=[
                'usernameOrEmail' => $request['username'],
                'password' => $request['password'],
                'ip' => $request->ip(),
                'device' => $device,
                'browser' => $browser
            ];

            // Auth login
            $authUser = new AuthUserLogin();
            $userLoggedInfo = $authUser->execute($input);

            // Get user info
            $getUserInfo = new GetUserInfoByToken();
            $userInfo = $getUserInfo->execute([
                'token' => $userLoggedInfo['user_token']
            ]);

            return response()->json([
                'status' => CommonConstant::$OK,
                'response' => $userInfo
            ])->withCookie(Cookie()->forever('FLA-TOKEN', $userLoggedInfo['user_token'], null, null, false, false));

        } catch (CoreException $e) {
            return response()->json([
                'status' => CommonConstant::$FAIL,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $destroyUserLogin = new DestroyUserLogin();
        $destroyUserLogin->execute([
            'userToken' => $request->header('FLA-TOKEN')
        ]);

        return response()->json([
            'status' => CommonConstant::$OK
        ]);
    }

}