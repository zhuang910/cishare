<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sdyinc_message{

	private $CI;
	const T_MESSAGE_DOT= 'message_dot';
	const T_MESSAGE='message';
	const T_MESSAGE_RECORD='message_record';
	const T_USER_MESSAGE='user_message';
	function __construct() {
		$this->CI = & get_instance ();
	}
	/**
	 * 获取发送点内容
	 */
	function get_dot_content($id = 0){
		if($id !==0 ){
			return $this->CI->db->get_where(self::T_MESSAGE_DOT,'id = '.$id,1,0)->row();
		}
	}
	/*
	 *发送点发送消息接口
	 */
	function dot_send_message($studentid,$dotid){
		$info = $this->get_dot_content($dotid);

		$mid=$this->send_message($studentid,$info->title,$info->content);
		$pid=$this->message_record($mid,2,$info->title);	
		return $this->user_message($studentid,$pid);
	}
	/*
	 *保存主表消息
	 */
   function send_message($studentid,$title,$content){
	   	$data['studentid']=$studentid;
	   	$data['title']=$title;
	   	$data['content']=$content;
	   	$data['sendtime']=time();
	   	$this->CI->db->insert ( self::T_MESSAGE, $data );
	   	return $this->CI->db->insert_id ();
   }
   /*
	 *保存记录
	 */
   function message_record($mid,$type=1,$title){
   		$data['messageid']=$mid;
   		$data['type']=$type;
   		$data['title']=$title;
   		$this->CI->db->insert ( self::T_MESSAGE_RECORD, $data );
   		return $this->CI->db->insert_id ();
   }

	/***
		保存用户消息
	*/
	function user_message($studentid,$rid){
		$data['studentid']=$studentid;
		$data['recordid']=$rid;
		$this->CI->db->insert ( self::T_USER_MESSAGE, $data );
	}

	/*
		自定义发送消息接口
	 */
	function custom_message($studentid,$title,$content){
		$mid=$this->send_message($studentid,$title,$content);
		$pid=$this->message_record($mid,1,$title);	
		return $this->user_message($studentid,$pid);
	}
	/**
	 *@studentid:学生id
	 *@state:消息状态
	 *获取学生消息接口
	 **/
	function get_student_message($studentid,$state=0){
			$this->CI->db->select('user_message.id as uid,user_message.studentid,user_message.readstate,user_message.readtime,message_record.title,message_record.messageid,message.content,message.sendtime');
			$this->CI->db->where('user_message.studentid',$studentid);
			if($state!=0){

				$this->CI->db->where('readstate',$state);
			}
			$this->CI->db->where('delete',2);
			$this->CI->db->join(self::T_MESSAGE_RECORD,self::T_MESSAGE_RECORD . '.id=' . self::T_USER_MESSAGE . '.recordid');
			$this->CI->db->join(self::T_MESSAGE,self::T_MESSAGE . '.id=' . self::T_MESSAGE_RECORD . '.messageid');
			$this->CI->db->order_by("user_message.id", "desc"); 
			return $this->CI->db->get(self::T_USER_MESSAGE)->result_array();
	}
	/**
	 *@studentid:学生id
	 *@state:消息状态
	 *获取学生消息接口
	 **/
	function get_student_message_all($studentid,$state=0){
			$this->CI->db->select('user_message.id as uid,user_message.studentid,user_message.readstate,user_message.readtime,message_record.title,message_record.messageid,message.content,message.sendtime');
			$this->CI->db->where('user_message.studentid',$studentid);
			if($state!=0){

				$this->CI->db->where('readstate',$state);
			}
			$this->CI->db->where('delete',2);
			$this->CI->db->join(self::T_MESSAGE_RECORD,self::T_MESSAGE_RECORD . '.id=' . self::T_USER_MESSAGE . '.recordid');
			$this->CI->db->join(self::T_MESSAGE,self::T_MESSAGE . '.id=' . self::T_MESSAGE_RECORD . '.messageid');
			$this->CI->db->order_by("user_message.id", "desc"); 
			$data= $this->CI->db->get(self::T_USER_MESSAGE)->result_array();
			for ($i=0; $i <6 ; $i++) { 
				unset($data[$i]);
			}
			$arr=array();
			foreach ($data as $k => $v) {
				foreach ($v as $kk => $vv) {
					if($kk=='sendtime'){
						$arr[$k][$kk]=date('Y-m-d H:s:i',$vv);
					}else{
						$arr[$k][$kk]=$vv;
					}
				}
			}

			return $arr;
	}
	/**
	 *
	 *读取学生消息
	 **/
	function read_student_message($id){
		$data=$this->CI->db->where('id',$id)->get(self::T_USER_MESSAGE)->row_array();
		if($data['readstate']==2){
			$data['readstate']=1;
			$data['readtime']=time();
			$this->CI->db->update ( self::T_USER_MESSAGE, $data, 'id = ' .$id );
			return 1;
		}
		return 0;
	}
	/**
	 *
	 *虚拟删除学生消息
	 **/
	function dummy_del_stu_message($id){
			$data['delete']=1;
			$this->CI->db->update ( self::T_USER_MESSAGE, $data, 'id = ' .$id );
			return 1;
	}
	
}
