<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/12/19
 * Time: 上午11:43
 */

namespace backend\controllers;


use common\models\ChildInfo;
use common\models\Examination;
use common\models\Pregnancy;
use common\models\UserLogin;
use common\models\UserParent;
use yii\db\Expression;

class DataController extends BaseController
{
    public function actionIndex(){

        $child=ChildInfo::find()->count();
        $parent=UserParent::find()->count();
        $login=UserLogin::find()->where(['>','Logintime',0])->count();
        $preg=Pregnancy::find()->count();

        return $this->render('index',[
            'child'=>$child,
            'parent'=>$parent,
            'login'=>$login,
            'preg'=>$preg,

        ]);
    }
    public function actionParent(){
//        //select SUBSTRING_INDEX(field28,"-",1) as a,count(*) from user_parent group by SUBSTRING_INDEX(field28,"-",1);
//        $parent=UserParent::find()->select(new Expression("SUBSTRING_INDEX(field28,'-',1) as a,count(*) as b"))->groupBy(new Expression("SUBSTRING_INDEX(field28,'-',1)"))->asArray()->all();
//        foreach($parent as $k=>$v){
//            $rs[$v['a']]=$v['b'];
//        }
//
//        $parent1=ChildInfo::find()->select('count(*)')->indexBy('field38')->groupBy('field38')->asArray()->column();
//

        $v[]=ChildInfo::find()->where(['>','birthday',strtotime('-2 year')])->count();
        $v[]=ChildInfo::find()->where(['>','birthday',strtotime('-3 year')])->andWhere(['<','birthday',strtotime('-2 year')])->count();
        $v[]=ChildInfo::find()->where(['>','birthday',strtotime('-4 year')])->andWhere(['<','birthday',strtotime('-3 year')])->count();
        $v[]=ChildInfo::find()->where(['>','birthday',strtotime('-5 year')])->andWhere(['<','birthday',strtotime('-4 year')])->count();
        $v[]=ChildInfo::find()->where(['>','birthday',strtotime('-6 year')])->andWhere(['<','birthday',strtotime('-5 year')])->count();
        $v[]=ChildInfo::find()->where(['<','birthday',strtotime('-6 year')])->count();

        foreach($v as $k=>$c){
            echo $c."<br>";
        }
        exit;

        return $this->render('parent',[
            'age'=>$rs,
            'parent1'=>$parent1,
        ]);
    }
    public function actionChild(){

        return $this->render('child');
    }

}