<?php


namespace api\modules\v4\controllers;


use api\controllers\Controller;
use common\models\FamilyDoctorServices;

class FamilyDoctorServicesController extends Controller
{
    public function actionIndex(){


        return ['list'=>FamilyDoctorServices::$init,'type'=>FamilyDoctorServices::$typeText];
    }
}