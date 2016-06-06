<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mymail {
	
	/**
	*发送邮件
	*$sentTo接收邮件者array('gaozhiwei429@sina.com','763097909@qq.com') OR 'gaozhiwei429@sina.com,763097909@qq.com
	*$cc 抄送接收邮件者array() OR string
	*$bcc 暗送接收邮件者array() OR string
	*$content 发送邮件的内容 string
	**/
	function domail($sentTo,$title=null,$content=null,$attach=null,$reply_to=null,$cc=null,$bcc=null)//
	{
		$CI = & get_instance ();
		$CI->config->load('email');
		$config = $CI->config->item('email');
		$email_title = $title ? $title :'ZUST Service Team';//$_POST['title'] ? $_POST['title'] : '';
		$email_content = $content ? $content : '';//$_POST['content'] ? $_POST['content'] : 
        $CI->load->library('email');
        $CI->email->initialize($config); 
        $CI->email->from('service@cucas.cn');
		//$sentTo = array('gaozhiwei429@sina.com','763097909@qq.com','1258279121@qq.com');
        $CI->email->to($sentTo); //可以发送多个email
        $CI->email->reply_to('service@cucas.cn');
		$CI->email->cc($cc);//抄送
		$CI->email->bcc($bcc);//暗送
		if(!empty($attach)){
			$CI->email->attach($attach);///发送的图片
		}
		$CI->email->subject($email_title); 
        $CI->email->message($email_content);
        return  $CI->email->send(); 
         
       	//echo $CI->email->print_debugger();
   }
}
