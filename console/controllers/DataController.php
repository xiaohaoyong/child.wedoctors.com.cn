<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2017/5/2
 * Time: 上午11:32
 */

namespace console\controllers;


use api\modules\v2\controllers\ExaController;
use callmez\wechat\sdk\components\BaseWechat;
use callmez\wechat\sdk\MpWechat;
use callmez\wechat\sdk\Wechat;
use common\components\HttpRequest;
use common\helpers\SmsSend;
use common\helpers\WechatSendTmp;
use common\models\Area;
use common\models\ArticleComment;
use common\models\ArticleUser;
use common\models\BabyGuide;
use common\models\BabyTool;
use common\models\BabyToolTag;
use common\models\ChatRecord;
use common\models\ChildInfo;
use common\models\DataUpdateRecord;
use common\models\DataUser;
use common\models\DoctorParent;
use common\models\Doctors;
use common\models\Examination;
use common\models\Hospital;
use common\models\HospitalAppoint;
use common\models\HospitalAppointWeek;
use common\models\Notice;
use common\models\Pregnancy;
use common\models\User;
use common\models\UserDoctor;
use common\models\UserDoctorAppoint;
use common\models\UserLogin;
use common\models\UserParent;
use common\models\Vaccine;
use common\models\WeOpenid;
use EasyWeChat\Factory;
use Faker\Provider\File;
use saviorlv\aliyun\Sms;
use yii\base\Controller;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


class DataController extends Controller
{

