<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admission Offer</title>
<style>
.cn{ font-family: 宋体}
.cn h1{ font-size:30px; line-height:50px;}
.cn h2{font-size:26px;line-height:40px;}
.cn div{ font-size:16px; margin-bottom:30px;}
.cn p{ line-height:26px; margin-bottom:30px;}

dt{ font-size:16px; font-weight:bold;}
dd{ line-height:26px; font-size:16px;}
</style>
</head>
<body>
<div style="width:649px;height:978px; margin:0 auto;" class="cn">
  <h1 style="margin:0 auto;width:100%;display:block; text-align:center; margin-top:100px">北京外国语大学汉语短训班邀请书</h1>
    <div style="margin-bottom:160px;">
          <p><?=!empty($data['name'])?$data['name']:''?> 先生：</p>
      <p style="float:right;"> 学号：<?=!empty($data['idnum'])?$data['idnum']:''?></p>
          <p style="clear:both;"> 我们高兴地通知您，北京外国语大学同意接收您于 <?=!empty($data['opening'])?$data['opening']:''?> 至 <?=!empty($data['xxendtime'])?$data['xxendtime']:''?>; 到我大学进行短期学习。在学期间，我们提供学生公寓住宿。 </p>
          <p> 如果您自愿遵守中国的法律、法规和学校的校纪、校规，请持本邀请书于<?=!empty($data['otime'])?$data['otime']:''?> 到培训学院多语种办公室报到。逾期不报到，则认为您自动放弃本次学习机会，本邀请书即告无效。 </p>
          <p> 报到地点：北京市海淀区西三环北路19号 <br />
            电话：8610-8881-7857<br />
          </p>
          <div style="clear:both; border-top:1px dashed #999;">
          <dl>
              <dt>学生信息如下：</dd>
              <dd>姓名：<?=!empty($data['name'])?$data['name']:''?> </dd>
              <?php
                $sex = array(
                    '1' => '男',
                    '2' => '女'
                  );
              ?>
              <dd>性别：<?=!empty($data['sex'])?$sex[$data['sex']]:''?></dd>
              <dd>国籍：<?=!empty($data['nationality'])?$nationality[$data['nationality']]:''?> </dd>
              <dd>护照号码：<?=!empty($data['passport'])?$data['passport']:''?></dd>
              <dd>来华学习经费来源：<?=!empty($data['money'])?$data['money']:''?> </dd>
              <dd>&nbsp;</dd>
          </dl>
      </div>
      <div style="float:right;">北京外国语大学留学生办公室<br /><?=!empty($data['ltime'])?$data['ltime']:''?></div>
      <div style="clear:both;"></div>
  </div>
  
</div>
</body>
</html>
