<script src="<?=RES?>home/js/plugins/jquery.validate.js"></script>
<?php if($puri == 'cn'){?>
<script src="<?=RES?>home/js/plugins/validate/localization/messages_zh.js"></script>
<?php }?>
<script src="<?=RES?>home/js/plugins/from/jquery.form.js"></script>
<style>
/* == 错误提示的样式 == */
span.error {background: url("/resource/home/images/public/unchecked.gif") no-repeat scroll 4px center transparent;
color: red;
overflow: hidden;
padding-left: 24px;
height: 25px;
line-height: 25px;
display: block;}
span.success { background: url("<?=RES?>home/images/public/checked.gif") no-repeat scroll 4px center transparent; color: red; overflow: hidden; padding-left: 19px; }
</style>