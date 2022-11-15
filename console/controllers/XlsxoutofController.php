<?php

namespace console\controllers;

use common\models\ChildInfo;

use common\models\Xlsxoutof;
use common\models\XlsxoutofErr;
use common\models\XlsxoutofInfo;

use common\models\Doctors;
use common\models\Examination;
use common\models\UserParent;
use console\models\Pregnancy;
use OSS\Core\OssException;
use OSS\OssClient;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use udokmeci\yii2beanstalk\BeanstalkController;

class XlsxoutofController extends BeanstalkController
{
    public function listenTubes()
    { 
        return ['xlsxoutof'];
    }
    
    
    public function actionXlsxoutof($job='')
    {
        $Xlsxoutof_id=0;
        
        
        $sentData = $job->getData();
        $hospitalid=$sentData->hospitalid;
        $date=$sentData->date;      //云 异步数据体  数据体
        
        $Xlsxoutof_id= $sentData->Xlsxoutof_id;
        
        $__Xlsxoutof = Xlsxoutof::findOne($Xlsxoutof_id);
        $__Xlsxoutof->lock_num=1;
        $__Xlsxoutof->save();
        
        $log=new \common\components\Log('xlsxoutof',true);
        
        $log->addLog('异步任务');
        $log->addLog(json_encode([$hospitalid,$date]));
        
        $log->addLog('0迁入1迁出'.$__Xlsxoutof->type_num);
        
        
        $bucket= "wedoctorschild";
        try{
            $ossClient = new OssClient('LTAIteFpOZnX3aoE', 'lYWI5AzSjQiZWBhC2d7Ttt06bnoDFF', 'oss-cn-beijing.aliyuncs.com');
            $prefix = $hospitalid.'/';
            $delimiter = '/';
            $nextMarker = '';
            $maxkeys = 100;
            $options = array(
                'delimiter' => $delimiter,
                'prefix' => $prefix,
                'max-keys' => $maxkeys,
                'marker' => $nextMarker,
            );
            $listObjectInfo = $ossClient->listObjects($bucket, $options);
            $objectList = $listObjectInfo->getObjectList(); // object list
            if($objectList[0]) {
                $object = $objectList[0]->getKey();
                // 指定文件下载路径。
                $localfile = "/tmp/xlsxoutof_" . $hospitalid . ".xlsx";
                $options = array(
                    OssClient::OSS_FILE_DOWNLOAD => $localfile
                );
                $ossClient->getObject($bucket, $object, $options);
                $log->addLog("下载成功");
                $log->saveLog();
            }else {
                $log->addLog("未找到文件");
                $log->saveLog();
                
                $__Xlsxoutof->msg_str='未找到文件';
                $__Xlsxoutof->lock_num=2;
                $__Xlsxoutof->save();
                
                return self::DELETE;
            }

        } catch(OssException $e) {
            $log->addLog("下载失败");
            $log->saveLog();
            
            $__Xlsxoutof->msg_str='下载失败';
            $__Xlsxoutof->lock_num=2;
            $__Xlsxoutof->save();
            
            return self::DELETE;
        }



        if (empty($localfile) OR !file_exists($localfile)) {

            $log->addLog($localfile."文件不存在");
            $log->saveLog();
            
            
            $__Xlsxoutof->msg_str='文件不存在.'.$localfile;
            $__Xlsxoutof->lock_num=2;
            $__Xlsxoutof->save();
            
            
            return self::DELETE;
        }
        ini_set('memory_limit','1500M');

        $objRead = new Xlsx();   //建立reader对象
        $objRead->setReadDataOnly(true);
        $obj = $objRead->load($localfile);  //建立excel对象
        $currSheet = $obj->getSheet(0);   //获取指定的sheet表
        $columnH = $currSheet->getHighestColumn();   //取得最大的列号
        $highestColumnNum = Coordinate::columnIndexFromString($columnH);
        $rowCnt = $currSheet->getHighestRow();   //获取总行数
        $log->addLog("文件解析成功");
        
        $__Xlsxoutof->sum_num=$rowCnt-1;        //总数
        $__Xlsxoutof->save();
        
        $file_type_num=0;   //初始化   1宝宝  2孕妈
        $__function='';     //
        
        $field_index=[];
        for ($_row = 1; $_row <= $rowCnt; $_row++) {  //循环 一行 xlsx
            $rs = [];
            for ($_column = 0; $_column < $highestColumnNum; $_column++) {
                $a = "";
                $b = $_column % 26;
                $c = floor($_column / 26);
                if ($c > 0) {
                    $a = chr($c - 1 + 65);
                }
                $a = $a . chr($b + 65);
                $cellId = $a . $_row;
                $cellValue = $currSheet->getCell($cellId)->getValue();

                if ($_row == 1) {
                    $fields[$_column]=$cellValue;
                } else {
                    if ($field_index[$_column] && $cellValue !== '') {
                        $rs[$field_index[$_column]] = $cellValue ? (string)$cellValue : 0;
                    }
                }
            }
            
            
            if ($_row == 1) 
            {       //xlsx 第一行 判断使用model   之后行  使用model->inputData()录入数据
                
                $file_type_num = self::typeXlsxoutof($fields,$__Xlsxoutof);     //1宝宝  2孕妈
                
                if($file_type_num) {    //1宝宝  2孕妈
                    $log->addLog( '分析类型 1宝宝2孕妈'.$file_type_num );
                    
                    
                    switch ($file_type_num){
                        case 1:
                            $__function = 'file_type_num1';
                            break;
                        case 2:
                            $__function = 'file_type_num2';
                            break;
                            
                    }
                    
                    $ossClient->copyObject($bucket, $object, $bucket, $hospitalid."list/xlsxoutof_".date('Ymd')."xlsx");
                    $log->addLog("另存文件");
                    //$log->saveLog();
                }else{
                    //执行失败  未知  xlsx模板
                    $ossClient->deleteObject($bucket, $object);
                    
                    
                    $__Xlsxoutof->lock_num=2;       //执行完毕
                    $__Xlsxoutof->msg_str='执行失败!未知xlsx模板.';
                    $__Xlsxoutof->save();
                    
                    $log->addLog("执行失败!未知xlsx模板.");
                    $log->saveLog();
                    
                    
                    return self::DELETE;
                }
            }else{
                var_dump($rs);
                $return = self::$__function($rs, $hospitalid,$__Xlsxoutof);
                if($return['code']==100)
                {
                    $__Xlsxoutof->s_num=$__Xlsxoutof->s_num+1;        //成功数
                    //$__Xlsxoutof->save();
                    
                    $data = array(
                        'type_num'=>$__Xlsxoutof->type_num,                     //0迁入1迁出
                        'in_hospitalid'=>$return['date']['in_hospitalid'],      //迁入社区
                        'out_hospitalid'=>$return['date']['out_hospitalid'],    //迁出社区
                        'file_type_num'=>$file_type_num,                        //1宝宝2孕妈
                        'userid'=>$return['date']['userid'],                    //用户id
                        'hospitalid'=>$hospitalid,                              //社区
                    );
                    self::XlsxoutofInfo_save($data);
                }
                else
                {
                    $data = array(
                        'xlsxoutofid'=>$__Xlsxoutof->id,                     //迁入迁出id
                        'row_msg'=>$_row.'行,模式:'.$file_type_num.'.',      //xlsx_row msg
                        'curl_msg'=>$return['data'],    //api msg
                    );
                    self::XlsxoutofErr_save($data);
                    
                    $__Xlsxoutof->f_num=$__Xlsxoutof->f_num+1;        //失败数
                    //$__Xlsxoutof->save();
                }
                
            }
        }
        $obj->disconnectWorksheets();
        $log->saveLog();
        
        
        $__Xlsxoutof->lock_num=2;       //执行完毕
        $__Xlsxoutof->msg_str='执行成功.总'.$__Xlsxoutof->sum_num.',成'.$__Xlsxoutof->s_num.',失'.$__Xlsxoutof->f_num;
        $__Xlsxoutof->save();
        
        $ossClient->deleteObject($bucket, $object);
        
        $log->addLog("删除源文件");
        $log->addLog("导入完成");

        $log->saveLog();
        return self::DELETE;
    }
    
