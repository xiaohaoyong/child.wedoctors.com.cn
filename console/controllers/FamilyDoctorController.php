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
use common\models\DoctorTeam;
use common\models\Hospital;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\console\Controller;

class FamilyDoctorController extends Controller
{
    public $num = [
        386661=>'0601',
        206262=>'0602',
        353548=>'0603',
        213581=>'0604',
        190922=>'0605',
        192821=>'0606',
        206260=>'0607',
        184793=>'0609',
        223413=>'0610',
        176156=>'0611',
        160226=>'0612',
        175877=>'0613',
        228039=>'0615',
        213579=>'0616',
        219333=>'0617',
        216592=>'0618',
        257888=>'0621',
        216593=>'0622',
        221895=>'0623',
        194606=>'0624',
        314896=>'060066',
        184741=>'0614',
        323716=>'0620',
        552186=>'060011',
        620543=>'',
    ];

    public $data=[
        175877=>['和义派出所',100076],
		190922=>['蒲黄榆派出所',100075],
		192821=>['右安门派出所',100069],
		160226=>['西罗园派出所',100077],
		213581=>['方庄派出所',	100078],
		216592=>['云岗派出所',	100074],
		223413=>['云岗派出所',	100074],
		221895=>['大红门派出所',100076],
		184793=>['张郭庄派出所',100072],
		353548=>['北京市公安局丰台分局青塔派出所',	100141],
		314896=>['大红门派出所',	100068],
		323716=>['东高地派出所',	100076],
		176156=>['成寿寺派出所',	100079],
		206260=>['樊家村派出所',	100070],
		194606=>['丰台镇派出所',	100071],
		213579=>['长辛店派出所',	100072],
		228039=>['长辛店派出所',	100072],
		386661=>['卢沟桥派出所',	100165],
		257888=>['玉泉营派出所',	100070],
		216593=>['西局派出所',	100161],
		184741=>['马家堡派出所',	100068],
        552186=>['樊家村派出所',100070],
        620543=>["",""]

    ];
    public function actionDown($doctorid=0,$action='Excel',$type=1)
    {
        $act='setDown'.$action;
        ini_set('memory_limit', '8048M');
        ini_set("max_execution_time", "0");
        set_time_limit(0);
        if($doctorid==0) {
            $doctor = UserDoctor::find()->where(['county' => 1106,'is_guanfang'=>0])->all();
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
        error_reporting(0);
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

        $spreadsheet->getActiveSheet()->mergeCells('A1:S2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:D3');
        $spreadsheet->getActiveSheet()->mergeCells('F3:I3');

        $worksheet->getCellByColumnAndRow(1,3)->setValue('工作联络人：');
        $worksheet->getCellByColumnAndRow(6,3)->setValue('联系电话：');

        $worksheet->getCellByColumnAndRow(1,4)->setValue('区');
        $worksheet->getCellByColumnAndRow(2,4)->setValue('中心编码');
        $worksheet->getCellByColumnAndRow(3,4)->setValue('机构编码');
        $worksheet->getCellByColumnAndRow(4,4)->setValue('机构名称');
        $worksheet->getCellByColumnAndRow(5,4)->setValue('签约医生姓名');
        $worksheet->getCellByColumnAndRow(6,4)->setValue('签约居民姓名');
        $worksheet->getCellByColumnAndRow(7,4)->setValue('签约居民身份证号');
        $worksheet->getCellByColumnAndRow(8,4)->setValue('出生日期');
        $worksheet->getCellByColumnAndRow(9,4)->setValue('首次签约日期');
        $worksheet->getCellByColumnAndRow(10,4)->setValue('续签日期');
        $worksheet->getCellByColumnAndRow(11,4)->setValue('联系电话1');
        $worksheet->getCellByColumnAndRow(12,4)->setValue('联系电话2');
        $worksheet->getCellByColumnAndRow(13,4)->setValue('现住址');
        $worksheet->getCellByColumnAndRow(14,4)->setValue('派出所');
        $worksheet->getCellByColumnAndRow(15,4)->setValue('签约服务包');
        $worksheet->getCellByColumnAndRow(16,4)->setValue('导入标识');
        $worksheet->getCellByColumnAndRow(17,4)->setValue('邮政编码');
        $worksheet->getCellByColumnAndRow(18,4)->setValue('区域行政编码');
        $worksheet->getCellByColumnAndRow(19,4)->setValue('镇/街道');
        $worksheet->getCellByColumnAndRow(20,4)->setValue('建档日期');

        $worksheet->getStyle('A4:T6')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->mergeCells('A4:A6');
        $spreadsheet->getActiveSheet()->mergeCells('B4:B6');
        $spreadsheet->getActiveSheet()->mergeCells('C4:C6');
        $spreadsheet->getActiveSheet()->mergeCells('D4:D6');
        $spreadsheet->getActiveSheet()->mergeCells('E4:E6');
        $spreadsheet->getActiveSheet()->mergeCells('F4:F6');
        $spreadsheet->getActiveSheet()->mergeCells('G4:G6');
        $spreadsheet->getActiveSheet()->mergeCells('H4:H6');
        $spreadsheet->getActiveSheet()->mergeCells('I4:I6');
        $spreadsheet->getActiveSheet()->mergeCells('J4:J6');
        $spreadsheet->getActiveSheet()->mergeCells('K4:K6');
        $spreadsheet->getActiveSheet()->mergeCells('L4:L6');
        $spreadsheet->getActiveSheet()->mergeCells('M4:M6');
        $spreadsheet->getActiveSheet()->mergeCells('N4:N6');
        $spreadsheet->getActiveSheet()->mergeCells('O4:O6');
        $spreadsheet->getActiveSheet()->mergeCells('P4:P6');
        $spreadsheet->getActiveSheet()->mergeCells('Q4:Q6');
        $spreadsheet->getActiveSheet()->mergeCells('R4:R6');
        $spreadsheet->getActiveSheet()->mergeCells('S4:S6');
        $spreadsheet->getActiveSheet()->mergeCells('T4:T6');

        $spreadsheet->getActiveSheet()->getStyle('A4:T6')
            ->getAlignment()->setWrapText(true);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        
        $worksheet->getStyle('A4:A6')->applyFromArray($styleArray);
        $worksheet->getStyle('B4:B6')->applyFromArray($styleArray);
        $worksheet->getStyle('C4:C6')->applyFromArray($styleArray);
        $worksheet->getStyle('D4:D6')->applyFromArray($styleArray);
        $worksheet->getStyle('E4:E6')->applyFromArray($styleArray);
        $worksheet->getStyle('F4:F6')->applyFromArray($styleArray);
        $worksheet->getStyle('G4:G6')->applyFromArray($styleArray);
        $worksheet->getStyle('H4:H6')->applyFromArray($styleArray);
        $worksheet->getStyle('I4:I6')->applyFromArray($styleArray);
        $worksheet->getStyle('J4:J6')->applyFromArray($styleArray);
        $worksheet->getStyle('K4:K6')->applyFromArray($styleArray);
        $worksheet->getStyle('L4:L6')->applyFromArray($styleArray);
        $worksheet->getStyle('M4:M6')->applyFromArray($styleArray);
        $worksheet->getStyle('N4:N6')->applyFromArray($styleArray);
        $worksheet->getStyle('O4:O6')->applyFromArray($styleArray);
        $worksheet->getStyle('P4:P6')->applyFromArray($styleArray);
        $worksheet->getStyle('Q4:Q6')->applyFromArray($styleArray);
        $worksheet->getStyle('R4:R6')->applyFromArray($styleArray);
        $worksheet->getStyle('S4:S6')->applyFromArray($styleArray);
        $worksheet->getStyle('S4:T6')->applyFromArray($styleArray);


        $birthday = strtotime('- 7 year');
        $auto = Autograph::find()->select('userid')
                ->andWhere(['doctorid' => $doctorid])
//                ->andFilterWhere(['>', 'starttime','20240920'])
//                ->andFilterWhere(['<', 'starttime','20241204'])
                ->column();

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
            $i = 7;

            foreach($auto as $ak=>$av) {
                $child = ChildInfo::find()
                    ->andFilterWhere(['userid'=>$av])
                    ->andFilterWhere(['>', '`child_info`.birthday', $birthday])
                    ->groupBy('name,birthday')
                    ->all();
                if($child) {
                    foreach ($child as $k => $v) {

                        if(isset($names[$v->name]) && $names[$v->name] == $v->birthday) continue;
                        $names[$v->name] =  $v->birthday;


                        $autoa = Autograph::findOne(['userid' => $v->userid]);
                        $userDoctor = UserDoctor::findOne(['userid' => $autoa->doctorid]);
                        $hospital = Hospital::findOne($userDoctor->hospitalid);
                        $userParent=UserParent::findOne([$v->userid]);
                        $doctorParent=DoctorParent::findOne(['parentid'=>$v->userid]);
                        $idcard = $v->field27 ? $v->field27 : $v->idcard;

                        if(!$idcard && $userParent->mother_id){
                            continue;
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
                        $worksheet->getStyle('A' . $i . ':S' . $i)->applyFromArray($styleArray);
                        $worksheet->getCellByColumnAndRow(1, $i)->setValue('丰台区');
                        $worksheet->getCellByColumnAndRow(2, $i)->setValue($this->num[$doctorid]);
                        $worksheet->getCellByColumnAndRow(3, $i)->setValue($this->num[$doctorid]);
                        $worksheet->getCellByColumnAndRow(4, $i)->setValue($hospital->name);
                        $team = DoctorTeam::findOne($v->teamid);
                        $worksheet->getCellByColumnAndRow(5, $i)->setValue($team?DoctorTeam::findOne($v->teamid)->title:"");
                        $worksheet->getCellByColumnAndRow(6, $i)->setValue($v->name);
                        $au = Autograph::findOne(['userid' => $v->userid]);
                
                        $idcard = str_replace('"','',$idcard);

                        $worksheet->getCellByColumnAndRow(7, $i)->setValueExplicit(str_replace('x','X',trim($idcard)),DataType::TYPE_STRING2);
                        $worksheet->getCellByColumnAndRow(8, $i)->setValue($v->birthday?date('Y-m-d',$v->birthday):'');
                        $worksheet->getCellByColumnAndRow(9, $i)->setValue(date('Y-m-d',strtotime($au->starttime)));
                        $worksheet->getCellByColumnAndRow(10, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(11, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(12, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(13, $i)->setValue(str_replace('*','',$userParent->fieldu46));
                        $worksheet->getCellByColumnAndRow(14, $i)->setValue($this->data[$doctorid][0]);
                        $worksheet->getCellByColumnAndRow(15, $i)->setValue('0-6岁儿童基本签约服务包');
                        $worksheet->getCellByColumnAndRow(16, $i)->setValue($this->num[$doctorid]);
                        $worksheet->getCellByColumnAndRow(17, $i)->setValue($this->data[$doctorid][1]);
                        $worksheet->getCellByColumnAndRow(18, $i)->setValue('110106000000000000000');
                        $worksheet->getCellByColumnAndRow(20, $i)->setValue(date('Y-m-d',$v->createtime));

                        $i++;
                    }
                }
            }
            echo ($i-6);

        }
    
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(dirname(__ROOT__) . "/static/w/".$hospital->name."儿童.xlsx");
    }
    public function setDownFExcel($doctorid,$type)
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

        $spreadsheet->getActiveSheet()->mergeCells('A1:S2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:D3');
        $spreadsheet->getActiveSheet()->mergeCells('F3:I3');

        $worksheet->getCellByColumnAndRow(1,3)->setValue('工作联络人：');
        $worksheet->getCellByColumnAndRow(6,3)->setValue('联系电话：');

        $worksheet->getCellByColumnAndRow(1,4)->setValue('区');
        $worksheet->getCellByColumnAndRow(2,4)->setValue('中心编码');
        $worksheet->getCellByColumnAndRow(3,4)->setValue('机构编码');
        $worksheet->getCellByColumnAndRow(4,4)->setValue('机构名称');
        $worksheet->getCellByColumnAndRow(5,4)->setValue('签约医生姓名');
        $worksheet->getCellByColumnAndRow(6,4)->setValue('签约居民姓名');
        $worksheet->getCellByColumnAndRow(7,4)->setValue('签约居民身份证号');
        $worksheet->getCellByColumnAndRow(8,4)->setValue('出生日期');
        $worksheet->getCellByColumnAndRow(9,4)->setValue('签约日期');
        $worksheet->getCellByColumnAndRow(10,4)->setValue('联系电话1');
        $worksheet->getCellByColumnAndRow(11,4)->setValue('联系电话2');
        $worksheet->getCellByColumnAndRow(12,4)->setValue('联系电话3');
        $worksheet->getCellByColumnAndRow(13,4)->setValue('现住址');
        $worksheet->getCellByColumnAndRow(14,4)->setValue('派出所');
        $worksheet->getCellByColumnAndRow(15,4)->setValue('签约服务包');
        $worksheet->getCellByColumnAndRow(16,4)->setValue('导入标识');
        $worksheet->getCellByColumnAndRow(17,4)->setValue('邮政编码');
        $worksheet->getCellByColumnAndRow(18,4)->setValue('区域行政编码');
        $worksheet->getCellByColumnAndRow(19,4)->setValue('镇/街道');
        $worksheet->getCellByColumnAndRow(20,4)->setValue('建档日期');

        $worksheet->getStyle('A4:S6')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->mergeCells('A4:A6');
        $spreadsheet->getActiveSheet()->mergeCells('B4:B6');
        $spreadsheet->getActiveSheet()->mergeCells('C4:C6');
        $spreadsheet->getActiveSheet()->mergeCells('D4:D6');
        $spreadsheet->getActiveSheet()->mergeCells('E4:E6');
        $spreadsheet->getActiveSheet()->mergeCells('F4:F6');
        $spreadsheet->getActiveSheet()->mergeCells('G4:G6');
        $spreadsheet->getActiveSheet()->mergeCells('H4:H6');
        $spreadsheet->getActiveSheet()->mergeCells('I4:I6');
        $spreadsheet->getActiveSheet()->mergeCells('J4:J6');
        $spreadsheet->getActiveSheet()->mergeCells('K4:K6');
        $spreadsheet->getActiveSheet()->mergeCells('L4:L6');
        $spreadsheet->getActiveSheet()->mergeCells('M4:M6');
        $spreadsheet->getActiveSheet()->mergeCells('N4:N6');
        $spreadsheet->getActiveSheet()->mergeCells('O4:O6');
        $spreadsheet->getActiveSheet()->mergeCells('P4:P6');
        $spreadsheet->getActiveSheet()->mergeCells('Q4:Q6');
        $spreadsheet->getActiveSheet()->mergeCells('R4:R6');
        $spreadsheet->getActiveSheet()->mergeCells('S4:S6');
        $spreadsheet->getActiveSheet()->mergeCells('T4:T6');

        $spreadsheet->getActiveSheet()->getStyle('A4:T6')
            ->getAlignment()->setWrapText(true);

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];
        
        $worksheet->getStyle('A4:A6')->applyFromArray($styleArray);
        $worksheet->getStyle('B4:B6')->applyFromArray($styleArray);
        $worksheet->getStyle('C4:C6')->applyFromArray($styleArray);
        $worksheet->getStyle('D4:D6')->applyFromArray($styleArray);
        $worksheet->getStyle('E4:E6')->applyFromArray($styleArray);
        $worksheet->getStyle('F4:F6')->applyFromArray($styleArray);
        $worksheet->getStyle('G4:G6')->applyFromArray($styleArray);
        $worksheet->getStyle('H4:H6')->applyFromArray($styleArray);
        $worksheet->getStyle('I4:I6')->applyFromArray($styleArray);
        $worksheet->getStyle('J4:J6')->applyFromArray($styleArray);
        $worksheet->getStyle('K4:K6')->applyFromArray($styleArray);
        $worksheet->getStyle('L4:L6')->applyFromArray($styleArray);
        $worksheet->getStyle('M4:M6')->applyFromArray($styleArray);
        $worksheet->getStyle('N4:N6')->applyFromArray($styleArray);
        $worksheet->getStyle('O4:O6')->applyFromArray($styleArray);
        $worksheet->getStyle('P4:P6')->applyFromArray($styleArray);
        $worksheet->getStyle('Q4:Q6')->applyFromArray($styleArray);
        $worksheet->getStyle('R4:R6')->applyFromArray($styleArray);
        $worksheet->getStyle('S4:S6')->applyFromArray($styleArray);
        $worksheet->getStyle('T4:T6')->applyFromArray($styleArray);


        $birthday = strtotime('- 7 year');
        $auto = Autograph::find()->select('userid')
                ->andWhere(['doctorid' => $doctorid])
                ->column();

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
                $i = 7;
                foreach($auto as $ak=>$av) {
                    $preg = \common\models\Pregnancy::find()
                        //->andWhere(['pregnancy.field49'=>0])
                        ->andWhere(['>','pregnancy.field11',strtotime('-84 week')])
                        ->andWhere(['familyid'=> $av])
                        ->andWhere(['!=','pregnancy.field4',''])
                        ->andWhere(['not like','field4','*'])
                        ->groupBy('field1,field11')
                        ->orderBy('source')
                        ->all();
                    if($preg) {
                        foreach ($preg as $k => $v) {
                            $autoa = Autograph::findOne(['userid' => $v->familyid]);

                            $userDoctor = UserDoctor::findOne(['userid' => $autoa->doctorid]);
                            $hospital = Hospital::findOne($userDoctor->hospitalid);
                            $au = Autograph::findOne(['userid' => $v->familyid]);

                        
                            if ($v->field6) {
                                $phone = "\t" . $v->field6;
                            } else {
                                $phone = "\t" . UserLogin::getPhone($v->familyid);
                            }
                            echo $v->field4."\n";
                            $worksheet->getStyle('A' . $i . ':S' . $i)->applyFromArray($styleArray);
                        $worksheet->getCellByColumnAndRow(1, $i)->setValue('丰台区');
                        $worksheet->getCellByColumnAndRow(2, $i)->setValue($this->num[$doctorid]);
                        $worksheet->getCellByColumnAndRow(3, $i)->setValue($this->num[$doctorid]);
                        $worksheet->getCellByColumnAndRow(4, $i)->setValue($hospital->name);
                        $worksheet->getCellByColumnAndRow(5, $i)->setValue(DoctorTeam::findOne($v->teamid)->title);
                        $worksheet->getCellByColumnAndRow(6, $i)->setValue($v->field1);
                        $au = Autograph::findOne(['userid' => $v->familyid]);
                        $worksheet->getCellByColumnAndRow(7, $i)->setValueExplicit($v->field4,DataType::TYPE_STRING2);
                        $worksheet->getCellByColumnAndRow(8, $i)->setValue($v->field2?date('Y-m-d',$v->field2):(string)$this->getIDCardInfo($v->field4));
                        $worksheet->getCellByColumnAndRow(9, $i)->setValue(date('Y-m-d',strtotime($au->starttime)));
                        $worksheet->getCellByColumnAndRow(10, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(11, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(12, $i)->setValue($phone);
                        $worksheet->getCellByColumnAndRow(13, $i)->setValue(str_replace('*','',$v->field10));
                        $worksheet->getCellByColumnAndRow(14, $i)->setValue($this->data[$doctorid][0]);
                        $worksheet->getCellByColumnAndRow(15, $i)->setValue('孕产妇基本签约服务包');
                        $worksheet->getCellByColumnAndRow(16, $i)->setValue($this->num[$doctorid]);
                        $worksheet->getCellByColumnAndRow(17, $i)->setValue($this->data[$doctorid][1]);
                        $worksheet->getCellByColumnAndRow(18, $i)->setValue('110106000000000000000');
                        $worksheet->getCellByColumnAndRow(20, $i)->setValue(date('Y-m-d',$v->createtime));



                            $i++;
                        }
                    }
                }
                echo ($i-6);

            }
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(dirname(__ROOT__) . "/static/w/".$hospital->name."孕妇.xlsx");
    }
    function getIDCardInfo($IDCard,$format=1){
        $result['error']=0;//0：未知错误，1：身份证格式错误，2：无错误
        $result['flag']='';//0标示成年，1标示未成年
        $result['tdate']='';//生日，格式如：2012-11-15
        if(false){
         $result['error']=1;
         return $result;
        }else{
         if(strlen($IDCard)==18)
         {
          $tyear=intval(substr($IDCard,6,4));
          $tmonth=intval(substr($IDCard,10,2));
          $tday=intval(substr($IDCard,12,2));
         }
         elseif(strlen($IDCard)==15)
         {
          $tyear=intval("19".substr($IDCard,6,2));
          $tmonth=intval(substr($IDCard,8,2));
          $tday=intval(substr($IDCard,10,2));
         }
           
         if($tyear>date("Y")||$tyear<(date("Y")-100))
         {
           $flag=0;
          }
          elseif($tmonth<0||$tmonth>12)
          {
           $flag=0;
          }
          elseif($tday<0||$tday>31)
          {
           $flag=0;
          }else
          {
           if($format)
           {
            $tdate=$tyear."-".$tmonth."-".$tday;
           }
           else
           {
            $tdate=$tmonth."-".$tday;
           }
             
           if((time()-mktime(0,0,0,$tmonth,$tday,$tyear))>18*365*24*60*60)
           {
            $flag=0;
           }
           else
           {
            $flag=1;
           }
          } 
        }
        $result['error']=2;//0：未知错误，1：身份证格式错误，2：无错误
        $result['isAdult']=$flag;//0标示成年，1标示未成年
        $result['birthday']=$tdate;//生日日期
        return $tdate;
       }
}