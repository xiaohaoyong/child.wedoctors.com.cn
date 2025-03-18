<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2020/11/23
 * Time: 下午7:42
 */

namespace console\controllers;


use common\models\Access;
use common\models\Appoint;
use common\models\Area;
use common\models\Autograph;
use common\models\ChildInfo;
use common\models\DoctorParent;
use common\models\Hospital;
use common\models\TmpLog;
use common\models\UserDoctor;
use common\models\UserLogin;
use common\models\UserParent;
use console\models\Pregnancy;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use yii\console\Controller;

class TextExcelController extends Controller
{
    public function actionIndex()
    {
        $inputFile = '/tmp/110548.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFile);
        $data = $spreadsheet->getActiveSheet()->toArray();
        var_dump($data);exit;
    }

}