    public function actionTesta()
    {

        $we=WeOpenid::find()->select('openid')->where(['>','createtime',1573088400])->column();
        foreach ($we as $k=>$v) {
            $config = [
                'app_id' => 'wx1147c2e491dfdf1d',
                'secret' => '98001ba41e010dea2861f3e0d95cbb15',
                // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
                'response_type' => 'array',
                //...
            ];

            $app = Factory::officialAccount($config);
            $app->customer_service->message("各位家长好，如果您在社区医院遇到的体检查看问题，疫苗预约、体检预约、健康指导等问题可随时联系儿宝小助手哦！！！微信号：erbbzs")->to($v)->send();
        }

//        $str='13691315689,13911049678,15810653314,13811731518,18600569888,15811148015,15001286298,13581751658,17710508897,13141195505,13552039512,15001187937,18811552970,15010214168,13671349631,13121094666,18911609412,13811896610,13601189989,13426351272,18515962649,13810289491,13584392805,15011425756,13621260920,13810185099,15811234021,13671213711,13426323309,13699210673,15801387053,13466519112,18301001520,13671260818,13311198924,13311527957,15810325266,18519869332,13693580100,18701561362,15901553775,13693061993,13693393533,13426134319,13911234607,13810032815,18855336853,13718207560,13691044381,13701203340,13810331088,15629039507,13141455652,13301143110,18211081430,13488765789,13811099360,13718700047,13581929786,13717795955,18600858695,13691589758,18001208028,18210164511,18500298897,15210464096,13651259006,15510286195,13521378023,18501038068,13811852436,18601095281,15810025376,13703227036,18310063144,15910659005,13811903701,13520870285,18500643335,18811074451,15010276048,13552366161,18910252335,13436380784,13161029976,13911444363,13121313055,15210005486,13321151015,13241547972,18911965596,15810009154,13718506439,13661218345,13810635733,18611557774,18610182723,18600770226,15810001366,15901086764,13301220329,13910924852,13261596510,15311200992,18618281657,13141052742,13701328992,13671239421,13581850010,13701035801,13581605717,13220166081,18612865729,13683145576,13716508611,13683543380,17710281233,13691408423,13641029964,18201606865,13401155269,15313007130,18201160219,18501282670,13681044005,18801054966,18612520578,13811295880,15210491264,18210837433,13810456811,15911098180,18210113545,18611642493,15901310726,13581814166,18610527883,1380895041,15801590294,15811597146,15011144637,13554061618,15810292806,13718405696,13681598755,13691201810,13439568893,13911577249,13520328524,15010983877,15101092195,13910344342,18618469785,18201067112,15101139440,15810154560,13331171981,13910095467,18600458353,18911447815,15101172977,18810041104,18810828109,18810096742,18911686555,13811712162,13718880752,18610168185,13601130435,18612445868,15711176836,15001280476,13488811019,13810014037,13522812717,13910207420,18910195564,13910713072,18701423296,15010878762,13693668675,17777827280,17746535583,15810533197,15300180178,13436953493,15600027270,13488706733,13811774600,13661242062,13501174677,18301301411,15201362952,15811026538,13810477909,15910660422,13520661032,13810871983,13501127734,13681400616,18610730052,18813130353,18365032083,13718088377,13911672599,18611023852,15811528623,15910747456,15901318381,15101153093,18610814115,13581991772,15201180239,13552613233,13693615735,18010282722,13521722803,18618268322,13911572426,15810176039,13311424131,15300061997,13910019401,13683328841,15001130313,18600172618,15811022178,15652598455,13811561365,13911246300,18310315273,13911537508,13426490087,18513856328,15010188821,18910289002,13552578046,13811200221,15210116279,18911132002,15201498299,13488868060,18501091096,15101101950,13810996026,13426189816,18511339123,18511698775,15801451827,13810312812,13683673480,18730050315,18801027562,18611365285,18201002855,15011008771,13366006835,13141103933,13488738262,18910652395,18210227338,13581939961,18500182660,13810901263,18510086015,18810848209,13521008829,18701010986,13911127680,18510826627,13552808760,18811038002,15810281427,18310865301,13716404202,18211170229,13611262934,15810561341,13501291852,13240825211,15010943861,13141336629,13520673660,13552592888,17600890750,15010336651,13810986041,13811198582,15010492157,13552621168,18911905185,13466416803,13121802362,13522173022,15010900091,13810509636,13911801982,13141216030,13718985556,18500193186,18910354156,13041026957,18310208919,18518167245,13315218001,18601207107,13811425521,15010522277,15811205852,13301033485,18210508276,18618268415,15810115601,13716313572,18401299357,15901116142,15311553089,13621262801,15699853195,13611068018,13552949538,18511020526,13811269840,18610808257,15010307572,18001102258,15810106125,18310714720,13581756507,13501227168,13810508638,13011105112,18500034861,13621139242,13426339254,13693158414,18600848748,15210816480,13311031219,18655679465,15001109991,17746535630,13522873102,13552485395,18511694158,13470365346,13691057487,18810004326,13146256688,13811223231,15810500917,18630693800,18610525186,18500623365,15110187861,18701479536,13811323436,15810909084,13522308923,18811424955,17778033729,13522321863,15810911633,13671161879,13801009196,18518396378,13810755936,15001011494,15010327673,13552986921,13910090894,13771745937,18911350805,13701288909,13264598583,13810625580,13520942913,13717501234,18601029206,18210219069,13401157491,13693288561,18810063375,15011003598,18610077745,13910380094,18601166521,13240410109,15801596420,13521738846,15910407390,13810099599,13260037020,18515339900,18611292098,18810825907,13426160428,13810085488,18611129436,13693052834,13611203083,15901078863,15210475883,13611202400,18566084040,13811250769,18610698499,13520298919,18612376655,15811466154,13521686188,13301178796,13810839667,13810814784,15201521108,15565545099,18519859848,15910508926,13810088696,13910845221,15201620884,13522602987,15110180947,15801081667,13691347200,18910981738,15210114825,18810998011,13810316104,15201269678,18518309015,18810756680,17718431097,13641355851,13260077882,13520656698,13661218788,18311391462,13311586278,15210856918,15811182440,13641232887,18611249066,13911668765,18811420074,15313176027,13811512268,13683148519,13167533529,13572988536,13810673731,13718630912,15001390868,18336718359,13641390851,13699100059,15731831271,13601096150,13911509266,18201528021,15810177560,13522191898,15110271366,13811411799,15810640583,13621214306,15724744225,15901566252,13520103099,13811619291,15811053201,13810816933,13810156376,13581951826,13811615129,18710187915,18801225097,13699297391,13552277674,15901249524,13810584342,13522846845,13581617514,18500079070,18618290767,13681182373,17710210923,18519667428,13910240092,13693146080,13466326930,13911516689,13810044835,15901529616,17888826096,17310072046,13501186599,13439415461,18701434863,18910590908,17858836237,15175307896,18610813030,15810774186,15910782839,13552285251,17600223754,15031608360,13718829952,18611346449,18614069636,15910648612,15601165657,13126601820,15811086590,15313164922,13260015334,15001207021,13121762526,15210363699,18801168623,13716434350,13581631695,18610838589,15301381700,13720083020,18611813995,13691100106,18210620866,13269460598,13520719596,13901248207,18600709685,13141030602,13436865596,15210429852,13718851516,13810255850,18513522317,18519693031,15210037996,13311443033,13121842856,18710040988,13165513457,13901110531,17710136502,13810773757,15811588733,13269691324,18911771995,15810229885,13522182929,18611676009,18732248722,18710274865,18210971319,13691564275,13146596710,13693258893,18611773685,13510537495,18618443870,18614099319,13801242861,18310976998,15910723625,13261537851,15001271273,13936426485,13811203830,17600854688,13070130302,13552687014,18518309011,15210301720,13521217401,13810230976,15801505380,13718736564,13611015505,13611002331,15811280612,135220884636,13683293938,13810309351,18701042620,15311554830,13811804082,18710016070,15810639656,13141230087,13031154234,18514593876,15110151135,13811363187,13691537193,13811318037,15910694328,13466753207,18701684042,18301335563,13426289730,13021008251,13621276103,15001371806,18518483266,13810100522,15968759526,15010178568,17501083255,15810561891,13811023199,13718732834,15000664205,13581687225,13260218588,13701206539,15726601187,18301287743,13811436933,13601227919,15210483630,13718505281,13366242505,18500387721,13693687714,17701277630,13716606687,18645247861,13811111071,13522697806,13235970000,15300340190,13716334601,18301230299,13269464193,13811404531,13901018804,13581762325,18210121755,15810427082,18611645055,13811998896,13910822704,13810556070,17701152990,13718199720,15210941543,13801080152,15811142120,15101619123,18501286838,13718576834,13020058044,13311484280,15011500405,13811018667,18201371742,13466603713,15210551151,13910892667,13466522024,17310865596,13520268109,18600199920,18910771076,18210906608,15031218101,13910868672,18710063856,18911782964,13439717168,17310076816,18810599897,13146653816,13121319940,13521398795,13146564537,13121779807,18514282058,13911807275,15010095133,17758883499,15222057053,17300171162,13011275579,18610162327,18618101310,18810265089,18801023520,13501237121,13910497279,18800170342,13811998253,13522106393,13439821814,13651225659,15811089160,15911083827,15801541412,15010329198,18710012923,15801324014,18331399354,13810771187,13522870913,18001361325,13681015764,17710171237,15210840863,18743029033,13581788596,15510773660,13512952801,18210503110,15650765533,18810779590,13522184069,18810205998,15201151819,13693250233,13261883050,13241435473,13681248779,18611732382,18910792899,13811310680,18600608441,18612758823,15001058320,15259989871,15732223674,18611038754,18701614481,13910918761,18515981618,18500335182,18311097790,13120021087,13811575661,13718419205,13718700360,18515002997,13810322486,15801599075,13716020197,15910682967,15606905709,15995880972,13511017966,13720080331,18611369263,13663513203,18701547355,13301073812,18555872125,17710208209,18618342144,18611118905,13552731256,13522318063,13683361020,13910229962,15176762568,13621117610,13511019533,18600819967,15210285093,15911151549,18811596122,13839761049,15001287111,13683368983,13466373878,18513669909,13691582837,15110187420,18513763768,18601274947,18500061178,18813018484,13301337167,15010144781,17708526790,18611468153,18510625531,13070122259,13581982851,15737686432,13601306959,15910861054,18610035569,13663220079,13522919880,15010333815,17611477758,17601621626,13581907077,18813153625,13522200159,13911007379,13146177888,13811905518,13522649875,13426408309,13520821439,15811385243,15801388657,18201420840,18513919499,18800198923,15501119444,13810365987,16501208839,18800001169,13146595743,13521386007,13120082927,15010385638,18610757497,13911934770,18911336166,15011061989,18501208420,15510085122,13811529055,15011284705,18515122982,13466754388,15811310528,15510585812,18810649802,152101584863,13311228765,13910130726,18612796165,18610516123,13661361858,13488853481,13581777031,18910615765,18610043420,15010945359,17710291590,15810512358,13381273023,13176207986,17689382706,13581881569,18301039819,15801233612,18911644615,13671233451,13811154449,17718344256,18800106974,13718686143,17600105170,18612931572,15801022987,18810426285,15611731831,18201067611,18613822558,15010012703,15652199024,13811693373,17600633344,13681275310,13439396869,18822858728,13521602468,13581737800,13552929525,13611156776,18810896840,13161707777,18610897917,13811520601,18310526903,13621335124,18301156907,15620988017,15103699714,13051793798,15313246975,13521421090,18210181676,13240146887,13611145363,13381477455,13552538553,18600169258,15801056613,15911059731,18911764215,13121970007,18010137758,15810157059,13717616635,15011128009,18911992437,18201156539,18210776267,18610096261,18510628180,18804598209,15811365152,18701511035,15652542751,15910846771,15611803262,18500192383,18513888937,15811176210,18500253340,13552373520,18630184035,18500527666,13261155553,13811245376,15810058483,13699162306,13911887367,13671015337,18611451237,13911703639,13488687835,18811150736,13031127275,13621258808,18210342572,18201017264,15810620342,15201155369,13511027792,13366392370,13716707927,13581755767,13651001643,15210390668,18201671539,18201552639,18515850286,13716601808,13716187586,13321114577,13811023367,18501396606,189910789941,13520504059,13681085020,18301098722,18301617572,13810906977,136077102762,13691130177,13331192969,13552823844,16639027936,13718984042,13716423392,13716446463,17611188065,13436863252,15321982812,18611692098,13371778015,13718823801,18610527208,18310300128,13051204132,13331055670,18601040928,13395439446,18811137362,13120207283,15001381063,15801612605,18210164533,13810318650,17701092047,13717653372,18801034659,13438363951,13520550018,18335260771,15501268312,15011429085,13120049000,15810917552,15201228119,13811802036,15811328977,15901458071,13466351608,18681337660,15355029616,15801127325,18612824485,18801416067,18911724955,18500473270,18518553380,13810219419,13810718537,18612310280,13601281625,13121880776,18600500925,15810973637,13811809228,18710159674,15851450481,13601258239,13810272279,18911132198,18810262986,13301080042,18871197077,13811233534,13911112135,15510300379,18515991904,18911266086,17600803132,15010703091,13683352015,13001011855,13683500726,15201369742,15955385025,15120053383,13121205851,15810759925,13070139776,13071188882,13718821417,13426385595,13161566592,18601195969,13810573634,18911412317,15801057519,13366355889,13520934689,13701168432,15811273712,18601206436,13716315591,18710208058,18813129036,13810162525,15711439295,13439373298,18502090016,15901116809,15201113090,15901019890,18801100521,18613899624,18665518028,13611359037,18001107396,18515690089,13021272629,15210318327,18601078817,13910157932,13521397717,15110088613,13621361679,13716521193,15210386350,13311095289,18612983961,18501372919,18610857288,18510602550,13641101260,18511334613,13581857766,13671168208,13552945363,13671022080,13910014774,18810147267,18910031226,13911570446,15810323738,15811317869,15810030461,18710022518,15810712495,13810427826,13601394339,13513439209,13718725857,18298434508,13522200453,13146482120,18701243112,18600504216,15910844381,13683133549,13621084499,13466551662,13581589695,15880846766,13439800380,15113666038,15910675784,15611628700,18511021369,13801370272,13126791369,13426431927,18519240421,13436648200,13718491590,17310215307,15201011751,15901002152,15010105700,13051404375,15901238098,13717588890,13811858638,15811452451,18810321127,15201579487,18610242820,13439831885,13810097394,13810848241,18691251226,13522190798,15120071790,17639628005,15901105802,18701661968,15842352673,13681089880,13001073285,13810159608,13552605258,13011882121,15001306140,13901258749,13811460469,13621205140,18310756030,18911922533,18801115865,13466392716,13261054425,13718479850,17600602130,18600486886,13910282334,18522516692,18958520835,13811396717,13581935905,17600810725,15801234356,13811667444,18401586891,15001044950,13611065604,15653096935,15010708307,18310087841,15210219915,18500729798,15201669217,15710058680,15601301863,15210209086,15901536522,13522526491,18701460654,15001366965,15010996928,18811591682,13321101456,15011442451,18001167819,18771718599,13581657011,,13720019878,15043119378,17395035613,15172177317,13810449922,15313263346,15210528810,13811392915,13717739221,13522896169,18514026281,18611898342,13910528572,18701533321,13810088523,15712959699,18911997033,18501258051,13691258888,13311266189,17718310204,15010544082,13341050304,17701309866,15110071683,13693628576,18701085668,18698577219,13701190114,13699203994,13810551922,15001316998,18518308238,15810169296,15801539657,13501235812,18553523265,15210601913,15801342745,13911631646,15010119731,13287251188,15811425158,13522919016,15311819181,18810438735,18601171967,13522152988,15201152081,13501820969,15110105546,15201524688,13801269659,13699123795,18518470101,15811167303,18801163343,18611058309,18810439823,13601197597,13811354403,13426373026,15210896951,1850026937,15201019959,18515699449,13911455924,18801067809,15011560352,13126553887,13716670178,15010079056,18600263318,13810661311,15864065511,13520263814,13910421646,15910661786,13810437577,13693368889,13810416406,18210750612,18031271110,18612825263,13261907588,15110011599,18710139613,18643399877';
//        $array=explode(',',$str);
//        $array=array_filter($array);
//        //echo count($array);exit;
//        $userid=UserParent::find()->select('userid')->where(['in','mother_phone',$array])->column();
//        //echo $userid->createCommand()->getRawSql();exit;
//        $openid=DoctorParent::find()->where(['in','parentid',$userid])->andWhere(['level'=>1])->count();
//
//        var_dump($openid);exit;
//        $query = ChildInfo::find()->select('userid');
//        $parentids = \common\models\DoctorParent::find()->select('parentid')->andFilterWhere(['`doctor_parent`.`doctorid`' => 113890])->andFilterWhere(['level' => 1])->column();
//        $query->andFilterWhere(['>', '`child_info`.birthday', strtotime("-3 year -1 day")]);
//        $query->andFilterWhere(['not in', '`child_info`.userid', $parentids]);
//        $query->andFilterWhere(['`child_info`.`admin`' => 110580]);
//        $userids = $query->column();
//
//        $openid=DoctorParent::find()->where(['in','parentid',$userids])->andWhere(['level'=>1])->count();
//        echo $openid;echo "\n";
//        exit;
//
////
////        $parent = UserParent::find()->select('mother_phone')->where(['in', 'userid', $userids])->column();
////        $parents = array_chunk($parent, 100);
////        foreach ($parents as $k => $v) {
////            $str = implode(',', $v);
////            $response = \Yii::$app->aliyun->sendSms(
////                "儿宝宝", // 短信签名
////                'SMS_176928692', // 短信模板编号
////                $str
////            );
////            $response = json_decode($response, true);
////            echo $str.$response."\n";
////        }
////
////        $response = \Yii::$app->aliyun->sendSms(
////            "儿宝宝", // 短信签名
////            'SMS_176928692', // 短信模板编号
////            $strUserid
////        );
////        $response=json_decode($response,true);
////        var_dump($response);exit;
//
//
////        $response = \Yii::$app->aliyun->sendBatchSms(
////            "儿宝宝", // 短信签名
////            "SMS_176928692", // 短信模板编号
////            [15811078604] // 短信接收者
////        );
////        $response=json_decode($response,true);
////        var_dump($response);exit;
////        return $response['code']==200?true:false;
//
//
//        $data = [
//            'first' => array('value' => "\n预签约已成功，点击完成正式签约"),
//            'keyword1' => ARRAY('value' => "儿宝宝社区卫生服务中心",),
//            'keyword2' => ARRAY('value' => "儿宝宝社区卫生服务中心"),
//            'keyword3' => ARRAY('value' => date('Y年m月d日')),
//            'remark' => ARRAY('value' => "请务必点击此信息授权进入，如果宝宝信息自动显示，就可以免费享受个性化儿童中医药健康指导服务啦，如果不显示，请正常添加宝宝信息即可", 'color' => '#221d95'),
//        ];
//        $url = \Yii::$app->params['site_url'] . "#/add-docter";
//        WechatSendTmp::send($data, "o5ODa0451fMb_sJ1D1T4YhYXDOcg", \Yii::$app->params['chenggong'], $url, ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
//        exit;
//
//        $doctors = UserDoctor::find()->where([])->all();
//        foreach ($doctors as $k => $v) {
//            $data = DataUpdateRecord::find()
//                ->where(['hospitalid' => $v->hospitalid])
//                ->andWhere(['type' => 1])
//                ->andWhere(['state' => 3])
//                ->orderBy('id desc')->one();
//            $data1 = DataUpdateRecord::find()
//                ->where(['hospitalid' => $v->hospitalid])
//                ->andWhere(['type' => 2])
//                ->andWhere(['state' => 3])
//                ->orderBy('id desc')->one();
//            $data2 = DataUpdateRecord::find()
//                ->where(['hospitalid' => $v->hospitalid])
//                ->andWhere(['type' => 3])
//                ->andWhere(['state' => 3])
//                ->orderBy('id desc')->one();
//
//            echo $v->name . ",";
//            echo date('Y-m-d', $data->createtime);
//            echo "," . date('Y-m-d', $data1->createtime);
//            echo "," . date('Y-m-d', $data2->createtime);
//            echo "\n";
//        }


    }

