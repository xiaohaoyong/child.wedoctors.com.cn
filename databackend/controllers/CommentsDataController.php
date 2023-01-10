<?php

namespace databackend\controllers;


use common\models\AppointComment;
use common\models\Question;
use common\models\QuestionComment;
use common\models\UserDoctor;
use Yii;
use yii\web\notFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Class DataCommentController
 * @package backend\Controllers
 * 评价统计
 */
class CommentsDataController extends BaseController
{
    public $county;
    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * doctorn 社区名称
     * ap_total 预约就诊总数
     * gd_total 就诊好评率
     * qd_total 回复率
     * qd_g_total 回复满意率
     * qd_gr_total 解决率
     */
    public function actionIndex()
    {

		$arr_data=array();
        $arr_datas=array();
		$doctor = UserDoctor::find()->andFilterWhere(['county' => \Yii::$app->user->identity->county])->andWhere(['is_guanfang'=>0])->andFilterWhere(['>', 'userid', 37])->all();
		$apm=Yii::$app->request->post('UserDoctor');
        $doctorid=$apm['doctorid'];
		if($doctorid>0){
			$doctor=array(
				'userid'=>$doctorid,
			);
		}
		foreach($doctor as $dv){
			$doctorid=$dv['userid'];
			//社区名称
            $doctor = UserDoctor::find()->where(['userid'=>$doctorid])->one();
            $arr_data['name']=$doctor->name;
            //评价总数
            $ap_qy=AppointComment::find()->andWhere(['doctorid'=>$doctorid]);
            if($sdate && $edate){
                $ap_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $arr_data['ap_total']=$ap_qy->count();
            //好评总数及好评率
            $gd_qy=AppointComment::find()->andWhere(['doctorid'=>$doctorid])->andWhere(['is_rate'=>'1']);
            if($sdate && $edate){
                $gd_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $gd_total = $gd_qy->count();
            $arr_data['gd_total'] = $gd_total>0?ceil($gd_total/$arr_data['ap_total']*100):0;

            //问题部分
            $q_totals=Question::find()->andWhere(['doctorid'=>$doctorid])->count();
            $q_total=Question::find()->andWhere(['doctorid'=>$doctorid])->andWhere(['>','state','0'])->count();
            $arr_data['q_gr'] = $q_total?ceil($q_total/$q_totals*100):0;

            //问题评价部分
            $qc_qy=QuestionComment::find()->andWhere(['doctorid'=>$doctorid]);
            if($sdate && $edate){
                $qc_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $qc_total=$qc_qy->count();
            $qc_gd_qy=$qc_qy->andWhere(['is_satisfied'=>'2'])->count();
            $arr_data['qc_gd_c']=$qc_gd_qy?ceil($qc_gd_qy/$qc_total*100):0;
            $qc_gs_qy=$qc_qy->andWhere(['is_solve'=>'2'])->count();
            $arr_data['qc_gs_c']=$qc_gs_qy?ceil($qc_gs_qy/$qc_total*100):0;

            $arr_datas[]=$arr_data;
		}


        /*$appointcomment = new AppointComment();
        $arr_data=array();
        $arr_datas=array();

        $sdate=Yii::$app->request->post('sdate');
        $edate=Yii::$app->request->post('edate');
        $edate = date("Y-m-d",strtotime($edate." 23:59:59"));
        $apm=Yii::$app->request->post('AppointComment');
        $doctorid=$apm['doctorid'];
        if($doctorid){
            //社区名称
            $doctor = UserDoctor::find()->where(['userid'=>$doctorid])->one();
            $arr_data['name']=$doctor->name;
            //评价总数
            $ap_qy=AppointComment::find()->andWhere(['doctorid'=>$doctorid]);
            if($sdate && $edate){
                $ap_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $arr_data['ap_total']=$ap_qy->count();
            //好评总数及好评率
            $gd_qy=AppointComment::find()->andWhere(['doctorid'=>$doctorid])->andWhere(['is_rate'=>'1']);
            if($sdate && $edate){
                $gd_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $gd_total = $gd_qy->count();
            $arr_data['gd_total'] = $gd_total>0?ceil($gd_total/$arr_data['ap_total']*100):0;

            //问题部分
            $q_totals=Question::find()->andWhere(['doctorid'=>$doctorid])->count();
            $q_total=Question::find()->andWhere(['doctorid'=>$doctorid])->andWhere(['>','state','0'])->count();
            $arr_data['q_gr'] = $q_total?ceil($q_total/$q_totals*100):0;

            //问题评价部分
            $qc_qy=QuestionComment::find()->andWhere(['doctorid'=>$doctorid]);
            if($sdate && $edate){
                $qc_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $qc_total=$qc_qy->count();
            $qc_gd_qy=$qc_qy->andWhere(['is_satisfied'=>'2'])->count();
            $arr_data['qc_gd_c']=$qc_gd_qy?ceil($qc_gd_qy/$qc_total*100):0;
            $qc_gs_qy=$qc_qy->andWhere(['is_solve'=>'2'])->count();
            $arr_data['qc_gs_c']=$qc_gs_qy?ceil($qc_gs_qy/$qc_total*100):0;

            $arr_datas[]=$arr_data;
        }else{
            //查询doctorid-就诊评价和回复评价去重
            $query=AppointComment::find()->select('doctorid');
            if($sdate && $edate){
                $query->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $ap_cm = $query->asArray()->all();

            $query_r=QuestionComment::find()->select('doctorid');
            if($sdate && $edate){
                $query_r->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
            }
            $qr_cm = $query_r->asArray()->all();
            $datas=array_merge($ap_cm,$qr_cm);
            $ar_da=array();
            foreach ($datas as $v){
                $ar_da[$v['doctorid']]=$v['doctorid'];
            }
            //以下获取展示数据
            foreach ($ar_da as $a_v){
                $doctorid=intval($a_v);
                $doctor = UserDoctor::find()->where(['userid'=>$doctorid])->one();
                $arr_data['name']=$doctor->name;
                //评价总数
                $ap_qy=AppointComment::find()->andWhere(['doctorid'=>$doctorid]);
                if($sdate && $edate){
                    $ap_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
                }
                $arr_data['ap_total']=$ap_qy->count();
                //好评总数及好评率
                $gd_qy=AppointComment::find()->andWhere(['doctorid'=>$doctorid])->andWhere(['is_rate'=>'1']);
                if($sdate && $edate){
                    $gd_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
                }
                $gd_total = $gd_qy->count();
                $arr_data['gd_total'] = $gd_total>0?ceil($gd_total/$arr_data['ap_total']*100):0;

                //问题部分
                $q_totals=Question::find()->andWhere(['doctorid'=>$doctorid])->count();
                $q_total=Question::find()->andWhere(['doctorid'=>$doctorid])->andWhere(['>','state','0'])->count();
                $arr_data['q_gr'] = $q_total?ceil($q_total/$q_totals*100):0;

                //问题评价部分
                $qc_qy=QuestionComment::find()->andWhere(['doctorid'=>$doctorid]);
                if($sdate && $edate){
                    $qc_qy->andWhere(['>=','createtime',strtotime($sdate)])->andWhere(['<=','createtime',strtotime($edate)]);
                }
                $qc_total=$qc_qy->count();
                $qc_gd_qy=$qc_qy->andWhere(['is_satisfied'=>'2'])->count();
                $arr_data['qc_gd_c']=$qc_gd_qy?ceil($qc_gd_qy/$qc_total*100):0;
                $qc_gs_qy=$qc_qy->andWhere(['is_solve'=>'2'])->count();
                $arr_data['qc_gs_c']=$qc_gs_qy?ceil($qc_gs_qy/$qc_total*100):0;

                $arr_datas[]=$arr_data;
            }
        }*/

		$userdoctor= new UserDoctor;
        return $this->render('index', [
            'arr_datas' => $arr_datas,
            'appointcomment' => $userdoctor,
        ]);
    }

}