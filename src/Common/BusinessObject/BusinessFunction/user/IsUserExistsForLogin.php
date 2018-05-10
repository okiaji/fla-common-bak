<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\Model\User;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\Util\ValidationUtil;

class IsUserExistsForLogin extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'usernameOrEmail');
        ValidationUtil::valBlankOrNull($input, 'password');

        $usernameOrEmail = $input['usernameOrEmail'];
        $password = $input['password'];

        $user = User::where([
                    ['username', $usernameOrEmail],
                    ['password', $password],
                ])
                ->orWhere([
                    ['email', $usernameOrEmail],
                    ['password', $password],
                ])->first();

        $result = [
            'exists' => false
        ];

        if ($user != null) {
            $result = [
                'exists' => true,
                'user' => $user
            ];
        }

        return $result;
    }

    function getDescription()
    {
        return "Untuk mencari data user by username atau email dan password";
    }
}