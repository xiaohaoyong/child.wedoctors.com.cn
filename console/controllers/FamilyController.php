<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/11/23
 * Time: 下午7:42
 */

namespace console\controllers;


use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use PHPExcel;
use PHPExcel_Style;
use PHPExcel_Style_NumberFormat;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\console\Controller;



class FamilyController extends Controller
{
    public function actionDown($doctorid=0,$action='Excel',$type=1)
    {
        $act='setDown'.$action;
        ini_set('memory_limit', '8048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);
        if($doctorid==0) {
            $doctor = UserDoctor::find()->where(['county' => 1106])->all();
            foreach ($doctor as $v) {
                $this->$act($v->userid,$type);
                echo "\n";
            }
        }else {
            $this->$act($doctorid,$type);
        }
        
    }
    
    public function setDownExcel($doctorid,$type)
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->getCellByColumnAndRow(1,1)->setValue('家庭医生签约服务签约居民基本信息汇总表');
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $worksheet->getStyle('A1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(30);


        $spreadsheet->getActiveSheet()->mergeCells('A1:X2');
        $worksheet->getCellByColumnAndRow(1,3)->setValue('区');
        $worksheet->getCellByColumnAndRow(2,3)->setValue('机构编码');
        $worksheet->getCellByColumnAndRow(3,3)->setValue('机构名称');
        $worksheet->getCellByColumnAndRow(4,3)->setValue('签约居民姓名');
        $worksheet->getCellByColumnAndRow(5,3)->setValue('身份证号');
        $worksheet->getCellByColumnAndRow(6,3)->setValue('签约日期');
        $worksheet->getCellByColumnAndRow(7,3)->setValue('续约日期');
        $worksheet->getCellByColumnAndRow(8,3)->setValue('签约医生');
        $worksheet->getCellByColumnAndRow(9,3)->setValue('联系电话1');
        $worksheet->getCellByColumnAndRow(10,3)->setValue('联系电话2');
        $worksheet->getCellByColumnAndRow(11,3)->setValue('联系电话3');
        $worksheet->getStyle('A3:X5')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->mergeCells('A3:A5');
        $spreadsheet->getActiveSheet()->mergeCells('B3:B5');
        $spreadsheet->getActiveSheet()->mergeCells('C3:C5');
        $spreadsheet->getActiveSheet()->mergeCells('D3:D5');
        $spreadsheet->getActiveSheet()->mergeCells('E3:E5');
        $spreadsheet->getActiveSheet()->mergeCells('F3:F5');
        $spreadsheet->getActiveSheet()->mergeCells('G3:G5');
        $spreadsheet->getActiveSheet()->mergeCells('H3:H5');
        $spreadsheet->getActiveSheet()->mergeCells('I3:I5');
        $spreadsheet->getActiveSheet()->mergeCells('J3:J5');
        $spreadsheet->getActiveSheet()->mergeCells('K3:K5');

        $spreadsheet->getActiveSheet()->getStyle('A3:X5')
            ->getAlignment()->setWrapText(true);

        $worksheet->getCellByColumnAndRow(12,3)->setValue('人群分类');
        $worksheet->getCellByColumnAndRow(12,4)->setValue('一般人群');
        $worksheet->getCellByColumnAndRow(13,4)->setValue('重点人群');
        $worksheet->getCellByColumnAndRow(13,5)->setValue('高血压患者');
        $worksheet->getCellByColumnAndRow(14,5)->setValue('糖尿病患者');
        $worksheet->getCellByColumnAndRow(15,5)->setValue('冠心病患者');
        $worksheet->getCellByColumnAndRow(16,5)->setValue('脑卒中患者');
        $worksheet->getCellByColumnAndRow(17,5)->setValue('65(含)岁以上老年人');
        $worksheet->getCellByColumnAndRow(18,5)->setValue('残疾人');
        $worksheet->getCellByColumnAndRow(19,5)->setValue('孕产妇');
        $worksheet->getCellByColumnAndRow(20,5)->setValue('0-6岁儿童');
        $worksheet->getCellByColumnAndRow(21,5)->setValue('重型精神疾病患者');
        $worksheet->getCellByColumnAndRow(22,5)->setValue('结核病患者');
        $worksheet->getCellByColumnAndRow(23,5)->setValue('低收入人口');
        $worksheet->getCellByColumnAndRow(24,5)->setValue('计划生育特殊家庭');
        $spreadsheet->getActiveSheet()->mergeCells('L3:X3');
        $spreadsheet->getActiveSheet()->mergeCells('M4:X4');
        $spreadsheet->getActiveSheet()->mergeCells('L4:L5');

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $worksheet->getStyle('A1:X2')->applyFromArray($styleArray);
        $worksheet->getStyle('L3:X3')->applyFromArray($styleArray);
        $worksheet->getStyle('M4:X4')->applyFromArray($styleArray);



        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                ]
            ]
        ];
        $worksheet->getStyle('L5:W5')->applyFromArray($styleArray);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $worksheet->getStyle('A3:A5')->applyFromArray($styleArray);
        $worksheet->getStyle('B3:B5')->applyFromArray($styleArray);
        $worksheet->getStyle('C3:C5')->applyFromArray($styleArray);
        $worksheet->getStyle('D3:D5')->applyFromArray($styleArray);
        $worksheet->getStyle('E3:E5')->applyFromArray($styleArray);
        $worksheet->getStyle('F3:F5')->applyFromArray($styleArray);
        $worksheet->getStyle('G3:G5')->applyFromArray($styleArray);
        $worksheet->getStyle('H3:H5')->applyFromArray($styleArray);
        $worksheet->getStyle('I3:I5')->applyFromArray($styleArray);
        $worksheet->getStyle('J3:J5')->applyFromArray($styleArray);
        $worksheet->getStyle('K3:K5')->applyFromArray($styleArray);
        $worksheet->getStyle('L3:X5')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(50);


        $birthday = strtotime('- 7 year');
        if($type==1) {
            $auto = Autograph::find()->select('userid')
                ->where(['and', ['>', 'createtime', strtotime('2022-07-01')], ['<', 'createtime', strtotime('2022-10-01')]])
                ->orWhere(['and', ['>', 'starttime', strtotime('2021-07-01')], ['<', 'starttime', strtotime('2022-10-01')]])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
        }elseif($type==2){
            $auto = Autograph::find()->select('userid')
                ->where(['<','createtime',strtotime('2022-04-01')])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-04-01'));
        }elseif($type==3){
            $auto = Autograph::find()->select('userid')
                ->where(['<','createtime',strtotime('2022-07-01')])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-01-01'));

        }elseif($type==4){
            $auto = Autograph::find()->select('userid')
                ->where(['<','createtime',strtotime('2023-06-26')])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2023-06-26'));


        }elseif($type==5){
            $auto = Autograph::find()->select('userid')
                ->where(['and', ['>', 'createtime', strtotime('2022-01-01')], ['<', 'createtime', strtotime('2022-04-01')]])
                ->orWhere(['and', ['>', 'starttime', strtotime('2022-01-01')], ['<', 'starttime', strtotime('2022-04-01')]])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-01-01'));

        }elseif($type==6){
            $auto = Autograph::find()->select('userid')
                ->where(['and', ['>', 'createtime', strtotime('2022-04-01')], ['<', 'createtime', strtotime('2022-07-01')]])
                ->orWhere(['and', ['>', 'starttime', strtotime('2022-04-01')], ['<', 'starttime', strtotime('2022-07-01')]])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-01-01'));

        }else{
            $auto = Autograph::find()->select('userid')
                ->andWhere(['doctorid' => $doctorid])
                ->column();
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $names=[];
        $userDoctor = UserDoctor::findOne(['userid' => $doctorid]);
        $hospital = Hospital::findOne($userDoctor->hospitalid);
        echo $hospital->name.":";
        if($auto) {
            $i = 6;
            foreach($auto as $ak=>$av) {
                $child = ChildInfo::find()
                    ->andFilterWhere(['userid'=>$av])
                    ->andFilterWhere(['>', '`child_info`.birthday', $birthday])
                    ->groupBy('name,birthday')
                    ->all();
                if($child) {
                    foreach ($child as $k => $v) {

                        if($names[$v->name] && $names[$v->name] == $v->birthday) continue;
                        $names[$v->name] =  $v->birthday;


                        $autoa = Autograph::findOne(['userid' => $v->userid]);
                        $userDoctor = UserDoctor::findOne(['userid' => $autoa->doctorid]);
                        $hospital = Hospital::findOne($userDoctor->hospitalid);
                        $userParent=UserParent::findOne([$v->userid]);

                        $idcard = $v->field27 ? $v->field27 : $v->idcard;
                        $idcard=str_replace('*','',$idcard);

                        if(!$idcard && $userParent->mother_id){
                            $idcard=$userParent->mother_id.'(母亲)';
                        }

                        $userParent = UserParent::findOne(['userid' => $v->userid]);
                        if ($userParent && $userParent->mother_phone) {
                            $phone = "\t" . $userParent->mother_phone;
                        } else {
                            $phone = "\t" . UserLogin::getPhone($v->userid);
                        }
                        if(strlen($idcard)<10 || !$phone){
                            continue;
                        }
                        $au = Autograph::findOne(['userid' => $v->userid]);

                        $worksheet->getStyle('A' . $i . ':X' . $i)->applyFromArray($styleArray);
                        $worksheet->getCellByColumnAndRow(1, $i)->setValue('丰台区');
                        $worksheet->getCellByColumnAndRow(2, $i)->setValue('');
                        $worksheet->getCellByColumnAndRow(3, $i)->setValue($hospital->name);
                        $gender = $v->gender?$v->gender:1;
                        $worksheet->getCellByColumnAndRow(4, $i)->setValue($v->name);
                        $worksheet->getCellByColumnAndRow(5, $i)->setValue($idcard."\t");
                        $worksheet->getCellByColumnAndRow(6, $i)->setValue(date('Y-m-d', $au->createtime));
                        $worksheet->getCellByColumnAndRow(7, $i)->setValue(date('Y-m-d',strtotime($au->starttime)));
                        $worksheet->getCellByColumnAndRow(8, $i)->setValue($userDoctor->name);
                        $worksheet->getCellByColumnAndRow(9, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(10, $i)->setValue($userParent->mother_phone);
                        $worksheet->getCellByColumnAndRow(11, $i)->setValue($userParent->father_phone);
                        $i++;
                    }
                }
            }
            echo ($i-6);

        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(dirname(__ROOT__) . "/static/s/" .$hospital->name.'儿童.xlsx');
    }
    public function setDownFExcel($doctorid,$type)
    {
        echo $doctorid."\n";
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->getCellByColumnAndRow(1,1)->setValue('家庭医生签约服务签约居民基本信息汇总表（截至2023年6月25日）');
        $styleArray = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        
        $worksheet->getStyle('A1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        $spreadsheet->getActiveSheet()->mergeCells('A1:X2');
        $worksheet->getCellByColumnAndRow(1,3)->setValue('区');
        $worksheet->getCellByColumnAndRow(2,3)->setValue('机构编码');
        $worksheet->getCellByColumnAndRow(3,3)->setValue('机构名称');
        $worksheet->getCellByColumnAndRow(4,3)->setValue('签约居民姓名');
        $worksheet->getCellByColumnAndRow(5,3)->setValue('身份证号');
        $worksheet->getCellByColumnAndRow(6,3)->setValue('签约医生');
        $worksheet->getCellByColumnAndRow(7,3)->setValue('签约日期');
        $worksheet->getCellByColumnAndRow(8,3)->setValue('续约日期');
        $worksheet->getCellByColumnAndRow(9,3)->setValue('联系电话1');
        $worksheet->getCellByColumnAndRow(10,3)->setValue('联系电话2');
        $worksheet->getCellByColumnAndRow(11,3)->setValue('联系电话3');
        $worksheet->getStyle('A3:X5')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->mergeCells('A3:A5');
        $spreadsheet->getActiveSheet()->mergeCells('B3:B5');
        $spreadsheet->getActiveSheet()->mergeCells('C3:C5');
        $spreadsheet->getActiveSheet()->mergeCells('D3:D5');
        $spreadsheet->getActiveSheet()->mergeCells('E3:E5');
        $spreadsheet->getActiveSheet()->mergeCells('F3:F5');
        $spreadsheet->getActiveSheet()->mergeCells('G3:G5');
        $spreadsheet->getActiveSheet()->mergeCells('H3:H5');
        $spreadsheet->getActiveSheet()->mergeCells('I3:I5');
        $spreadsheet->getActiveSheet()->mergeCells('J3:J5');
        $spreadsheet->getActiveSheet()->mergeCells('K3:K5');

        $spreadsheet->getActiveSheet()->getStyle('A3:X5')
            ->getAlignment()->setWrapText(true);

        $worksheet->getCellByColumnAndRow(12,3)->setValue('人群分类');
        $worksheet->getCellByColumnAndRow(12,4)->setValue('一般人群');
        $worksheet->getCellByColumnAndRow(13,4)->setValue('重点人群');
        $worksheet->getCellByColumnAndRow(13,5)->setValue('高血压患者');
        $worksheet->getCellByColumnAndRow(14,5)->setValue('糖尿病患者');
        $worksheet->getCellByColumnAndRow(15,5)->setValue('冠心病患者');
        $worksheet->getCellByColumnAndRow(16,5)->setValue('脑卒中患者');
        $worksheet->getCellByColumnAndRow(17,5)->setValue('65(含)岁以上老年人');
        $worksheet->getCellByColumnAndRow(18,5)->setValue('残疾人');
        $worksheet->getCellByColumnAndRow(19,5)->setValue('孕产妇');
        $worksheet->getCellByColumnAndRow(20,5)->setValue('0-6岁儿童');
        $worksheet->getCellByColumnAndRow(21,5)->setValue('重型精神疾病患者');
        $worksheet->getCellByColumnAndRow(22,5)->setValue('结核病患者');
        $worksheet->getCellByColumnAndRow(23,5)->setValue('低收入人口');
        $worksheet->getCellByColumnAndRow(24,5)->setValue('计划生育特殊家庭');
        $spreadsheet->getActiveSheet()->mergeCells('L3:X3');
        $spreadsheet->getActiveSheet()->mergeCells('M4:X4');
        $spreadsheet->getActiveSheet()->mergeCells('L4:L5');

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $worksheet->getStyle('A1:X2')->applyFromArray($styleArray);
        $worksheet->getStyle('L3:X3')->applyFromArray($styleArray);
        $worksheet->getStyle('M4:X4')->applyFromArray($styleArray);



        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                ]
            ]
        ];
        $worksheet->getStyle('L5:W5')->applyFromArray($styleArray);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $worksheet->getStyle('A3:A5')->applyFromArray($styleArray);
        $worksheet->getStyle('B3:B5')->applyFromArray($styleArray);
        $worksheet->getStyle('C3:C5')->applyFromArray($styleArray);
        $worksheet->getStyle('D3:D5')->applyFromArray($styleArray);
        $worksheet->getStyle('E3:E5')->applyFromArray($styleArray);
        $worksheet->getStyle('F3:F5')->applyFromArray($styleArray);
        $worksheet->getStyle('G3:G5')->applyFromArray($styleArray);
        $worksheet->getStyle('H3:H5')->applyFromArray($styleArray);
        $worksheet->getStyle('I3:I5')->applyFromArray($styleArray);
        $worksheet->getStyle('J3:J5')->applyFromArray($styleArray);
        $worksheet->getStyle('K3:K5')->applyFromArray($styleArray);
        $worksheet->getStyle('L3:X5')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getRowDimension('5')->setRowHeight(50);


        $birthday = strtotime('- 7 year');
        if($type==1) {
            $auto = Autograph::find()->select('userid')
                ->where(['and', ['>', 'createtime', strtotime('2021-04-01')], ['<', 'createtime', strtotime('2022-04-01')]])
                ->orWhere(['and', ['>', 'starttime', strtotime('2021-04-01')], ['<', 'starttime', strtotime('2022-04-01')]])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
        }elseif($type==2){
            $auto = Autograph::find()->select('userid')
                ->where(['<','createtime',strtotime('2022-04-01')])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-04-01'));
        }elseif($type==3){
            $auto = Autograph::find()->select('userid')
                ->where(['<','createtime',strtotime('2022-07-01')])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-07-01'));

        }elseif($type==4){
            $auto = Autograph::find()->select('userid')
                ->where(['<','createtime',strtotime('2023-07-01')])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2023-01-01'));

        }elseif($type==5){
            $auto = Autograph::find()->select('userid')
                ->where(['and', ['>', 'createtime', strtotime('2022-01-01')], ['<', 'createtime', strtotime('2022-04-01')]])
                ->orWhere(['and', ['>', 'starttime', strtotime('2022-01-01')], ['<', 'starttime', strtotime('2022-04-01')]])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-01-01'));

        }elseif($type==6){
            $auto = Autograph::find()->select('userid')
                ->where(['and', ['>', 'createtime', strtotime('2022-04-01')], ['<', 'createtime', strtotime('2022-07-01')]])
                ->orWhere(['and', ['>', 'starttime', strtotime('2022-04-01')], ['<', 'starttime', strtotime('2022-07-01')]])
                ->andWhere(['doctorid' => $doctorid])
                ->column();
            $birthday = strtotime('- 7 year',strtotime('2022-01-01'));

        }else{
            $auto = Autograph::find()->select('userid')
                ->andWhere(['doctorid' => $doctorid])
                ->column();
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                ]
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $userDoctor = UserDoctor::findOne(['userid' => $doctorid]);
        $hospital = Hospital::findOne($userDoctor->hospitalid);
        echo $hospital->name.":";
        if($auto) {
            $i = 6;
            foreach($auto as $ak=>$av) {
                $preg = \common\models\Pregnancy::find()
                    //->andWhere(['pregnancy.field49'=>0])
                    ->andWhere(['>','pregnancy.field11',strtotime('-84 week')])
                    ->andWhere(['familyid'=> $av])
                    ->andWhere(['!=','pregnancy.field4',''])
                    ->groupBy('field1,field4')
                    ->all();
                if($preg) {
                    foreach ($preg as $k => $v) {
                        $autoa = Autograph::findOne(['userid' => $v->familyid]);

                        $userDoctor = UserDoctor::findOne(['userid' => $autoa->doctorid]);
                        $hospital = Hospital::findOne($userDoctor->hospitalid);

                        $worksheet->getStyle('A' . $i . ':X' . $i)->applyFromArray($styleArray);
                        $worksheet->getCellByColumnAndRow(1, $i)->setValue('丰台区');
                        $worksheet->getCellByColumnAndRow(2, $i)->setValue('');
                        $worksheet->getCellByColumnAndRow(3, $i)->setValue($hospital->name);
                        $worksheet->getCellByColumnAndRow(4, $i)->setValue($v->field1);
                        $worksheet->getCellByColumnAndRow(5, $i)->setValue($v->field4."\t");
                        $worksheet->getCellByColumnAndRow(6, $i)->setValue($userDoctor->name);
                        $au = Autograph::findOne(['userid' => $v->familyid]);
                        $worksheet->getCellByColumnAndRow(7, $i)->setValue(date('Y-m-d', $au->createtime));
                        $worksheet->getCellByColumnAndRow(8, $i)->setValue(date('Y-m-d',strtotime($au->starttime)));


                        if ($v->field6) {
                            $phone = "\t" . $v->field6;
                        } else {
                            $phone = "\t" . UserLogin::getPhone($v->familyid);
                        }
                        $worksheet->getCellByColumnAndRow(9, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(19, $i)->setValue('✅');

                        $i++;
                    }
                }
            }
            echo ($i-6);

        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(dirname(__ROOT__) . "/static/s/" .$hospital->name.'孕妇.xlsx');
    }

}