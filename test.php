<?php
echo strtotime(date('YmdHis')) . substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 999));
exit;
$field_Examination=$field=[
    1=>'儿童姓名',
    2=>'实足年龄_岁',
    3=>'实足年龄_月',
    4=>'体检日期',
    5=>'年龄别体重评价',
    6=>'年龄别身长评价',
    7=>'身长别体重评价',
    8=>'体检医生签名',
    9=>'体检机构',
    10=>'腹部',
    11=>'腹部异常信息',
    12=>'贫血评价',
    13=>'佝偻病评价',
    14=>'佝偻病活动期',
    15=>'营养不良评价',
    16=>'肥胖评价（依肥胖度划分）',
    17=>'肥胖评价（标准差五分法）',
    18=>'健康评价_其他',
    19=>'出生日期',
    20=>'BMI值',
    21=>'24小时母乳喂养',
    22=>'前囟',
    23=>'面色',
    24=>'矫治颗数',
    25=>'两次随访间患病情况',
    26=>'耳外观',
    27=>'耳外观异常信息',
    28=>'眼外观',
    29=>'眼外观异常信息',
    30=>'步态',
    31=>'步态异常信息',
    32=>'性别',
    33=>'肛门/外生殖器',
    34=>'肛门/外生殖器异常信息',
    35=>'发育评估',
    36=>'指导内容',
    37=>'先天性心脏病可疑征象',
    38=>'心脏',
    39=>'先天性心脏病可疑征象阳性内容',
    40=>'身长（cm）',
    41=>'血红蛋白值',
    42=>'先天性髋关节脱位可疑征象',
    43=>'发育性髋关节发育不良可疑征象阳性内容',
    44=>'四肢',
    45=>'四肢异常信息',
    46=>'肺脏',
    47=>'肺脏异常信息',
    48=>'口腔',
    49=>'口腔异常信息',
    50=>'脐部',
    51=>'脐部异常信息',
    52=>'下次体检日期',
    53=>'肥胖度评价',
    54=>'户外活动（小时/日）',
    55=>'经皮测氧饱和度',
    56=>'经皮测氧饱和度异常_右手',
    57=>'经皮测氧饱和度异常_足',
    58=>'体格检查_其他',
    59=>'转诊建议',
    60=>'转诊原因',
    61=>'转诊机构及科室',
    62=>'佝偻病体征',
    63=>'佝偻病症状',
    64=>'新生儿产科听力筛查情况',
    65=>'皮肤',
    66=>'皮肤异常信息',
    67=>'服用铁剂情况',
    68=>'出牙数（颗）',
    69=>'服用维生素D',
    70=>'体重（kg）',
    71=>'听力筛查',
    72=>'左耳听力',
    73=>'右耳听力',
    74=>'OAE耳声发射请选择',
    75=>'扁桃体',
    76=>'沙眼',
    77=>'视力左',
    78=>'视力右',
    79=>'心脏异常',
    80=>'头围',
    81=>'听力筛查方法',
    82=>'本次体检属于',
    83=>'体检费用',
    84=>'龋齿颗数',
    85=>'户口',
    86=>'发放中医药健康指导宣传材料',
    87=>'发放年龄',
    88=>'使用国家母子健康手册',
    89=>'预警征象',
    90=>'预警征象描述',
    91=>'心理行为问题',
    92=>'心理行为问题描述'
];
$rs="儿童姓名,实足年龄_岁,实足年龄_月,体检日期,年龄别体重评价,年龄别身长评价,身长别体重评价,体检医生签名,体检机构,腹部,腹部异常信息,贫血评价,佝偻病评价,佝偻病活动期,营养不良评价,肥胖评价（依肥胖度划分）,肥胖评价（标准差五分法）,健康评价_其他,出生日期,BMI值,24小时母乳喂养,前囟,面色,矫治颗数,两次随访间患病情况,耳外观,耳外观异常信息,眼外观,眼外观异常信息,步态,步态异常信息,性别,肛门/外生殖器,肛门/外生殖器异常信息,发育评估,指导内容,先天性心脏病可疑征象,心脏,先天性心脏病可疑征象阳性内容,身长（cm）,血红蛋白值,先天性髋关节脱位可疑征象,发育性髋关节发育不良可疑征象阳性内容,四肢,四肢异常信息,肺脏,肺脏异常信息,口腔,口腔异常信息,脐部,脐部异常信息,下次体检日期,肥胖度评价,户外活动（小时/日）,经皮测氧饱和度,经皮测氧饱和度异常_右手,经皮测氧饱和度异常_足,体格检查_其他,转诊建议,转诊原因,转诊机构及科室,佝偻病体征,佝偻病症状,新生儿产科听力筛查情况,皮肤,皮肤异常信息,服用铁剂情况,出牙数（颗）,服用维生素D,体重（kg）,听力筛查,左耳听力,右耳听力,OAE耳声发射请选择,扁桃体,沙眼,视力左,视力右,心脏异常,头围,听力筛查方法,本次体检属于,体检费用,龋齿颗数,户口,发放中医药健康指导宣传材料,发放年龄,使用国家母子健康手册,预警征象,预警征象描述,心理行为问题,心理行为问题描述";

$r=array_diff($field_Examination,explode(',',$rs));
var_dump($r);exit;
echo md5("158110786043".date('Ymd')."123456789");
echo "\n";
exit;

//创建一个socket套接流
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
/****************设置socket连接选项，这两个步骤你可以省略*************/
//接收套接流的最大超时时间1秒，后面是微秒单位超时时间，设置为零，表示不管它
socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec" => 1, "usec" => 0));
//发送套接流的最大超时时间为6秒
socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array("sec" => 6, "usec" => 0));
/****************设置socket连接选项，这两个步骤你可以省略*************/

//连接服务端的套接流，这一步就是使客户端与服务器端的套接流建立联系
if(socket_connect($socket,'139.129.246.51',9502) == false){
    echo 'connect fail massege:'.socket_strerror(socket_last_error());
}else{
    $message = 'l love you 我爱你 socket';
    //转为GBK编码，处理乱码问题，这要看你的编码情况而定，每个人的编码都不同
    $message = mb_convert_encoding($message,'GBK','UTF-8');
    //向服务端写入字符串信息

    if(socket_write($socket,$message,strlen($message)) == false){
        echo 'fail to write'.socket_strerror(socket_last_error());

    }else{
        echo 'client write success'.PHP_EOL;
        //读取服务端返回来的套接流信息
        while($callback = socket_read($socket,1024)){
            echo 'server return message is:'.PHP_EOL.$callback;
        }
    }
}
socket_close($socket);//工作完毕，关闭套接流