<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonConstant;
use FLA\Common\Model\UserAdditionalInfo;
use FLA\Common\Model\UserType;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use FLA\Common\Model\User;
use Illuminate\Support\Facades\DB;

class GetUserListAdvance extends AbstractBusinessFunction
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
        $limit = empty($input['limit'])?CommonConstant::$EMPTY_VALUE:$input['limit'];
        $offset = empty($input['offset'])?CommonConstant::$EMPTY_VALUE:$input['offset'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.username, A.full_name, A.email, B.phone_number, B.religion, B.date_of_birth, ')
                ->add(' B.place_of_birth, B.country, B.full_address, C.user_type_name, A.active ')
                ->add(' FROM ')->add(User::getTableName())->add(' A ')
                ->add(' INNER JOIN ')->add(UserAdditionalInfo::getTableName())->add(' B ')
                ->add(' ON A.user_id = B.user_id ')
                ->add(' INNER JOIN ')->add(UserType::getTableName())->add(' C ')
                ->add(' ON B.user_type_id = C.user_type_id ')
                ->add(' WHERE true ')
                ->addIfNotEmpty($username, ' AND '.ConditionExpression::likeCaseInsensitive('A.username', $username))
                ->addIfNotEmpty($fullName, ' AND '.ConditionExpression::likeCaseInsensitive('A.full_name', $fullName))
                ->addIfNotEmpty($email, ' AND '.ConditionExpression::likeCaseInsensitive('A.email', $email))
                ->addIfNotEmpty($phoneNumber, ' AND '.ConditionExpression::likeCaseInsensitive('B.phone_number', $phoneNumber))
                ->add(' ORDER BY A.full_name ')
                ->addIfNotEmpty($limit, ' LIMIT '.$limit)
                ->addIfNotEmpty($offset,' OFFSET '.$offset);
        $user = DB::select($builder->toString());

        return [
            "userList"=>$user
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan list user";
    }
}