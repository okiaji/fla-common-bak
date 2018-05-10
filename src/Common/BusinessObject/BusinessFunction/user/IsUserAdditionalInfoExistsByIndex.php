<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\Model\UserAdditionalInfo;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\Util\ValidationUtil;

class IsUserAdditionalInfoExistsByIndex extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'userId');

        $userId = $input['userId'];
        $userAdditionalInfo = UserAdditionalInfo::where('user_id', '=', $userId)->first();

        $result = [
            'exists' => false
        ];
        if ($userAdditionalInfo != null) {
            $result = [
                'exists' => true,
                'userAdditionalInfo' => $userAdditionalInfo
            ];
        }

        return $result;
    }

    function getDescription()
    {
        return "Digunakan untuk mengambil data additional info user by index";
    }
}