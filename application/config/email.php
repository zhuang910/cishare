<?php
//$config['email']['mail_name']='世华易网'; 
$config['email']['protocol'] = 'smtp'; 
$config['email']['smtp_host'] = 'smtp.c2.corpease.net'; 
$config['email']['smtp_user'] = 'service@share.com';
$config['email']['smtp_pass'] = 'es_s-e-r-v-i-c-e';

$config['email']['_subject'] = 'ES. Service Team';
$config['email']['useragent'] = 'ES. Service Team';

$config['email']['smtp_port'] = '25'; 
$config['email']['smtp_timeout'] = '5';
$config['email']['mailtype'] = "html"; 
$config['email']['newline'] = "\r\n"; 
//$config['crlf'] = "\r\n"; 
$config['email']['charset'] = 'utf-8';
$config['email']['bcc_batch_size'] = 200;//批量暗送的邮件数.