<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv='Refresh' content='<?=$sleep?>;URL=<?=!empty($jump)? $jump : $_SERVER ["HTTP_REFERER"]?>'>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<title><?=APPNAME?></title>
<link rel="stylesheet" href="<?=RES?>admin/css/power/css/style.css" />
</head>
<body class='error'>
	<div class="wrapper">
		<div class="code"><span><?=$state == 1 ? '成功' : '错误'?></span><i class="icon-warning-sign"></i></div>
		<div class="desc"><?=$msg?>，<?=$sleep?> 秒后跳转...</div>
		<div class="buttons">
			<div class="pull-left"><a href="<?=$_SERVER ["HTTP_REFERER"]?>" class="btn"><i class="icon-arrow-left"></i> 返回</a></div>
		</div>
	</div>
	
</body>

</html>