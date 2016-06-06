<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * model: 申请管理模块
 *
 * @author Laravel
 *        
 */
class Appmanager_model extends CI_Model {
	const T_APP = 'applyscholarship_info';
	const T_COURSE = 'scholarship_info';
	
	const T_STU_NAT='student_info';
	
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
	function get_app($where = '', $orderby = 'applyscholarship_info.id desc') {
		$this->db->order_by ( $orderby );
		$this->db->select ( '*,applyscholarship_info.id as appid,applyscholarship_info.state as status' );
		$this->db->from ( self::T_APP );
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		$this->db->join ( 'scholarship_info', 'applyscholarship_info.scholarshipid=scholarship_info.id' );
		$this->db->join ( 'student_info', 'applyscholarship_info.userid=student_info.id' );
		
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
	 * 获取申请操作日志
	 */
	function get_logs($id = NULL){
		if ($id != null) {
			return $this->db->get_where ( self::T_L, $id)->result ();
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
		return $this->db->get_where ( self::T_C, 'id >0 AND ordertype = 1' )->result_array ();
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
		return array(
			 'number' =>  '申请号',
			  'userid' =>  '用户ID',
			  'courseid' =>  '专业ID',
			  'ordernumber' =>  '订单号',
			  'tuition' =>  '学费',
			  'applytime' =>  '申请时间' ,
			  'registration_fee' =>  '申请费',
			  'paystate' =>  '支付状态 0未支付 1成功 2失败 3待确认' ,
			  'paytime' =>  '支付时间' ,
			  'paytype' =>  '支付方式 1paypal 2payease 3凭据' ,
			  'isstart' =>  '是否开始 1是 0否' ,
			  'isinformation' =>  '资料是否完成 0否 1是' ,
			  'isatt' =>  '附件是否完成 1是 0否' ,
			  'issubmit' =>  '是否提交 1是 0否' ,
			  'issubmittime' =>  '提交时间' ,
			  'isproof' =>  '是否为凭据用户 1是 0否' ,
			  'lasttime' =>  '最后操作时间' ,
			  'state' =>  '申请状态 1 审核中 2 打回 3 打回提交 4 拒绝 5 调剂 6 预录取 7 录取 8 已入学 9 结束' ,
			  'addressconfirm' =>  '地址是否确认 -1 未确认 1确认' ,
			  'address_ctime' =>  '地址确认时间' ,
			  'deposit_state' =>  '交押金状态 -1 未交 1 已交' ,
			  'tips' =>  '进度提示' ,
			  'opening' =>  '开学时间' ,
			  'isscholar' =>  '是否是 奖学金 1 是 0 否' ,
			  'remark' =>  '备注',
			  'pagesend_status' =>  '是否发送纸质offer -1未发送 1 发送' ,
			  'e_offer_status' =>  'e_offer是否发送 -1 未发送 1发送' ,
			  'pagesend_time' =>  '发送纸质时间' ,
			  'confirm_admission' =>  '是否确认入学 -1 未确认 1 确认' ,
			  'scholorshipid' =>  '奖学金id' ,
			  'scholorstate' =>  '0 待审核 1 通过 2 不通过' ,
			  'validfrom' =>  '护照开始时间' ,
			  'validuntil' =>  '护照截至时间' ,
			);
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
		$data=$this->db->get(self::T_STU_NAT)->row_array();
		if(!empty($data['id'])){
			$arr['chname']=$chname;
			$this->db->update ( self::T_STU_NAT, $arr, 'id = ' . $data['id']);
		}
		return $data['id'];
	}	
	function insert_student_info($n,$e){
		$data['chname']=$n;
		$data['email']=$e;
		$data['password']=substr ( md5 ( '123456' ), 0, 27 );
		$this->db->insert ( self::T_STU_NAT, $data );
		return $this->db->insert_id ();
	}	
	function check_app($studentid,$majorid,$app_time){
		$this->db->select('count(*) as num');
		$this->db->where('userid',$studentid);
		$this->db->where('courseid',$majorid);
		$this->db->where('applytime',$app_time);
		$data=$this->db->get(self::T_APP)->row_array();
		return $data['num'];
	}
	/**
	 * 获取申请学生的信息
	 */
	function get_student_one_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_STU_NAT)->row_array();
		}
		return array();
	}
	//获取国家的名字
	function get_nationality_name($id){
		$data=CF('public','',CACHE_PATH);
		if(!empty($id)){
			return $data['global_country_cn'][$id];
		}
		return false;
	}
	/**
	 * 获得专业名字       	
	 */
	function get_majorname($id){
		$data= $this->db->where('id',$id)->get ( self::T_MAJOR)->row_array ();
		return $data['name'];
	}
	/**
	 * 获取一条专业的信息
	 */
	function get_major_info_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get ( self::T_MAJOR)->row_array ();
		}
		return array();
	}
	//获取一条申请信息
	function get_one_app_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_APP)->row_array();
		}
		return array();
	}
	/**
	 * [pay_change_state 现场缴费修改状态]
	 * @param  [array] $data [修改数据]
	 * @return [type]       [Boolean]
	 */
	function pay_change_state($data){
		$arr=array();
		if(!empty($data)){
			$arr['paytime']=time();
			$arr['danwei']=$data['currency'];
			$arr['registration_fee']=$data['amount'];
			$arr['paystate']=1;
			$this->db->update ( self::T_APP, $arr, 'id = ' . $data['id']);
			return true;
		}
		return flase;
	}
	/**
	 * [insert_pay_record 插入缴费记录]
	 * @param  [array] $data [插入数据]
	 * @return [type]       [Boolean]
	 */
	function insert_pay_record($data){
		if(!empty($data)){
			unset($data['id']);
			$data['state']=1;
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert ( self::T_C, $data);
			return true;
		}
		return flase;
	}	
}