    //添加明细表
    public static function XlsxoutofInfo_save($data)
    {
        
        $XlsxoutofInfo=new XlsxoutofInfo();
        $XlsxoutofInfo->type_num=$data['type_num'];                 //0迁入1迁出
        $XlsxoutofInfo->in_hospitalid=$data['in_hospitalid'];       //迁入社区
        $XlsxoutofInfo->out_hospitalid=$data['out_hospitalid'];     //迁出社区
        $XlsxoutofInfo->file_type_num=$data['file_type_num'];       //1宝宝2孕妈
        $XlsxoutofInfo->userid=$data['userid'];                     //用户id
        $XlsxoutofInfo->hospitalid=$data['hospitalid'];             //社区
        $XlsxoutofInfo->add_time=time();
        $XlsxoutofInfo->save();
    }
    
    public static function XlsxoutofErr_save($data)
    {
        $XlsxoutofErr=new XlsxoutofErr();
        $XlsxoutofErr->xlsxoutofid=$data['xlsxoutofid'];                 //迁入迁出id
        $XlsxoutofErr->row_msg=$data['row_msg'];       //xlsx_row msg
        $XlsxoutofErr->curl_msg=$data['curl_msg'];     //api msg
        $XlsxoutofErr->add_time=time();
        $XlsxoutofErr->save();
    }
    
