<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * model: 申请管理模块
 *
 * @author Laravel
 *        
 */
class Tuition_model extends CI_Model {
	const T_APP = 'tuition_info';
	const T_COURSE = 'major';
	
	const T_ORDER = 'apply_order_info';
	const T_C = 'credentials';
	const T_MAJOR='major';
	const T_STU_NAT='student';
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取申请信息
	 * $where='' 搜索条件
	 * $orderby='' 排序字段
	 */
	function get_app($where = '', $orderby = 'apply_info.id desc') {
		$this->db->order_by ( $orderby );
		$this->db->select ( '*,apply_info.id as appid,apply_info.state as status' );
		$this->db->from ( self::T_APP );
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		$this->db->join ( 'major', 'apply_info.courseid=major.id' );
		$this->db->join ( 'student_info', 'apply_info.userid=student_info.id' );
		
		$list = $this->db->get ()->result ();
		
		return $list;
	}
	
	

	/**
	 * 获取申请信息
	 * $where='' 搜索条件
	 * $orderby='' 排序字段
	 */
	function get_app_1($where = '', $orderby = 'id ASC') {
		$this->db->order_by ( $orderby );
		$this->db->select ( '*' );
		$this->db->from ( self::T_APP );
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		
		$list = $this->db->get ()->result ();
	
		return $list;
	}
	/**
	 * 获取一条申请信息详情
	 */
	function get_one_app_detail($id = NULL){
		if ($id != null) {
	
			$this->db->select('*,apply_info.id as appid,apply_info.state as status');
			$this->db->from(self::T_APP);
			$this->db->where('apply_info.id',$id);
			$this->db->join('major','apply_info.courseid=major.id');
			$this->db->join('student_info','apply_info.userid=student_info.id');
	
			return $this->db->get()->row ();
		}
		return  false;
	}
	
	/**
	 * 附件下载
	 */
	function  get_attach($id = NULL){
		if ($id != null) {
			$this->db->select('apply_attachment.id as attid,apply_attachment.title as att_title,apply_attachment_info.truename,apply_attachment_info.url,apply_attachment_info.attachmentid');
			$this->db->from('apply_attachment_info');
			$this->db->where('apply_attachment_info.applyid',$id);
			$this->db->join('apply_attachment','apply_attachment_info.attachmentid=apply_attachment.id');
			return $list = $this->db->get()->result();
		}
		return false;
	}
	
	
	/**
	 * 获取申请操作日志
	 */
	function get_log($id = NULL){
		if ($id != null) {
			return $this->db->get_where ( self::T_LOG, 'appid = ' . $id)->result ();
		}
	}
	/**
	 * 获取 地址确认状态
	 *
	 * @param string $id        	
	 */
	function get_confirm_address_status($id = null) {
		if ($id != null) {
			return $this->db->get_where ( self::T_OFFER, 'appid = ' . $id, 1, 0 )->row ();
		}
	}
	
	/**
	 * 获得审核信息
	 */
	function get_app_infos($id = NULL) {
		if ($id != null) {
			$this->db->select ( 'apply_template_info.applyid,apply_template_info.keyid,apply_template_info.value' );
			return $this->db->get_where ( 'apply_template_info', 'applyid = ' . $id )->result ();
		}
		return FALSE;
	}
	
	function  get_apply_block(){
		return $this->db->get_where ( 'apply_block', 'id >0 ' )->result ();
	}
	
