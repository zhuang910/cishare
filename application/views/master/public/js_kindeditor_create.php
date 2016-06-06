<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-9-19
 * Time: 下午5:42
 */
?>
<script type="text/javascript"> 
$(document).ready(function(){
		KindEditor.create('.<?=empty($editorid) ? 'content' : $editorid?>', {
		cssPath : '<?=RES?>master/js/Kindeditor/plugins/code/prettify.css',
		fileManagerJson:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=20&file_types=jpg,jpeg,gif,png,doc,docx,rar,zip,swf&file_size=5&moduleid=2&is_edit=1',
		editorid:'<?=empty($editorid) ? 'content' : $editorid?>',
		upImgUrl:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=gif,jpg,jpeg,png,bmp&file_size=5&moduleid=2&is_edit=1',
		upFlashUrl:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=swf,flv&file_size=5&moduleid=2&is_edit=1',
		upMediaUrl:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=mpg,wmv,avi,wma,mp3,mid,asf,rm,rmvb,wav,wma,mp4&file_size=5&moduleid=2&is_edit=1',
		allowFileManager : true,
		afterBlur: function(){this.sync();}
		});
});
</script>