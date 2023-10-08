<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2021/2/8
 * Time: 下午4:52
 */

namespace common\helpers;


use yii\validators\Validator;

class IdcardValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if(is_array($model->$attribute)){
            $content=array_pop($model->$attribute);
        }else{
            $content=$model->$attribute;
        }
        if (!$this->gapassport_verify($content)
            && !$this->idCardVerify($content)
            && !$this->taibaoVerify($content)
            && !$this->twpassport_verify($content)
            && !$this->junguanVerify($content)) {
            $this->addError($model, $attribute,  '请填写正确身份证号/港澳台身份证');
        }
    }


    /**
     * 来往港澳通行证:
     * 1.W，C+8位数字
     * 2.7位数字
     */
    function gapassport_verify($content)
    {
        $pattern = "/^\d{7}$|^[W|C|H|M]\d{8}$/";
        if (!preg_match($pattern, $content)) {
            return false;
        }
        return true;
    }

    /**
     * 身份证校验代码
     */
    function idCardVerify($id_card)
    {
        if (strlen($id_card) == 18) {
            return $this->idcard_checksum18($id_card);
        } else {
            return false;
        }
    }

// 计算身份证校验码，根据国家标准GB 11643-1999
    function idcard_verify_number($idcard_base)
    {
        if (strlen($idcard_base) != 17) {
            return false;
        }
        // 加权因子
        $factor = array(
            7,
            9,
            10,
            5,
            8,
            4,
            2,
            1,
            6,
            3,
            7,
            9,
            10,
            5,
            8,
            4,
            2
        );
        // 校验码对应值
        $verify_number_list = array(
            '1',
            '0',
            'X',
            '9',
            '8',
            '7',
            '6',
            '5',
            '4',
            '3',
            '2'
        );
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

// 将15位身份证升级到18位
    function idcard_15to18($idcard)
    {
        if (strlen($idcard) != 15) {
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array(
                    '996',
                    '997',
                    '998',
                    '999'
                )) !== false
            ) {
                $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . $this->idcard_verify_number($idcard);
        return $idcard;
    }


    /**
     * 18位身份证校验码有效性检查
     */
    function idcard_checksum18($idcard)
    {
        if (strlen($idcard) != 18) {
            return false;
        }
        $aCity = array(11 => "北京", 12 => "天津", 13 => "河北", 14 => "山西", 15 => "内蒙古",
            21 => "辽宁", 22 => "吉林", 23 => "黑龙江",
            31 => "上海", 32 => "江苏", 33 => "浙江", 34 => "安徽", 35 => "福建", 36 => "江西", 37 => "山东",
            41 => "河南", 42 => "湖北", 43 => "湖南", 44 => "广东", 45 => "广西", 46 => "海南",
            50 => "重庆", 51 => "四川", 52 => "贵州", 53 => "云南", 54 => "西藏",
            61 => "陕西", 62 => "甘肃", 63 => "青海", 64 => "宁夏", 65 => "新疆",
            71 => "台湾", 81 => "香港", 82 => "澳门",
            91 => "国外");
        //非法地区
        if (!array_key_exists(substr($idcard, 0, 2), $aCity)) {
            return false;
        }
        //验证生日
        if (!checkdate(substr($idcard, 10, 2), substr($idcard, 12, 2), substr($idcard, 6, 4))) {
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        if ($this->idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * 国内护照
     * 1.G|E+8位数字：如：G12345678
     * 2.D|S|P+7位数字：如：D1234567
     */
    function passportVerify($content)
    {
        $pattern = "/^[\w]{5,17}$/";
        return preg_match($pattern, $content);
    }


    /**
     * 台胞证：
     * 1、8位数字，如：12345678
     * 2、10位数字+(1位英文字母)，如：1234567890(T)
     */
    function taibaoVerify($content)
    {
        $pattern_one = "/^[\d]{8}$/";
        $pattern_two = "/^[\d]{10}[(|（][a-zA-z][)|）]$/iu";
        return (preg_match($pattern_one, $content) || preg_match($pattern_two, $content));
    }

    /**
     * 赴台证:
     * 1.T+8位数字
     * 2.25+7位数字
     */
    function twpassport_verify($content)
    {
        $pattern = "/^25\d{7}$|^T\d{8}$/";
        if (!preg_match($pattern, $content)) {
            return false;
        }
        return true;
    }


    /**
     * 军官证
     * X字+8位，如：南字12345678
     */
    function junguanVerify($content)
    {
        if (mb_strlen($content) != 10) {
            return false;
        }
        $check = preg_match("/^[\x{4e00}-\x{9fa5}]{1}\x{5b57}$/u", mb_substr($content, 0, 2));
        if ($check && preg_match("/^\d{8}$/u", mb_substr($content, 2, 8))) {
            return true;
        }
        return false;
    }



}