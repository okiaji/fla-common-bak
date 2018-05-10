<?php
namespace FLA\Common\BusinessObject\BusinessTransaction\role;

use FLA\Common\BusinessObject\BusinessFunction\role\FindRoleById;
use FLA\Common\CommonConstant;
use FLA\Common\Model\Role;
use FLA\Core\AbstractBusinessTransaction;
use FLA\Core\Util\ModelUtil;
use FLA\Core\Util\ValidationUtil;

class EditRole extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoginId");
        ValidationUtil::valBlankOrNull($input, "roleLoginId");
        ValidationUtil::valBlankOrNull($input, "id");
        ValidationUtil::valBlankOrNull($input, "roleName");
        ValidationUtil::valBlankOrNull($input, "roleDesc");

        $userLoginId = $input['userLoginId'];
        $roleLoginId = $input['roleLoginId'];
        $id = $input['id'];
        $roleName = $input['roleName'];
        $roleDesc = $input['roleDesc'];
        $active = $input['active'];

        $findRole = new FindRoleById();
        $role = $findRole->execute([
            'id'=>$id
        ]);

        $roleArr = [
            'id' => $role->role_id,
            'roleName' => $roleName,
            'roleDesc' => $roleDesc,
            'updateUserId' => $userLoginId
        ];
        if($role->active != $active) {
            if (CommonConstant::$YES == $active) {
                $this->activated($roleArr);
            } else if (CommonConstant::$NO == $active) {
                $this->deActivated($roleArr);
            }
        }

        $input['roleArr'] = $roleArr;
    }

    protected function process(&$input, $oriInput)
    {
        $roleArr = $input['roleArr'];

        // Update data role
        $role = Role::find($roleArr['id']);
        $role = ModelUtil::convertArrayToModel($roleArr, $role);
        $result = $role->edit();

        return $result;
    }

    function getDescription()
    {
        return "Untuk mengubah data role";
    }
}