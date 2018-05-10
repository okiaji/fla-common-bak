<?php
namespace FLA\Common\BusinessObject\BusinessFunction\role;

use FLA\Common\CommonConstant;
use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\UserRole;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\Util\ValidationUtil;

class FindDefaultUserRoleByUserId extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userId");

        $userId = $input['userId'];

        $userRole = UserRole::where([
            ['user_id', $userId],
            ['flg_default', CommonConstant::$YES],
        ])->first();

        if ($userRole == null) {
            throw new CoreException(CommonExceptionsConstant::$DEFAULT_USER_ROLE_IS_NOT_FOUND, $userId);
        }

        return $userRole;
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan default role id dari user id yang dikirim";
    }
}