<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Educe_Model extends CI_Model {
	const T_MAJOR='major';
	const T_FACULTY='faculty';
	const T_DEGREE_INFO='degree_info';
	const T_COURSE='course';
	const T_SCORE='score';
	const T_MAJOR_COURSE='major_course';
	const T_SQUAD='squad';
	const T_STUDENT='student';
	const T_CHECKING='checking';
	const T_TEACHER_COURSE='teacher_course';
	const T_TEACHER='teacher';
	const T_TEMPLATECLASS='templateclass';
	const T_ATTAPIC='attachmentstopic';
	const T_FORMTOPIC='formtopic';
	const T_APPLY_TEMPLATE_INFO='apply_template_info';
	const T_COURSE_CONTENT='course_content';
	const T_COURSE_IMAGES='course_images';
	const T_ATTACHMENTS='attachments';
	const T_SHIP='scholarship_info';

	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}

	
	/**
	 * 获取专业字段1
	 */
	function get_fields(){
		$arr=array(
		 'id' =>  'ID' ,
		  'name' =>  '名称' ,
		  'facultyid' =>  '学院id' ,
		  'englishname' =>  '英文名字' ,
		  'alias' =>  '别名' ,
		  'degree' =>  '学历id' ,
		  'termnum' =>  '学期数' ,
		  'termdays' =>  '学期天数' ,
		  'coursenum' =>  '课程总数',
		  'squadnum' =>  '班级总数',
		  'state' =>  '是否停用1是0否',
		  'opentime' =>  '专业指定开学时间',
		  'endtime' =>  '请指定申请截止日期',
		  'regtime' =>  '指定注册时间',
		  'schooling' =>  '指定学制',
		  'xzunit' =>  '学制的单位 ',
		  'tuition' =>  '请指定学费',
		  'applytuition' =>  '请指定申请费',
		  'language' =>  '授课语言 ',
		  'hsk' =>  'hsk要求' ,
		  'minieducation' =>  '最低学历要求' ,
		  'isapply' =>  '是否可申请' ,
		  'attatemplate' =>  '请指定附件模板' ,
		  'applytemplate' =>  '请指定申请表模板' ,
		  'scholarship' =>  '关联奖学金' ,
		  'createtime' =>  '创建时间' ,
		  'difficult' =>  '录取难度' ,
		  'cashpledge' =>  '押金' ,
		  'isdeposit' =>  '押金 1 需要 -1 不需要' ,
		  'ispageoffer' =>  '纸质offer 1 需要 -1 不需要' ,
		  'video' =>  '视频地址' ,
		  'orderby' =>  '排序',
		  );
		return $arr;
	}
	/**
	 * 获取专业信息
	 */
	function get_major_info(){
		$this->db->select('id,name');
		return $this->db->get(self::T_MAJOR)->result_array();
	}
		/**
	 * 获取课程字段
	 */
	// function get_course_fields(){
	// 	$sql='SELECT column_name as name,column_comment as comment FROM Information_schema.columns where table_name="sdyinc_course"';
	// 	$query=$this->db->query($sql);
	// 	$f=$query->result();
	// 	$data=array();
	// 	foreach ($f as $k => $v) {
	// 		$data[$v->name]=$v->comment;
	// 	}
	// 	unset($data['id']);
	// 	return $data;
	// }
		function get_course_fields(){
			$arr=array(
				 'name' =>  '课程名' ,
				  'englishname' =>  '英文名字' ,
				  'hour' =>  '课时' ,
				  'absenteeism' =>  '缺勤通知线' ,
				  'expel' =>  '开除通知线' ,
				  'variable' =>  '是否选修' ,
				  'state' =>  '状态(是否启用)' ,
				 );
		return $arr;
	}
		/**
	 * 获取成绩字段
	 */
	function get_score_fields(){
		$arr=array(
		 'id' =>  '自增ID' ,
		  'studentid' =>  '学生id' ,
		  'majorid' =>  '专业id' ,
		  'courseid' =>  '课程id' ,
		  'squadid' =>  '班级id' ,
		  'term' =>  '学期' ,
		  'score' =>  '分数' ,
		  'scoretype' =>  '考试类型' ,
		  'email' =>  '学生邮箱' ,
		  );
		unset($arr['adminid']);
		return $arr;
	}
		/**
	 * 
	 * 获取考勤字段
	 * */
	function get_check_fields(){
		$arr=array(
			 'id' =>  '自增ID' ,
			  'studentid' =>  '学生id' ,
			  'majorid' =>  '专业id' ,
			  'teacherid' =>  '老师id' ,
			  'courseid' =>  '课程id' ,
			  'squadid' =>  '班级id' ,
			  'nowterm' =>  '当前学期' ,
			  'date' =>  '日期' ,
			  'type' =>  '类别0 正点 1缺勤 2早退 3迟到4请假' ,
			  'week' =>  '当前星期' ,
			  'knob' =>  '当前节课' ,
			  'email' =>  '学生邮箱' ,
			  'remark' =>  '备注' ,
			  );
		return $arr;
	}
	/**
	 * 
	 * 获取major数据
	 * */
	function get_all_major($data){
		$str='';
		foreach ($data as $key => $value) {
			$str.=$key.',';

		}
		$str=trim($str);
		$this->db->select($str);
		return $this->db->get(self::T_MAJOR)->result_array();
	}
	/**
	 * 
	 * 获取学院名称
	 * */
	function get_facultyname($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_FACULTY)->row_array();
		return $data['name'];
	}
	/**
	 * 获取学历名称
	 */
	function get_degreename($id){
		$this->db->select('title');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_DEGREE_INFO)->row_array();
		return $data['title'];
	}
	/**
	 * 
	 * 获取score数据
	 * */
	function get_all_score($data){
		$str='';
		foreach ($data as $key => $value) {
			$str.=$key.',';

		}
		$str=trim($str);
		$this->db->select($str);
		return $this->db->get(self::T_SCORE)->result_array();
	}
	/**
	 * 
	 * 获取学生名称
	 * */
	function get_studentname($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['name'];
	}
	/**
	 * 
	 * 获取专业名称
	 * */
	function get_majorname($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_MAJOR)->row_array();
		return $data['name'];
	}
	/**
	 * 
	 * 获取课程名称
	 * */
	function get_coursename($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_COURSE)->row_array();
		return $data['name'];
	}
	/**
	 * 
	 * 获取班级名称
	 * */
	function get_squadname($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_SQUAD)->row_array();
		return $data['name'];
	}

	/**
	 *获取考试类型 
	 * */
	function get_scoretype($id){
		$scoretype=CF('scoretype','',CONFIG_PATH);
		return $scoretype[$id];
	}
	/**
	 * 
	 * 获取老师名称
	 * */
	function get_teachername($id){
		$this->db->select('name');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_TEACHER)->row_array();
		return $data['name'];
	}
	/**
	 * 
	 * 获取考勤类别编号
	 * */
	function get_checktype($str){
		$str=trim($str);
		switch ($str)
			{
			case 0 :
			  return '正点';
			  break;  
			case 1 :
			  return '缺勤';
			  break;
			case 2 :
			  return '早退';
			  break;
			case 3 :
			  return '迟到';
			  break;
			case 4 :
			  return '请假';
			  break;
		
			}
	}
	/**
	 * 
	 * 获取week
	 * */
	function get_week($str){
			switch ($str)
			{
			case 1:
			  return '星期一';
			  break;  
			case 2:
			  return '星期二';
			  break;
			case 3:
			  return '星期三';
			  break;
			case 4:
			  return '星期四';
			  break; 
			case 5:
			  return '星期五';
			  break;
			case 6:
			  return '星期六';
			case 7:
		      return'星期日' ;
			  break;
		
			}
	}
	/**
	 * 
	 * 获取配置hour
	 * */
	function get_hour($v){
		$str='';
			$s=$v*2-1;
			$e=$v*2;
			$str.= $s.'-'.$e.'节课';
		return $str;
	}
	/**
	 * 
	 * 获取checking数据
	 * */
	function get_all_checking($data){
		$str='';
		foreach ($data as $key => $value) {
			$str.=$key.',';

		}
		$str=trim($str);
		$this->db->select($str);
		return $this->db->get(self::T_CHECKING)->result_array();
	}
	/**
	 * 
	 * 获取课程信息
	 * */
	function get_courseinfo(){
		$this->db->select('id,name');
		return $this->db->get(self::T_COURSE)->result_array();
	}
	/**
	 * 
	 * 获取课程模板
	 * */
	function get_attatemplateinfo($id){
		$this->db->select('applytemplate');
		$this->db->where('id',$id);
		$data= $this->db->get(self::T_COURSE)->row_array();
		$this->db->select('tClass_id,ClassName,des');
		$this->db->where('tClass_id',$data['applytemplate']);
		return $this->db->get(self::T_TEMPLATECLASS)->row_array();
	}
	/**
	 * 
	 * 获取申请页
	 * */
	function get_shenqing1($classid){
		$this->db->select('tClass_id,ClassName,des');
		$this->db->where('parent_id',$classid);
		$this->db->where('classType',2);
		return $this->db->get(self::T_TEMPLATECLASS)->result_array();

	}
	/**
	 * 
	 * 获取申请群
	 * */
	function get_shenqing2($data){
		$arr=array();
		foreach ($data as $key => $value) {
			$this->db->select('tClass_id,ClassName,des');
			$this->db->where('parent_id',$value['tClass_id']);
			$this->db->where('classType',3);
			$arr[$value['tClass_id']]=$this->db->get(self::T_TEMPLATECLASS)->row_array();
		}
			
		return $arr;
	}
	/**
	 * 
	 * 获取表单项
	 * */
	function get_shenqing3($data){
		$arr=array();
		foreach ($data as $k => $v) {
				$this->db->select('formTitle,topic_id,formID');
				$this->db->where('Class_id',$v['tClass_id']);
				$arr[$v['tClass_id']]=$this->db->get(self::T_FORMTOPIC)->result_array();
			
		}
		return $arr;
	}
	/**
	 * 
	 * 获取申请数据
	 * */
	function get_all_shenqing($data){
		$arr=array();
		foreach ($data as $key => $value) {
			$this->db->select('value');
			$this->db->where('key',$value);
			$data=$this->db->get(self::T_APPLY_TEMPLATE_INFO)->row_array();
			$arr[]=!empty($data['value'])?$data['value']:'null';
		}
		return $arr;
	}
