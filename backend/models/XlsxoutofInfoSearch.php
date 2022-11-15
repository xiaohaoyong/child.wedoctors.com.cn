<?php

namespace backend\models;

use Yii;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\XlsxoutofInfo;


class XlsxoutofInfoSearch extends XlsxoutofInfo
{
    public static $file_type_num_Text = [1 => '宝宝', 2 => '孕妈'];
    public static $type_num_Text = [1 => '迁入', 2 => '迁出'];
    
    public $userid;
    public $username;
    public $add_time;
    public $add_time_e;
    public $file_type_num;
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userid' => '用户ID',
            'username' => '姓名',
            
            'add_time'=>'迁入/迁出时间',
            'add_time_e'=>'~',
            
            'file_type_num'=>'用户类型',
            
            'type_num'=>'类型',
        ];
    }
    
    public function rules()
    {
        return [
            [['id','userid','file_type_num','type_num'], 'integer'],
            [['username'], 'string'],
            [['add_time','add_time_e'], 'safe'],
        ];
    }
    
    public function search($params)
    {
        $query = XlsxoutofInfo::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
        
        if (!$this->validate()) 
        {
            return $dataProvider;
        }
        
        if( $this->file_type_num )
        {
            $query->andWhere(['xlsxoutof_info.file_type_num'=>$this->file_type_num]);
        }
        
        $user_info='';
        if($this->userid || $this->username)
        {
            if($this->file_type_num==1)     //[1 => '宝宝', 2 => '孕妈'];
            {
                $user_info = \common\models\ChildInfo::find()->select('id');
                if( $this->userid )
                {
                    $user_info->andWhere(['child_info.id'=>$this->userid]);
                }
                if( $this->username )
                {
                    $user_info->andWhere(['child_info.name'=>$this->username]);
                }
                
            }
            else if($this->file_type_num==2) 
            {
                $user_info = \common\models\UserParent::find()->select('userid as id');
                if( $this->userid )
                {
                    $user_info->andWhere(['user_parent.userid'=>$this->userid]);
                }
                if( $this->username )
                {
                    $user_info->andWhere(['user_parent.mother'=>$this->username]);
                }
            }
        }
        
        
        
        
        if($this->add_time){
            $query->andWhere(['>','xlsxoutof_info.add_time',strtotime($this->add_time)]);
        }
        if($this->add_time_e){
            $query->andWhere(['<','xlsxoutof_info.add_time',strtotime($this->add_time_e)]);
        }
        
        if($this->type_num){
            $query->andWhere(['xlsxoutof_info.type_num'=>$this->type_num-1]);
        }
        
        if( $user_info )
        {
            $query->andWhere(['in', 'userid', $user_info->column()]);
        }
        $query->orderBy([self::primaryKey()[0] => SORT_DESC]);
        
        return $dataProvider;
    }
    
    
    
    
    public function scenarios1()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
}
