<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\User;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\CoreException;
use FLA\Core\Util\ValidationUtil;

class FindUserById extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'id');

        $id = $input['id'];
        $user = User::find($id);
        if ($user == null) {
            throw new CoreException(CommonExceptionsConstant::$DATA_NOT_FOUND, 'User', $id);
        }

        return $user;
    }

    function getDescription()
    {
        return "Digunakan untuk mengambil data user by id yang dikirim";
    }
}