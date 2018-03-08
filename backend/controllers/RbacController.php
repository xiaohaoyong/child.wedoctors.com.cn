<?php

namespace backend\controllers;

use Yii;
use backend\models\rbac\AuthMenu;
use backend\models\rbac\Rbac;
use backend\models\rbac\Role;
use backend\models\rbac\AuthItem;
use yii\rbac\Item;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

class RbacController extends BaseController {

    /**
     * 添加 角色
     */
    public function actionAddRole() {
        $model = new Role();

        if (!\Yii::$app->request->isPost) {
            return $this->render('role', ['model' => $model]);
        }

        // 表单字段验证
        $model->load(\Yii::$app->request->post(), 'Role');
        $model->validate();
        if ($model->getErrors()) {
            $error = array_values($model->FirstErrors)[0];
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        $rbac = new Rbac();
        $object = new Item();
        $object->type = Item::TYPE_ROLE;
        $object->name = $model->name;
        $object->description = $model->description;

        if ($rbac->add($object)) {
            return "<script>alert('完成');history.back()</script>";
        }
    }

    /**
     * 更新 角色
     */
    public function actionUpdateRole() {
        $model = new Role();
        $rbac = new Rbac();

        if (!\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->get('id', '');
            $row = (array) $rbac->getRole($id);
            return $this->render('role', ['model' => $model, 'row' => $row, 'id' => $id]);
        }

        // 表单字段验证
        $model->load(\Yii::$app->request->post(), 'Role');
        if (!$model->validate()) {
            $error = array_values($model->FirstErrors)[0];
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        $id = \Yii::$app->request->post('id', '');

        $object = new Item();
        $object->type = Item::TYPE_ROLE;
        $object->name = $model->name;
        $object->description = $model->description;
        if ($rbac->update($id, $object)) {
            return "<script>alert('完成');history.back()</script>";
        }
    }

    /**
     * 删除一个角色
     */
    public function actionRemoveRole() {
        $id = \Yii::$app->request->get('id', '');

        $rbac = new Rbac();
        $object = new Item();
        $object->name = $id;
        if ($rbac->remove($object)) {
            return "<script>alert('完成');history.back()</script>";
        }
    }

    /**
     * 角色列表
     */
    public function actionRoles() {
        $query = AuthItem::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        // 增加过滤条件来调整查询对象
        $query->andWhere(['type' => 1]);

        return $this->render('rolelist', ['dataProvider' => $dataProvider]);
    }

    /**
     * 添加菜单
     */
    public function actionAddMenu() {
        $model = new AuthMenu();

        if (!\Yii::$app->request->isPost) {

            $menus = $model->getMenus1And2();

            $model->display = 1;

            return $this->render('menu', ['model' => $model, 'menus' => [0 => '顶级', $menus], 'row' => []]);
        }

        $model->load(\Yii::$app->request->post(), 'AuthMenu');
        $model->validate();
        if ($model->getErrors()) {
            var_dump($model->errors);exit;
            $error = array_values($model->FirstErrors)[0];
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        // 权限节点添加
        $rbac = new Rbac();
        $object = new Item();
        $object->type = Item::TYPE_PERMISSION;
        $object->name = $model->name;
        $object->description = $model->description;

        if ($rbac->add($object)) {
            $model->save();
            return "<script>alert('完成');history.back()</script>";
        }
    }

    /**
     * 菜单列表管理
     * @return type
     */
    public function actionMenuList() {

        $query = AuthMenu::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        // 增加过滤条件来调整查询对象
        $query->andWhere(['parent_id' => 0]);
        $query->orderBy("sort asc, display desc");

        return $this->render('menulist', ['dataProvider' => $dataProvider]);
    }

    /**
     * 子菜单
     * @return type
     */
    public function actionSubmenu() {
        $query = AuthMenu::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $id = \Yii::$app->request->get('id', '');

        // 增加过滤条件来调整查询对象
        $query->andWhere(['parent_id' => $id]);
        $query->orderBy("sort asc, display desc");

        return $this->render('menulist', ['dataProvider' => $dataProvider]);
    }

    /**
     * 更新菜单
     * @return string
     */
    public function actionUpdate() {
        $model = new AuthMenu();

        if (!\Yii::$app->request->isPost) {

            $menus = $model->getMenus1And2();

            $id = \Yii::$app->request->get('id', '');
            $row = $model->find()->where(['id' => $id])->asArray()->one();
            if (!$row)
                return 'null';

            $mca = explode('/', $row['name']);
            if (count($mca) > 2) {
                $row['moduleId'] = $mca[0];
                $row['controllerId'] = $mca[1];
                $row['actionId'] = $mca[2];
            } else {
                $row['moduleId'] = '';
                $row['controllerId'] = $mca[0];
                $row['actionId'] = $mca[1];
            }


            $model->display = $row['display'];
            $model->parent_id = $row['parent_id'];

            return $this->render('menu', ['model' => $model, 'menus' => [0 => '顶级', $menus], 'row' => $row, 'id' => $id]);
        }

        $id = \Yii::$app->request->post('id');

        $query = $model->findOne($id);
        $model->load(\Yii::$app->request->post(), 'AuthMenu');
        $model->_oldUniqueId = $query->name;
        $model->validate();

        if ($model->getErrors()) {
            $error = array_values($model->FirstErrors)[0];
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        $query->load(\Yii::$app->request->post(), 'AuthMenu');
        $query->name = $model->name;

        $rbac = new Rbac();
        $object = new Item();
        $object->type = Item::TYPE_PERMISSION;
        $object->name = $query->name;
        $object->description = $query->description;

        if ($rbac->update($model->_oldUniqueId, $object)) {
            $query->save(false);
            //return "<script>alert('完成');location.href='index.php?r=rbac/menu-list';</script>";
            return "<script>alert('完成');history.back();</script>";
        }
    }

    /**
     * 删除菜单
     * @return string
     */
    public function actionDelete() {
        $id = \Yii::$app->request->get('id', '');

        $model = new AuthMenu();
        $res = $model->findOne($id);

        $rbac = new Rbac();
        $object = new Item();
        $object->name = $res->name;
        if ($rbac->remove($object)) {
            $res->delete();
            return "<script>alert('完成');history.back()</script>";
        }
    }

    /**
     * 赋予权限给角色
     */
    public function actionAddChild() {
        $rbac = new Rbac();

        if (!\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->get('id', '');

            $permissions = $rbac->getPermissionsByRole($id);
            $permissions_keys = array_keys($permissions);

            return $this->renderAjax('addchild', ['id' => $id, 'permissions' => $permissions_keys]);
        }

        $parentName = \Yii::$app->request->post('id', ''); // 角色
        $childs = \Yii::$app->request->post('childs', []); // 权限


        if (!is_array($childs) && count($childs) < 1) {
            $error = '请选择';
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        $parent = (object) ['name' => $parentName];

        // 先移除改角色所有的权限
        $rbac->removeChildren($parent);

        foreach ($childs as $childsName) {
            $child = (object) ['name' => $childsName];
            $rbac->addChild($parent, $child);
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['code'=>10000,'msg'=>'成功'];
    }

    /**
     * 撤回权限向角色
     * [暂不用]
     * @return string
     */
    public function actionRemoveChild() {
        $rbac = new Rbac();

        $parentName = \Yii::$app->request->get('parent', 'caiwu'); // 角色
        $childs = \Yii::$app->request->get('childs', ['user:user:remove1']); // 权限

        if (!is_array($childs) && count($childs) < 1) {
            $error = '请选择';
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        $parent = (object) ['name' => $parentName];
        foreach ($childs as $childsName) {
            $child = (object) ['name' => $childsName];
            $rbac->removeChild($parent, $child);
        }

        return "<script>alert('完成');history.back()</script>";
    }

    /**
     * 撤回 角色/权限 向用户
     * [暂不用]
     */
    public function actionRevoke() {
        $role = new Item();
        $rbac = new Rbac();

        $userId = \Yii::$app->request->post('userid', '1');
        $names = \Yii::$app->request->post('names', ['user:user:add']);
        $names = array_filter($names);

        if (!is_array($names) && count($names) < 1) {
            $error = '请选择';
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        foreach ($names as $name) {
            $role->name = $name;
            $rbac->revoke($role, $userId);
        }

        exit('done');
    }

    /**
     * 获取权限 BY user/role
     */
    public function actionRoless() {
        $rbac = new Rbac();

        $userId = 1;
        $roles = $rbac->getRolesByUser($userId);

        echo "<pre>";
//        print_r($roles);
//        exit;

        $roles = $rbac->getPermissionsByUser($userId);
//        print_r($roles);

        $permissionName = 'aaa';
        $res = $rbac->checkAccess($userId, $permissionName);
//        var_dump($res);
        // 获取子节点
        $name = 'caiwu';
        $res = $rbac->getChildren($name);
//        print_r($res);
//        exit;

        $parent = (object) ['name' => 'aaa'];
        $child = (object) ['name' => 'bbb'];

        $res = $rbac->canAddChild($parent, $child);
//        var_dump($res);

        $res = $rbac->hasChild($parent, $child);
//        var_dump($res);

        $res = $rbac->getAssignments($userId);
//        print_r($res);

        $res = $rbac->getRules();
//        print_r($res);
//        $name = 'user';
//        $res = $rbac->getItem($name);
//        var_dump($res);
        // 检测是否可以添加 && 检测是否已经存在父子关系
        if (!$rbac->canAddChild($parent, $child)) {
            $error = 'you can\'t add this child. because there is relation parent and child ';
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }
        // 检测是否存在子节点
        if ($rbac->hasChild($parent, $child)) {
            $error = 'the child exist.';
            return $this->render('/site/error', ['name' => 'Error', 'message' => $error]);
        }

        if ($rbac->addChild($parent, $child)) {
            exit('done');
        }
    }

}
