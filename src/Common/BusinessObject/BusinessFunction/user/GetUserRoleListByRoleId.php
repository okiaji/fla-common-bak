<?php
namespace FLA\Common\BusinessObject\BusinessFunction\user;

use FLA\Common\Model\UserRole;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetUserRoleListByRoleId extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valContainsKey($input, 'roleId');

        $roleId = $input['roleId'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.user_role_id, A.user_id, A.role_id, A.flg_default, A.version ')
                ->add(' FROM ')->add(UserRole::getTableName())->add(' A ')
                ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("A.role_id", $roleId));
        $userRole = DB::select($builder->toString());

        return [
            "userRoleList"=>$userRole
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan list user role by role id";
    }
}