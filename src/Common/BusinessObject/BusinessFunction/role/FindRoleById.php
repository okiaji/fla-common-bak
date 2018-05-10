<?php
namespace FLA\Common\BusinessObject\BusinessFunction\role;

use FLA\Common\CommonExceptionsConstant;
use FLA\Common\Model\Role;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\CoreException;
use FLA\Core\Util\ValidationUtil;

class FindRoleById extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valContainsKey($input, 'id');

        $id = $input['id'];

        $role = Role::find($id);

        if($role==null) {
            throw new CoreException(CommonExceptionsConstant::$DATA_NOT_FOUND, "Role", $id);
        }

        return $role;
    }

    function getDescription()
    {
        return "Digunakan untuk melakukan pengecekan apakah role yang dikirim benar terdaftar";
    }
}