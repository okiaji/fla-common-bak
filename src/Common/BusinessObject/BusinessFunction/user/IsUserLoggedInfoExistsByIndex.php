<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\Model\UserLoggedInfo;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\Util\ValidationUtil;

class IsUserLoggedInfoExistsByIndex extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'userId');
        ValidationUtil::valBlankOrNull($input, 'userIp');
        ValidationUtil::valBlankOrNull($input, 'userDevice');
        ValidationUtil::valBlankOrNull($input, 'userBrowser');

        $userId = $input['userId'];
        $userIp = $input['userIp'];
        $userDevice = $input['userDevice'];
        $userBrowser = $input['userBrowser'];

        $userLoggedInfo = UserLoggedInfo::where([
            ['user_id', $userId],
            ['user_ip', $userIp],
            ['user_device', $userDevice],
            ['user_browser', $userBrowser],
        ])->first();

        $result = [
            'exists' => false
        ];

        if ($userLoggedInfo != null) {
            $result = [
                'exists' => true,
                'userLoggedInfo' => $userLoggedInfo
            ];
        }

        return $result;
    }

    function getDescription()
    {
        return "Untuk mencari data user logged info by index";
    }
}