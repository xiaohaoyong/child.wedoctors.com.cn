<?php
/**
 * 系统
 */
namespace backend\controllers;

use backend\models\rbac\AuthAdminuser;
//use yii\widgets\DetailView;
//use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
//use yii\grid\GridView;
use yii\rbac\Item;
use backend\models\rbac\Rbac;



class AdminuserController extends BaseController {

    public function actionIndex() {
        $query = AuthAdminuser::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        // 增加过滤条件来调整查询对象
        //$query->andWhere(['in','status', [0, 1]]);
        //$query->orderBy(['is_admin'=>SORT_DESC]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionAdd() {
        $model = new AuthAdminuser();
        $adminInfo = \Yii::$app->session->get('_ADMININFO');

        if (!\Yii::$app->request->isPost) {

            $model->status = 1;
            $model->is_admin = 0;

            return $this->render('add', ['model' => $model, 'row' => $model]);
        }

        // 表单字段验证
        $model->load(\Yii::$app->request->post(), 'AuthAdminuser');
        if (!$model->validate()) {
            $error = array_values($model->FirstErrors)[0];
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        $model->createtime = time();
        $model->save(false);

        return "<script>alert('完成');history.back()</script>";
    }

    public function actionUpdate() {
        $model = new AuthAdminuser();

        $id = \Yii::$app->request->get('id', 1);

        if (!\Yii::$app->request->isPost) {

            $row = $model->find()->where(['id' => $id])->one();

            $model->status = $row->status;
            $model->is_admin = $row->is_admin;

            return $this->render('add', ['model' => $model, 'row' => $row]);
        }

        // 表单字段验证
        $query = $model->findOne($id);
        $query->load(\Yii::$app->request->post());
        $query->_oldUniqueId = $query->userlogin;
        $query->validate();

        if ($query->getErrors()) {
            $error = array_values($query->FirstErrors)[0];
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }
        
        // 如果不更新密码
        if (!$query->isUpdatePassword) {
            unset($query->password);
        }

        $query->save(false);

        return "<script>alert('完成');history.back()</script>";
    }

    public function actionDelete() {
        $model = new AuthAdminuser();

        $id = \Yii::$app->request->get('id', 1);

        // 表单字段验证
        $query = $model->findOne($id);
        $query->delete();

        return "<script>alert('完成');history.back()</script>";
    }

    /**
     * 赋予 角色/权限 给用户
     */
    public function actionAssign() {
        $role = new Item();
        $rbac = new Rbac();

        if (!\Yii::$app->request->isPost) {
            $userId = \Yii::$app->request->get('id', 0);

            $list = $rbac->getRoles();

            $roles = $rbac->getRolesByUser($userId);
            $roles_keys = array_keys($roles);

            return $this->renderAjax('assign', ['list' => $list, 'roles' => $roles_keys, 'id' => $userId]);
        }

        $userId = \Yii::$app->request->post('id', 0);
        $names = \Yii::$app->request->post('childs', []);

        if (!is_array($names) && count($names) < 1) {
            $error = '请选择';
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        // 先移除该用户所有角色
        $rbac->revokeAll($userId);

        foreach ($names as $name) {
            $role->name = $name;
            $rbac->assign($role, $userId);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['code'=>10000,'msg'=>'成功'];
    }

}