    public function actionTests()
    {
        $doctorParent = DoctorParent::find()->where(['doctorid' => 113890])->andWhere(['level' => 1])->all();
        foreach ($doctorParent as $k => $v) {
//            $child=ChildInfo::find()
//                ->where(['userid'=>$v->parentid])
//                ->andFilterWhere(['>', '`child_info`.birthday', strtotime('-6 year')])
//                ->andWhere(['>', 'child_info.doctorid', 0])
//                ->all();
            $child = Pregnancy::find()->where(['familyid' => $v->parentid])->andWhere(['field49' => 0])->all();
            if ($child) {
                $rs = [];
                //$parent=UserParent::findOne(['userid'=>$v->parentid]);
                foreach ($child as $ck => $cv) {
                    $rs[] = '11010538';
                    $rs[] = '11010538';
                    $rs[] = 'XHMWN';
                    $rs[] = $cv->field1;
                    $rs[] = date("Y-m-d", $cv->field2);
                    $rs[] = $cv->field6;
                    $rs[] = $cv->field4;
                    $rs[] = "1";
                    $rs[] = "0";
                    $rs[] = "0";
                    $rs[] = date("Y-m-d", $v->createtime);
                    $rs[] = "0";
                    $rs[] = "0538";
                    $rs[] = "北京市朝阳区小红门社区卫生服务中心";
                    $rs[] = "XHMWN";
                    $rs[] = "2019/9/19";
                    echo implode(',', $rs);
                    $rs = [];
                    echo "\n";
                }
            }
        }
    }

    public function actionAppoint()
    {
        $userAppoint = UserDoctorAppoint::find()->all();
        foreach ($userAppoint as $k => $v) {
            $a = $v->toArray();
            print_r($a);
            $weeks = str_split((string)$v->weeks);
            $hospitalAppoint = HospitalAppoint::findOne(['doctorid' => $v->doctorid, 'type' => $v->type]);
            $hospitalAppoint = $hospitalAppoint ? $hospitalAppoint : new HospitalAppoint();
            $hospitalAppoint->doctorid = $v->doctorid;
            $hospitalAppoint->cycle = $v->cycle;
            $hospitalAppoint->delay = $v->delay;
            $hospitalAppoint->type = $v->type;
            $hospitalAppoint->week = $weeks;
            $hospitalAppoint->save();
            $id = $hospitalAppoint->id;
            $row = [];
            foreach ($weeks as $wk => $wv) {
                for ($i = 1; $i <= 6; $i++) {
                    $rs = [];
                    $rs[] = $wv;
                    $rs[] = $i;
                    $name = 'type' . $i . "_num";
                    $rs[] = $a[$name];
                    $rs[] = $id;
                    $row[] = $rs;
                }
            }
            \Yii::$app->db->createCommand()->batchInsert(HospitalAppointWeek::tableName(), ['week', 'time_type', 'num', 'haid'],
                $row
            )->execute();
        }
    }

    public function actionPush()
    {
        $phones = [
            18201599388,
            15810423758,
            13488789894,
            13581573919,
            17701133177,
            18810873734,
            17812003996,
            13970138080,
            15711103486,
            18612550502,
            18610327998,
            13935898710,
            18911836010,
            18612003631,
            15010344309,
            15321797996,
            18801150325,
            13240287336,
            13381026325,
            18611619771,
            18910709931,
            17610798331,
            18911820229,
            13810366926,
            15811078604,
        ];

        foreach ($phones as $k => $v) {
            $response = \Yii::$app->aliyun->sendSms(
                "儿宝宝", // 短信签名
                "SMS_157275701", // 短信模板编号
                $v, // 短信接收者
                Array(  // 短信模板中字段的值
                    "title" => "早产宝宝该如何护理和喂养",
                    "datetime" => "2019年1月26日 14:00",
                    "hospital" => "美中宜和妇儿医院",
                    "address" => "北京市朝阳区安慧北里逸园5号楼",
                )
            );
            $response = json_decode($response, true);
            var_dump($response);
        }
        exit;

//        $doctorParent=DoctorParent::find()->select('parentid')->where(['doctorid'=>4135])->column();
//        $preg=Pregnancy::find()->select('field1')->where(['in','familyid',$doctorParent])->andWhere(['source'=>0])->column();
//        var_dump($preg);exit;
//        $doctorParent = DoctorParent::find()->where(['doctorid'=>0])->all();
//        foreach($doctorParent as $k=>$v){
//
//            $child=ChildInfo::findOne(['userid'=>$v->parentid]);
//            if($child && $child->source)
//            {
//                $child->doctorid=$child->source;
//                $child->save();
//                $doctorid=UserDoctor::findOne(['hospitalid'=>$child->source])->userid;
//                $v->doctorid=$doctorid;
//                $v->save();
//            }
//
//        }
        //exit;


        $childs = ChildInfo::find()->select(UserLogin::tableName() . ".openid")
            ->andWhere([ChildInfo::tableName() . '.field42' => 37])
            ->andWhere(['>', ChildInfo::tableName() . '.birthday', 1485878400])
            ->leftJoin(UserLogin::tableName(), UserLogin::tableName() . ".userid=" . ChildInfo::tableName() . ".userid")
            ->andWhere(['!=', UserLogin::tableName() . ".openid", ''])
            ->groupBy(UserLogin::tableName() . ".openid")
            ->column();
        //var_dump($childs);exit;
        $openids = ["o5ODa0--aywXYjum-UJl3gjZWbmo",
            "o5ODa0-02li3y9dj8SyeUp3cSFF8",
            "o5ODa0-0ImAzRWN1S47X8SjWYLHQ",
            "o5ODa0-2vWpkGm5kSUR5DnF-aPoE",
            "o5ODa0-3MD67GZ1oEE_Lq1dDn108",
            "o5ODa0-4BH897lFFWXxF3cuCjHIA",
            "o5ODa0-4ecwpN1PDq_f8znIGzl6M",
            "o5ODa0-5-EznjYvsPs8NMrY1f6AI",
            "o5ODa0-6IHYD5VNw1V9Z1Z-TUNPQ",
            "o5ODa0-6wD1x1RQxafBvg-WcIO4o",
            "o5ODa0-7gKErEFk5CpdDMnaiWYK4",
            "o5ODa0-AEnmla9QNqVhxHbpZEjIU",
            "o5ODa0-AocCvrPKsokV4cj9RhTsM",
            "o5ODa0-b7t0DwLM-YXY5ktIQBii0",
            "o5ODa0-bQPN-QJObsB6LvDR0cCj8",
            "o5ODa0-bq_K9q-wOK8yZSnBYit5A",
            "o5ODa0-BrGqGIBQ65nUPUvXtIWU0",
            "o5ODa0-d_cOB3dpSgX314q4KWSvA",
            "o5ODa0-f9dhFWMQozaJtmLICAfNI",
            "o5ODa0-FVpjzg7K7P-RFJ0FtDlf8",
            "o5ODa0-fZ-CxZOaZ7tIUh7z9OoNU",
            "o5ODa0-gnMJ9RU-XNG-ctReRyRus",
            "o5ODa0-GzPRfJl27-_aidG7BdEHI",
            "o5ODa0-H9FtiVwFuSP1VgmGDL6ic",
            "o5ODa0-HlJAdHupG_WbrzStwSRrw",
            "o5ODa0-IL7Ss2DZmVKNAnHIjK6qc",
            "o5ODa0-ILgeYHghN87QgQcC7AUo0",
            "o5ODa0-Is2yDOauZfKP8xiItsyZo",
            "o5ODa0-J8kYIdOYqPSkGhA2KhTig",
            "o5ODa0-jAwtAzIUUn8YbZ37WB0UY",
            "o5ODa0-Jcl-7t6S-0T7m4jhoco8M",
            "o5ODa0-jI105LmM6jthcslZh7lTc",
            "o5ODa0-k1rHl8mjPcVvzYh8LRRss",
            "o5ODa0-KMqIzB3y-B8Y3YhptAUno",
            "o5ODa0-KvEQumyzOJIbq56zoMAJw",
            "o5ODa0-lqTLOIXNxiYR8Pkn1f_5o",
            "o5ODa0-lZB_CFzFPRdpnVXVXVYA4",
            "o5ODa0-MuFu7CFCvGsGSJukpqr7o",
            "o5ODa0-N8VdKRq2faFfAgrco7aZ4",
            "o5ODa0-Nc9qSofborETNeoLHYJGE",
            "o5ODa0-NVLJYLfEGgud900yBSYdU",
            "o5ODa0-O8X0mIIYuWybQF0vE1bmU",
            "o5ODa0-oqqkT61WGA3MxAFxIwmHc",
            "o5ODa0-pXs7E5oWsUezd5Y4F8yy0",
            "o5ODa0-Q-G0l_W7Y_sHArWoIk_0M",
            "o5ODa0-Q5eT2_MZMy5zp-xkqd7sI",
            "o5ODa0-qbSr7GUpIbnBv5JYol_5A",
            "o5ODa0-QBWfQo1pYks_jxpLtnSOo",
            "o5ODa0-QPO84MiH17hGQ8mGl_W3g",
            "o5ODa0-RIZJ1hG0k8Xch5lqRRam0",
            "o5ODa0-rlIVnjheYEoWXW9_5viq8",
            "o5ODa0-r_CbdPWLNdPCsGdPcD_J8",
            "o5ODa0-S-VWLraJ0jMwHx86jyapc",
            "o5ODa0-SgGZFsAA4jYYoxgH4gAPQ",
            "o5ODa0-sMuWFIXuIiD5HNRfVMYBg",
            "o5ODa0-sQ0zUqcZOADvbn-zgy2TM",
            "o5ODa0-sSYgfG_4REufHiCIKCeds",
            "o5ODa0-sue7lrP_GjfzMcVJXO528",
            "o5ODa0-svcRgo-5OLQTuUFku12s0",
            "o5ODa0-TIxUQhqTft6XLnQC5RtEU",
            "o5ODa0-tk1DDP-WHUI_UEL-7Oic0",
            "o5ODa0-v3jsW63Ca7Q3UtCoZVbHQ",
            "o5ODa0-vKvMsOScqDfRPsWPz7JLg",
            "o5ODa0-vy8mkpmkwHnzomVK7tkaA",
            "o5ODa0-vztmIx9HjwP5JvNi4D9GU",
            "o5ODa0-WQ0WNg49xCm9l2ovStO-I",
            "o5ODa0-WVq1WSafEePos1yfKFgcM",
            "o5ODa0-Y0RZ5XR8SB1xICnaCgX8g",
            "o5ODa0-YDtj9OTvyYvz9SWgJ5kws",
            "o5ODa0-zpHn46q6uuwRumq9UMIzo",
            "o5ODa0-ZtUkeutKUjIfIQdTzDc7M",
            "o5ODa0-_-8lo7lk5c_zRGsKJtkdw",
            "o5ODa0-_Gd_-rsottz7CqmazAinU",
            "o5ODa00-6jL6m5xanUMX_F_rN-cI",
            "o5ODa00-ijvNGQNuKAg_sxcWpwDQ",
            "o5ODa00-jVB8n23Vva5B5FKl35-A",
            "o5ODa000BukDVD2dvz9q6kEF2I9o",
            "o5ODa000Ea4L-g3WAEKUj2dnatb8",
            "o5ODa000lKEBhNmSmvzG-pGWsFx0",
            "o5ODa001NPfICkDBM66M83zlzaEk",
            "o5ODa002EwxPcGeBdJDZn6lzargY",
            "o5ODa004cZczvurB_KizHuTCsh1I",
            "o5ODa005R7TK0QwUjtsIRag8VtGw",
            "o5ODa005VVhB42Mbi71s2wmW5KmU",
            "o5ODa006Re90x6MJATGGECeXi8ts",
            "o5ODa006u_g94cGLn7PxEjlTioPk",
            "o5ODa008ou3pa5m4yWQTrxQbycXk",
            "o5ODa0091Z6hpLzKLMTgM1zN-AwI",
            "o5ODa009MPsxc7tL4SfqKF4AX4p8",
            "o5ODa00aj7vC4Bv5pnjndzF2zhhw",
            "o5ODa00AoHLPihn-9LZampIwwkBI",
            "o5ODa00b6B_tGr5iaSXpoh-pFY9o",
            "o5ODa00B969sSbXlFOzJ47n48f94",
            "o5ODa00Du77OnR9Qsr6UHDQRZhpc",
            "o5ODa00DwGwl8J4xbUMJ5bDeMjis",
            "o5ODa00EqMeQhViy_L1XgHAID_vg",
            "o5ODa00EwEdMU0KeHLrgy5mPsn4E",
            "o5ODa00f2mURa32gHtoUidfJRQTY",
            "o5ODa00F7gIFLzq5eotHuptXtG1I",
            "o5ODa00FBnjxqE7Tsc_KILdYpSsE",
            "o5ODa00fJXMmQ7YkirtT3lUmKOuE",
            "o5ODa00FYB-J1QA8ksmEsGlSRBaM",
            "o5ODa00gGCtvpG5M8AoELxYhFeIM",
            "o5ODa00gy2WXs1eoyZN_ansWjhGQ",
            "o5ODa00hPmG7-x_ZogRR-4EG_h90",
            "o5ODa00Hqab0glUFKOhNKrXeIxHs",
            "o5ODa00iyxbFRbCBtfjW_2yyj5O8",
            "o5ODa00JhChW4ty6gq0yd_aUdwS4",];
        foreach ($childs as $k => $v) {
            if (!in_array($v, $openids)) {
                $data = [
                    'first' => array('value' => "家长您好，近期有个免费讲座邀请您参加\n",),
                    'keyword1' => ARRAY('value' => "早产宝宝该如何护理和喂养-李瑛主任"),
                    'keyword2' => ARRAY('value' => "2019-01-26 14:00"),
                    'remark' => ARRAY('value' => "地点：美中宜和妇儿医院（朝阳区安慧北里逸园5号楼）名额有限，速速报名"),
                ];
                //var_dump($doctor->name);
                $rs = WechatSendTmp::send($data, $v, 'u1B7beQlAmsvM_1HkW9nVtzv4Yr2CJ_dOx9WzFYCAmI', 'https://jinshuju.net/f/hfZOIr');
                $openids[] = $v;
                echo $v . "" . $rs . "\n";
            } else {
                echo "jump\n";
            }
        }
        exit;
    }

