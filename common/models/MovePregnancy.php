<?php 
namespace common\models;
use Yii;
class MovePregnancy  extends \yii\db\ActiveRecord
{
    public $name;
    public $birthday;
    public $idcard;
    public $hospitalid;
    public function rules()
    {
        return [
            [['name','birthday','hospitalid','idcard'], 'required'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '产妇姓名',
            'birthday' => '产妇生日',
            'hospitalid' => '操作社区',
            'idcard' => '身份证号',
        ];
    }
    /**
     * 迁入操作
     */
    public function actionOn(){
        $doctor = UserDoctor::findOne(['userid'=>$this->hospitalid]);
        if($doctor){
            $preg=Pregnancy::find()
            ->andWhere(['pregnancy.field1'=>$this->name])
            ->andWhere(['pregnancy.field2'=>$this->birthday])
            ->andWhere(['pregnancy.field4'=>$this->idcard])
            ->one();
            if($preg){
                $auto = Autograph::findOne(['userid' => $preg->familyid]);
                if ($auto) {
                    $auto->level = 0;
                    $auto->save();
                }
            
                $doctorParent = DoctorParent::findOne(['parentid' => $preg->familyid]);
                if ($doctorParent) {
                    //$doctorParent->createtime = time();
                    $doctorParent->doctorid = $this->hospitalid;
                    $doctorParent->save();
                }
                return ['code'=>'100','msg'=>'成功'];
            }
            return ['code'=>200,'msg'=>'未查询到孕妇'];
        }
        return ['code'=>200,'msg'=>'失败，社区不存在'];
    }
    /**
     * 
     * 迁出操作
     * 
     */
    public function actionOff(){
        $preg=Pregnancy::find()
        ->andWhere(['pregnancy.field1'=>$this->name])
        ->andWhere(['pregnancy.field2'=>$this->birthday])
        ->andWhere(['pregnancy.field4'=>$this->idcard])
        ->one();
        if($preg){
            $auto = Autograph::findOne(['userid' => $preg->familyid]);
            if ($auto) {
                $auto->level = 0;
                $auto->save();
            }
        
            $doctorParent = DoctorParent::findOne(['parentid' => $preg->familyid]);
            if ($doctorParent) {
                //$doctorParent->createtime = time();
                $doctorParent->doctorid = 47156;
                $doctorParent->save();
            }
            return ['code'=>200,'msg'=>'成功'];
        }
        return ['code'=>200,'msg'=>'未查询到孕妇'];
    }
}