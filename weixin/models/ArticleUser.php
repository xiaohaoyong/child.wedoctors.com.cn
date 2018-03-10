<?php

namespace weixin\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "article_user".
 *
 * @property string $id
 * @property string $childid
 * @property string $touserid
 * @property string $artid
 * @property string $createtime
 * @property string $userid
 * @property string $child_type
 */
class ArticleUser extends \common\models\ArticleUser {

    /**
     * 儿童宣教记录
     *
     * @param string $childid
     * @return 数组
     */
    public static function GetListByChildid($childid = null, $page = 1, $pageSize = 10) {
        $data = static::find();
        if (!empty($childid)) {
            $data->andWhere(['childid' => $childid]);
        }
        $data->orderBy('createtime desc');
        //总共多少页
        $results['countPages'] = ceil($data->count() / $pageSize);
        if ($page > $results['countPages']) {
            $results['list'] = '';
            return $results;
        }
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pageSize]);
        $datas = $data->offset($pages->offset)->limit($pages->limit)->all();
        foreach ($datas as $key => $val) {
            $results['list'][$key]['createdata'] = date('Y/m/d', $val->createtime);
            $results['list'] [$key]['userid'] = $val->userid;
            $InfoData = \common\models\ArticleInfo::findOne($val->artid);
            $results['list'] [$key]['content'] = !empty($InfoData['content']) ? $InfoData['content'] : '未知';
            $results['list'] [$key]['title'] = !empty($InfoData['title']) ? $InfoData['title'] : '未知';
            $results['list'] [$key]['id'] = !empty($InfoData['id']) ? $InfoData['id'] : 0;
        }
        return $results;
    }

}
