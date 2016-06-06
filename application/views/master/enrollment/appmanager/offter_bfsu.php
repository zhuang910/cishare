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

.en{ font-family: Verdana, Geneva, sans-serif;}
.en h1{ font-size:20px; line-height:50px;}
.en p{line-height:26px; margin-bottom:30px;}
.en h2{font-size:26px;line-height:40px;}

.en div{font-size:16px; margin-bottom:30px;}

dt{ font-size:16px; font-weight:bold;}
dd{ line-height:26px; font-size:16px;}
</style>
</head>
<body>
<?php 
  $ctype = array(
    '1' => '长期进修生',
    '2' => '短期进修生'
    );
  $etype = array(
    '1' => 'Long-term student',
    '2' => 'Short-term student'
    );
  $clang = array('1' => '汉语' ,'2' => '英语');
  $elang = array('1' => 'Chinese' ,'2' => 'English');
  $cm = array(
              '22' => '长期进修',
              '23' => '短期进修',
              '24' => '寒暑假进修',
              '25' => '夏令营/冬令营',
              '26' => '商务汉语',
              '27' => '汉语翻译',
              '28' => 'HSK系列课程',
              '29' => '团体项目',
    );
$em = array(
              '22' => 'Long-term Programs',
              '23' => 'Short-term Programs',
              '24' => 'Summer/Winter Vacation Programs',
              '25' => 'Summer Camp/Winter Camp',
              '26' => 'Business Chinese',
              '27' => 'Chinese Translation',
              '28' => 'HSK Training Programs',
              '29' => 'Group Programs',
    );
  