function bc(){
	return array(
		  0 => 'A1' ,
		  1 => 'B1' ,
		  2 => 'C1' ,
		  3 => 'D1' ,
		  4 => 'E1' ,
		  5 => 'F1' ,
		  6 => 'G1' ,
		  7 => 'H1' ,
		  8 => 'I1' ,
		  9 => 'J1' ,
		  10 => 'K1' ,
		  11 => 'L1' ,
		  12 => 'M1' ,
		  13 => 'N1' ,
		  14 => 'O1' ,
		  15 => 'P1' ,
		  16 => 'Q1' ,
		  17 => 'R1' ,
		  18 => 'S1' ,
		  19 => 'T1' ,
		  20 => 'U1' ,
		  21 => 'V1' ,
		  22 => 'W1' ,
		  23 => 'X1' ,
		  24 => 'Y1' ,
		  25 => 'Z1',
		  26 => 'AA1' ,
		  27 => 'AB1' ,
		  28 => 'AC1' ,
		  29 => 'AD1' ,
		  30 => 'AE1' ,
		  31 => 'AF1' ,
		  32 => 'AG1' ,
		  33 => 'AH1' ,
		  34 => 'AI1' ,
		  35 => 'AJ1' ,
		  36 => 'AK1' ,
		  37 => 'AL1' ,
		  38 => 'AM1' ,
		  39 => 'AN1' ,
		  40 => 'AO1' ,
		  41 => 'AP1' ,
		  42 => 'AQ1' ,
		  43 => 'AR1' ,
		  44 => 'AS1' ,
		  45 => 'AT1' ,
		  46 => 'AU1' ,
		  47 => 'AV1' ,
		  48 => 'AW1' ,
		  49 => 'AX1' ,
		  50 => 'AY1' ,
		  51 => 'AZ1',
		  51 => 'BA1' ,
		  52 => 'BB1' ,
		  53 => 'BC1' ,
		  54 => 'BD1' ,
		  55 => 'BE1' ,
		  56 => 'BF1' ,
		  57 => 'BG1' ,
		  58 => 'BH1' ,
		  59 => 'BI1' ,
		  60 => 'BJ1' ,
		  61 => 'BK1' ,
		  62 => 'BL1' ,
		  63 => 'BM1' ,
		  64 => 'BN1' ,
		  65 => 'BO1' ,
		  66 => 'BP1' ,
		  67 => 'BQ1' ,
		  68 => 'BR1' ,
		  69 => 'BS1' ,
		  70 => 'BT1' ,
		  71 => 'BU1' ,
		  72 => 'BV1' ,
		  73 => 'BW1' ,
		  74 => 'BX1' ,
		  75 => 'BY1' ,
		  76 => 'BZ1',
		  77 => 'CA1',
		  78 => 'CB1',
		  ) ;
}
	function get_all_course($data){
		$str='';
		foreach ($data as $key => $value) {
			if($value=='line1'||$value=='line2'){
				continue;
			}
			$str.=$value.',';

		}
		
		$str=trim($str,',');
		$this->db->select('id,'.$str);
		return $this->db->get(self::T_COURSE)->result_array();
	}

	function get_all_course_content($data,$id){
		$str='';
		foreach ($data as $key => $value) {
			$str.=$value.',';
		}
		
		$str=trim($str,',');
		$this->db->select($str);
		$this->db->where('courseid',$id);
		return $this->db->get(self::T_COURSE_CONTENT)->row_array();
	}

	function get_all_course_images($data,$id){
		$str='';
		foreach ($data as $key => $value) {
			if($value=='imaorder'){
				$value='orderby';
			}
			if($value=='ima_language'){
				$value='site_language';	
			}
			$str.=$value.',';
		}
		
		$str=trim($str,',');

		$this->db->select($str);
		$this->db->where('courseid',$id);
		return $this->db->get(self::T_COURSE_IMAGES)->row_array();
	}
	/**
	 * 
	 * 获取申请表
	 * */
	function get_applytemplate($id){
		$this->db->select('ClassName');
		$this->db->where('tClass_id',$id);
		$data=$this->db->get(self::T_TEMPLATECLASS)->row_array();
		return $data['ClassName'];
	}
	/**
	 * 
	 * 获取申请附件模板
	 * */
	function get_attatemplate($id){
		$this->db->select('AttaName');
		$this->db->where('atta_id',$id);
		$data=$this->db->get(self::T_ATTACHMENTS)->row_array();
		return $data['AttaName'];
	}
	/**
	 * 
	 * 获取奖学金名字
	 * */
	function get_ship($id){
		$this->db->select('title');
		$this->db->where('id',$id);
		$data=$this->db->get(self::T_SHIP)->row_array();
		return $data['title'];
	}
	/**
	 *
	 *获取申请字段
	 **/
	function get_app_fields(){
		$arr=array(
		  'id' =>  '申请ID' ,
		  'number' =>  '申请号' ,
		  'userid' =>  '用户ID' ,
		  'courseid' =>  '专业ID' ,
		  'ordernumber' =>  '订单号' ,
		  'tuition' =>  '学费' ,
		  'applytime' =>  '申请时间' ,
		  'registration_fee' =>  '申请费' ,
		  'paystate' =>  '支付状态' ,
		  'paytime' =>  '支付时间' ,
		  'paytype' =>  '支付方式 1paypal 2payease 3凭据' ,
		  'isstart' =>  '是否开始 1是 0否' ,
		  'isinformation' =>  '资料是否完成 0否 1是' ,
		  'isatt' =>  '附件是否完成 1是 0否' ,
		  'issubmit' =>  '是否提交 1是 0否' ,
		  'issubmittime' =>  '提交时间' ,
		  'isproof' =>  '是否为凭据用户 1是 0否' ,
		  'lasttime' =>  '最后操作时间' ,
		  'state' =>  '状态' ,
		  'addressconfirm' =>  '地址是否确认 -1 未确认 1确认' ,
		  'address_ctime' =>  '地址确认时间' ,
		  'deposit_state' =>  '交押金状态 -1 未交 1 已交' ,
		  'tips' =>  '进度提示' ,
		  'opening' =>  '开学时间' ,
		  'isscholar' =>  '是否是 奖学金 1 是 0 否' ,
		  'remark' =>  '备注' ,
		  'pagesend_status' =>  '是否发送纸质offer -1未发送 1 发送' ,
		  'e_offer_status' =>  'e_offer是否发送 -1 未发送 1发送' ,
		  'pagesend_time' =>  '发送纸质时间' ,
		  'confirm_admission' =>  '是否确认入学 -1 未确认 1 确认' ,
		  'scholorshipid' =>  '奖学金id' ,
		  'scholorstate' =>  '0 待审核 1 通过 2 不通过' ,
		  'validfrom' =>  '护照开始时间' ,
		  'validuntil' =>  '护照截至时间' ,
		  'user'=>'userid',
		  'email'=>'邮箱'
		  );
		$arr['state']='状态';
		$arr['paystate']='支付状态';
		return $arr;
	}
	/**
	 *获取学生导出字段
	 **/
	function get_student_fields(){
		return array(
			'isshort'=>'短期生',
			'studentid'=>'学号',
			'name'=>'中文姓名',
			'firstname'=>'护照姓名',
			'passport'=>'护照号码',
			'nationality'=>'国籍',
			'sex'=>'性别',
			'birthday'=>'出生日期',
			'enroltime'=>'入校日期',
			'leavetime'=>'离校日期',
			'houseaddress'=>'家庭住址',
			'profession'=>'职业',
			'degreeid'=>'学生类别',
			'scholarshipid'=>'经费来源',
			'religion'=>'宗教信仰',
			'squadid'=>'现在班级',
			'state'=>'学生状态',
			'remark'=>'备注',
			'applytime'=>'申请时间',
			'nowaddress'=>'现在的住址及联系方式',
			'visatype'=>'签证类别',
			'visanumber'=>'签证号码',
			'visatime'=>'签证有效期',
			'school'=>'报道院校',
			'faculty'=>'院系',
			'csc_cis'=>'CSC/CIS号',
			'userid'=>'userid',
			'email'=>'邮箱',
			'premium'=>'保险费',
			'deadline'=>'保险期限',
			'effect_time'=>'保险生效日期'
			
			);
	}
	/**
	 * 考试类型字段
	 */
	function get_itemsetting_fields(){
		return array(
			'id'=>'id',
			'code'=>'代号',
			'name'=>'中文名字',
			'enname'=>'英文名字',
			'scores_of'=>'成绩占比',
			'state'=>'状态'
			);
	}
	/**
	 * 考试类型字段
	 */
	function get_books_fields(){
		return array(
			'id'=>'id',
			'name'=>'中文名字',
			'enname'=>'英文名字',
			'price'=>'单价',
			'state'=>'状态'
			);
	}
	/**
	 * 考试类型字段
	 */
	function get_teacher_fields(){
		return array(
			'name'=>'姓名',
			'englishname'=>'英文名字',
			'username'=>'用户名',
			'sex'=>'性别',
			'tel'=>'电话',
			'email'=>'邮箱',
			'phone'=>'手机',
			'phone'=>'职称',
			'content'=>'简介',
			'introduce'=>'介绍',
			'state'=>'状态'
			);
	}
}