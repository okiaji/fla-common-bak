<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\Model\UserRole;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetUserRoleListByUserId extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valContainsKey($input, 'userId');

        $userId = $input['userId'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.user_role_id, A.user_id, A.role_id, A.flg_default, A.version ')
                ->add(' FROM ')->add(UserRole::getTableName())->add(' A ')
                ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("A.user_id", $userId));
        $userRole = DB::select($builder->toString());

        return [
            "userRoleList"=>$userRole
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan list user role by user id";
    }
}