    public function actionDoctorChild()
    {
        $doctorids = [];
        $weopenid = WeOpenid::findAll(['level' => 0]);
        foreach ($weopenid as $k => $v) {
            $doctor = $doctorids[$v->doctorid];
            if (!$doctor) {
                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                $doctorids[$v->doctorid] = $doctor;
            }

            $data = [
                'first' => array('value' => "您好，为确保享受儿童中医药健康指导服务,请完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => $doctor->name),
                'remark' => ARRAY('value' => "\n 点击授权并完善宝宝信息，如果已添加宝宝请忽略此条提醒", 'color' => '#221d95'),
            ];
            //var_dump($doctor->name);
            $rs = WechatSendTmp::send($data, $v->openid, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
            echo $v->openid . "\n";
            usleep(300000);
        }
        exit;

        $doctorParent = DoctorParent::find()->andWhere(['level' => 1])->all();
        foreach ($doctorParent as $k => $v) {
            $child = ChildInfo::findOne(['userid' => $v->parentid]);
            if (!$child) {
                echo $v->parentid . "\n";
            }
        }
    }

    public function actionDeleteMotherFather()
    {

        $file = fopen('test3.log', 'r');
        while (($line = fgets($file)) !== false) {
            $tmp = explode(',', trim($line));
            $this->userLogin($tmp[0], $tmp[1]);
            $this->doctorParent($tmp[0], $tmp[1]);
            $this->articleUser($tmp[0], $tmp[1]);
            $this->child($tmp[0], $tmp[1]);
            echo "==" . $tmp[0] . "," . $tmp[1] . "\n";
            $user = User::findOne($tmp[1]);
            if ($user) {
                $user->delete();
            } else {
                $userParent = UserParent::findOne(['userid' => $tmp[1]]);
                $userParent->delete();
            }
        }
        exit;


        $userParent = UserParent::find()->select('count(*) as c,mother_id,mother,father')
            ->groupBy('mother_id,father')->andWhere(['!=', 'mother', ''])->andWhere(['!=', 'mother_id', ''])
            ->andWhere(['!=', 'father', ''])->andWhere(['!=', 'source', 0])->having('c > 1')->all();
        foreach ($userParent as $k => $v) {
            $tmp = [];
            $master = [];
            $up = UserParent::find()->where(['mother' => $v->mother])->andWhere(['father' => $v->father])->orderBy('userid desc')->all();
            foreach ($up as $uk => $uv) {
                $tmp[] = array_filter($uv->toArray(), function ($v) {
                    return $v ? true : false;
                });
                if (!$uk) {
                    $master = $tmp[$uk];
                } else {
                    if (count($tmp[$uk]) > count($tmp[$uk - 1])) {
                        $master = $tmp[$uk];
                    }
                }
            }
            foreach ($tmp as $tk => $tv) {
                if ($master['userid'] != $tv['userid']) {
                    $this->userLogin($master['userid'], $tv['userid']);
                    $this->doctorParent($master['userid'], $tv['userid']);
                    $this->articleUser($master['userid'], $tv['userid']);
                    $this->child($master['userid'], $tv['userid']);
                    echo "==" . $master['userid'] . "," . $tv['userid'] . "\n";
                    $user = User::findOne($tv['userid']);
                    if ($user) {
                        $user->delete();
                    } else {
                        $userParent = UserParent::findOne(['userid' => $tv['userid']]);
                        $userParent->delete();
                    }
                }
            }
        }
    }

    public function actionDatac()
    {

        $dataUser = DataUser::find()->all();
        foreach ($dataUser as $k => $v) {
            $v->token = md5($v->id . "wwdsa");
            $v->save();

        }
        exit;


        var_dump(__LOG__);
        exit;
        $babyTag = BabyToolTag::find()->all();
        foreach ($babyTag as $k => $v) {
            echo $v->id . $v->name . "\n";
            BabyGuide::updateAll(['period' => $v->id], 'tag = "' . $v->name . '"');
        }
        exit;
        $file = fopen('data.txt', 'r');
        $j = 1;
        while (($line = fgets($file)) !== false) {
            $i = 1;
            if ($j > 124) break;
            $j++;
            $rs1 = explode('||', trim($line));
            if ($rs1[1]) {
                $babyGuide = new BabyGuide();
                $babyGuide->sort = $i;
                $babyGuide->title = '孕期注意事项';
                $babyGuide->content = strip_tags($rs1[1]);
                $babyGuide->tag = $rs1[0];
                $babyGuide->save();
                $i++;
            }
            $rs2 = explode('|=|', $rs1[2]);
            $rs3 = explode('|=|', $rs1[3]);
            foreach ($rs2 as $k => $v) {
                $babyGuide = new BabyGuide();
                $babyGuide->sort = $i;
                $babyGuide->title = $v;
                $babyGuide->content = html_entity_decode(strip_tags($rs3[$k]));
                $babyGuide->tag = $rs1[0];

                $babyGuide->save();
                $i++;
            }
        }
    }


    public function userLogin($userid, $buserid)
    {
        //登录
        $userLogin = UserLogin::findAll(['userid' => $buserid]);
        foreach ($userLogin as $ulv) {
            echo "id:" . $ulv->id . "==";
            if ($ulv->phone || $ulv->openid || $ulv->xopenid || $ulv->unionid) {
                $or = ['or'];
                if ($ulv->phone) {
                    $or[] = ['phone' => $ulv->phone];
                }
                if ($ulv->openid) {
                    $or[] = ['openid' => $ulv->openid];
                }
                if ($ulv->xopenid) {
                    $or[] = ['xopenid' => $ulv->xopenid];
                }
                if ($ulv->unionid) {
                    $or[] = ['unionid' => $ulv->unionid];
                }

                $ul = UserLogin::find()
                    ->andFilterWhere(["userid" => $userid])
                    ->andFilterWhere($or)->one();
                if (!$ul) {
                    $ul = new UserLogin();
                    $ul->userid = $userid;
                    if ($ulv->password) $ul->password = $ulv->password;
                    if ($ulv->openid) $ul->openid = $ulv->openid;
                    if ($ulv->logintime) $ul->logintime = $ulv->logintime;
                    if ($ulv->xopenid) $ul->xopenid = $ulv->xopenid;
                    if ($ulv->unionid) $ul->unionid = $ulv->unionid;
                    if ($ulv->hxusername) $ul->hxusername = $ulv->hxusername;
                    if ($ulv->phone) $ul->phone = $ulv->phone;
                    if ($ulv->createtime) $ul->createtime = $ulv->createtime;
                    $ul->save();
                    echo "save==" . $ul->id;
                }

            }

        }
        ArticleComment::updateAll(['userid' => $userid], "userid=" . $buserid);
    }

    public function loginWeOpenid($bchildid)
    {
        //登录
        $userLogin = UserLogin::findAll(['userid' => $bchildid]);
        foreach ($userLogin as $ulv) {

            if ($ulv->openid || $ulv->xopenid || $ulv->unionid) {
                $weOpenid = WeOpenid::find()->andFilterWhere(['or', ['openid' => $ulv->openid], ['xopenid' => $ulv->xopenid], ['unionid' => $ulv->unionid]])->one();
                if ($weOpenid) {
                    $dp = DoctorParent::findOne(['parentid' => $bchildid]);
                    if (!$dp) {
                        $dp = new DoctorParent();
                    }
                    $dp->doctorid = $weOpenid->doctorid;
                    $dp->parentid = $bchildid;
                    $dp->level = $weOpenid->level;
                    $dp->createtime = $weOpenid->createtime;
                    $dp->save();
                    echo implode(',', $weOpenid->toArray());
                }
            }
        }
    }

    public function doctorParent($userid, $buserid)
    {
        $dp1 = DoctorParent::findOne(['parentid' => $buserid, 'level' => 1]);
        $dp = DoctorParent::findOne(['parentid' => $userid, 'level' => 1]);
        if ($dp) {
            echo "主已签约";
        }
        if (!$dp && $dp1) {
            $dp = new DoctorParent();
            $dp->doctorid = $dp1->doctorid;
            $dp->level = 1;
            $dp->createtime = $dp1->createtime;
            $dp->parentid = $userid;
            $dp->save();
            echo "dp update==";
        }
        if ($dp1) {
            DoctorParent::deleteAll('parentid =' . $buserid);
            echo "dp delete==";
        }
    }

    public function articleUser($userid, $buserid)
    {
        //修改宣教记录所属儿童
        $articleUser = ArticleUser::findAll(['touserid' => $buserid]);
        if ($articleUser) {
            foreach ($articleUser as $av) {
                $av->touserid = $userid;
                $av->save();
                echo "article:" . $av->artid;
            }
        } else {
            echo "article:无";
        }
    }

    public function child($userid, $buserid)
    {
        echo "==child:";
        echo ChildInfo::updateAll(['userid' => $userid], 'userid=' . $buserid);
    }

    /**
     * 清除重复记录
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDataa()
    {
        exit;
        $child = ChildInfo::find()
            ->select('count(*) as c,name,birthday,doctorid')
            // ->andFilterWhere(['doctorid'=>110555])
            ->groupBy('name,birthday,doctorid')->having(['>', 'c', 1])->all();
        foreach ($child as $k => $v) {
            echo "name:" . $v->name . "==";
            $childAll = ChildInfo::find()
                ->andWhere(['name' => $v->name])
                ->andWhere(['birthday' => $v->birthday])
                ->andWhere(['doctorid' => $v->doctorid])
                ->andWhere(['<', 'source', '39'])
                ->all();
            if ($childAll) {

                foreach ($childAll as $cv) {
                    echo "cv->id:" . $cv->id . "==";
                    echo "cv->userid:" . $cv->userid . "==";

                    $oldChild = ChildInfo::find()
                        ->andFilterWhere(['name' => $cv->name])
                        ->andFilterWhere(['birthday' => $cv->birthday])
                        ->andFilterWhere(['source' => $cv->doctorid])
                        ->andFilterWhere(['!=', 'id', $cv->id])
                        ->one();
//                    $oldChild = ChildInfo::find()
//                        ->andFilterWhere(['birthday' => $cv->birthday])
//                        ->andFilterWhere(['userid' => $cv->userid])
//                        ->andFilterWhere(['!=', 'id', $cv->id])
//                        ->one();
                    //var_dump($oldChild);
                    if ($oldChild) {
                        // echo implode(',', $oldChild->toArray()) . "\n";

                        $childid = $oldChild->id;
                        $userid = $oldChild->userid;
                        //$this->articleUser($childid, $userid, $cv->id);
                        // $this->doctorParent($userid, $cv->userid);
                        //$this->loginWeOpenid($cv->userid);
                        //$this->userLogin($userid,$cv->userid);
                        $user = User::findOne($cv->userid);
                        if ($user) {
                            $user->delete();
                        } else {
                            $cv->delete();
                        }

//                        //删除错误数据
//                        $cv->delete();

                    }
                }
            }
            echo "\n";
        }

    }

    /**
     * 清除重复记录
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionData()
    {
        exit;
        $field7 = ChildInfo::find()->select('field7')->andFilterWhere(['like', 'field7', 'E'])->column();
        $child = ChildInfo::find()
            ->select('count(*) as c,field7')
            ->andWhere(["!=", "field7", ""])
            ->andFilterWhere(['not in', 'field7', $field7])
            ->groupBy('field7')->having(['>', 'c', 1])->all();
        foreach ($child as $k => $v) {
            echo "field7:" . $v->field7 . "==";
            $childAll = ChildInfo::find()->andWhere(['field7' => $v->field7])->all();
            if ($childAll) {
                $childid = $childAll[0]->id;
                $userid = $childAll[0]->userid;
                echo "childid:" . $childid . "==";
                echo "userid:" . $userid . "==";

                foreach ($childAll as $ck => $cv) {
                    if ($ck == 0) {
                        continue;
                    }
                    echo "cv->id:" . $cv->id . "==";
                    echo "cv->userid:" . $cv->userid . "==";

                    $user = User::findOne($cv->userid);
                    if ($user) {
                        $user->delete();
                    } else {
                        $cv->delete();
                    }
//                    //登录
//                    $userLogin=UserLogin::findAll(['userid'=>$cv->userid]);
//                    foreach($userLogin as $ulv){
//                        echo "id:".$ulv->id."==";
//                        if($ulv->phone ||$ulv->openid || $ulv->xopenid || $ulv->unionid)
//                        {
//                            $or=['or'];
//                            if($ulv->phone){
//                                $or[]=['phone'=>$ulv->phone];
//                            }
//                            if($ulv->openid){
//                                $or[]=['openid'=>$ulv->openid];
//                            }
//                            if($ulv->xopenid){
//                                $or[]=['xopenid'=>$ulv->xopenid];
//                            }
//                            if($ulv->unionid){
//                                $or[]=['unionid'=>$ulv->unionid];
//                            }
//
//                            $ul=UserLogin::find()
//                                ->andFilterWhere(["userid"=>$userid])
//                                ->andFilterWhere($or)->one();
//                            if(!$ul)
//                            {
//                                $ul=new UserLogin();
//                                $ul->userid          =$userid;
//                                if($ulv->password)  $ul->password   =$ulv->password;
//                                if($ulv->openid)        $ul->openid   =$ulv->openid;
//                                if($ulv->logintime)     $ul->logintime   =$ulv->logintime;
//                                if($ulv->xopenid)       $ul->xopenid   =$ulv->xopenid;
//                                if($ulv->unionid)       $ul->unionid   =$ulv->unionid;
//                                if($ulv->hxusername)    $ul->hxusername   =$ulv->hxusername;
//                                if($ulv->phone)         $ul->phone   =$ulv->phone;
//                                if($ulv->createtime) $ul->createtime   =$ulv->createtime;
//                                $ul->save();
//                                echo "save==".$ul->id;
//                            }
//
//                        }
//
//                    }
//                   ArticleComment::updateAll(['userid' => $userid], "userid=" . $cv->userid);


//                    //登录
//                    $userLogin=UserLogin::findAll(['userid'=>$cv->userid]);
//                    foreach($userLogin as $ulv){
//
//                        if($ulv->openid || $ulv->xopenid || $ulv->unionid)
//                        {
//                            $weOpenid=WeOpenid::find()->andFilterWhere(['or',['openid'=>$ulv->openid],['xopenid'=>$ulv->xopenid],['unionid'=>$ulv->unionid]])->one();
//                            if($weOpenid)
//                            {
//                                $dp=DoctorParent::findOne(['parentid'=>$cv->userid]);
//                                if(!$dp)
//                                {
//                                    $dp=new DoctorParent();
//                                }
//                                $dp->doctorid=$weOpenid->doctorid;
//                                $dp->parentid=$cv->userid;
//                                $dp->level=$weOpenid->level;
//                                $dp->createtime=$weOpenid->createtime;
//                                $dp->save();
//                                var_dump($dp->errors);
//
//                                echo implode(',',$weOpenid->toArray());
//                                echo "\n";
//                            }
//                        }
//                    }


//                    $dp1 = DoctorParent::findOne(['parentid' => $cv->userid, 'level' => 1]);
//                    $dp = DoctorParent::findOne(['parentid' => $userid, 'level' => 1]);
//                    if (!$dp && $dp1) {
//                        $dp=new DoctorParent();
//                        $dp->doctorid = $dp1->doctorid;
//                        $dp->level = 1;
//                        $dp->createtime=$dp1->createtime;
//                        $dp->parentid=$userid;
//                        $dp->save();
//                        echo "dp update==";
//                    } else {
//                        echo "dp delete==";
//                        DoctorParent::deleteAll('parentid =' . $cv->userid);
//                    }
                    //修改宣教记录所属儿童
//                    $articleUser=ArticleUser::findAll(['childid'=>$cv->id]);
//                    if($articleUser){
//                        foreach($articleUser as $av) {
//                            echo "artid1:".$av->id."==";
//                            $au = ArticleUser::find()->andWhere(['childid' => $childid])
//                                ->andFilterWhere(['artid' => $av->artid])->one();
//                            echo "artid2:".$au->id."==";
//                            if (!$au) {
//                                echo "update==";
//                                $av->touserid=$userid;
//                                $av->childid=$childid;
//                                $av->save();
//                            }else{
//                                echo "delete==";
//                                $av->delete();
//                            }
//                        }
//                    }

//                    ArticleUser::updateAll(['childid' => $childid, 'userid' => $userid], 'childid =' . $cv->id);
//                    DoctorParent::updateAll(['parentid' => $userid], 'parentid =' . $cv->userid);
//                    ArticleComment::updateAll(['userid' => $userid], "userid=" . $cv->userid);
//                    UserLogin::updateAll(['userid' => $userid], "userid=" . $cv->userid);
                }
            }
            echo "\n";
        }

    }


    //禁用危险
    public function actionName()
    {
        exit;
        $childInfo = ChildInfo::find()->andFilterWhere(['source' => 0])->andFilterWhere(['id' => 60413])->all();
        foreach ($childInfo as $k => $v) {
            //var_dump($v->toArray());
            $child = ChildInfo::find()
                ->andFilterWhere(['child_info.name' => $v->name])
                ->andWhere(['>', 'child_info.source', 0])
                //->andWhere(['child_info.source'=>$v->doctorid])
                ->andFilterWhere(['child_info.birthday' => $v->birthday])
                ->andFilterWhere(['child_info.gender' => $v->gender])
                ->andFilterWhere(['!=', 'child_info.userid', $v->userid])
                ->one();
            //var_dump($child);exit;
            $doctorP = DoctorParent::findOne(['parentid' => $child->userid]);

            if ($child && $doctorP->level != 1) {
                echo implode(',', $v->toArray());
                echo "\n";
                echo implode(',', $child->toArray());
                echo "\n";
                echo implode(',', User::findOne($v->userid)->toArray());
                echo "\n";
                echo implode(',', User::findOne($v->userid)->toArray());
                echo "\n=======================";


                $vuserid = $v->userid;
                $cuserid = $child->userid;

                var_dump($vuserid);
                var_dump($cuserid);
                $userParent = UserParent::findOne(['userid' => $cuserid]);
                $userParent1 = UserParent::findOne(['userid' => $vuserid]);


                if ($userParent && $userParent1) {
                    $userParent->userid = 0;
                    $userParent->save();

                    $child->userid = $vuserid;
                    $child->save();


                    $userParent1->userid = $cuserid;
                    $userParent1->save();


                    $userParent->userid = $vuserid;
                    $userParent->save();

                    $v->userid = $cuserid;
                    $v->save();
                    echo "====end";
                    exit;
                    echo "\n";
                }
            }
        }
        // echo $childInfo->count();exit;
    }

    public function actionEbb()
    {

        echo date('Y-m-d\TH:i:s\Z', time() + (3600 * 24));
        exit;

        $childs = ChildInfo::find()->andFilterWhere(['doctorid' => 110555])->all();
        foreach ($childs as $k => $v) {
            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid, 'level' => 1]);
            if (!$doctorParent) {
                $v->doctorid = $v->source;
                $v->save();
                echo $v->userid;
                echo "\n";
            }
        }
        exit;
        $doctorParent = DoctorParent::find()->andFilterWhere(['level' => 1])->andFilterWhere(['doctorid' => 47156])->all();

//        foreach ($doctorParent as $k => $v) {
//            $child = ChildInfo::findOne(['userid' => $v->parentid]);
//
//            if ($child) {
//                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
//
//                $child->doctorid = $doctor->hospitalid;
//                $child->save();
//                echo $child->userid;
//                echo "\n";
//            }
//
//        }
//        exit;

        foreach ($doctorParent as $k => $v) {
            echo $v->parentid . "===";
            $userParent = UserParent::findOne(['userid' => $v->parentid]);
            if ($userParent->source > 38) {
                $doctor = UserDoctor::findOne(['hospitalid' => $userParent->source]);
                if ($doctor) {
                    echo $doctor->userid;
                    $v->doctorid = $doctor->userid;
                    $v->save();
                }
            }
            echo "\n";
        }
        exit;
        $user = User::find()->where(['source' => 1])->all();
        foreach ($user as $k => $v) {
            $doctorParent = DoctorParent::find()->andFilterWhere(['parentid' => $v->id])->one();
            if (!$doctorParent or $doctorParent->level != 1) {
                $hospitalid = 110565;
                $doctorid = 47156;
                $userParent = UserParent::findOne(['userid' => $v->id]);
                if ($userParent->source > 38) {
                    $doctor = UserDoctor::findOne(['hospitalid' => $userParent->source]);
                    $doctorid = $doctor ? $doctor->userid : 47156;
                    $hospitalid = $doctor ? $doctor->hospitalid : 110565;
                }

                echo $v->id . "==";
                $doctorParent = DoctorParent::findOne(['parentid' => $v->id]);
                $doctorParent = $doctorParent ? $doctorParent : new DoctorParent();
                $doctorParent->doctorid = $doctorid ? $doctorid : 47156;
                $doctorParent->parentid = $v->id;
                $doctorParent->level = 1;
                echo $doctorParent->doctorid . "==";
                $doctorParent->save();
                echo $hospitalid . "==";

                ChildInfo::updateAll(['doctorid' => $hospitalid], 'userid=' . $v->id);
            }
            echo "\n";
        }
        exit;
    }

    public function actionDoctoridn()
    {
        ini_set('memory_limit', '1024M');
        $doctorParent = DoctorParent::find()->where(['level' => 1])->orderBy('createtime desc')->all();
        foreach ($doctorParent as $k => $v) {
            $userDoctor = UserDoctor::findOne(['userid' => $v->doctorid]);
            if ($userDoctor) {
                $hospital = $userDoctor->hospitalid;
                ChildInfo::updateAll(['doctorid' => $hospital], 'userid=' . $v->parentid);
                echo $v->doctorid . "==";
                echo $v->parentid . "==";
                echo $hospital;
            }
            echo "\n";
        }
    }

    public function actionDoctorid()
    {
        ini_set('memory_limit', '1024M');

        $child = ChildInfo::find()->andFilterWhere(['source' => 110559])->all();
        foreach ($child as $k => $v) {
            echo $v->id . "==";
            echo $v->source;
            $v->doctorid = $v->source;
            $v->save();
            echo "\n";
        }
    }

    public function actionArc()
    {

        $user = User::find()
            ->andFilterWhere(['`user`.source' => 1])
            ->leftJoin('child_info', '`child_info`.`userid` = `user`.`id`')
            ->andWhere(['!=', '`child_info`.`userid`', '']);
        $i = 0;
        foreach ($user->all() as $k => $v) {
            echo $v->id . "==";
            $childInfo = ChildInfo::findOne(['userid' => $v->id]);
            $child = ChildInfo::find()->andFilterWhere(['name' => $childInfo->name])->andFilterWhere(['birthday' => $childInfo->birthday])
                ->andFilterWhere(['gender' => $childInfo->gender])->andFilterWhere(['>', 'source', 38])->one();
            if ($child) {
                echo $child->name;
                $i++;
            }
            echo "\n";
        }
        var_dump($i);
        exit;


    }

    public function actionArea()
    {
        ini_set('memory_limit', '1024M');
        $child = ChildInfo::find()->andFilterWhere(['>', 'source', 38])->all();
        foreach ($child as $k => $v) {
            echo "userid=>" . $v->userid;
            $doctor = UserDoctor::findOne(['hospitalid' => $v->source]);
            if ($doctor) {
                echo ",doctorid=>" . $doctor->userid;

                $userParent = UserParent::findOne(['userid' => $v->userid]);
                if ($userParent) {
                    $userParent->province = $doctor->province;
                    $userParent->city = $doctor->city;
                    $userParent->county = $doctor->county;
                    echo ",county=>" . $userParent->county;
                    $userParent->save();
                }
            }
            echo "\n";
        }
    }

    public function actionUrlPush()
    {
//        $data = [
//            'first' => array('value' => "参与社区儿童中医药健康指导服务调查问卷，必得现金红包，先到先得\n",),
//            'keyword1' => ARRAY('value' => "2018-05-20"),
//            'keyword2' => ARRAY('value' => "为了更好的服务每一个家庭，请参与我们社区中医健康指导服务的问卷调查，希望各位家长抽出宝贵时间支持我们的工作"),
//            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
//        ];

//        $data = [
//            'first' => array('value' => "您好，为确保享受体检通知服务,请尽快完善宝宝信息\n",),
//            'keyword1' => ARRAY('value' => "宝宝基本信息"),
//            'keyword2' => ARRAY('value' => "广外社区区卫生服务中心"),
//            'remark' => ARRAY('value' => "\n 点击完善宝宝信息", 'color' => '#221d95'),
//        ];
//
//        $rs = WechatSendTmp::send($data, 'o5ODa0wc1u3Ihu5WvCVqoACeQ-HA', 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', 'https://jinshuju.net/f/Sfodlu');
//        exit;


        $data = [
            'first' => array('value' => "您好，为确保享受体检通知服务,请尽快完善宝宝信息\n",),
            'keyword1' => ARRAY('value' => "宝宝基本信息"),
            'keyword2' => ARRAY('value' => "为了更好的服务每一个家庭，请参与我们社区中医健康指导服务的问卷调查，希望各位家长抽出宝贵时间支持我们的工作"),
            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
//
        $rs = [];
        $file = fopen("openid2.log", 'r');
        while (($line = fgets($file)) !== false) {
            $row = explode(',', trim($line));
            $openid = $row[0];
            $doctor = $row[1];
            $rs[$openid] = $doctor;
        }
//        $openidl=[];
//        $file1=fopen("openidl",'r');
//        while(($line1=fgets($file1))!==false){
//            $rsa=trim($line1);
//            if(!in_array($rs,$openidl))
//            {
//                $openidl[]=$rsa;
//            }
//        }
//        foreach($rs as $k=>$v){
//            if(!in_array($k,$openidl))
//            {
//                echo $k.",".$v."\n";
//            }
//        }
//        exit;


        foreach ($rs as $k => $v) {

            $data = [
                'first' => array('value' => "您好，为确保享受儿童中医健康指导服务,请尽快完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => $v),
                'remark' => ARRAY('value' => "\n 点击完善宝宝信息", 'color' => '#221d95'),
            ];
            $rs = WechatSendTmp::send($data, $k, 'wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', '', ['appid' => \Yii::$app->params['wxXAppId'], 'pagepath' => 'pages/index/index',]);
            echo $k . "\n";
        }
        exit;

        $weOpenid = WeOpenid::find()->andWhere(['level' => 0])->all();
        foreach ($weOpenid as $k => $v) {
            if ($v->openid) {
                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                $hospital = Hospital::findOne($doctor->hospitalid);
                echo $v->openid . "," . $hospital->name . "\n";
            }
        }

        $doctorParent = DoctorParent::findAll(['level' => 1]);
        foreach ($doctorParent as $k => $v) {

            if (!ChildInfo::findOne(['userid' => $v->parentid])) {
                $userLogin = UserLogin::findOne(['userid' => $v->parentid]);
                $doctor = UserDoctor::findOne(['userid' => $v->doctorid]);
                $hospital = Hospital::findOne($doctor->hospitalid);
                if ($userLogin && $userLogin->openid) {
                    echo $userLogin->openid . "," . $hospital->name;
                    echo "\n";
                }
            }
        }
        exit;


        foreach ($weOpenid as $k => $v) {
            $data = [
                'first' => array('value' => "您好，为确保享受体检通知服务,请尽快完善宝宝信息\n",),
                'keyword1' => ARRAY('value' => "宝宝基本信息"),
                'keyword2' => ARRAY('value' => "为了更好的服务每一个家庭，请参与我们社区中医健康指导服务的问卷调查，希望各位家长抽出宝贵时间支持我们的工作"),
                'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
            ];
        }


        $userids = UserLogin::find()->where(['!=', 'openid', ''])->all();
        foreach ($userids as $k => $v) {
            echo $v->userid . "==";
            //$userLogin=UserLogin::findOne(['userid'=>$v->parentid]);
            $userLogin = $v;
            if ($userLogin->openid) {
                $rs = WechatSendTmp::send($data, 'o5ODa0wc1u3Ihu5WvCVqoACeQ-HA', '﻿wiVMfEAlt4wYwfpjcawOTDwgUN8SRPIH1Fc8wVWfGEI', 'https://jinshuju.net/f/Sfodlu');
                echo $rs;
            }
            echo "\n";
        }

    }

    public function actionArticlePush()
    {
        $article = \common\models\Article::findOne(323);

//        $data = [
//            'first' => array('value' => $article->info->title . "\n",),
//            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
//            'keyword2' => ARRAY('value' => strip_tags($article->info->content)),
//            'remark' => ARRAY('value' => "\n 点击查看社区卫生服务中心通知详情", 'color' => '#221d95'),
//        ];
//        $miniprogram = [
//            "appid" => \Yii::$app->params['wxXAppId'],
//            "pagepath" => "/pages/article/view/index?id=" . $article->id,
//        ];


        $data = [
            'first' => array('value' => $article->info->title . "\n",),
            'keyword1' => ARRAY('value' => date('Y年m月d H:i'),),
            'keyword2' => ARRAY('value' => '儿宝宝'),
            'keyword3' => ARRAY('value' => '儿宝宝'),
            'keyword4' => ARRAY('value' => '宝爸宝妈'),
            'keyword5' => ARRAY('value' => $article->info->title),

            'remark' => ARRAY('value' => "\n 请点击查看", 'color' => '#221d95'),
        ];
        $miniprogram = [
            "appid" => \Yii::$app->params['wxXAppId'],
            "pagepath" => "/pages/article/view/index?id=" . $article->id,
        ];
        //$userids=UserLogin::find()->where(['userid'=>'47388'])->all();

        //$userids = DoctorParent::find()->andFilterWhere(['parentid' => 77107])->all();


        $userids = WeOpenid::find()->andFilterWhere([">", 'createtime', '1529942400'])
            ->andFilterWhere(['level' => 0])
            ->andWhere(['!=', 'openid', ''])
            ->all();
        $openids = [];
        if ($article) {
            foreach ($userids as $k => $v) {
                //$userLogin = UserLogin::findOne(['userid' => $v->userid]);
                //$userLogin=$v;
                if (in_array($v->openid, $openids))
                    $openids[] = $v->openid;
                if ($v->openid) {
                    $rs = WechatSendTmp::send($data, $v->openid, \Yii::$app->params['zhidao'], '', $miniprogram);
                    echo $rs;
                }
                if ($article->art_type != 2) {
                    $key = $article->catid == 6 ? 3 : 5;
                    //Notice::setList($v->userid, $key, ['title' => $article->info->title, 'ftitle' => date('Y年m月d H:i'), 'id' => "/article/view/index?id=" . $article->id,]);
                }
                echo "\n";
            }
        }
    }

    public function actionTe()
    {
        $weOpenid = WeOpenid::find()->andFilterWhere(['level' => 1])->andFilterWhere(['>', 'createtime', '1524067200'])->all();
        foreach ($weOpenid as $k => $v) {
            $user = UserLogin::findOne(['openid' => $v->openid]);
            if ($user) {
                $parentid = $user->userid;
                $doctorParent = DoctorParent::findOne(['parentid' => $parentid]);
                if ($doctorParent) {
                    $doctorParent->parentid = $parentid;
                    $doctorParent->doctorid = $v->doctorid;
                    $doctorParent->createtime = $v->createtime;
                    $doctorParent->level = 1;
                    $doctorParent->save();
                    echo $parentid;
                }
            }
        }
        exit;
    }

    public function actionText()
    {


        $connection = new \yii\db\Connection([
            'dsn' => 'mysql:host=139.129.246.51;dbname=child_health',
            'username' => 'wedoctors_admin',
            'password' => 'trd7V37v3PXeU9vn',
        ]);
        $connection->open();

        $f = fopen("data/doctor_parent.sql", 'r');
        $i = 0;
        while (($line = fgets($f)) !== false) {
            echo $line;
            $command = $connection->createCommand(trim($line));
            $command->execute();
            echo "\n";
            //var_dump($post);exit;
        }
        exit;
    }


    public function actionBd()
    {

        echo md5(md5("139110083832QH@6%3(87"));
        exit;

        for ($i = 1; $i < 10; $i++) {
            echo $i . "\n";
            $row = ChildInfo::getChildType($i);
            echo date('Y-m-d', $row['firstday']) . "\n";
            echo date('Y-m-d', $row['lastday']) . "\n";
        }
        exit;


        $text = [
            '2018-01-01',
            '2019-02-01',
            '2018-12-01',
            '2017-01-01'
        ];
        rsort($text);
        var_dump($text);
        exit;

        $curl = new HttpRequest('https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eptlQANnGdZaa0B61xqymbGJib67XqeOEufjIeUXXUx9CibrrAkic1JichlNr698cbfN7u8IEsGJEVic9g/0', true, 2);
        echo $curl->get();
        exit;
        ini_set('memory_limit', '2048M');

        $child = ChildInfo::find()->all();
        foreach ($child as $k => $v) {
            $v->birthday = strtotime(date('Y-m-d', $v->birthday));
            $v->save();
            echo date('Y-m-d H:i:s', $v->birthday);
            echo "\n";
        }
    }

    /**
     * 体检数据
     */
    public function actionEx()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $file_list = glob("data/ex/*.csv");
        foreach ($file_list as $fk => $fv) {
            preg_match("#\d+#", $fv, $m);
            $hospitalid = substr($m[0], 0, 6);
            echo $hospitalid . "\n";
            $f = fopen($fv, 'r');
            $i = 0;
            while (($line = fgetcsv($f)) !== false) {
                if ($i == 0) {
                    $i++;
                    continue;
                }
                $i++;
                echo $hospitalid . "=" . $i . "===";
                $row = $line;

                if ($row[3] < '2018-01-01 00:00:00') {
                    echo "end\n";
                    break;
                }
                $row[3] = substr($row[3], 0, strlen($row[3]) - 11);
                $ex = Examination::find()->andFilterWhere(['field1' => $row[0]])
                    ->andFilterWhere(['field2' => $row[1]])
                    ->andFilterWhere(['field3' => $row[2]])
                    ->andFilterWhere(['field4' => $row[3]])
                    ->andFilterWhere(['source' => $hospitalid])
                    ->andFilterWhere(['field19' => $row[18]])->one();
                if ($ex) {
                    echo "jump\n";
                    continue;
                }
                $ex = $ex ? $ex : new Examination();

                $child = ChildInfo::find()->andFilterWhere(['name' => trim($row[0])])
                    ->andFilterWhere(['birthday' => strtotime($row[18])])
                    ->andFilterWhere(['source' => $hospitalid])
                    ->one();
                echo $row[0];

                $childData = [

                    'field1' => $row[0],
                    'field2' => $row[1],
                    'field3' => $row[2],
                    'field4' => $row[3],
                    'field5' => $row[4],
                    'field6' => $row[5],
                    'field7' => $row[6],
                    'field8' => $row[7],
                    'field9' => $row[8],
                    'field10' => $row[9],
                    'field11' => $row[10],
                    'field12' => $row[11],
                    'field13' => $row[12],
                    'field14' => $row[13],
                    'field15' => $row[14],
                    'field16' => $row[15],
                    'field17' => $row[16],
                    'field18' => $row[17],
                    'field19' => $row[18],
                    'field20' => $row[19],
                    'field21' => $row[20],
                    'field22' => $row[21],
                    'field23' => $row[22],
                    'field24' => $row[23],
                    'field25' => $row[24],
                    'field26' => $row[25],
                    'field27' => $row[26],
                    'field28' => $row[27],
                    'field29' => $row[28],
                    'field30' => $row[29],
                    'field31' => $row[30],
                    'field32' => $row[31],
                    'field33' => $row[32],
                    'field34' => $row[33],
                    'field35' => $row[34],
                    'field36' => $row[35],
                    'field37' => $row[36],
                    'field38' => $row[37],
                    'field39' => $row[38],
                    'field40' => $row[39],
                    'field41' => $row[40],
                    'field42' => $row[41],
                    'field43' => $row[42],
                    'field44' => $row[43],
                    'field45' => $row[44],
                    'field46' => $row[45],
                    'field47' => $row[46],
                    'field48' => $row[47],
                    'field49' => $row[48],
                    'field50' => $row[49],
                    'field51' => $row[50],
                    'field52' => $row[51],
                    'field53' => $row[52],
                    'field54' => $row[53],
                    'field55' => $row[54],
                    'field56' => $row[55],
                    'field57' => $row[56],
                    'field58' => $row[57],
                    'field59' => $row[58],
                    'field60' => $row[59],
                    'field61' => $row[60],
                    'field62' => $row[61],
                    'field63' => $row[62],
                    'field64' => $row[63],
                    'field65' => $row[64],
                    'field66' => $row[65],
                    'field67' => $row[66],
                    'field68' => $row[67],
                    'field69' => $row[68],
                    'field70' => $row[69],
                    'field71' => $row[70],
                    'field72' => $row[71],
                    'field73' => $row[72],
                    'field74' => $row[73],
                    'field75' => $row[74],
                    'field76' => $row[75],
                    'field77' => $row[76],
                    'field78' => $row[77],
                    'field79' => $row[78],
                    'field80' => $row[79],
                    'field81' => $row[80],
                    'field82' => $row[81],
                    'field83' => $row[82],
                    'field84' => $row[83],
                    'field85' => $row[84],
                    'field86' => $row[85],
                    'field87' => $row[86],
                    'field88' => $row[87],
                    'field89' => $row[88],
                    'field90' => $row[89],
                    'field91' => $row[90],
                    'field92' => $row[91],
                    'source' => $hospitalid,
                ];

                if (!$child) {
                    echo "--儿童不存在";
                    // $childData['childid'] = 0;
                } else {
                    echo "--儿童存在";
                    $childData['childid'] = $child->id;
                }


//                $childData = array_filter($childData, function ($e) {
//                    if ($e != '' || $e != null) return true;
//                    return false;
//                });
                foreach ($childData as $k => $v) {
                    $ex->$k = $v;
                }
                $ex->save();
                if ($ex->firstErrors) {
                    echo "error";
                    var_dump($ex->firstErrors);
                }
                echo "\n";
            }
        }
    }

    /**
     * 体检更新提醒
     */
    public function actionExUpdate()
    {
        $logins = [];
        $i = 0;
        ini_set('memory_limit', '1024M');
        $ex = Examination::find()->andFilterWhere(['isupdate' => 1])->andFilterWhere(['>', 'field4', '2018-12-13'])->groupBy('childid')->all();


        foreach ($ex as $k => $v) {
            $child = ChildInfo::findOne(['id' => $v->childid]);
            if ($child) {
                //echo $child->id . "===$k" . "===";
                $login = $child->login;
                if ($login->openid && !in_array($login->openid, $logins)) {
                    $logins[] = $login->openid;
                    $data = [
                        'first' => array('value' => "您好，宝宝近期的体检结果已更新\n",),
                        'keyword1' => ARRAY('value' => $child->name),
                        'keyword2' => ARRAY('value' => "身高:{$v->field40},体重:{$v->field70},头围:{$v->field80}"),
                        'keyword3' => ARRAY('value' => $v->field9),
                        'keyword4' => ARRAY('value' => $v->field4),
                        'remark' => ARRAY('value' => "\n 点击可查看本体检报告的详细内容信息", 'color' => '#221d95'),
                    ];
                    $miniprogram = [
                        "appid" => \Yii::$app->params['wxXAppId'],
                        "pagepath" => "/pages/user/examination/index?id=" . $child->id,
                    ];
                    $rs = WechatSendTmp::send($data, $login->openid, \Yii::$app->params['tijian'], '', $miniprogram);
                    echo $child->userid . "======";
                    echo $login->openid . "======";

                    echo json_encode($rs);
                    echo "\n";
                    //小程序首页通知
                    Notice::setList($login->userid, 1, ['title' => "宝宝近期的体检结果已更新", 'ftitle' => "点击可查看本体检报告的详细内容信息", 'id' => "/user/examination/index?id=" . $child->id,], "id=" . $child->id);
                    $i++;
                }
            }
            $v->isupdate = 0;
            $v->save();
        }
        echo $i;
        Examination::updateAll(['isupdate' => 0]);
        echo "true\n";
    }

    /**
     * 更新用户unionid
     * @throws \yii\web\HttpException
     */
    public function actionGetUid()
    {
        $wechat = new MpWechat([
            'token' => \Yii::$app->params['WeToken'],
            'appId' => \Yii::$app->params['AppID'],
            'appSecret' => \Yii::$app->params['AppSecret'],
            'encodingAesKey' => \Yii::$app->params['encodingAesKey']
        ]);
        $access_token = $wechat->getAccessToken();

        $weOpenid = WeOpenid::find()->andWhere(['unionid' => ''])->andWhere(['!=', 'openid', ''])->all();
        foreach ($weOpenid as $k => $v) {

            $path = '/cgi-bin/user/info?access_token=' . $access_token . "&openid=" . $v->openid . "&lang=zh_CN";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 2);
            $userJson = $curl->get();
            $userInfo = json_decode($userJson, true);
            if ($userInfo['unionid']) {
                $v->unionid = $userInfo['unionid'];
                $v->save();
                echo "成功\n";
            } else {
                echo "失败\n";
            }
        }
        exit;


        $user = UserLogin::find()->where(['!=', 'openid', ''])->andWhere(["=", 'unionid', ''])->orderBy('userid desc')->all();
        foreach ($user as $k => $v) {
            $userLogin = UserLogin::findOne(['userid' => $v->userid]);
            $openid = $userLogin->openid;
            $path = '/cgi-bin/user/info?access_token=' . $access_token . "&openid=" . $openid . "&lang=zh_CN";
            $curl = new HttpRequest(\Yii::$app->params['wxUrl'] . $path, true, 2);
            $userJson = $curl->get();
            $userInfo = json_decode($userJson, true);
            var_dump($userInfo);
            exit;
            if ($userInfo['unionid']) {
                $userLogin->unionid = $userInfo['unionid'];
                $userLogin->save();
                echo $v->userid . "成功";
            } else {
                echo $v->userid . "没有";

            }
            echo "\n";
        }

    }

