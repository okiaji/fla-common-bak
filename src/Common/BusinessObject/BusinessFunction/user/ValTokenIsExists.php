<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonConstant;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\CoreException;
use FLA\Core\Util\ValidationUtil;

class ValTokenIsExists extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'token');

        $token = $input['token'];

        $userLoggedInfo = UserLoggedInfo::where([
            ['user_token', $token],
            ['active', CommonConstant::$YES]
        ])->first();

        if ($userLoggedInfo == null) {
            throw new CoreException('Not Authorized');
        }

        return null;
    }

    function getDescription()
    {
        return "Digunakan untuk memastikan apakah token yang dikirim benar terdaftar";
    }
}