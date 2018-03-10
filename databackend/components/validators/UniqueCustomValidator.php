<?php

namespace common\components\validators;

use Yii;
use yii\validators\UniqueValidator;

/**
 * 自定义unique验证 (针对用于update操作, 也可用于add操作)
 * cuikai 16-9-13 下午3:41
 */
class UniqueCustomValidator extends UniqueValidator {
    
    /**
     * old unique key name
     */
    const UNIQUE_NAME = '_oldUniqueId';

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute) {
        /* @var $targetClass ActiveRecordInterface */
        $targetClass = $this->targetClass === null ? get_class($model) : $this->targetClass;
        $targetAttribute = $this->targetAttribute === null ? $attribute : $this->targetAttribute;

        if (is_array($targetAttribute)) {
            $params = [];
            foreach ($targetAttribute as $k => $v) {
                $params[$v] = is_int($k) ? $model->$v : $model->$k;
            }
        } else {
            $params = [$targetAttribute => $model->$attribute];
        }

        foreach ($params as $value) {
            if (is_array($value)) {
                $this->addError($model, $attribute, Yii::t('yii', '{attribute} is invalid.'));

                return;
            }
        }

        $query = $targetClass::find();
        $query->andWhere($params);

        if ($this->filter instanceof \Closure) {
            call_user_func($this->filter, $query);
        } elseif ($this->filter !== null) {
            $query->andWhere($this->filter);
        }

        $exists = $query->select([$attribute])->scalar();
        
        $_oldUniqueId = self::UNIQUE_NAME;
        if ($exists && !isset($model->$_oldUniqueId)) {
            $this->addError($model, $attribute, $this->message);
        } elseif ($exists && $exists != $model->$_oldUniqueId) {            
            $this->addError($model, $attribute, $this->message);
        } elseif ($exists && !$model->$_oldUniqueId) {
            $this->addError($model, $attribute, $this->message);
        }
        
        return true;
    }

}
