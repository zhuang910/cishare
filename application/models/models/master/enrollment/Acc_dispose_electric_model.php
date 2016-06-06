<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Acc_dispose_electric_Model extends CI_Model {
	const T_ACC = 'accommodation_info';
	const T_STUDENT = 'student';
	const T_MAJOR = 'major';
	const T_SQUAD ='squad';
	const T_STUDENT_INFO='student_info';
	const T_INSUR='insurance_info';
	const T_ARTICLE='tuition_info';
	const T_C = 'credentials';
	const T_MESSAGE_LOG='message_log';
	const T_P_M_C='push_mail_config';
	const T_M_R='mail_record';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_USER_ROOM='user_room';
	const T_ACC_RECORD='accommodation_record';//住宿历表
	const T_OUT_ROOM='out_room';//退房表
	const T_ACC_HISTORY='accommodation_history';//历史记录表
	const T_LAN_INFO='landlord_info';
	const T_ROOM_ELE_RECORD='room_electric_record';//电费导入记录表
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($where=null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_PRICES )->count_all_results ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		$data= $this->db->order_by ( $orderby )->get ( self::T_PRICES )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save($data = array()) {
		if (! empty ( $data )) {
			if ($this->db->insert ( self::T_ROOM_ELE_RECORD, $data )) {
				return $this->db->insert_id ();
			}
		}
	}
	/**
	 * [get_room_user 查找该房间的学生]
	 * @return [type] [description]
	 */
	function get_room_user($roomid){
		if(!empty($roomid)){
			$this->db->where('roomid',$roomid);
			$this->db->where('acc_state',6);
			return $this->db->get(self::T_ACC)->result_array();
		}
	}
	/**
	 * [get_all_room 获取所有的房间
	 * @return [type] [description]
	 */
	function get_all_room(){
		return $this->db->get(self::T_PRICES)->result_array();
	}
	/**
	 * [get_all_room 获取所有的房间
	 * @return [type] [description]
	 */
	function get_one_room($id){
		$this->db->where('id',$id);
		return $this->db->get(self::T_PRICES)->row_array();
	}
	/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_campus_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_CAMPUS)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
		/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_buliding_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_BULIDING)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * 导出模板字段
	 */
	function get_room_fields(){
		return array(
			 'id'=>'房间id',
			 'columnid' =>  '校区名字',
			 'bulidingid' =>  '楼宇名字',
			 'floor' =>  '楼层',
			 'name' =>  '房间中文名',
			 'enname' =>  '房间英文名',
			 'starttime' =>  '电表记录开始时间',
			 'endtime' =>  '电表记录结束时间',
			 'spend' =>  '用电单位度',
			 'money' =>  '金额',
			);
	}
	/**
	 * [get_room_tochanel_data 模板数据]
	 * @return [type] [description]
	 */
	function get_room_tochanel_data(){
		$this->db->select('school_accommodation_prices.id,school_accommodation_prices.columnid,school_accommodation_prices.bulidingid,school_accommodation_prices.floor,school_accommodation_prices.name,school_accommodation_prices.enname,school_accommodation_buliding.name as bname,school_accommodation_campus.name as cname');
		$this->db->join(self::T_BULIDING,self::T_BULIDING . '.id=' . self::T_PRICES . '.bulidingid');
		$this->db->join(self::T_CAMPUS,self::T_CAMPUS . '.id=' . self::T_PRICES . '.columnid');
		return $this->db->get(self::T_PRICES)->result_array();
	}
}