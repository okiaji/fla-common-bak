<?php
namespace FLA\Common\BusinessObject\BusinessTransaction\user;

use FLA\Common\BusinessObject\BusinessFunction\user\FindUserById;
use FLA\Common\BusinessObject\BusinessFunction\user\GetUserLoggedInfoListByUserId;
use FLA\Common\BusinessObject\BusinessFunction\user\GetUserRoleListByUserId;
use FLA\Common\BusinessObject\BusinessFunction\user\IsUserAdditionalInfoExistsByIndex;
use FLA\Common\Model\User;
use FLA\Common\Model\UserAdditionalInfo;
use FLA\Common\Model\UserLoggedInfo;
use FLA\Common\Model\UserRole;
use FLA\Core\AbstractBusinessTransaction;
use FLA\Core\Util\ValidationUtil;

class RemoveUser extends AbstractBusinessTransaction
{
    protected function prepare(&$input, $oriInput)
    {
        ValidationUtil::valBlankOrNull($input, 'userLogginId');
        ValidationUtil::valBlankOrNull($input, 'userRoleId');
        ValidationUtil::valBlankOrNull($input, 'id');

        $userLogginId = $input['userLogginId'];
        $userRoleId = $input['userRoleId'];
        $id = $input['id'];

        $findUserById = new FindUserById();
        $findUserById->execute([
            "id" => $id
        ]);

        $isUserAdditionalInfoExistsByIndex = new IsUserAdditionalInfoExistsByIndex();
        $resultExistsUserAdditionalInfo = $isUserAdditionalInfoExistsByIndex->execute([
            "userId" => $id
        ]);

        $userAdditionalInfo = [];
        if($resultExistsUserAdditionalInfo['exists']){
            $userAdditionalInfo = $resultExistsUserAdditionalInfo['userAdditionalInfo'];
        }
        $input["userAdditionalInfo"] = $userAdditionalInfo;

        $getUserLoggedInfoListByUserId = new GetUserLoggedInfoListByUserId();
        $userLoggedInfoList = $getUserLoggedInfoListByUserId->execute([
            "userId"=>$id
        ]);
        $input["userLoggedInfoList"] = $userLoggedInfoList["userLoggedInfoList"];

        $getUserRoleListByUserId = new GetUserRoleListByUserId();
        $userRoleList = $getUserRoleListByUserId->execute([
            "userId"=>$id
        ]);
        $input["userRoleList"] = $userRoleList["userRoleList"];

    }

    protected function process(&$input, $oriInput)
    {
        $userAdditionalInfo = $input["userAdditionalInfo"];
        $userLoggedInfoList = $input["userLoggedInfoList"];
        $userRoleList = $input["userRoleList"];

        $user = User::find($input["id"]);
        $user->delete();

        if($userAdditionalInfo!=null && !empty($userAdditionalInfo)) {
            $userAdditionalInfo = UserAdditionalInfo::find($userAdditionalInfo->user_additional_info_id);
            $userAdditionalInfo->delete();
        }

        if($userLoggedInfoList!=null && !empty($userLoggedInfoList)) {

            foreach($userLoggedInfoList as $value) {
                $userLoggedInfo = UserLoggedInfo::find($value->user_logged_info_id);
                $userLoggedInfo->delete();
            }
        }

        if($userRoleList!=null && !empty($userRoleList)) {

            foreach($userRoleList as $value) {
                $userRole = UserRole::find($value->user_role_id);
                $userRole->delete();
            }
        }
    }

    function getDescription()
    {
        return "Digunakan untuk menghapus user";
    }
}