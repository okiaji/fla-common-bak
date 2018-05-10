<?php
namespace FLA\Common\BusinessObject\BusinessTransaction\user;

use FLA\Common\BusinessObject\BusinessFunction\role\FindDefaultUserRoleByUserId;
use FLA\Common\BusinessObject\BusinessFunction\user\IsTokenExists;
use FLA\Common\BusinessObject\BusinessFunction\user\IsUserExistsForLogin;
use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Core\AbstractBusinessTransaction;
use FLA\Core\CoreException;
use FLA\Core\CoreMail;
use FLA\Core\Util\DateUtil;
use FLA\Core\Util\GeneralUtil;
use FLA\Core\Util\ModelUtil;
use FLA\Core\Util\ValidationUtil;

class AuthUserLogin extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "usernameOrEmail");
        ValidationUtil::valBlankOrNull($input, "password");
        ValidationUtil::valBlankOrNull($input, "ip");
        ValidationUtil::valBlankOrNull($input, "device");
        ValidationUtil::valBlankOrNull($input, "browser");

        $usernameOrEmail = $input['usernameOrEmail'];
        $password = md5($input['password']);
        $ip = $input['ip'];
        $device = $input['device'];
        $browser = $input['browser'];
        $datetimeNow = DateUtil::currentDatetime();
        $isNewClient = false;

        $checkUser = new IsUserExistsForLogin();
        $resultCheckUser = $checkUser->execute([
            'usernameOrEmail'=>$usernameOrEmail,
            'password'=>$password
        ]);

        if(!$resultCheckUser['exists']) {
            throw new CoreException(CommonExceptionsConstant::$LOGIN_INFORMATION_IS_NOT_VALID);
        }

        $user = $resultCheckUser['user'];
        $userToken = GeneralUtil::generateToken($user->username, $datetimeNow);
        $userId = $user->user_id;

        $isTokenExists = new IsTokenExists();
        $resultCheckUserLoggedInfo = $isTokenExists->execute([
            'token'=>$userToken
        ]);

        if($resultCheckUserLoggedInfo['exists']) {

            $userLoggedInfo = $resultCheckUserLoggedInfo['userLoggedInfo'];

            $userLoggedInfoArr = [
                'id' => $userLoggedInfo->user_logged_info_id,
                'updateUserId' => $userId
            ];

        } else {
            $findDefaultUserRoleByUserId = new FindDefaultUserRoleByUserId();
            $userRole = $findDefaultUserRoleByUserId->execute([
                'userId'=>$user->user_id
            ]);
            $isNewClient = true;
            $userLoggedInfoArr = [
                'userId' => $user->user_id,
                'userIp' => $ip,
                'userDevice' => $device,
                'userBrowser' => $browser,
                'userToken' => $userToken,
                'userCurrentRoleId' => $userRole->role_id,
                'createUserId' => $userId,
                'updateUserId' => $userId,
                'version' => 0
            ];
            $this->activated($userLoggedInfoArr);
        }

        $input['isNewClient'] = $isNewClient;
        $input['userLoggedInfoArr'] = $userLoggedInfoArr;


    }

    protected function process(&$input, $oriInput)
    {
        $isNewClient = $input['isNewClient'];
        $userLoggedInfoArr = $input['userLoggedInfoArr'];

        if($isNewClient) {

            // Insert data user logged info
            $userLoggedInfo = new UserLoggedInfo();
            $userLoggedInfo = ModelUtil::convertArrayToModel($userLoggedInfoArr, $userLoggedInfo);
            $result = $userLoggedInfo->add();

        } else {

            // Update data user logged info
            $userLoggedInfo = UserLoggedInfo::find($userLoggedInfoArr['id']);
            $userLoggedInfo = ModelUtil::convertArrayToModel($userLoggedInfoArr, $userLoggedInfo);
            $result = $userLoggedInfo->edit();

        }

        $data = array('name'=>"Congky", 'text'=>'Anda baru saja login');

//        CoreMail::send('mail',$data);

        return $result;
    }

    function getDescription()
    {
        return "Untuk melakukan authentikasi login";
    }
}