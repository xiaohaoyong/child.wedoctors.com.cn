<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/1/25
 * Time: 下午3:06
 */

?>

<form  action="http://wedoctorschild.oss-cn-beijing.aliyuncs.com" method="post" enctype="multipart/form-data">
    <input type="text" name="OSSAccessKeyId" value="<?=$row['accesskeyid']?>">
    <input type="text" name="policy" value="<?=$row['policy']?>">
    <input type="text" name="Signature" value="<?=$row['signature']?>">
    <input type="text" name="key" value="<?=$row['key']?>">
    <input type="text" name="callback" value="<?=$row['callback']?>">
    <input type="text" name="success_action_redirect" value="<?=$row['success_action_redirect']?>">
    <input type="text" name="success_action_status" value="<?=$row['success_action_status']?>">
    <input name="file" type="file" id="file">
    <input name="submit" value="Upload" type="submit">
</form>
