<?php
namespace FLA\Common\BusinessObject\BusinessFunction\role;

use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\Role;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\CoreException;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class FindCurrentUserRoleByToken extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "token");

        $token = $input['token'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.role_id, A.role_code, A.role_name, A.role_desc ')
            ->add(' FROM ')->add(Role::getTableName())->add(' A ')
            ->add(' INNER JOIN ')->add(UserLoggedInfo::getTableName())->add(' B ON A.role_id = B.user_current_role_id ')
            ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("B.user_token", $token));
        $role = DB::select($builder->toString());

        if(!empty($role) && $role[0]!=null) {
            return $role[0];
        } else {
            throw new CoreException(CommonExceptionsConstant::$CURRENT_USER_ROLE_IS_NOT_FOUND_BY_TOKEN, $token);
        }
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan current role dari token yang dikirim";
    }
}