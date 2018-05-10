<?php
namespace FLA\Common\BusinessObject\BusinessFunction\role;

use FLA\Common\Model\RoleTask;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetRoleTaskListByRoleId extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valContainsKey($input, 'roleId');

        $roleId = $input['roleId'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.role_task_id, A.task_id, A.role_id, A.version ')
                ->add(' FROM ')->add(RoleTask::getTableName())->add(' A ')
                ->add(' WHERE ')->add(ConditionExpression::equalCaseSensitive("A.role_id", $roleId));
        $roleTask = DB::select($builder->toString());

        return [
            "roleTaskList"=>$roleTask
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan list role task by role id";
    }
}