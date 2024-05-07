<?php
namespace api\modules\v4\controllers;

use api\controllers\Controller;
use common\models\Agreement;

class AgreementController extends Controller
{
    public function actionContent(){
        $agreement = Agreement::find()->one();
        
        return ['content'=>$agreement->content];
    }

}
?>