    public static function typeXlsxoutof($rs,Xlsxoutof $__Xlsxoutof){
        
        if( array_search('儿童姓名',$rs)){
            $__Xlsxoutof->file_type_num=1;
            $__Xlsxoutof->save();
            
            return 1;
            /*
            if( $__Xlsxoutof->type_num )    //0迁入1迁出
            {
                return 'out_baby';
            }
            return 'in_baby';
            */
        }
        
        if( array_search('孕产妇姓名',$rs)){
            $__Xlsxoutof->file_type_num=2;
            $__Xlsxoutof->save();
            
            return 2;
            /*
            if( $__Xlsxoutof->type_num )    //0迁入1迁出
            {
                return 'out_mam';
            }
            return 'in_mam';
            */
        }
        return 0;
    }
    
    
    //处理 1宝宝  2孕妈
    public static function file_type_num1($rs, $hospitalid,$__Xlsxoutof)         //1宝宝  2孕妈
    {
        return file_type_num($rs, $hospitalid,$__Xlsxoutof,1);
    }
    public static function file_type_num2($rs, $hospitalid,$__Xlsxoutof)         //1宝宝  2孕妈
    {
        return file_type_num($rs, $hospitalid,$__Xlsxoutof,2);
    }
    
    public static function file_type_num($rs, $hospitalid,$__Xlsxoutof,$file_type_num)         //1宝宝  2孕妈
    {
        //$rs一条excel    $file_type_num 1宝宝  2孕妈
        /*
        $file_type_num 1宝宝
        儿童姓名	儿童生日	母亲姓名	儿童身份证号码（非必填）	原签约社区（迁入时必填）
        
        $file_type_num 2孕妈
        孕产妇姓名	孕产妇生日	孕产妇身份证号码	原签约社区（迁入时必填）
        */
        //$hospitalid       社区id
        //$file_type_num    1宝宝  2孕妈
        //$__Xlsxoutof->type_num     //0迁入1迁出
        $url = self::api_mkurl($rs, $hospitalid,$file_type_num,$__Xlsxoutof->type_num);       //API url
        
        $curl = new HttpRequest($url, false, 10);
        $json_str = $curl->get();
        $user_data = json_decode($json_str, true);
        /**   
        --------------需 接口 实现数据返回------------------------
        返回数组
        code 返回状态  100正确
        data 数据数组
            in_hospitalid   迁入社区
            out_hospitalid  迁出社区
            userid          用户id
        --------------需 接口 实现数据返回------------------------ 
        */ 
        if( $user_data['code']=='100' ) // 处理成功
        {
            return array('code'=>100,'data'=>$user_data['data']);
        }
        else            //处理失败
        {
            return array('code'=>800,'data'=>$json_str);
        }
    }
    
    //$rs一条excel    $file_type_num 1宝宝  2孕妈
    /*
    $file_type_num 1宝宝
    儿童姓名	儿童生日	母亲姓名	儿童身份证号码（非必填）	原签约社区（迁入时必填）
    
    $file_type_num 2孕妈
    孕产妇姓名	孕产妇生日	孕产妇身份证号码	原签约社区（迁入时必填）
    */
    //$hospitalid       社区id
    //$file_type_num    1宝宝  2孕妈
    //$type_num     //0迁入1迁出
    public static function api_mkurl($rs, $hospitalid,$file_type_num,$type_num)
    {
        if( $type_num==0 )
        {//迁入
            if( $file_type_num==1 )
            {//迁入宝宝
                $url='';
            }
            else
            {//迁入孕妈
                $url='';
            }
        }
        else
        {//迁出
            if( $file_type_num==1 )
            {//迁出宝宝
                $url='';
            }
            else
            {//迁出孕妈
                $url='';
            }
        }
        return $url;
    }
    
    
    
    
    
}