<?php

namespace backend\models;

use common\models\ArticleInfo;
use Yii;

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
class Article extends \common\models\Article
{


}
