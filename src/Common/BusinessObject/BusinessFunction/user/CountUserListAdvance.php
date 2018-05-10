<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\Model\UserAdditionalInfo;
use FLA\Common\Model\UserType;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use FLA\Common\Model\User;
use Illuminate\Support\Facades\DB;

class CountUserListAdvance extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valContainsKey($input, 'username');
        ValidationUtil::valContainsKey($input, 'fullName');
        ValidationUtil::valContainsKey($input, 'email');
        ValidationUtil::valContainsKey($input, 'phoneNumber');

        $username = $input['username'];
        $fullName = $input['fullName'];
        $email = $input['email'];
        $phoneNumber = $input['phoneNumber'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT COUNT(1) AS count ')
                ->add(' FROM ')->add(User::getTableName())->add(' A ')
                ->add(' INNER JOIN ')->add(UserAdditionalInfo::getTableName())->add(' B ')
                ->add(' ON A.user_id = B.user_id ')
                ->add(' INNER JOIN ')->add(UserType::getTableName())->add(' C ')
                ->add(' ON B.user_type_id = C.user_type_id ')
                ->add(' WHERE true ')
                ->addIfNotEmpty($username, ' AND '.ConditionExpression::likeCaseInsensitive('A.username', $username))
                ->addIfNotEmpty($fullName, ' AND '.ConditionExpression::likeCaseInsensitive('A.full_name', $fullName))
                ->addIfNotEmpty($email, ' AND '.ConditionExpression::likeCaseInsensitive('A.email', $email))
                ->addIfNotEmpty($phoneNumber, ' AND '.ConditionExpression::likeCaseInsensitive('B.phone_number', $phoneNumber));
        $user = DB::select($builder->toString());

        return [
            "count"=>$user[0]->count
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan list user";
    }
}