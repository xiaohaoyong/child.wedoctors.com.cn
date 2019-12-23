<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2019/12/19
 * Time: 下午3:35
 */
?>
<div class="pregnancy-index">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">宝妈年龄：</h3>
                <!-- /.box-tools -->
            </div>
            <div class="box-body table-responsive no-padding">

                <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                    <table id="w0" class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td>年龄</td>
                            <?php for ($i = 20; $i < 50; $i++) {
                                $year = date('Y', strtotime("-$i year"))
                                ?>
                                <td><?= date('Y')-$year ?></td>
                            <?php } ?>
                        </tr>
                        <tr>
                            <td>数量</td>
                            <?php for ($i = 20; $i < 50; $i++) {
                                $year = date('Y', strtotime("-$i year"))
                                ?>
                                <td><?= $age[$year] ?></td>
                            <?php } ?>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>