<?php
namespace FLA\Common\BusinessObject\BusinessFunction\task;

use FLA\Common\Model\RoleTask;
use FLA\Common\Model\Task;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Common\Model\UserRole;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class IsTaskValidForCurrentRole extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "token");
        ValidationUtil::valBlankOrNull($input, "taskList");

        $token = $input['token'];
        $taskList = $input['taskList'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT count(1) AS resultCount ')
            ->add(' FROM ')->add(Task::getTableName())->add(' A ')
            ->add(' INNER JOIN ')->add(RoleTask::getTableName())->add(' B ON A.task_id = B.task_id ')
            ->add(' INNER JOIN ')->add(UserRole::getTableName())->add(' C ON B.role_id = C.role_id ')
            ->add(' INNER JOIN ')->add(UserLoggedInfo::getTableName())->add(' D ON C.user_id = D.user_id ')
            ->add(' AND ')->add(ConditionExpression::equalCaseSensitive("D.user_token", $token))
            ->add(' WHERE ')->add(ConditionExpression::inListCaseInsensitive("A.task_code", $taskList));
        $task = DB::select($builder->toString());
        if(!empty($task) && $task[0]!=null && ($task[0])->resultcount > 0) {
            return true;
        }
        return false;
    }

    function getDescription()
    {
        return "Digunakan untuk melakukan pengecekan apakah current user role berhak atas task-task yang dikirim";
    }
}