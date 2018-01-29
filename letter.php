<?
if (isset($_REQUEST['dev'])) {
    $_SERVER['SERVER_NAME'] = 'taleat-staging.i-market.ru';
}
?>
<div style="height: 100% !important; padding: 40px; margin: 0; font: normal 16px Arial, 'Helvetica Neue', Helvetica, sans-serif; position: relative; cursor: default; background-color: #f2f3f7;">
    <div style="float: left; margin-bottom: 20px;"><img src="<?= 'http://'.$_SERVER['SERVER_NAME'].'/local/templates/main/build/assets/images/ico/logo.png' ?>" alt="" style="height: 40px; width: auto"></div>
    <div style="float: right; margin-bottom: 20px">
        <a href="<?= 'http://'.$_SERVER['SERVER_NAME'].'/personal/order/' ?>" style="display: block; height: 40px; line-height: 40px; padding: 0 25px; border: 1px solid #68c; color: #68c; font-size: 16px; text-decoration: none;">Личный кабинет</a>
    </div>
    <div style="clear: both; padding: 40px; background: #fff; box-shadow: 0 2px 4px 0 rgba(0,0,0,.1);">
        #WORK_AREA#
    </div>
</div>
