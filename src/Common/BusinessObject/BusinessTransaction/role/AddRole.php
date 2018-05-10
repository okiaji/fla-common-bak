<?php
namespace FLA\Common\BusinessObject\BusinessTransaction\role;

use FLA\Common\BusinessObject\BusinessFunction\role\IsRoleExistsByIndex;
use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\Role;
use FLA\Core\AbstractBusinessTransaction;
use FLA\Core\CoreException;
use FLA\Core\Util\DateUtil;
use FLA\Core\Util\ModelUtil;
use FLA\Core\Util\ValidationUtil;

class AddRole extends AbstractBusinessTransaction
{

    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, "userLoginId");
        ValidationUtil::valBlankOrNull($input, "roleLoginId");
        ValidationUtil::valBlankOrNull($input, "roleCode");
        ValidationUtil::valBlankOrNull($input, "roleName");
        ValidationUtil::valBlankOrNull($input, "roleDesc");

        $userLoginId = $input['userLoginId'];
        $roleLoginId = $input['roleLoginId'];
        $roleName = $input['roleName'];
        $roleCode = $input['roleCode'];
        $roleDesc = $input['roleDesc'];
        $datetimeNow = DateUtil::currentDatetime();

        $checkRoleByIndex = new IsRoleExistsByIndex();
        $resultCheckRole = $checkRoleByIndex->execute([
            'roleCode'=>$roleCode
        ]);

        if($resultCheckRole['exists']) {
            throw new CoreException(CommonExceptionsConstant::$ROLE_IS_EXISTS_BY_INDEX, $roleName, $roleCode);
        }

        $roleArr = [
            'roleCode' => $roleCode,
            'roleName' => $roleName,
            'roleDesc' => $roleDesc,
            'version' => 0,
            'createUserId' => $userLoginId,
            'updateUserId' => $userLoginId
        ];
        $this->activated($roleArr);

        $input['roleArr'] = $roleArr;
    }

    protected function process(&$input, $oriInput)
    {
        $roleArr = $input['roleArr'];

        // Insert data role
        $role = new Role();
        $role = ModelUtil::convertArrayToModel($roleArr, $role);
        $result = $role->add();

        return $result;
    }

    function getDescription()
    {
        return "Untuk menambah data role";
    }
}