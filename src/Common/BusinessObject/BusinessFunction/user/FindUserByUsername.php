<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonExceptionsConstant;
use FLA\Core\AbstractBusinessFunction;
use FLA\Common\Model\User;
use FLA\Core\CoreException;
use FLA\Core\Util\ValidationUtil;

class FindUserByUsername extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'username');
        $username = $input['username'];

        $user = User::where('username', '=', $username)->first();
        if ($user == null) {
            throw new CoreException(CommonExceptionsConstant::$USER_WITH_USERNAME_NOT_FOUND, $username);
        }

        return $user;
    }

    function getDescription()
    {
        return "Digunakan untuk mengambil data user by username yang dikirim";
    }
}