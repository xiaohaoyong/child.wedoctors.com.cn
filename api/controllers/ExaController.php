<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/2/5
 * Time: 下午3:32
 */

namespace api\controllers;

use api\controllers\Controller;

use common\models\Examination;

class ExaController extends Controller
{
    public function actionChildEx($id){
        $ex=Examination::find()->andFilterWhere(['childid'=>$id])->orderBy('field52 desc,field4 desc,field2 desc,field3 desc')->all();
        $data=[];
        foreach($ex as $k=>$v)
        {
            $row['id']=$v->id;
            $row['tizhong']=$v->field70;
            $row['shenchang']=$v->field40;
            $row['touwei']=$v->field80;
            $row['bmi']=$v->field20;
            $row['feipang']=$v->field53;
            $row['fayu']=$v->field35;
            $row['yingyang']=$v->field15;
            $row['title']=$v->field2."岁".$v->field3."月体检";
            $row['next']=$v->field52;
            $row['date']=$v->field4;

            $data[]=$row;
        }
        return $data;
    }
    public function actionChildExView($id){
        $ex=Examination::findOne($id);
        $exa=new Examination();
        $field=$exa->attributeLabels();
        $data=[];
        if($ex) {
            $list = $ex->toArray();
            unset($list['id']);
            unset($list['childid']);
            unset($list['source']);
            unset($list['field6']);
            unset($list['field7']);
            unset($list['field8']);
            unset($list['field11']);
            unset($list['field12']);
            unset($list['field13']);
            unset($list['field14']);
            unset($list['field17']);
            unset($list['field24']);
            unset($list['field25']);
            unset($list['field30']);
            unset($list['field31']);
            unset($list['field33']);
            unset($list['field34']);
            unset($list['field36']);
            unset($list['field37']);
            unset($list['field38']);
            unset($list['field39']);
            unset($list['field42']);
            unset($list['field43']);
            unset($list['field45']);
            unset($list['field46']);
            unset($list['field47']);
            unset($list['field49']);
            unset($list['field51']);
            unset($list['field54']);
            unset($list['field55']);
            unset($list['field56']);
            unset($list['field57']);
            unset($list['field58']);
            unset($list['field59']);
            unset($list['field60']);
            unset($list['field61']);
            unset($list['field62']);
            unset($list['field63']);
            unset($list['field64']);
            unset($list['field66']);
            unset($list['field67']);
            unset($list['field69']);
            unset($list['field74']);
            unset($list['field75']);
            unset($list['field76']);
            unset($list['field77']);
            unset($list['field78']);
            unset($list['field79']);
            unset($list['field81']);
            unset($list['field82']);
            unset($list['field83']);
            unset($list['field84']);
            unset($list['field85']);
            unset($list['field86']);
            unset($list['field87']);
            unset($list['field88']);
            unset($list['field89']);
            unset($list['field90']);
            unset($list['field91']);
            unset($list['field92']);
            foreach ($list as $k => $v){
                $row['name']=$field[$k];
                $row['value']=$v;
                $data[$k]=$row;
            }
        }
        return $data;
    }
}