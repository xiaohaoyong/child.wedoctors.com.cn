<?php

namespace databackend\controllers;

use common\models\Article;
use common\models\ArticleInfo;
use common\models\ChildInfo;
use databackend\models\user\UserDoctor;
use Yii;
use common\models\ArticleUser;
use databackend\models\article\ArticleUserSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleUserController implements the CRUD actions for ArticleUser model.
 */
class ArticleUserController extends BaseController
{

    /**
     * Lists all ArticleUser models.
     * @return mixed
     */
    public function actionIndex($userid=0)
    {
        if($userid)
        {
            Yii::$app->request->queryParams['ArticleUserSearchModel']['userid']=$userid;
        }
        $searchModel = new ArticleUserSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownload($childid=0)
    {

        $chileInfo=ChildInfo::findOne($childid);
        $excel=new \PHPExcel();
        $excel->setActiveSheetIndex(0);


        //报表头的输出
        $excel->getActiveSheet()->mergeCells('A1:N1');
        //设置居中
        $excel->getActiveSheet()->getStyle('A1:N1')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->getActiveSheet()->getStyle('A2:N6')
            ->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中

        $excel->getActiveSheet()->getStyle('A2:N3')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->getStyle('A5:N6')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->getActiveSheet()->setCellValue('A1',$chileInfo->name.'-儿童中医药健康管理服务表');
        $excel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(24);
        $excel->setActiveSheetIndex(0)->getStyle('A1:N6')->getFont()->setName('宋体');
        $excel->getActiveSheet()->getStyle('A2:N6')->getAlignment()->setWrapText(true);
        $excel->getActiveSheet()->getStyle('A1:A6')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $excel->setActiveSheetIndex(0)->getStyle('A2:N6')->getFont()->setSize(16);


        $excel->setActiveSheetIndex(0)->setCellValue('A2','月龄');
        $excel->setActiveSheetIndex(0)->setCellValue('A3','随访日期');
        $excel->setActiveSheetIndex(0)->setCellValue('A4','中医药健康管理服务');
        $excel->setActiveSheetIndex(0)->setCellValue('A5','下次随访日期');
        $excel->setActiveSheetIndex(0)->setCellValue('A6','随访医生签名');
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $excel->getActiveSheet()->getRowDimension('3')->setRowHeight(30);
        $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(30);
        $excel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);

        $excel->getActiveSheet()->getStyle('A1:N6')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);;

        $chile_type=Article::$childText;

        $ascii=66;
        foreach($chile_type as $k=>$v)
        {
            $articleUser=ArticleUser::find()->where(['childid'=>$childid,'child_type'=>$k])->all();



            $t = chr($ascii);
            $excel->setActiveSheetIndex(0)->setCellValue($t."2", $v);

            if($articleUser) {
                $articleid=ArticleUser::find()->select('artid')->where(['childid'=>$childid,'child_type'=>$k])->column();
                $article=ArticleInfo::find()->select('title')->where(['in','id',$articleid])->column();
                foreach($article as $ak=>$av)
                {
                    $article[$ak]=($ak+1).".".$av;
                }
                $excel->setActiveSheetIndex(0)->setCellValue($t."3", date('Y/m/d',$articleUser[0]->createtime));
                $excel->setActiveSheetIndex(0)->setCellValue($t."4", implode("\n",$article));
                $excel->setActiveSheetIndex(0)->setCellValue($t."5", '');
                $excel->setActiveSheetIndex(0)->setCellValue($t."6", UserDoctor::findOne(['userid'=>$articleUser[0]->userid])->name);

            }
            $excel->getActiveSheet()->getColumnDimension($t)->setWidth(25);
            $ascii++;
        }

        $excel->getActiveSheet()->getStyle('A2:N2')
            ->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        ob_end_clean();
        ob_start();

        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="'.$chileInfo->name.'-儿童中医药健康管理服务表-'.date("Y年m月j日").'.xls"');
        $objWriter= \PHPExcel_IOFactory::createWriter($excel,'Excel5');
        $objWriter->save('php://output');
    }

    /**
     * Displays a single ArticleUser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ArticleUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ArticleUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ArticleUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ArticleUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
