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
use console\models\Pregnancy;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\console\Controller;

class FamilyDoctorController extends Controller
{
    public function actionDown()
    {
        ini_set('memory_limit', '2048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);
//        $doctor=UserDoctor::find()->where(['county'=>1106])->all();
//        foreach($doctor as $v)
//        {
//            $this->setDownExcel($v->userid);
//            echo "\n";
//        }
        $this->setDownExcel(192821);
    }

    public function setDownExcel($doctorid)
    {
        echo $doctorid."\n";
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
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);

        $spreadsheet->getActiveSheet()->mergeCells('A3:V3');
        $spreadsheet->getActiveSheet()->mergeCells('A1:V2');

        $worksheet->getCellByColumnAndRow(1,4)->setValue('区级工作联络人：');
        $spreadsheet->getActiveSheet()->mergeCells('A4:C4');
        $worksheet->getCellByColumnAndRow(4,4)->setValue('联系电话：');
        $spreadsheet->getActiveSheet()->mergeCells('D4:F4');
        $spreadsheet->getActiveSheet()->mergeCells('G4:V4');
        $worksheet->getCellByColumnAndRow(1,5)->setValue('区');
        $worksheet->getCellByColumnAndRow(2,5)->setValue('机构编码');
        $worksheet->getCellByColumnAndRow(3,5)->setValue('机构名称');
        $worksheet->getCellByColumnAndRow(4,5)->setValue('签约居民姓名');
        $worksheet->getCellByColumnAndRow(5,5)->setValue('身份证号');
        $worksheet->getCellByColumnAndRow(6,5)->setValue('签约日期');
        $worksheet->getCellByColumnAndRow(7,5)->setValue('联系电话1');
        $worksheet->getCellByColumnAndRow(8,5)->setValue('联系电话2');
        $worksheet->getCellByColumnAndRow(9,5)->setValue('联系电话3');
        $worksheet->getStyle('A5:V7')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->mergeCells('A5:A7');
        $spreadsheet->getActiveSheet()->mergeCells('B5:B7');
        $spreadsheet->getActiveSheet()->mergeCells('C5:C7');
        $spreadsheet->getActiveSheet()->mergeCells('D5:D7');
        $spreadsheet->getActiveSheet()->mergeCells('E5:E7');
        $spreadsheet->getActiveSheet()->mergeCells('F5:F7');
        $spreadsheet->getActiveSheet()->mergeCells('G5:G7');
        $spreadsheet->getActiveSheet()->mergeCells('H5:H7');
        $spreadsheet->getActiveSheet()->mergeCells('I5:I7');
        $spreadsheet->getActiveSheet()->getStyle('A5:V7')
            ->getAlignment()->setWrapText(true);

        $worksheet->getCellByColumnAndRow(10,5)->setValue('人群分类');
        $worksheet->getCellByColumnAndRow(10,6)->setValue('一般人群');
        $worksheet->getCellByColumnAndRow(11,6)->setValue('重点人群');
        $worksheet->getCellByColumnAndRow(11,7)->setValue('高血压患者');
        $worksheet->getCellByColumnAndRow(12,7)->setValue('糖尿病患者');
        $worksheet->getCellByColumnAndRow(13,7)->setValue('冠心病患者');
        $worksheet->getCellByColumnAndRow(14,7)->setValue('脑卒中患者');
        $worksheet->getCellByColumnAndRow(15,7)->setValue('65(含)岁以上老年人');
        $worksheet->getCellByColumnAndRow(16,7)->setValue('残疾人');
        $worksheet->getCellByColumnAndRow(17,7)->setValue('孕产妇');
        $worksheet->getCellByColumnAndRow(18,7)->setValue('0-6岁儿童');
        $worksheet->getCellByColumnAndRow(19,7)->setValue('重型精神疾病患者');
        $worksheet->getCellByColumnAndRow(20,7)->setValue('结核病患者');
        $worksheet->getCellByColumnAndRow(21,7)->setValue('低收入人口');
        $worksheet->getCellByColumnAndRow(22,7)->setValue('计划生育特殊家庭');
        $spreadsheet->getActiveSheet()->mergeCells('J5:V5');
        $spreadsheet->getActiveSheet()->mergeCells('K6:V6');
        $spreadsheet->getActiveSheet()->mergeCells('J6:J7');

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $worksheet->getStyle('A1:V2')->applyFromArray($styleArray);
        $worksheet->getStyle('A4:C4')->applyFromArray($styleArray);
        $worksheet->getStyle('D4:F4')->applyFromArray($styleArray);
        $worksheet->getStyle('J5:V5')->applyFromArray($styleArray);
        $worksheet->getStyle('K6:V6')->applyFromArray($styleArray);
        $worksheet->getStyle('J6:J7')->applyFromArray($styleArray);



        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN //细边框
                ]
            ]
        ];
        $worksheet->getStyle('K7:V7')->applyFromArray($styleArray);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        $worksheet->getStyle('A5:A7')->applyFromArray($styleArray);
        $worksheet->getStyle('B5:B7')->applyFromArray($styleArray);
        $worksheet->getStyle('C5:C7')->applyFromArray($styleArray);
        $worksheet->getStyle('D5:D7')->applyFromArray($styleArray);
        $worksheet->getStyle('E5:E7')->applyFromArray($styleArray);
        $worksheet->getStyle('F5:F7')->applyFromArray($styleArray);
        $worksheet->getStyle('G5:I7')->applyFromArray($styleArray);
        $worksheet->getStyle('J5:V7')->applyFromArray($styleArray);

        $spreadsheet->getActiveSheet()->getRowDimension('7')->setRowHeight(50);

        $doctor=UserDoctor::find()->select('userid')->where(['county'=>1106])->column();

        $auto=DoctorParent::find()->select('parentid')->where(['in','doctorid',$doctor])->andWhere(['<','createtime',strtotime('2022-06-01')])->column();

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
        if($auto) {
            foreach($auto as $ak=>$av) {
                $child = ChildInfo::find()
                    ->andFilterWhere(['userid'=>$av])
                    //->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
                    ->groupBy('name,birthday')
                    ->all();
                $i = 8;
                if($child) {
                    foreach ($child as $k => $v) {
                        $autoa = DoctorParent::findOne(['parentid' => $v->userid]);
                        $userDoctor = UserDoctor::findOne(['userid' => $autoa->doctorid]);
                        $hospital = Hospital::findOne($userDoctor->hospitalid);

                        $idcard = $v->field27 ? $v->field27 : $v->idcard;
                        $worksheet->getStyle('A' . $i . ':V' . $i)->applyFromArray($styleArray);
                        $worksheet->getCellByColumnAndRow(3, $i)->setValue($hospital->name);
                        $worksheet->getCellByColumnAndRow(4, $i)->setValue($v->name);
                        $worksheet->getCellByColumnAndRow(5, $i)->setValue("\t" . $idcard);
                        $au = Autograph::findOne(['userid' => $v->userid]);
                        $worksheet->getCellByColumnAndRow(6, $i)->setValue(date('Y-m-d', $au->createtime));

                        $userParent = UserParent::findOne(['userid' => $v->userid]);
                        if ($userParent && $userParent->mother_phone) {
                            $phone = "\t" . $userParent->mother_phone;
                        } else {
                            $phone = "\t" . UserLogin::getPhone($v->userid);
                        }
                        $worksheet->getCellByColumnAndRow(7, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(18, $i)->setValue('✅');

                        $i++;
                    }
                }
            }
//            foreach($auto as $ak=>$av) {
//                $preg = \common\models\Pregnancy::find()
//                    //->andWhere(['pregnancy.field49'=>0])
//                    //->andWhere(['>','pregnancy.field11',strtotime('-43 week')])
//                    ->andWhere(['familyid'=> $av])
//                    ->groupBy('field1,field11')
//                    ->all();
//                if($preg) {
//                    foreach ($preg as $k => $v) {
//                        $autoa = DoctorParent::findOne(['parentid' => $v->familyid]);
//                        $userDoctor = UserDoctor::findOne(['userid' => $autoa->doctorid]);
//                        $hospital = Hospital::findOne($userDoctor->hospitalid);
//
//                        $worksheet->getStyle('A' . $i . ':V' . $i)->applyFromArray($styleArray);
//                        $worksheet->getCellByColumnAndRow(3, $i)->setValue($hospital->name);
//                        $worksheet->getCellByColumnAndRow(4, $i)->setValue($v->field1);
//                        $worksheet->getCellByColumnAndRow(5, $i)->setValue("\t" . $v->field4);
//                        $au = Autograph::findOne(['userid' => $v->familyid]);
//                        $worksheet->getCellByColumnAndRow(6, $i)->setValue(date('Y-m-d', $au->createtime));
//
//                        if ($v->field6) {
//                            $phone = "\t" . $v->field6;
//                        } else {
//                            $phone = "\t" . UserLogin::getPhone($v->familyid);
//                        }
//                        $worksheet->getCellByColumnAndRow(7, $i)->setValue($phone);
//                        $worksheet->getCellByColumnAndRow(17, $i)->setValue('✅');
//                        $i++;
//                    }
//                }
//            }

        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(dirname(__ROOT__) . "/static/1106/" .$doctorid.'-family.xlsx');
    }
}