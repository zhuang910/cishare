<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo lang('title');?></title>
<link rel="stylesheet" type="text/css" href="<?=CSS?>main.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=CSS?>user.css" media="screen" />
</head>
<body>
<div class="RegHeadBox">
	<div class="RegHead"><img src="<?=IMG?>public/logo1.png" /></div>
</div>

<div class="RegBox">
	
	
    <div class="LogCon">
    	 <p style="font-size:16px;"><?php echo $msg;?></p>
               		<p class="alert_btnleft">
                    <?php if ($url): ?> 
                    <a href="<?php echo $url;?>">Please click this link if your browser has not redirected automatically.</a>
                    <meta http-equiv="refresh" content="<?php echo $time;?>; url=<?php echo $url;?>">
                    <?php else:?>
                    <a href="javascript:history.back();" >[Back to previous]</a>
                   <?php endif;?>
    </div>
   
    <div class="RegFoot">
    	
    </div>
</div>

<?php $this->load->view(HINDEX.'public/foot');?>

 