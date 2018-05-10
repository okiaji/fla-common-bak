<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonConstant;
use FLA\Common\CommonExceptionsConstant;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\Util\ValidationUtil;

class ValUserLoginId extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'id');

        $id = $input['id'];
        $user = User::where('user_id', '=', $id)->first();
        if ($user == null) {
            throw new CoreException(CommonExceptionsConstant::$DATA_NOT_FOUND, 'User', $id);
        }

        if ($user->active != CommonConstant::$YES) {
            throw new CoreException(CommonExceptionsConstant::$DATA_IS_NOT_ACTIVE, 'User', $id);
        }

        return $user;
    }

    function getDescription()
    {
        "Digunakan untuk mengambil data user by id yang dikirim";
    }
}