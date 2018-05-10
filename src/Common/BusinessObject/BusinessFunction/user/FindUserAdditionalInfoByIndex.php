<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\UserAdditionalInfo;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\CoreException;
use FLA\Core\Util\ValidationUtil;

class FindUserAdditionalInfoByIndex extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'userId');

        $userId = $input['userId'];
        $userAdditionalInfo = UserAdditionalInfo::where('user_id', '=', $userId)->first();
        if ($userAdditionalInfo == null) {
            throw new CoreException(CommonExceptionsConstant::$USER_ADDITIONAL_INFO_IS_NOT_FOUND_WITH_INDEX, $userId);
        }

        return $userAdditionalInfo;
    }

    function getDescription()
    {
        return "Digunakan untuk mengambil data additional info user by index";
    }
}