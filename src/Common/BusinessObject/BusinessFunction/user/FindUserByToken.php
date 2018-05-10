<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\User;
use FLA\Common\Model\UserAdditionalInfo;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Common\Model\UserType;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\CoreException;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class FindUserByToken extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'token');

        $token = $input['token'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.user_id, A.username, A.full_name, A.email, B.phone_number, B.religion, B.date_of_birth, ')
                ->add(' B.place_of_birth, B.country, B.full_address, C.user_type_name, A.active ')
                ->add(' FROM ')->add(User::getTableName())->add(' A ')
                ->add(' INNER JOIN ')->add(UserAdditionalInfo::getTableName())->add(' B ')
                ->add(' ON A.user_id = B.user_id ')
                ->add(' INNER JOIN ')->add(UserType::getTableName())->add(' C ')
                ->add(' ON B.user_type_id = C.user_type_id ')
                ->add(' INNER JOIN ')->add(UserLoggedInfo::getTableName())->add(' D ')
                ->add(' ON A.user_id = D.user_id ')
                ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive('D.user_token', $token));
        $user = DB::select($builder->toString());

        if ($user[0] == null) {
            throw new CoreException(CommonExceptionsConstant::$TOKEN_IS_NOT_VALID);
        }

        return $user[0];

    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan informasi user berdasarkan token yang dikirim";
    }
}