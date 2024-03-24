<form name="appoint" id="appoint_form" method="post" enctype="multipart/form-data">
<input name="_csrf-frontend"

type="hidden"

id="_csrf-frontend"

value="<?= Yii::$app->request->csrfToken ?>">
<input type="file" name="img[]" multiple="multiple" id="fileName"/>
<button type="submit">确定预约</button>

</form>