<?php

namespace console\models;

/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/6/13
 * Time: 下午3:23
 */
use common\models\ChildInfo;
use common\models\Examination;
use common\models\User;
use common\models\UserLogin;
use common\models\UserParent;

class ExInput
{
    public $hospitalid;


    public function addLog($value)
    {
        $this->lineLog .= "==" . $value;
    }

    public function saveLog()
    {
        $file = "log/" . date('Y-m-d') . "-t-" . $this->hospitalid . ".log";
        echo $this->lineLog."\n";
        file_put_contents($file, $this->lineLog . "\n", FILE_APPEND);
    }

    /**
     * 主操作函数
     * @param $value
     */
    public function inputData($value)
    {
        $row=$value;
        $this->addLog($this->hospitalid);
        $this->addLog($value[3]);

        $row[3] = substr($row[3], 0, strlen($row[3]) - 11);
        $ex = Examination::find()->andFilterWhere(['field1' => $row[0]])
            ->andFilterWhere(['field2' => $row[1]])
            ->andFilterWhere(['field3' => $row[2]])
            ->andFilterWhere(['field4' => $row[3]])
            ->andFilterWhere(['source' => $this->hospitalid])
            ->andFilterWhere(['field19' => $row[18]])->one();
        // if($ex){ echo "jump\n";continue;}
        $isupdate = $ex ? 0 : 1;
        $ex = $ex ? $ex : new Examination();

        $child = ChildInfo::find()->andFilterWhere(['name' => trim($row[0])])
            ->andFilterWhere(['birthday' => strtotime($row[18])])
            ->andFilterWhere(['source'=>$this->hospitalid])
            ->one();
        echo $row[0];
        if (!$child) {
            echo "--儿童不存在";
        } else {
            echo "--儿童存在";
            $ex->childid = $child->id;
        }

        $ex->field1 = $row[0];
        $ex->field2 = $row[1];
        $ex->field3 = $row[2];
        $ex->field4 = $row[3];
        $ex->field5 = $row[4];
        $ex->field6 = $row[5];
        $ex->field7 = $row[6];
        $ex->field8 = $row[7];
        $ex->field9 = $row[8];
        $ex->field10 = $row[9];
        $ex->field11 = $row[10];
        $ex->field12 = $row[11];
        $ex->field13 = $row[12];
        $ex->field14 = $row[13];
        $ex->field15 = $row[14];
        $ex->field16 = $row[15];
        $ex->field17 = $row[16];
        $ex->field18 = $row[17];
        $ex->field19 = $row[18];
        $ex->field20 = $row[19];
        $ex->field21 = $row[20];
        $ex->field22 = $row[21];
        $ex->field23 = $row[22];
        $ex->field24 = $row[23];
        $ex->field25 = $row[24];
        $ex->field26 = $row[25];
        $ex->field27 = $row[26];
        $ex->field28 = $row[27];
        $ex->field29 = $row[28];
        $ex->field30 = $row[29];
        $ex->field31 = $row[30];
        $ex->field32 = $row[31];
        $ex->field33 = $row[32];
        $ex->field34 = $row[33];
        $ex->field35 = $row[34];
        $ex->field36 = $row[35];
        $ex->field37 = $row[36];
        $ex->field38 = $row[37];
        $ex->field39 = $row[38];
        $ex->field40 = $row[39];
        $ex->field41 = $row[40];
        $ex->field42 = $row[41];
        $ex->field43 = $row[42];
        $ex->field44 = $row[43];
        $ex->field45 = $row[44];
        $ex->field46 = $row[45];
        $ex->field47 = $row[46];
        $ex->field48 = $row[47];
        $ex->field49 = $row[48];
        $ex->field50 = $row[49];
        $ex->field51 = $row[50];
        $ex->field52 = $row[51];
        $ex->field53 = $row[52];
        $ex->field54 = $row[53];
        $ex->field55 = $row[54];
        $ex->field56 = $row[55];
        $ex->field57 = $row[56];
        $ex->field58 = $row[57];
        $ex->field59 = $row[58];
        $ex->field60 = $row[59];
        $ex->field61 = $row[60];
        $ex->field62 = $row[61];
        $ex->field63 = $row[62];
        $ex->field64 = $row[63];
        $ex->field65 = $row[64];
        $ex->field66 = $row[65];
        $ex->field67 = $row[66];
        $ex->field68 = $row[67];
        $ex->field69 = $row[68];
        $ex->field70 = $row[69];
        $ex->field71 = $row[70];
        $ex->field72 = $row[71];
        $ex->field73 = $row[72];
        $ex->field74 = $row[73];
        $ex->field75 = $row[74];
        $ex->field76 = $row[75];
        $ex->field77 = $row[76];
        $ex->field78 = $row[77];
        $ex->field79 = $row[78];
        $ex->field80 = $row[79];
        $ex->field81 = $row[80];
        $ex->field82 = $row[81];
        $ex->field83 = $row[82];
        $ex->field84 = $row[83];
        $ex->field85 = $row[84];
        $ex->field86 = $row[85];
        $ex->field87 = $row[86];
        $ex->field88 = $row[87];
        $ex->field89 = $row[88];
        $ex->field90 = $row[89];
        $ex->field91 = $row[90];
        $ex->field92 = $row[91];
        $ex->source = $this->hospitalid;
        $ex->isupdate = $isupdate;
        $ex->save();
        if ($ex->firstErrors) {
            echo "error";
            var_dump($ex->firstErrors);
        }
        echo "\n";
        $this->saveLog();
    }
}