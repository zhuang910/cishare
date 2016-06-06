<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sdyinc_email{

	private $CI;
	const T_M_D= 'mail_dot';
	const T_P_M_C='push_mail_config';

	function __construct() {
		$this->CI = & get_instance ();
	}

	/**
	 * 获取发送点内容
	 */

	function get_dot_content($id = 0){
		if($id !==0 ){
			return $this->CI->db->get_where(self::T_M_D,'id = '.$id,1,0)->row();
		}
	}
	/**
	 * 
	 * 按发送点发送
	 * */
	function dot_send_mail($id,$sentTo=null,$data = array(),$attachment = null){
		$info = $this->get_dot_content($id);
		if(!empty($info)){
			if(!empty($id)&&!empty($sentTo)){
			$str= $info->content;
			if(!empty($data)){
				foreach ($data as $k => $v) {
					$str=str_replace("{{$k}}",$v,$str);
				}
			}
			return $this->do_send_mail($sentTo,$info->addresserset,$info->theme,$str,$info->addresser,$attachment);
		}
		}
		
		return false;
	}
	/**
	 * 自定义发送
	 * */
	function do_send_mail($sentTo,$addressersetid,$title=null,$content=null,$reply_to=null,$attachment=null){
		
		// 获取配置
		$config = $this->_get_email_config($addressersetid);
		$this->CI->load->library('email');
        $this->CI->email->initialize($config); 
        $this->CI->email->from($config['smtp_user']);
        
        $this->CI->email->to($sentTo); //可以发送多个email
        $this->CI->email->reply_to($reply_to);
		$this->CI->email->cc(null);//抄送
		$this->CI->email->bcc(null);//暗送
		if(!empty($attachment)){
			$this->CI->email->attach($attachment);///发送的图片
		}
		$this->CI->email->subject($title); 
        $this->CI->email->message($content);
        return  $this->CI->email->send(); 
	}
	private function _get_email_config($id = 0){
			$data=$this->CI->db->get_where(self::T_P_M_C,'id = '.$id,1,0)->row_array();
			if(empty($data)){
				$data = CF('emailset','',CONFIG_PATH);
				$data=$data['defult'];
			}
			
			$config['protocol'] = 'smtp'; 
			$config['smtp_host'] = $data['smtp_host']; 
			$config['smtp_user'] = $data['smtp_user'];
			$config['smtp_pass'] = $data['smtp_pass']; 
			
			$config['smtp_port'] = $data['smtp_port']; 
			$config['smtp_timeout'] = '5';
			$config['mailtype'] = $data['mailtype']; 
			$config['newline'] = "\r\n"; 
			//$config['crlf'] = "\r\n"; 
			$config['charset'] = 'utf-8';
			$config['bcc_batch_size'] = 200;
			return $config;
	}


}
