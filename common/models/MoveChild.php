<?php 
namespace common\models;
use Yii;
class MoveChild  extends \yii\db\ActiveRecord
{
    public $name;
    public $birthday;
    public $idcard;
    public $mother;
    public $hospitalid;
    public function rules()
    {
        return [
            [['name','birthday','mother','hospitalid'], 'required'],
            [['idcard'], 'safe'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '儿童姓名',
            'birthday' => '儿童生日',
            'mother' => '母亲姓名',
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
            $query=ChildInfo::find()
                ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
                ->andWhere(['user_parent.mother'=>$this->mother])
                    ->andWhere(['child_info.name'=>$this->name])
                    ->andWhere(['child_info.birthday'=>$this->birthday]);
            if($this->idcard){
                $query->andWhere(['child_info.idcard'=>$this->idcard]);
            }
            $child = $query->one();
            if($child){
                $auto = Autograph::findOne(['userid' => $child->userid]);
                if ($auto) {
                    $auto->level = 0;
                    $auto->save();
                }
            
                $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
                $in_hospitalid = $doctorParent->doctorid;
                if ($doctorParent) {
                    //$doctorParent->createtime = time();
                    $doctorParent->doctorid = $this->hospitalid;
                    $doctorParent->save();
                }
                $child->doctorid = $doctor->hospitalid;
                if ($child->admin) {
                    $child->admin = $doctor->hospitalid;
                }
                return ['code'=>'100','msg'=>'成功','data'=>['in_hospitalid'=>$this->hospitalid,'out_hospitalid'=>$in_hospitalid,'userid'=>$preg->familyid]];
            }
            return ['code'=>200,'msg'=>'未查询到儿童'];
        }
        return ['code'=>200,'msg'=>'失败，社区不存在'];
    }
    /**
     * 
     * 迁出操作
     * 
     */
    public function actionOff(){
        $query=ChildInfo::find()
            ->leftJoin('user_parent', '`user_parent`.`userid` = `child_info`.`userid`')
            ->andWhere(['user_parent.mother'=>$this->mother])
                ->andWhere(['child_info.name'=>$this->name])
                ->andWhere(['child_info.birthday'=>$this->birthday]);
        if($this->idcard){
            $query->andWhere(['child_info.idcard'=>$this->idcard]);
        }
        $child = $query->one();
        if($child){
            $auto = Autograph::findOne(['userid' => $child->userid]);
            if ($auto) {
                $auto->level = 0;
                $auto->save();
            }
        
            $doctorParent = DoctorParent::findOne(['parentid' => $child->userid]);
            if ($doctorParent) {
                //$doctorParent->createtime = time();
                $doctorParent->doctorid = 47156;
                $doctorParent->save();
            }
            $child->doctorid = 110565;
            if ($child->admin) {
                $child->admin = 0;
            }
            return ['code'=>'100','msg'=>'成功','data'=>['in_hospitalid'=>47156,'out_hospitalid'=>$this->hospitalid,'userid'=>$child->userid]];
        }
        return ['code'=>200,'msg'=>'未查询到儿童'];
    }
}