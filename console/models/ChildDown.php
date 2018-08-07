<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/7/30
 * Time: 下午6:16
 */

namespace console\models;


use common\models\DataUser;
use common\models\DataUserTask;
use console\models\ChildInfoSearchModel;
use common\models\Article;
use common\models\ArticleInfo;
use common\models\ArticleUser;
class ChildDown
{
    public $data;
    public $_server;
    public $_server_fd;
    public $dataUser;
    public function setData($data,DataUser $dataUser){

        $this->dataUser=$dataUser;
        $searchModel = new ChildInfoSearchModel();
        if($dataUser->type==1){
            $searchModel->county=$dataUser->county;
        }else{
            $searchModel->admin=$dataUser->hospital;
        }
        $dataProvider = $searchModel->search($data);

        $this->data=$dataProvider->query->asArray()->all();
    }

    public function excel(){
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties();

        //设置A3单元格为文本
        $objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getNumberFormat()
            ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $key1 = 1;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$key1, '姓名')
            ->setCellValue('B'.$key1, '联系电话')
            ->setCellValue('C'.$key1, '性别')
            ->setCellValue('D'.$key1, '年龄')
            ->setCellValue('E'.$key1, '出生日期')
            ->setCellValue('F'.$key1, '父母')
            ->setCellValue('G'.$key1, '母亲电话')
            ->setCellValue('H'.$key1, '父亲电话')
            ->setCellValue('I'.$key1, '联系人姓名')
            ->setCellValue('J'.$key1, '联系人电话')
            ->setCellValue('K'.$key1, '签约社区')
            ->setCellValue('L'.$key1, '签约时间')
            ->setCellValue('M'.$key1, '签约状态')
            ->setCellValue('N'.$key1, '是否宣教')
            ->setCellValue('O'.$key1, '宣教月龄')
            ->setCellValue('P'.$key1, '宣教内容')
            ->setCellValue('Q'.$key1, '宣教时间');
//写入内容
        $totle=count($this->data);
        foreach($this->data as $k=>$v) {
            $e=$v;
            $sign = \common\models\DoctorParent::findOne(['parentid'=>$v['userid'],'level'=>1]);

            $DiffDate = \common\helpers\StringHelper::DiffDate(date('Y-m-d', time()), date('Y-m-d', $v['birthday']));
            if($DiffDate[0]) {
                $age=$DiffDate[0]."岁";
            }elseif($DiffDate[1]){
                $age=$DiffDate[1]."月";
            }else{
                $age=$DiffDate[2]."天";
            }
            if($sign->level!=1)
            {
                $return="未签约";
            }else{
                if($e['source']<=38){
                    $return="已签约未关联";

                }else {
                    $return = "已签约";
                }
            }

            $article=ArticleUser::findAll(['touserid'=>$v['userid']]);

            $date='';
            $child_type='';
            $title='';

            if($article) {
                foreach ($article as $ak => $av) {
                    $date.="，".date('Y-m-d',$av->createtime);
                    $child_type.="，".Article::$childText[$av->child_type];
                    $articleInfo=ArticleInfo::findOne(['id'=>$av->artid]);
                    $title.=$articleInfo?"，".$articleInfo->title:"";
                }
                $is_article="是";
            }else{
                $is_article="否";
            }


            $key1 = $k + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $key1, $v['name'])
                ->setCellValue('B' . $key1, " ".\common\models\User::findOne($v['userid'])->phone)
                ->setCellValue('C' . $key1, \common\models\ChildInfo::$genderText[$v['gender']])
                ->setCellValue('D' . $key1, $age)
                ->setCellValue('E' . $key1, date('Y-m-d', $v['birthday']))
                ->setCellValue('F' . $key1, $v['mother'] || $v['father']?$v['mother']."/".$v['father']:"无")
                ->setCellValue('G' . $key1, $v['mother_phone'] ? " ".$v['mother_phone'] : "无")
                ->setCellValue('H' . $key1, $v['father_phone'] ?  " ".$v['father_phone'] : "无")
                ->setCellValue('I' . $key1, $v['field11'] ? $v['field11'] : "无")
                ->setCellValue('J' . $key1, $v['field12'] ? " ".$v['field12'] : "无")
                ->setCellValue('K' . $key1, $sign->level==1 ? \common\models\UserDoctor::findOne(['userid'=>$sign->doctorid])->name : "--")
                ->setCellValue('L' . $key1, $sign->level == 1 ? date('Y-m-d H:i', $sign->createtime) : "无")
                ->setCellValue('M' . $key1, $return)
                ->setCellValue('N' . $key1, $is_article)
                ->setCellValue('O' . $key1, $child_type)
                ->setCellValue('P' . $key1, $title)
                ->setCellValue('Q' . $key1, $date);
            var_dump($this->_server);

            if($this->_server){
                $line=round(($k+1)/$totle,4)*100;
                if(ceil($line)%5==0){
                    $dataUserTask = DataUserTask::findOne(['datauserid' => $this->dataUser->id, 'state' => 0]);
                    if($dataUserTask){
                        $this->_server_fd=$dataUserTask->fd;
                    }
                }
                $this->_server->send(json_encode(['type' => 'Schedule','id'=>$dataUserTask->id,'fd'=>$this->_server_fd,'line'=>$line]));
            }

        }
        // $objPHPExcel->setActiveSheetIndex(0);


        $objWriter= \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');

        $title="child_info_".date('Y-m-d_H:i').".xlsx";
        $objWriter->save(dirname(__ROOT__)."/static/".$title);
        return $title;
    }
}