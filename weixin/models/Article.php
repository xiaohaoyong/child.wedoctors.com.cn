<?php

namespace weixin\models;

use Yii;
use yii\data\Pagination;

/**
 * This is the model class for table "article".
 *
 * @property string $id
 * @property integer $catid
 * @property integer $level
 * @property string $createtime
 * @property integer $child_type
 * @property string $num
 * @property integer $type
 */
class Article extends \common\models\Article {

    public  static function find()
    {
        return parent::find()->andFilterWhere(['!=','catid',6]);
    }
    /**
     * 文章列表
     *
     * @param string $childid
     * @return 数组
     */
    public static function getList($page = 1, $catid = null,$usertype=0) {
        $pageSize = 10;
        $data = self::find();
        if (!empty($catid)) {
            $data->andWhere(['catid' => $catid]);
        }
        if($usertype==1){
            $data->andFilterWhere(['type'=>0]);
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
        if (!empty($datas)) {
            foreach ($datas as $key => $val) {
                $results['list'][$key] = self::findById($val->id);
            }
        }
        return $results;
    }

    /**
     * userid查用户
     * @param string $id
     * @return 数组
     * slx
     */
    public static function findById($id) {
        $data = self::findOne($id);
        if (empty($data)) {
            return [];
        }
        $result = $data->toArray();
        $InfoData = \common\models\ArticleInfo::findOne($id);
        $result['title'] = $InfoData->title;
        $result['content'] = $InfoData->content;
        $result['img'] = empty($InfoData->img) ? 11 : $InfoData->img;
        $result['source']=$InfoData->source?$InfoData->source:"儿宝宝";
        return $result;
    }

}