	function  get_apply_form(){
		return $this->db->get_where ( 'apply_form', 'id >0 ' )->result ();
	}
	
	
	function  get_apply_form_item(){
		return $this->db->get_where ( 'apply_form_item', 'id >0 ' )->result ();
	}
	
/**
 * 获取凭据表的信息
 */
	function get_proof(){
		return $this->db->get_where ( self::T_C, 'id >0 ' )->result_array ();
	}
	
	
	/**
	 * 更改操作的状态
	 */
	function update_app_flow_status($id = null,$data= array(),$action = null,$tips = null){
		if ($id != null && ! empty ( $data ) && is_array ( $data )) {
			$data['lasttime']=time();  //将最后更新时间加入待更新数组
			$result = $this->db->update ( self::T_APP, $data, 'id = ' . $id );
			if($result){
				$appresult = $this->db->get_where ( self::T_APP, 'id = ' . $id, 1, 0 )->row ();
				
				if(count($appresult)>0){
					$data_history= array(
									'appid'=>$appresult->id,
									'events'=>$action,
									'lasttime'=>time(),
									'operater'=> 1,
									'remark'=> $tips
					);
					return $this->db->insert ( self::T_LOG, $data_history); //往前端插入操作日志
				}else{
					return false;
				}
			}else{
				return false;	
			}
		}
		return false;
	}
	/**
	 *获取apply_info的所有专业
	 *
	 *
	 **/
	function get_major(){
		$this->db->select('major.id as id,major.name as name');
		$this->db->group_by('apply_info.courseid');
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_APP.'.courseid');
		return $this->db->get(self::T_APP)->result_array();
	}
	/**
	 *获取apply_info的所有学生的国籍
	 *
	 *
	 **/
	function get_nationality(){
		$this->db->select('student_info.nationality as guoji');
		$this->db->group_by('student_info.nationality');
		$this->db->join(self::T_STU_NAT ,self::T_STU_NAT.'.id='.self::T_APP.'.userid');
		$data =$this->db->get(self::T_APP)->result_array();
		$guoji=array();
		$guojiarr= CF ( 'nationality', '', 'application/cache/' );
		
		foreach ($data as $key => $value) {
			$guoji[$value['guoji']]=$guojiarr[$value['guoji']];
		}
		return $guoji;
	}
	/**
	 *
	 *获取申请字段
	 **/
	function get_app_fields(){
		$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_apply_info"';
		$query=$this->db->query($sql);
		$f=$query->result();
		$data=array();
		foreach ($f as $k => $v) {
			$data[$v->name]=$v->comment;
		}
		unset($data['id']);
		return $data;
	}
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into sdyinc_apply_info ('.$insert.') values('.$value.')';
		$this->db->query($sql);
	}

		/**
	 * 获取专业id
	 */
	function get_majorid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_MAJOR)->row_array();
		return $data['id'];
	}

	function get_H($s){
		switch ($s) {
			case '未支付':
				return 0;
				break;
			case '成功':
				return 1;
				break;
				case '失败':
				return 2;
				break;
			case '待确认':
				return 3;
				break;
		}
	}


	function get_J($s){
		switch ($s) {
			case 'paypal':
				return 1;
				break;
			case 'payease':
				return 2;
				break;
				case '凭据':
				return 3;
				break;
		}
	}


	function get_is($s){
		switch ($s) {
			case '是':
				return 1;
				break;
			case '否':
				return 0;
				break;
		}
	}

		function get_R($s){
		switch ($s) {
			case '审核中':
				return 1;
				break;
			case '打回':
				return 2;
				break;
			case '打回提交':
				return 3;
				break;
			case '拒绝':
				return 4;
				break;
			case '调剂':
				return 5;
				break;
			case '预录取':
				return 6;
				break;
			case '录取':
				return 7;
				break;
			case '已入学':
				return 8;
				break;
			case '结束':
				return 9;
				break;
		}
	}

		function get_S($s){
		switch ($s) {
			case '未确认':
				return -1;
				break;
			case '确认':
				return 1;
				break;
		}
	}


	function get_U($s){
		switch ($s) {
			case '未交':
				return -1;
				break;
			case '已交':
				return 1;
				break;
		}
	}

	function get_X($s){
		switch ($s) {
			case '是':
				return 1;
				break;
			case '否':
				return 0;
				break;
		}
	}

	function get_Z($s){
		switch ($s) {
			case '是':
				return -1;
				break;
			case '否':
				return 0;
				break;
		}
	}
	function get_student_id($chname,$email){
		$this->db->where('email',$email);
		$this->db->where('chname',$chname);
		$data=$this->db->get(self::T_STU_NAT)->row_array();
		return $data['id'];
	}	

	function check_app($studentid,$majorid,$app_time){
		$this->db->select('count(*) as num');
		$this->db->where('userid',$studentid);
		$this->db->where('courseid',$majorid);
		$this->db->where('applytime',$app_time);
		$data=$this->db->get(self::T_APP)->row_array();
		return $data['num'];
	}
}