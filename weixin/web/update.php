<?php
chdir(__DIR__);
echo '<pre>';
echo date('Y-m-d H:i:s');
echo "<hr>";
exec('/usr/bin/git pull -u origin developer 2>&1',$retval);
var_dump($retval);

?>