?>
<div style="width:649px;height:1956px; margin:0 auto;">
    <div style="width:100%;height:978px; margin:30px auto 0px; position:relative;" class="cn">
        <h1 style="margin:0 auto;width:100%;display:block; text-align:center;">北 京 外 国 语 大 学</h1>
        <h2 style="margin:0 auto;width:100%;display:block; text-align:center;">录 取 通 知 书</h2>
        <div style="margin-bottom:160px;">
            <p><?=!empty($data['name'])?$data['name']:''?> 先生：</p>
            <p>我们高兴地通知您，经审查您的申请材料，我校决定录取您作为<?=!empty($data['stype'])?$ctype[$data['stype']]:''?>，自 <?=!empty($data['opening'])?$data['opening']:''?> 起至 
              <?=!empty($data['xxendtime'])?$data['xxendtime']:''?> 到我校培训学院学习<?=!empty($data['columnid'])?$cm[$data['columnid']]:''?>，授课语言为 <?=!empty($data['language'])?$clang[$data['language']]:''?>。</p>
            <p>在华期间，您的一切费用自理，其中学费 <?=!empty($data['tuition'])?$data['tuition'].'元人民币/学期':''?>  ；<?=!empty($data['tuition1'])?$data['tuition1'].'元人民币/学年':''?>  。</p>
            <p>  如果您自愿遵守中国的法律、法规和学校的校纪、校规，请您持本《录取通知书》、《外国留学
              人员来华签证申请表》(JW202)表、《外国人体格检查记录》及血液化验报告（均为原件），前往中国
              大使馆（领事馆）申请来华学习，并于<?=!empty($data['otime'])?$data['otime']:''?>  至<?=!empty($data['etime'])?$data['etime']:''?>  期间，持上述证明到我校报到
              ，因故不能按期报到，必须事先征得我校同意。否则，将视为自动放弃入学资格。
             </p>
        </div>
        <div style="width:50%; float:left;">学生本人签字_______________<br />年 月 日</div>
        <div style="width:50%; float:left;">北京外国语大学<br /><?=!empty($data['ltime'])?$data['ltime']:''?> </div>
        <div style="clear:both; border-top:1px dashed #999;">
            <dl>
                <dt>注意事项：</dt>
                <dd>1、请您一定持来华学习签证入境。否则，一切后果由本人自负。</dd>
                <dd>2、请准备8张照片（与本人护照照片一致）。</dd>
                <dd>3、入境后，请立即到学校办理报到手续，并在留学生办公室登记签证信息，如个人原因导致签证过期，所受处罚的一切费用自理。</dd>
            </dl>
            <div>
              地 址： 北京市海淀区西三环北路19号留学生办公室<br />
              电 话： 8610-8881-6549<br />
              Email： wsclxb@bfsu.edu.cn<br />
            </div>
          <div style="position:absolute;right:0px;top:20px; text-align:center;"><img src="<?=!empty($data['qrcode'])?$data['qrcode']:''?>" style="height:180px; width:180px;"/><span style="display:block;"><?=!empty($data['idnum'])?$data['idnum']:''?></span></div>
        </div>
    </div>
  <div style="width:100%;height:978px; margin:30px auto 0px; position:relative;" class="en">
      <h1 style="margin:0 auto;width:100%;display:block; text-align:center;"> Beijing Foreign Studies University</h1>
  <h2 style="margin:0 auto;width:100%;display:block; text-align:center;">ADMISSION NOTICE</h2>
      <div style="margin-bottom:160px;">
      <p>Dear <?=!empty($data['name'])?$data['name']:''?></p>
      <p> We are pleased to inform you that, having examined your application materials, We have
        decided to enroll you to study <?=!empty($data['columnid'])?$em[$data['columnid']]:''?> at the Training College of our university as a
        <?=!empty($data['stype'])?$etype[$data['stype']]:''?> from<?=!empty($data['opening'])?$data['opening']:''?> to  <?=!empty($data['xxendtime'])?$data['xxendtime']:''?>. All classes will be taught
        in  <?=!empty($data['language'])?$elang[$data['language']]:''?>.
      </p>
      <p>You’re enrolled to the University as a self-funded student.<br />
        <?=!empty($data['tuition'])?' Tution fee per semester :'.$data['tuition']:''?>  ； <?=!empty($data['tuition1'])?'RMB Tution fee per year :'.$data['tuition1']:''?>  RMB.</p>
      <p> If you observe the laws and decrees of China as well as the rules and regulations of the
        institution you attend, you can apply for student visa to the Chinese embassy or consulate in your
        country with this Admission Notice, the original copies of your Visa Application for Study in China
        (JW202), Foreigner Physical Examination Form and your blood test report. Please note that you
        must register, with these materials, at Beijing Foreign Studies University from <?=!empty($data['otime'])?$data['otime']:''?>  to <?=!empty($data['etime'])?$data['etime']:''?>. If you fail to register within the time limit without the permission of the institution, you will
        be regarded as giving up this admission.
      </p>
    </div>
    <div style="width:50%; float:left;">Applicant’s signature:<br />Date:</div>
    <div style="width:50%; float:left;">Beijing Foreign Studies University<br /><?=!empty($data['ltime'])?$data['ltime']:''?></div>
    <div style="clear:both; border-top:1px dashed #999;">
          <dl>
            <dt>Note:</dt>
            <dd>1.Be sure to enter China with student visa, otherwise you will be responsible for all the possible consequences.</dd>
            <dd>2.Please prepare eight passport-size photos.</dd>
            <dd>3.Please do all the procedure of registration to our university and register your visa information at the
            Overseas Students Affairs Office. Otherwise you will be responsible for the consequences of visa
            expiration.</dd>
          </dl>
          <div>
            Address: Overseas Students Affairs Office 19 North Xisanhuan Avenue, Beijing 100089, P.R. China<br />
            Telephone:008610-8881-6549<br />
            Email: wsclxb@bfsu.edu.cn
          </div>
          <div style="position:absolute;right:0px;top:0px; text-align:center;"><img src="<?=!empty($data['qrcode'])?$data['qrcode']:''?>" style="height:180px; width:180px;"/><span style="display:block;"><?=!empty($data['idnum'])?$data['idnum']:''?></span></div>
    </div>
</div>
</div>
</body>
</html>
