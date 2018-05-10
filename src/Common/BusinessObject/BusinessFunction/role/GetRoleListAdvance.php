<?php
namespace FLA\Common\BusinessObject\BusinessFunction\role;

use FLA\Common\CommonConstant;
use FLA\Common\Model\Role;
use FLA\Core\AbstractBusinessFunction;
use FLA\Core\ConditionExpression;
use FLA\Core\QueryBuilder;
use FLA\Core\Util\ValidationUtil;
use Illuminate\Support\Facades\DB;

class GetRoleListAdvance extends AbstractBusinessFunction
{

    protected function process($input, $oriInput)
    {
        ValidationUtil::valContainsKey($input, 'code');
        ValidationUtil::valContainsKey($input, 'name');
        ValidationUtil::valContainsKey($input, 'desc');

        $code = $input['code'];
        $name = $input['name'];
        $desc = $input['desc'];
        $limit = empty($input['limit'])?CommonConstant::$EMPTY_VALUE:$input['limit'];
        $offset = empty($input['offset'])?CommonConstant::$EMPTY_VALUE:$input['offset'];

        $builder = new QueryBuilder();
        $builder->add(' SELECT A.role_id, A.role_code, A.role_name, A.role_desc, A.active ')
                ->add(' FROM ')->add(Role::getTableName())->add(' A ')
                ->add(' WHERE true ')
                ->addIfNotEmpty($code, ' AND '.ConditionExpression::likeCaseInsensitive('A.role_code', $code))
                ->addIfNotEmpty($name, ' AND '.ConditionExpression::likeCaseInsensitive('A.role_name', $name))
                ->addIfNotEmpty($desc, ' AND '.ConditionExpression::likeCaseInsensitive('A.role_desc', $desc))
                ->add(' ORDER BY A.role_code, A.role_name ')
                ->addIfNotEmpty($limit, ' LIMIT '.$limit)
                ->addIfNotEmpty($offset,' OFFSET '.$offset);
        $role = DB::select($builder->toString());

        return [
            "roleList"=>$role
        ];
    }

    function getDescription()
    {
        return "Digunakan untuk mendapatkan list role";
    }
}