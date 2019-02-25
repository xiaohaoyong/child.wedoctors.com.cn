<?php

namespace console\models;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/13
 * Time: 下午3:23
 */
use common\models\ChildInfo;
use common\models\Examination;
use common\models\User;
use common\models\UserLogin;
use common\models\UserParent;

class ExInput
{

    /**
     * 主操作函数
     * @param $value
     */
    public function inputData($row,$hospitalid)
    {
        //if($row['field4']<'2018-01-01 00:00:00'){ echo "end\n";return;}

        $row['field4'] = substr($row['field4'], 0, strlen($row['field4']) - 11);
        $ex = Examination::find()->andFilterWhere(['field1' => $row['field1']])
            ->andFilterWhere(['field2' => $row['field2']])
            ->andFilterWhere(['field3' => $row['field3']])
            ->andFilterWhere(['field4' => $row['field4']])
            ->andFilterWhere(['source' => $hospitalid])
            ->andFilterWhere(['field19' => $row['field19']])->one();
        if($ex){ echo "jump\n";return 'jump';}
        $ex = $ex ? $ex : new Examination();

        $child = ChildInfo::find()->andFilterWhere(['name' => trim($row['field1'])])
            ->andFilterWhere(['birthday' => strtotime($row['field19'])])
            ->andFilterWhere(['source' => $hospitalid])
            ->one();
        echo $row['field1'];
        $row['source']=$hospitalid;



        if (!$child) {
            echo "--儿童不存在";
            // $childData['childid'] = 0;
        } else {
            echo "--儿童存在";
            $row['childid'] = $child->id;
        }

        $ex->load(['Examination'=>$row]);
        $ex->save();
        if ($ex->firstErrors) {
            echo "error";
            var_dump($ex->firstErrors);
        }
        echo "\n";
        return 'true';
    }
}