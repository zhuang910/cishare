<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Student_Model extends CI_Model {
	const T_STUDENT = 'student';
	const T_MAJOR = 'major';
	const T_SQUAD ='squad';
	const T_COURSE='course';
	const T_SCORE='score';
	const T_CHECKING='checking';
	const T_FACULTY='faculty';
	const T_STUDENT_VISA='student_visa';
	const T_STUDENT_INFO='student_info';
	const T_DEGREE_INFO='degree_info';
	const T_INSUR='insurance_info';
	const T_ARTICLE='tuition_info';
	const T_C = 'credentials';
	const T_MESSAGE_LOG='message_log';
	const T_P_M_C='push_mail_config';
	const T_M_R='mail_record';
	const T_STUDENT_REBUILD='student_rebuild';
	const T_STU_BAR_CARD='student_barter_card';
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
		return $this->db->from ( self::T_STUDENT )->count_all_results ();
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
	
		$data= $this->db->order_by ( $orderby )->get ( self::T_STUDENT )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
	}
	function get_student_on_info($id){
		// var_dump($id);exit;
		if(!empty($id)){
			$this->db->select('student.*,student.id as s_id,student_visa.*');
			$this->db->join(self::T_STUDENT_VISA,self::T_STUDENT_VISA . '.studentid=' . self::T_STUDENT . '.id');
			$this->db->where('student.id',$id);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
		return array();
	}
	function get_student_on_infos($id){
		// var_dump($id);exit;
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
		return array();
	}
		/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count_part_class($where=null) {
		if (! empty ( $where )) {
			$this->db->where ( $where );
		}
		return $this->db->from ( self::T_STUDENT )->count_all_results ();
	
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get_part_class($where = null, $limit = 0, $offset = 0, $orderby = 'id desc') {
		if (! empty ( $where )) {
			$this->db->where ( $where, NULL, false );
		}
		if ($limit) {
			$this->db->limit ( $limit, $offset );
		}
	
		$data= $this->db->order_by ( $orderby )->get ( self::T_STUDENT )->result ();
		if(!empty($data)){
			return $data;
		}else{
			return array();
		}
	}
	/**
	 * [delete 删除学生]
	 * @param  [type] $where [description]
	 * @return [type]        [description]
	 */
		function delete($where = null) {
		if ($where != null) {
			$this->db->delete ( self::T_STUDENT, $where);
			return true;
		}
		return false;
	}
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array();
				$base = $this->db->where ($where)->limit(1)->get(self::T_STUDENT)->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	/**
	 *
	 *获取student_visa一条数据
	 **/
	function get_visa_one($id){
		if(!empty($id)){
			$base = $this->db->where ('studentid',$id)->limit(1)->get(self::T_STUDENT_VISA)->row ();
			if(!empty($base)){
				return $base;
			}
		}
		return false;
	}
	/**
	 *
	 *获取student_visa一条数据
	 **/
	function get_insurance_one($id){
		if(!empty($id)){
			$base = $this->db->where ('studentid',$id)->limit(1)->get(self::T_INSUR)->row ();
			if(!empty($base)){
				return $base;
			}
		}
		return false;
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save($id = null, $data = array()) {
		if (! empty ( $data )) {
			if ($id == null) {
				$this->db->insert ( self::T_STUDENT, $data );
				return $this->db->insert_id ();
			} else {
				$this->db->update ( self::T_STUDENT, $data, 'id = ' . $id );
			}
		}
	}
	/**
	 *更新字段
	 **/
	function update_fields($data=array()){
		
		if(!empty($data)){
			$arr[$data['name']]=$data['value'];
			
			if($data['name']=='degreeid'){
				$d = $this->db->select('id,title')->get_where('degree_info','id > 0')->result_array();
					foreach($d as $kkkkk => $vvvvv){
						$d_d[$vvvvv['id']] = $vvvvv['title']; 

				}
				
				$arrs = array(
					'studenttype' => !empty($d_d[$data['value']])?$d_d[$data['value']]:'',
					'degreeid' => $data['value'],
				);
				$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
				
				/*$is=$this->get_student_type($data['value']);
				if($is=='1'){
					$arrs['studenttype']='学历生';
					
				}else{
					$arrs['studenttype']='非学历生';
					$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
				}*/
			}elseif($data['name']=='firstname'){
				$lastname=$this->get_student_lastname($data['pk']);
				$enname=$data['value'].' '.$lastname;
				$arrs['enname']=$enname;
				$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
			}elseif($data['name']=='lastname'){
				$firstname=$this->get_student_firstname($data['pk']);
				$enname=$firstname.' '.$data['value'];
				$arrs['enname']=$enname;
				$this->db->update ( self::T_STUDENT, $arrs, 'id = ' . $data['pk'] );
			}else{
				$this->db->update ( self::T_STUDENT, $arr, 'id = ' . $data['pk'] );
			}
			
		}
	}
	function get_student_lastname($id){
		if(!empty($id)){
			$this->db->select('lastname');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['lastname'];
		}
		return '';
	}
	function get_student_firstname($id){
		if(!empty($id)){
			$this->db->select('firstname');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['firstname'];
		}
		return '';
	}
	/**
	 *更新student_visa字段
	 **/
	function update_visa_fields($data,$name){
		//查询id是否存在
		$count=$this->check_student_visa($data['pk']);
		if($count>0){
			if(!empty($data)&&!empty($name)){
				if($name=='visatime'){
					$date=strtotime($data['value']);
					$visaendtime['visaendtime']=$date;
					$this->db->update ( self::T_STUDENT, $visaendtime, 'id = ' . $data['pk'] );
				}
				$arr[$name]=$data['value'];
				$this->db->update(self::T_STUDENT_VISA,$arr,'studentid='.$data['pk']);
			}
		}else{
			//创建student_visa
			$arr[$name]=$data['value'];
			$arr['studentid']=$data['pk'];
			$this->db->insert ( self::T_STUDENT_VISA, $arr );
		}
		
	}
	/**
	 *更新student_visa字段
	 **/
	function update_insurance_fields($data,$name){
		//查询id是否存在
		$count=$this->check_student_insurance($data['pk']);
		if($count>0){
			if(!empty($data)&&!empty($name)){
				if($name=='visatime'){
					$date=strtotime($data['value']);
					$visaendtime['visaendtime']=$date;
					$this->db->update ( self::T_STUDENT, $visaendtime, 'id = ' . $data['pk'] );
				}
				$arr[$name]=$data['value'];
				$this->db->update(self::T_INSUR,$arr,'studentid='.$data['pk']);
			}
		}else{
			//创建student_visa
			$arr[$name]=$data['value'];
			$arr['studentid']=$data['pk'];
			$this->db->insert ( self::T_INSUR, $arr );
		}
		
	}
	//检查是否存在
	function check_student_visa($studentid){
		if(!empty($studentid)){
			$this->db->select('count("*") as count');
			$this->db->where('studentid',$studentid);
			$data=$this->db->get(self::T_STUDENT_VISA)->row_array();
			return $data['count']; 
		}
		return false;
	}
	//检查是否存在
	function check_student_insurance($studentid){
		if(!empty($studentid)){
			$this->db->select('count("*") as count');
			$this->db->where('studentid',$studentid);
			$data=$this->db->get(self::T_INSUR)->row_array();
			return $data['count']; 
		}
		return false;
	}
	/**
	 * 获取专业信息       	
	 */
	function get_major_info($where=null,$limit=0,$offset=0,$orderby = null){
        if($where!==null){
            $this->db->where($where);
        }
        if($limit){
            $this->db->limit($limit,$offset);
        }
        if($orderby!==null){
            $this->db->order_by($orderby);
        }
		return $this->db->get ( self::T_MAJOR)->result ();
	}
	function get_major_info_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get ( self::T_MAJOR)->row_array ();
		}
		return array();
	}
	/**
	 * 获取课程信息        	
	 */
	function get_squad_info($majorid){
		$this->db->where('majorid',$majorid);
		return $this->db->get ( self::T_SQUAD)->result_array ();
	}
	/**
	 * 分班        	
	 */
	function add_qm($data){
		$id=$data['id'];
		unset($data['id']);
		$data['isclass']=1;
		$this->db->update ( self::T_STUDENT, $data, 'id = ' . $id );
		return 1;
	}
	/**
	 * 获得专业名字       	
	 */
	function get_majorname($id){
		$data= $this->db->where('id',$id)->get ( self::T_MAJOR)->row_array ();
		return $data['name'];
	}
	/**
	 * 获得班级名字       	
	 */
	function get_squadname($id){
		if($id==0){
			return '还没有分班';
		}else{
			$data= $this->db->where('id',$id)->get ( self::T_SQUAD)->row_array ();
		return $data['name'];
		}
		
	}
	/**
	 *
	 *获取该学生所在专业的学期数
	 **/
	function get_term($userid){
		$this->db->select('major.termnum');
		$this->db->where('student.id',$userid);
		$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_STUDENT . '.majorid');
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['termnum'];
	}
	/**
	 *
	 *获取该学生的成绩
	 **/
	function get_achievement($userid,$nowterm,$scoretype,$identifying){
		if(!empty($userid)){
			$this->db->select('score.score,course.name,course.englishname,major.name as mname');
			$this->db->where('studentid',$userid);
			$this->db->where('term',$nowterm);
			$this->db->where('scoretype',$scoretype);
			$this->db->join(self::T_COURSE,self::T_COURSE . '.id=' . self::T_SCORE . '.courseid');
			$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_SCORE . '.majorid');
			if($identifying=='part'){
				$this->db->limit(6);
			}
			
			$data= $this->db->get(self::T_SCORE)->result_array();
			for ($i=0; $i <count($data) ; $i++) { 
				if($data[$i]['score']>=90){
					$data[$i]['type']='优秀';
				}elseif($data[$i]['score']>=80){
					$data[$i]['type']='良好';
				}elseif($data[$i]['score']>=70){
					$data[$i]['type']='中等';
				}elseif($data[$i]['score']>=60){
					$data[$i]['type']='及格';
				}elseif($data[$i]['score']<60){
					$data[$i]['type']='不及格';
				}
				
			}
			return $data;
		}
	}
	/**
	 *
	 *计算学生所有科目的平均分
	 **/
	function avg_score($data){
		if(!empty($data)){
			$sum=0;
			foreach ($data as $k => $v) {
				$sum+=$v['score'];
			}
			$num=count($data);
			$avg=$sum/$num;
			return number_format($avg,1);
		}
		return 0;
	}
		/**
	 *
	 *获取该学生的考勤
	 **/
	function get_attendance($userid,$nowterm,$identifying){
		if(!empty($userid)){
			$this->db->select('checking.date,checking.type,checking.knob,course.name,course.englishname,major.name as mname,major.englishname as menglishname');
			$this->db->where('checking.studentid',$userid);
			$this->db->where('nowterm',$nowterm);
			$this->db->join(self::T_COURSE,self::T_COURSE . '.id=' . self::T_CHECKING . '.courseid');
			$this->db->join(self::T_MAJOR,self::T_MAJOR . '.id=' . self::T_CHECKING . '.majorid');
			if($identifying=='part'){
				$this->db->limit(6);
			}
			$this->db->order_by('date desc, knob asc'); 
			$data= $this->db->get(self::T_CHECKING)->result_array();
			$attendance=array();
			$i=0;
			foreach ($data as $key => $value) {
				foreach ($value as $k => $v) {
					if($k=='type'){
						switch ($v) {
							case 0:
								$v='正点';
								break;
							case 1:
								$v='缺勤';
								break;
							case 2:
								$v='早退';
								break;
							case 3:
								$v='迟到';
								break;
							case 4:
								$v='请假';
								break;
						
						}
					}
					if($k=='knob'){
						$v=($v*2-1).','.($v*2);
					}
					if($k=='date'){
						$v=date('Y-m-d',$v);
					}	
				$attendance[$i][$k]=$v;
				}
				$i++;
			}
			return $attendance;
		}
	}
	/**
	 *获取字段
	 **/
	function get_student_fields(){
		$arr=array(
				'nationality'=>'国家',
				'paperstype'=>'证件类型',
				'passport'=>'证件号码',
				'visaendtime'=>'证件有效期',
				'studentid'=>'学号',
				'firstname'=>'英文姓',
				'lastname'=>'英文名',
				'name'=>'中文名',
				'sex'=>'性别',
				'birthday'=>'出生日期',
				'profession'=>'身份职业',
				'religion'=>'宗教信仰',
				'scholarshipid'=>'资金来源',
				'backdrop'=>'社会背景',
				'degreeid'=>'学生类别',
				'studenttype'=>'学生类别明细',
				'police'=>'所属派出所',
				'nowaddress'=>'境内住址',
				'houseaddress'=>'家庭住址',
				'oldvisatime'=>'原签证有效期',
				'issuetime'=>'签发有效期',
				'state'=>'状态',
				'mobile'=>'手机',
				'enroltime'=>'入校日期',
				'leavetime'=>'离校日期',
				'isshort'=>'是否短期生',
				'oldpassport'=>'旧护照号码',
				'putupstate'=>'住宿状态',
				'isnegro'=>'是否黑人',
				'remark'=>'备注',
				
			);
		return $arr;
	}
	/**
	 *获取字段
	 **/
	function get_student_insurance_fields(){
		$arr=array(
				'enname'=>'英文名字',
				'name'=>'中文名字',
				'studentid'=>'学号',
				'birthday'=>'出生日期',
				'passport'=>'护照号',
				'visatime'=>'签证到期日期',
				'premium'=>'保险费',
				'deadline'=>'保险期限',
				'effect_time'=>'保险生效日期',
				
			);
		return $arr;
	}
	/**
	 *获取字段
	 **/
	function insert_student_fields(){
		$arr=array(
				'nationality'=>'国家',
				'paperstype'=>'证件类型',
				'passport'=>'证件号码',
				'passporttime'=>'证件有效期',
				'studentid'=>'学号',
				'firstname'=>'英文姓',
				'lastname'=>'英文名',
				'name'=>'中文名',
				'sex'=>'性别',
				'birthday'=>'出生日期',
				'profession'=>'身份职业',
				'religion'=>'宗教信仰',
				'scholarshipid'=>'资金来源',
				'backdrop'=>'社会背景',
				'studenttype'=>'学生类别明细',
				'degreeid'=>'学生类别',
				'state'=>'状态',
				'mobile'=>'手机',
				'enroltime'=>'入校日期',
				'leavetime'=>'离校日期',
				'isshort'=>'是否短期生',
				'oldpassport'=>'旧护照号码',
				'putupstate'=>'住宿状态',
				'isnegro'=>'是否黑人',
				'remark'=>'备注',
				
			);
		return $arr;
	}
	//学生签证表插入字段
	function insert_student_visa_fields(){
		return array(
				'police'=>'所属派出所',
				'nowaddress'=>'境内住址',
				'houseaddress'=>'家庭住址',
				'oldvisatime'=>'原签证有效期',
				'issuetime'=>'签发有效期',
			);
	}
	//学生注册表插入字段
	function insert_student_info_fields(){
		return array(
			'nationality'=>'国籍',
			'passport'=>'护照号',
			'validuntil'=>'护照有效期',
			'firstname'=>'英文姓',
			'lastname'=>'英文名',
			'chname'=>'汉语名',
			'sex'=>'性别',
			'birthday'=>'出生日期',
			'religion'=>'宗教信仰',
			'password'=>'密码',
			'registertime'=>'注册时间',
			'registerip'=>'注册ip',
			'mobile'=>'手机',
			);
	}
	/**
	 * 插入student_info字段
	 */
	function insert_student_info($insert,$value){
		// var_dump($insert);
		// var_dump($value);exit;
		$sql='insert into sdyinc_student_info ('.$insert.') values('.$value.')';
		$this->db->query($sql);
		return $this->db->insert_id ();
	}			
	/**
	 * 插入字段
	 */
	function insert_fields($insert,$value){
		$sql='insert into sdyinc_student ('.$insert.') values('.$value.')';
		$this->db->query($sql);
		return $this->db->insert_id ();
	}
	
	/**
	 * 插入student_visa
	 */
	function insert_visa_fields($insert,$value){
		$sql='insert into sdyinc_student_visa ('.$insert.') values('.$value.')';
		$this->db->query($sql);
		return $this->db->insert_id ();
	}
	/**
	 *
	 *获取最低学历要求
	 **/
	function get_degree_type($data,$name){
		if(!empty($name)){
			$this->db->select();
			$this->db->where('title',$name);
			$data=$this->db->get(self::T_DEGREE_INFO)->row_array();
			return $data['id'];
		}
		return 0;		
	}
	function get_student_state($str){
		switch ($str)
				{
				case '在校':
				  return 1;
				  break;
				case '转学':
				  return 2;
				  break;
				case '正常离开':
				  return 3;
				  break;
				case '非正常离开':
				  return 4;
				  break;
				case '休学':
				  return 5;
				  break;
				case '申请':
				  return 6;
				  break;
				case '已报到':
				  return 7;
				  break;
				case '未报到':
				  return 8;
				  break;
				}

	}
	function get_student_state_str($id){
		switch ($id)
				{
				case 1:
				  return '在校';
				  break;
				case 2:
				  return '毕业离校';
				  break;
				case 3:
				  return '主动退学';
				  break;
				case 4:
				  return '应予退学（开除）';
				  break;
            default:
                return null;
                break;
				}

	}
	/**
	 *
	 *检查是否有重复记录
	 *@$insert:字段
	 *@$value:字段值
	 **/
	function check_student($papers_num){
		if(empty($papers_num)){
			return 2;
		}
		$this->db->select('count(*) as count');
		$this->db->where('passport',$papers_num);
		$data=$this->db->get(self::T_STUDENT)->row_array();
		return $data['count'];
	}
	/**
	 *
	 *检查student_info是否有重复记录
	 *@$insert:字段
	 *@$value:字段值
	 **/
	function check_student_info($papers_num){
		if(empty($papers_num)){
			return 2;
		}
		$this->db->select('count(*) as count');
		$this->db->where('passport',$papers_num);
		$data=$this->db->get(self::T_STUDENT_INFO)->row_array();
		return $data['count'];
	}
	/**
	 * 
	 * 获取院系id
	 * */
	function get_facultyid($name){
		$this->db->select('id');
		$this->db->where('name',$name);
		$data=$this->db->get(self::T_FACULTY)->row_array();
		return $data['id'];
	}
	//获取国家的名字
	function get_nationality($id){
		$data=CF('public','',CACHE_PATH);
		if(!empty($id)){
			return $data['global_country_cn'][$id];
		}
		return false;
	}
	//反查国家的id
	function get_nationality_id($str){
		$data=CF('public','',CACHE_PATH);
		if(!empty($str)){
			foreach ($data['global_country_cn'] as $k => $v) {
				if($str==$v){
					return $k;
				}
			}
		}
		return '';
	}
	//批量分班
	function do_student_class($ids,$mid,$sid){
		foreach ($ids['sid'] as $k => $v) {
			$data['majorid']=$mid;
			$data['squadid']=$sid;
			$this->db->update ( self::T_STUDENT, $data, 'id = ' . $v );
		}
		return 1;
	}
	//获取专业id
	function get_major_id($str){
		if(!empty($str)){
			$this->db->like('name',$str);
			$data=$this->db->get(self::T_MAJOR)->row_array();
		}
		if(!empty($data)){
			return $data['id'];
		}else{
			return '';
		}
	}
	//获取班级id
	function get_squad_id($str){
		if(!empty($str)){
			$this->db->like('name',$str);
			$data=$this->db->get(self::T_SQUAD)->row_array();
		}
		if(!empty($data)){
			return $data['id'];
		}else{
			return '';
		}
	}
		//获取学历数据
	function get_degree_name($where = null,$limit=0,$offset=0,$orderby = null){
        if($where != null){
            $this->db->where($where);
        }

        if($limit){
            $this->db->limit($limit,$offset);
        }

        if ($orderby != null){
            $this->db->order_by($orderby);
        }
		return $this->db->get(self::T_DEGREE_INFO)->result_array();
	}
		//获取一条学历数据
	function get_degree_name_one($id){
		$this->db->select('title');
		$this->db->where('id',$id);
		$data= $this->db->get(self::T_DEGREE_INFO)->row_array();
		return $data['title'];
	}
	//获取学生类别
	function get_student_type($id){
		$this->db->select('isdegree');
		$this->db->where('id',$id);
		$data= $this->db->get(self::T_DEGREE_INFO)->row_array();
		return $data['isdegree'];
	}
	/**
	 * [get_student_id 根据护照号反查id]
	 * @param  [varchar] $passport [护照号]
	 */
	function get_student_id($passport=null){
		if($passport!=null){
			$this->db->select('id');
			$this->db->where('passport',$passport);
			$row=$this->db->get(self::T_STUDENT)->row_array();
			return $row['id'];
		}
		return 0;
	}
	/**
	 * [insurance_check_student 检查保险表是否有重复记录]
	 * @param  [int] $studentid [学生id]
	 * @return [int]            [数据库的条数]
	 */
	function insurance_check_student($studentid=null){
		if(empty($studentid)){
			return 2;
		}
		$this->db->select('count(*) as count');
		$this->db->where('studentid',$studentid);
		$data=$this->db->get(self::T_INSUR)->row_array();
		return $data['count'];
	}
	/**
	 * 插入student_visa
	 */
	function insert_insurance_fields($insert=null,$value=null){
		$sql='insert into sdyinc_insurance_info ('.$insert.') values('.$value.')';
		$this->db->query($sql);
		return $this->db->insert_id ();
	}
		/**
	 * [pay_change_state 现场缴费修改状态]
	 * @param  [array] $data [修改数据]
	 * @return [type]       [Boolean]
	 */
	function pay_change_state($data){
		$arr=array();
		if(!empty($data)){

			$arr['userid']=$data['id'];
			//获取学生信息
			$where='id='.$data['id'];
			$student_info=$this->get_one($where);
			$arr['tel']=$student_info->tel;
			$arr['name']=$student_info->name;
			$arr['email']=$student_info->email;
			$arr['mobile']=$student_info->mobile;
			$arr['passport']=$student_info->passport;
			$arr['nationality']=$student_info->nationality;
			//获取学期
			if(empty($student_info->squadid)){
				$arr['nowterm']=1;
			}else{
				$arr['nowterm']=$this->get_pay_nowterm($student_info->squadid);
			}
			
			$arr['paytime']=time();
			$arr['createtime']=time();
			$arr['danwei']=$data['currency'];
			$arr['tuition']=$data['amount'];
			$arr['paystate']=1;
			$result=$this->select_tuition($arr);
			if($result>0){
				return 0;
			}
			$this->db->insert ( self::T_ARTICLE, $arr);
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
	/**
	 * [get_pay_nowterm 获取学生的所在学期]
	 * @param  [int] $id [班级id]
	 * @return [int]     [当前学期数]
	 */
	function get_pay_nowterm($id){
		if(!empty($id)){
			$this->db->select('nowterm');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_SQUAD)->row_array();
			if(!empty($data['nowterm'])){
				return $data['nowterm'];
			}else{
				return 1;
			}
		}
		return 1;
	}
	//获取专业id
	function get_major_ids($str){
		if(!empty($str)){
			$this->db->like('name',$str);
			$data=$this->db->get(self::T_MAJOR)->result_array();
		}
		$ids='';
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$ids.=$v['id'].',';
			}
			$ids=trim($ids,',');
			return $ids;
		}else{
			return '';
		}
	}
	/**
	 * [select_tuition 查找学费缴费记录]
	 * @param  [array] $arr [缴费信息]
	 * @return [布尔型]      [description]
	 */
	function select_tuition($arr){
		if(!empty($arr['userid'])&&!empty($arr['nowterm'])){
			$this->db->select('count(*) as num');
			$this->db->where('userid',$arr['userid']);
			$this->db->where('nowterm',$arr['nowterm']);
			$num=$this->db->get(self::T_ARTICLE)->row_array();
			return $num['num'];
		}
		return flase;
	}
	/**
	 * [get_squad_name 获取班级名字]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_squad_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_SQUAD)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * 保存基本信息
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_message( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_MESSAGE_LOG, $data );
				return $this->db->insert_id ();
		
		}
	}



	//获取userid数组
	function get_userid_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_userid_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条userid
	function get_userid_one($id){
		if(!empty($id)){
			$this->db->select('userid');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['userid'];
		}
		return 0;
	}
	//获取发邮件配置
	function get_addresserset(){
		return $this->db->get(self::T_P_M_C)->result_array();
	}
	/**
	 * 保存邮件发送记录
	 *
	 * @param number $id        	
	 * @param array $data        	
	 */
	function save_email( $data = array()) {
		if (! empty ( $data )) {
			
				$this->db->insert ( self::T_M_R, $data );
				return $this->db->insert_id ();
		
		}
	}
	function get_email_user_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_email_user_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条email
	function get_email_user_one($id){
		if(!empty($id)){
			$this->db->select('email');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT_INFO)->row_array();
			return $data['email'];
		}
		return 0;
	}
	function get_email_arr($arr=array()){
		$userid=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				$userid_one=$this->get_email_one($v);
				if(!empty($userid_one)){
					$userid[]=$userid_one;
				}
			}
			return $userid;
		}
		return array();
	}
	//获取一条email
	function get_email_one($id){
		if(!empty($id)){
			$this->db->select('email');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			return $data['email'];
		}
		return 0;
	}
	/**
	 * [save_rebuild 插入重修费用表]
	 * @return [type] [description]
	 */
	function save_rebuild($data){
		if(!empty($data)){
			$this->db->insert ( self::T_STUDENT_REBUILD, $data );
			return $this->db->insert_id ();

		}
		return 0;
	}
	/**
	 * [save_rebuild 插入换证费用表]
	 * @return [type] [description]
	 */
	function save_add_barter_card($data){
		if(!empty($data)){
			$this->db->insert ( self::T_STU_BAR_CARD, $data );
			return $this->db->insert_id ();

		}
		return 0;
	}
}