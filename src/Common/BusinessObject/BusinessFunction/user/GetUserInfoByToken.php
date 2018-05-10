<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\CommonConstant;
use FLA\Common\Model\Role;
use FLA\Common\Model\User;
use FLA\Common\Model\UserAdditionalInfo;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Common\Model\UserRole;
use FLA\Common\Model\UserType;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetUserInfoByToken extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'token');

        $token = $input['token'];

        // get user information
        $builder = new QueryBuilder();
        $builder->add(' SELECT A.user_token, B.username, B.full_name, D.user_type_code, ')
                ->add(' D.user_type_name, E.role_code AS current_role_code, E.role_name AS current_role_name ')
                ->add(' FROM ')->add(UserLoggedInfo::getTableName())->add(' A ')
                ->add(' INNER JOIN ')->add(User::getTableName())->add(' B ON A.user_id = B.user_id ')
                ->add(' INNER JOIN ')->add(UserAdditionalInfo::getTableName())->add(' C ON A.user_id = C.user_id ')
                ->add(' INNER JOIN ')->add(UserType::getTableName())->add(' D ON C.user_type_id = D.user_type_id ')
                ->add(' INNER JOIN ')->add(Role::getTableName())->add(' E ON A.user_current_role_id = E.role_id ')
                ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("A.user_token", $token))
                ->add(' AND ')->add(ConditionExpression::equalCaseSensitive("A.active", CommonConstant::$YES));
        $userInfo = DB::select($builder->toString());

        // get user role list
        $builder = new QueryBuilder();
        $builder->add(' SELECT C.role_code, C.role_name, C.role_desc, B.flg_default ')
            ->add(' FROM ')->add(UserLoggedInfo::getTableName())->add(' A ')
            ->add(' INNER JOIN ')->add(UserRole::getTableName())->add(' B ON A.user_id = B.user_id ')
            ->add(' INNER JOIN ')->add(Role::getTableName())->add(' C ON B.role_id = C.role_id ')
            ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("A.user_token", $token))
            ->add(' AND ')->add(ConditionExpression::equalCaseSensitive("A.active", CommonConstant::$YES));
        $userRoleList = DB::select($builder->toString());

        // get current role
        $builder = new QueryBuilder();
        $builder->add(' SELECT C.role_code, C.role_name, C.role_desc, B.flg_default ')
            ->add(' FROM ')->add(UserLoggedInfo::getTableName())->add(' A ')
            ->add(' INNER JOIN ')->add(UserRole::getTableName())->add(' B ON A.user_id = B.user_id AND A.user_current_role_id = B.role_id ')
            ->add(' INNER JOIN ')->add(Role::getTableName())->add(' C ON B.role_id = C.role_id ')
            ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("A.user_token", $token))
            ->add(' AND ')->add(ConditionExpression::equalCaseSensitive("A.active", CommonConstant::$YES));
        $currentRole = DB::select($builder->toString());

        return [
            "userInfo"=>$userInfo[0],
            "userRoleList"=>$userRoleList,
            "currentRole"=>$currentRole[0]
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan informasi user dari token yang dikirim";
    }
}