    public function actionSet()
    {
        ini_set('memory_limit', '1024M');

        $userParent = UserParent::find()->andFilterWhere(['>', 'source', 38])->all();
        foreach ($userParent as $k => $v) {
            echo "parentid=" . $v->userid . ",";
            $doctorParent = DoctorParent::findOne(['parentid' => $v->userid]);
            if (!$doctorParent) {
                $userid = UserDoctor::findOne(['hospitalid' => $v->source])->userid;
                echo "doctorid=" . $userid . ",";
                if ($userid) {

                    $doctorP = new DoctorParent();
                    $doctorP->doctorid = $userid;
                    $doctorP->parentid = $v->userid;
                    $doctorP->level = -1;
                    if ($doctorP->save()) {
                        echo "成功\n";
                    } else {
                        var_dump($doctorP->firstErrors);
                        echo "\n";
                    }
                    continue;
                }
            }
            echo "失败\n";
        }


    }

    /**
     * 妇幼二期数据
     */
    public function actionGet()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        ini_set("max_execution_time", "0");
        set_time_limit(0);

        $file_list = glob("data/*.csv");
        foreach ($file_list as $fk => $fv) {
            preg_match("#\d+#", $fv, $m);
            $hospitalid = $m[0];
            if ($hospitalid) {
                $f = fopen($fv, 'r');
                $i = 0;
                while (($line = fgets($f)) !== false) {
                    echo $i . "===";
                    $i++;
                    $row = explode(",", trim($line));
                    if (strlen($row[31]) < 11 && strlen($row[35]) < 11 && strlen($row[12]) < 11) {
                        echo "--31-" . $row['31'];
                        echo "--35-" . $row['35'];
                        echo "--12-" . $row['12'];

                        echo "无手机号\n";
                        continue;
                    }


                    if (strlen($row[31]) == 11) {
                        $phone = $row[31];
                    } elseif (strlen($row[12]) == 11) {
                        $phone = $row[12];
                    } elseif (strlen($row[35]) == 11) {
                        $phone = $row[35];
                    }

                    if (!$phone || strlen($phone) != 11) {
                        echo "手机号不合法\n";
                        continue;
                    }
                    $user = User::findOne(['phone' => $phone]);
                    $user = $user ? $user : new User();
                    $user->phone = $phone;
                    $user->source = 2;
                    $user->type = 1;
                    echo $user->id . "====";
                    if ($user->save()) {
                        $login = UserLogin::findOne(['userid' => $user->id]);
                        $login = $login ? $login : new UserLogin();
                        $login->userid = $user->id;
                        $login->password = md5(md5($user->phone . "2QH@6%3(87"));
                        $login->save();
                        $userparent = UserParent::findOne(['userid' => $user->id]);
                        $userparent = $userparent ? $userparent : new UserParent();
                        echo $row[8] . "====";

                        $userparent->userid = $user->id;
                        $userparent->mother = $row[8];
                        $userparent->mother_phone = intval($row[31]);
                        $userparent->father_phone = intval($row[35]);

                        $userparent->father = $row[10];
                        $userparent->mother_id = $row[9];
                        $userparent->father_birthday = strtotime($row[32]);
                        $userparent->address = $row[36];
                        $userparent->source = $hospitalid;
                        $userparent->field1 = $row[1];
                        $userparent->field34 = $row[34];
                        $userparent->field33 = $row[33];
                        $userparent->field30 = $row[30];
                        $userparent->field29 = $row[29];
                        $userparent->field28 = $row[28];
                        $userparent->field12 = intval($row[12]);
                        $userparent->field11 = $row[11];
                        if ($userparent->save()) {
                            $child = ChildInfo::findOne(['name' => $row[3], 'userid' => $user->id]);
                            $child = $child ? $child : new ChildInfo();
                            $child->userid = $user->id;
                            $child->name = $row[3];
                            $child->gender = $row[4] == "男" ? 1 : 2;
                            $child->birthday = intval(strtotime($row[5]));
                            $child->createtime = time();
                            $child->source = $hospitalid;
                            $child->doctorid = $hospitalid;
                            $child->field54 = $row[54];
                            $child->field53 = $row[53];
                            $child->field52 = $row[52];
                            $child->field51 = $row[51];
                            $child->field50 = $row[50];
                            $child->field49 = $row[49];
                            $child->field48 = $row[48];
                            $child->field47 = $row[47];
                            $child->field46 = $row[46];
                            $child->field45 = $row[45];
                            $child->field44 = $row[44];
                            $child->field43 = $row[43];
                            $child->field42 = $row[42];
                            $child->field41 = $row[41];
                            $child->field40 = $row[40];
                            $child->field39 = $row[39];
                            $child->field38 = $row[38];
                            $child->field37 = $row[37];
                            $child->field27 = $row[27];
                            $child->field26 = $row[26];
                            $child->field25 = $row[25];
                            $child->field24 = $row[24];
                            $child->field23 = $row[23];
                            $child->field22 = $row[22];
                            $child->field21 = $row[21];
                            $child->field20 = $row[20];
                            $child->field19 = $row[19];
                            $child->field18 = $row[18];
                            $child->field17 = $row[17];
                            $child->field16 = $row[16];
                            $child->field15 = $row[15];
                            $child->field14 = $row[14];
                            $child->field13 = $row[13];
                            $child->field7 = $row[7];
                            $child->field6 = $row[6];
                            $child->field0 = $row[0];
                            if ($child->save()) {
                                echo "成功\n";
                                continue;
                            }
                            var_dump($child->firstErrors);
                        }
                        var_dump($userparent->firstErrors);

                    }
                    var_dump($user->firstErrors);
                }
                echo "失败\n";
            }
        }
    }


    public function actionTest()
    {
//        $list = DoctorParent::find()->andFilterWhere(['level'=>1])->andFilterWhere(['doctorid'=>0])->all();
//        foreach($list as $k=>$v)
//        {
//            echo $v->parentid;
////            $doctorParent=DoctorParent::find()->where(['>','doctorid',0])->andFilterWhere(['parentid'=>$v->parentid])->all();
////            if(count($doctorParent)==1)
////            {
////                $v->doctorid=$doctorParent[0]->doctorid;
////                $v->save();
////                $doctorParent[0]->delete();
////                echo "==del";
////            }
//            $childInfo = ChildInfo::findOne(['userid'=>$v->parentid]);
//            if($childInfo->source)
//            {
//                $doctor=UserDoctor::findOne(['hospitalid'=>$childInfo->source]);
//                if($doctor) {
//                    $v->doctorid =$doctor->userid;
//                    $v->save();
//                }
//                if($childInfo->source==38)
//                {
//                    $v->doctorid =38;
//                    $v->save();
//                }
//
//            }else{
//                $v->doctorid =47156;
//                $v->save();
//            }
//            echo "\n";
//        }


        $return = \Yii::$app->beanstalk
            ->putInTube('export', ['artid' => 301, 'userids' => [49106]]);
        var_dump($return);
        exit;

        //ChatRecord::updateAll(['read'=>1],['touserid'=>18486,'userid'=>4146]);
    }

}