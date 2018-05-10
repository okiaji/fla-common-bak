<?php
namespace FLA\Common\BusinessObject\BusinessTransaction\role;

use FLA\Common\BusinessObject\BusinessFunction\role\FindRoleById;
use FLA\Common\BusinessObject\BusinessFunction\role\GetRoleTaskListByRoleId;
use FLA\Common\BusinessObject\BusinessFunction\user\GetUserRoleListByRoleId;
use FLA\Common\Model\Role;
use FLA\Common\Model\RoleTask;
use FLA\Common\Model\UserRole;
use FLA\Core\AbstractBusinessTransaction;
use FLA\Core\Util\ValidationUtil;

class RemoveRole extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoginId");
        ValidationUtil::valBlankOrNull($input, "roleLoginId");
        ValidationUtil::valBlankOrNull($input, "id");

        $userLoginId = $input['userLoginId'];
        $roleLoginId = $input['roleLoginId'];
        $id = $input['id'];

        $findRole = new FindRoleById();
        $role = $findRole->execute([
            'id'=>$id
        ]);
        $input['roleArr'] = $role;

        $getRoleTaskListByRoleId = new GetRoleTaskListByRoleId();
        $roleTaskList = $getRoleTaskListByRoleId->execute([
            "roleId"=>$id
        ]);
        $input["roleTaskList"] = $roleTaskList["roleTaskList"];

        $getUserRoleListByRoleId = new GetUserRoleListByRoleId();
        $userRoleList = $getUserRoleListByRoleId->execute([
            "roleId"=>$id
        ]);
        $input["userRoleList"] = $userRoleList["userRoleList"];
    }

    protected function process(&$input, $oriInput)
    {
        $roleArr = $input['roleArr'];
        $roleTaskList = $input['roleTaskList'];
        $userRoleList = $input['userRoleList'];

        // Delete data role
        $role = Role::find($roleArr['role_id']);
        $role->delete();

        if($roleTaskList!=null && !empty($roleTaskList)) {

            foreach($roleTaskList as $value) {
                $roleTask = RoleTask::find($value->role_task_id);
                $roleTask->delete();
            }
        }

        if($userRoleList!=null && !empty($userRoleList)) {

            foreach($userRoleList as $value) {
                $userRole = UserRole::find($value->user_role_id);
                $userRole->delete();
            }
        }

        return ['id'=>$roleArr['role_id']];
    }

    function getDescription()
    {
        return "Untuk menghapus data role";
    }
}