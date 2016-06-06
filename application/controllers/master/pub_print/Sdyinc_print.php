<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 通知邮件配置
 *
 * @author JJ
 *
 */
class Sdyinc_print extends Master_Basic
{
	
	protected $sex=array(
			0=>'其他',
			1=>'男',
			2=>'女'
		);
	protected $en_sex=array(
			0=>'Other',
			1=>'Male',
			2=>'Female'
		);
	protected $en_sex_3=array(
			0=>'Other',
			1=>'he',
			2=>'she'
		);
	protected $chinese_big=array(
			0 => '〇',
			1 => '一',
			2 => '二',
			3 => '三',
			4 => '四',
			5 => '五',
			6 => '六',
			7 => '七',
			8 => '八',
			9 => '九',
		);
	protected $nationality;
	protected $zaixiao=array();
	//支付方式
	public $config_paytype = array(
		1 => 'paypal',
		2 => 'payease',
		3 => '汇款',
		4 => '现金',
		5 => '刷卡',
		6=>'奖学金支付',
		7=>'申请减免'
		);
	/**
	 * 基础类构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->nationality=CF('public','',CACHE_PATH);
		$this->load->model('master/enrollment/change_offer_status_model');
	}

	/**
	 * 配置主页
	 */
	function index()
	{	
		$print_tempid=$this->input->get('print_tempid');
		$studentid=$this->input->get('studentid');
		$info=$this->db->get_where('print_template','id = '.$print_tempid)->row_array();
		$return_data=$this->_get_data($info,$studentid);
		
		$this->load->view('/master/public/zust_print',array(
				'info'=>$info,
				'return_data'=>$return_data
			));
	}
	/**
	 * [_get_data 获取模板数据 ]
	 * @return [type] [description]
	 */
	function _get_data($info,$studentid){
		if(!empty($info)){
			//学生的基本信息
			$student_info=$this->db->get_where('student','id = '.$studentid)->row_array();
			//获取该模板的所有字段
			if($info['parentid']==4){
				$data=$this->_zaixiaozhengming($student_info,$info);
			}
			//中国政府奖学金
			if($info['parentid']==123){
				$data=$this->_scholarship($student_info,$info);
			}
			//结业证
			if($info['parentid']==6){
				$data=$this->_jieyezhengshu($student_info,$info);
			}
			if($info['parentid']==5){
				$data=$this->_lixiaozhengming($student_info,$info);
			}
			if($info['parentid']==1){
				$data=$this->_juliuxuke($student_info,$info);
			}
			// 登记卡
			if($info['parentid']==9){
				$data=$this->_student_register($student_info,$info);
			}
			return $data;
		}
		return array();
		
	}
	
			/**
	 * [_juliuxuke 学生登记卡
	 * @return [type] [description]
	 */
	function _student_register($student_info,$info){
	//	var_dump($student_info);
		if(!empty($student_info)){
			
			$fields_all=explode(',',$info['fields']);
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				$major_info=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
			}	
				/*if(!empty($squad_info['classtime'])){
				    $openstime=date('Y-m',$squad_info['classtime']);
				}else{
				    $openstime='';
				}
				
				if(!empty($major_infos['termdays'])){
					$leavetime=date('Y-m',$squad_info['classtime']+$major_infos['termdays']*3600*24);
				}else{
				    
			    	$leavetime='';
				}
				
			}else{
				$checking=100;
				$openstime='';
				$leavetime='';
			}
			*/
			
			//学习期限
			$otime = '';
			$leavetime = '';
			//首先判断 学生表中的 学习 期限 若果 没有 再 计算
			$study_time = $this->db->select('studystarttime,studyendtime')->get_where('student_info','id = '.$student_info['userid'])->row();
			
			if(!empty($study_time->studystarttime)){
				$otime = $study_time->studystarttime;
			}
			if(!empty($study_time->studyendtime)){
				$leavetime = $study_time->studyendtime;
			}
			if(empty($otime)){
				$otime = !empty($student_info['createtime'])?$student_info['createtime']:'';
			}
			
			//如果没有班级的话 就用 在学表的专业
			if(empty($major_info)){
				$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			}
			//查询出生地
			$birthplace = $this->db->select('birthplace,marital')->get_where('student_info','id = '.$student_info['userid'])->row();
			
			$marital = '';
			if(!empty($birthplace->marital)){
				if($birthplace->marital == 1){
					$marital = 'Single';
				}
				if($birthplace->marital == 2){
					$marital = 'Married';
				}
			}else{
				$marital = 'Single';
			}
			$student_visa=$this->db->get_where('student_visa','studentid = '.$student_info['id'])->row_array();
			
			if(!empty($student_info['birthday'])){
				if(strlen($student_info['birthday']) == 8){
					$b_t_year = substr($student_info['birthday'],0,4);
					$b_t_mo = substr($student_info['birthday'],4,2);
					$b_t_day = substr($student_info['birthday'],6,2);
					
				}
				
			}
			
			$data=array(
			  'nationality' =>$this->nationality['global_country_cn'][$student_info['nationality']],
			   'cn_name' =>!empty($student_info['enname'])?$student_info['enname']:$student_info['enname'],			  
			   'en_name' =>!empty($student_info['enname'])?$student_info['enname']:$student_info['enname'],			  
			   'name' =>!empty($student_info['enname'])?$student_info['enname']:$student_info['enname'],			  
			   'birth_place' =>!empty($birthplace->birthplace)?$birthplace->birthplace:'',			  
			   'sex' =>$this->sex[$student_info['sex']],			 
		       'passport' =>$student_info['passport'],
			  
			   'b_t_year' =>!empty($b_t_year)?$b_t_year:'',
			   'b_t_mo' =>!empty($b_t_mo)?$b_t_mo:'',
			   'b_t_day' =>!empty($b_t_day)?$b_t_day:'',
			   'nowtime' =>date('Y-m-d',time()),
			   'marital' => $marital,
			   'major'=>!empty($major_info['name'])?$major_info['name']:'',
			   'scholarshipid'=>$student_info['scholarshipid'],
			   'mobile'=>$student_info['mobile'],
			   'oldvisatime'=>!empty($student_visa['oldvisatime'])?date('Y-m-d',$student_visa['oldvisatime']):'',
			   'visatime'=>!empty($student_visa['visatime'])?date('Y-m-d',$student_visa['visatime']):'',
			  
			   'houseaddress'=>!empty($student_visa['houseaddress'])?$student_visa['houseaddress']:'',
			   'nowaddress'=>!empty($student_visa['nowaddress'])?$student_visa['nowaddress']:'',
			   'studentid'=>!empty($student_info['studentid'])?$student_info['studentid']:'',
			   'email'=>!empty($student_info['email'])?$student_info['email']:'',
			   'family_tel'=>!empty($student_info['family_tel'])?$student_info['family_tel']:'',
			   'q_xs_year' => !empty($otime)?date('Y',$otime):'',
			   'q_xs_mo' => !empty($otime)?date('m',$otime):'',
			   'q_xs_day' => !empty($otime)?date('d',$otime):'',
			   'q_xe_year' => !empty($leavetime)?date('Y',$leavetime):'',
			   'q_xe_mo' => !empty($leavetime)?date('m',$leavetime):'',
			   'q_xe_day' => !empty($leavetime)?date('d',$leavetime):'',
			    );
			return $data;
		}
		return array();
	}
	/**
	 * [_juliuxuke 居留许可证明]
	 * @return [type] [description]
	 */
	function _juliuxuke($student_info,$info){
		if(!empty($student_info)){
			$fields_all=explode(',',$info['fields']);
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				$major_info=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
			
			}
			$student_visa=$this->db->get_where('student_visa','studentid = '.$student_info['id'])->row_array();
				//如果没有班级的话 就用 在学表的专业
			if(empty($major_info)){
				$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			}
			$birthday = '';
			
			if(!empty($student_info['birthday'])){
				$birthday .= date('Y',strtotime($student_info['birthday'])).'年';
				$birthday .= date('m',strtotime($student_info['birthday'])).'月';
				$birthday .= date('d',strtotime($student_info['birthday'])).'日';
			}
			$visatime = '';
			
			if(!empty($student_info['birthday'])){
				$visatime .= date('Y',$student_visa['visatime']).'年';
				$visatime .= date('m',$student_visa['visatime']).'月';
				$visatime .= date('d',$student_visa['visatime']).'日';
			}
			$oldvisatime = '';
			
			if(!empty($student_visa['oldvisatime'])){
				$oldvisatime .= date('Y',$student_visa['oldvisatime']).'年';
				$oldvisatime .= date('m',$student_visa['oldvisatime']).'月';
				$oldvisatime .= date('d',$student_visa['oldvisatime']).'日';
			}
			$nowtime = '';
			$nowtime .= date('Y',time()).'年';
				$nowtime .= date('m',time()).'月';
				$nowtime .= date('d',time()).'日';
			$data=array(
			   'nationality' =>$this->nationality['global_country_cn'][$student_info['nationality']],
			   'name' =>!empty($student_info['name'])?$student_info['name']:$student_info['enname'],			  
			   'sex' =>$this->sex[$student_info['sex']],			 
		       'passport' =>$student_info['passport'],
			   'birthday' =>$birthday,
			   'nowtime' =>$nowtime,
			   'major'=>!empty($major_info['name'])?$major_info['name']:'',
			   'scholarshipid'=>$student_info['scholarshipid'],
			   'mobile'=>$student_info['mobile'],
			   'oldvisatime'=>$oldvisatime,
			   'visatime'=>$visatime,
			   'nowaddress'=>!empty($student_visa['nowaddress'])?$student_visa['nowaddress']:'',
			   'studentid'=>!empty($student_info['studentid'])?$student_info['studentid']:''
			    );
			return $data;
		}
		return array();
	}
	/**
	 * [_zaixiaozhengming 获取在校证明字段
	 * @return [type] [description]
	 */
	function _zaixiaozhengming($student_info,$info){
		if(!empty($student_info)){
			$fields_all=explode(',',$info['fields']);
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				$major_infos=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				if(!empty($squad_info['classtime'])){
				    $openstime=date('Y-m',$squad_info['classtime']);
				}else{
				    $openstime='';
				}
				
				if(!empty($major_infos['termdays'])){
					$leavetime=date('Y-m',$squad_info['classtime']+$major_infos['termdays']*3600*24);
				}else{
				    
			    	$leavetime='';
				}
				$major_info=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				//出勤率
				$checking=$this->get_student_rate($student_info['id'],$squad_info['majorid'],$squad_info['nowterm'],$squad_info['id']);
			}else{
				$checking=100;
				$openstime='';
				$leavetime='';
			}
			
			//学习期限
			$openstime = '';
			$leavetime = '';
			//首先判断 学生表中的 学习 期限 若果 没有 再 计算
			$study_time = $this->db->select('studystarttime,studyendtime')->get_where('student_info','id = '.$student_info['userid'])->row();
			
			if(!empty($study_time->studystarttime)){
				$openstime = $study_time->studystarttime;
			}
			if(!empty($study_time->studyendtime)){
				$leavetime = $study_time->studyendtime;
			}
			if(empty($openstime)){
				$openstime = !empty($student_info['createtime'])?$student_info['createtime']:'';
			}
			
			//如果没有班级的话 就用 在学表的专业
			if(empty($major_info)){
				$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			}
			
			$birthday = '';
			$birthday_en = '';
			if(!empty($student_info['birthday'])){
				$birthday .= date('Y',strtotime($student_info['birthday'])).'年';
				$birthday .= date('n',strtotime($student_info['birthday'])).'月';
				$birthday .= date('j',strtotime($student_info['birthday'])).'日';
				
				$birthday_en .= date('M',strtotime($student_info['birthday'])).'. '.date('j',strtotime($student_info['birthday'])).', '.date('Y',strtotime($student_info['birthday']));
			}
			$cn_nowtime = '';
			$en_nowtime = '';
			$cn_nowtime .= date('Y',time()).'年';
				$cn_nowtime .= date('n',time()).'月';
				$cn_nowtime .= date('j',time()).'日';
				
				$en_nowtime .= date('M',time()).'. '.date('j',time()).', '.date('Y',time());
				
				$cn_openstime = $cn_enrolltime = $cn_leavetime = $en_leavetime = $en_openstime = '';
				
					if(!empty($openstime)){
				 
				$cn_openstime .=  date('Y',$openstime).'年';
				$cn_openstime .= date('n',$openstime).'月';
				$cn_openstime .= date('j',$openstime).'日';
				$cn_enrolltime = $cn_openstime;
				$en_openstime = date('M',$openstime).'. '.date('j',$openstime).', '.date('Y',$openstime);
			}
			
			if(!empty($leavetime)){
				 
				$cn_leavetime .=date('Y',$leavetime).'年';
				$cn_leavetime .= date('n',$leavetime).'月';
				$cn_leavetime .= date('j',$leavetime).'日';
				$en_leavetime = date('M',$leavetime).'. '.date('j',$leavetime).', '.date('Y',$leavetime);
			}
			$data=array(
			   'cn_nationality' =>!empty($student_info['nationality'])?$this->nationality['global_country_cn'][$student_info['nationality']]:'',
			   'cn_name' =>!empty($student_info['name'])?:$student_info['enname'],			  
			   'cn_sex' =>$this->sex[$student_info['sex']],			 
		       'cn_passport' =>$student_info['passport'],
			   'cn_birthday' =>$birthday,
			   'en_name'=> $student_info['enname'],				 
		       'en_openstime' =>$en_openstime,
			   'en_passport' =>$student_info['passport'],		
			   'cn_nowtime' =>$cn_nowtime,
			   'cn_checking' =>'',
			   'en_cql' =>'',
			   'en_birthday' =>$birthday_en,
			   'cn_leavetime' => $cn_leavetime,
			   'en_sex' =>$this->en_sex[$student_info['sex']],			  
			   'en_nowtime' =>$en_nowtime,
			   'en_leavetime' =>$en_leavetime,
			   'en_nationality' =>!empty($student_info['nationality'])?$this->nationality['global_country'][$student_info['nationality']]:'',
			   'cn_openstime'=>$cn_openstime,
			   'cn_enrolltime'=>$cn_enrolltime,
			   'cn_majorname'=>!empty($major_info['name'])?$major_info['name']:'',
			   'en_majorname'=>!empty($major_info['englishname'])?$major_info['englishname']:'',
			    );
			return $data;
		}
		return array();

	}
	
	/**
	 * [_zaixiaozhengming 获取在校证明字段
	 * @return [type] [description]
	 */
	function _scholarship($student_info,$info){
		if(!empty($student_info)){
			$fields_all=explode(',',$info['fields']);
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				$major_infos=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				
				if(!empty($squad_info['classtime'])){
				    $openstime=date('Y-m',$squad_info['classtime']);
				}else{
				    $openstime='';
				}
				
				if(!empty($major_infos['termdays'])){
					$leavetime=date('Y-m',$squad_info['classtime']+$major_infos['termdays']*3600*24);
				}else{
				    
			    	$leavetime='';
				}
				$major_info=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				//出勤率
				$checking=$this->get_student_rate($student_info['id'],$squad_info['majorid'],$squad_info['nowterm'],$squad_info['id']);
			}else{
				$checking=100;
				$openstime='';
				$leavetime='';
			}
			//学习期限
			$openstime = '';
			$leavetime = '';
			//首先判断 学生表中的 学习 期限 若果 没有 再 计算
			$study_time = $this->db->select('studystarttime,studyendtime')->get_where('student_info','id = '.$student_info['userid'])->row();
			
			if(!empty($study_time->studystarttime)){
				$openstime = $study_time->studystarttime;
			}
			if(!empty($study_time->studyendtime)){
				$leavetime = $study_time->studyendtime;
			}
			if(empty($openstime)){
				$openstime = !empty($student_info['createtime'])?$student_info['createtime']:'';
			}
			
			//如果没有班级的话 就用 在学表的专业
			if(empty($major_info)){
				$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			}
			//学历名称
			$degree_cn = '';
			$degree_en = '';
			if(!empty($major_infos) && !empty($major_infos['degree'])){
				$degree = $this->db->get_where('degree_info','id = '.$major_infos['degree'])->row();
				if(!empty($degree->title)){
					$degree_cn = $degree->title;
				}
				if(!empty($degree->entitle)){
					$degree_en = $degree->entitle;
				}
			}
			
			//学院名称
			$faculty_cn = '';
			$faculty_en = '';
			if(!empty($major_infos) && !empty($major_infos['facultyid'])){
				$faculty = $this->db->get_where('faculty','id = '.$major_infos['facultyid'])->row();
				if(!empty($faculty->name)){
					$faculty_cn = $faculty->name;
				}
				if(!empty($faculty->englishname)){
					$faculty_en = $faculty->englishname;
				}
			}
			$title_cn = '';
			$title_en = '';
			if($student_info['sex'] == 1){
				$title_cn = ' 先生';
				$title_en = 'Ms. ';
			}else if($student_info['sex'] == 2){
				$title_cn = ' 女士';
				$title_en = 'Mr. ';
			}
			$leavetime_cn = '';
			$leavetime_en = '';
			
			if(!empty($leavetime)){
				$leavetime_cn .= date('Y',$leavetime).'年';
				$leavetime_cn .= date('n',$leavetime).'月';
				$leavetime_cn .= date('j',$leavetime).'日';
				$leavetime_en .= date('M',$leavetime).'. '.date('j',$leavetime).', '.date('Y',$leavetime);
			}
			$opentime_cn = '';
			$opentime_en = '';
			
			if(!empty($openstime)){
				$opentime_cn .= date('Y',$openstime).'年';
				$opentime_cn .= date('n',$openstime).'月';
				$opentime_cn .= date('j',$openstime).'日';
				$opentime_en .= date('M',$openstime).'. '.date('j',$openstime).', '.date('Y',$openstime);
			}
			$cn_lang = '';
			$en_lang = '';
			if(!empty($major_infos) && !empty($major_infos['language'])){
				if($major_infos['language'] == 1){
					$cn_lang = '汉语';
					$en_lang = 'Chinese';
				}else{
					$cn_lang = '英语';
					$en_lang = 'English';
				}
			}
			$data=array(
			   'nationality' =>!empty($student_info['nationality'])?$this->nationality['global_country_cn'][$student_info['nationality']]:'',
			   'cn_name' =>$student_info['name'].$title_cn,			  
			   'cn_sex' =>$this->sex[$student_info['sex']],			 
		       'cn_passport' =>$student_info['passport'],
			   'cn_birthday' =>!empty($student_info['birthday'])?date('Y-m-d',strtotime($student_info['birthday'])):'',
			   'en_name'=> $title_en.$student_info['enname'],				 
		       'en_openstime' =>$opentime_cn,
			   'en_passport' =>$student_info['passport'],		
			   'cn_nowtime' =>date('Y-m-d',time()),
			   'cn_checking' =>$checking.'%',
			   'en_birthday' =>!empty($student_info['birthday'])?date('Y-m-d',strtotime($student_info['birthday'])):'',
			   'cn_leavetime' =>$leavetime_cn,
			   'en_sex' =>$this->en_sex[$student_info['sex']],			  
			   'en_nowtime' =>date('Y-m-d',time()),
			   'en_leavetime' =>$leavetime_en,
			   'en_nationality' =>!empty($student_info['nationality'])?$this->nationality['global_country'][$student_info['nationality']]:'',
			   'cn_openstime'=>$opentime_en,
			   'cn_enrolltime'=>!empty($student_info['createtime'])?date('Y-m-d',$student_info['createtime']):'',
			   'cn_major'=>!empty($major_info['name'])?$major_info['name']:'',
			   'en_major'=>!empty($major_info['englishname'])?$major_info['englishname']:'',
			   'cn_education' => $degree_cn,
			   'en_education' => $degree_en,
			   'en_college' => $faculty_en,
			   'cn_college' => $faculty_cn,
			   'xqs_year' => !empty($openstime)?date('Y',strtotime($openstime)):'',
			   'xqs_mo' => !empty($openstime)?date('m',strtotime($openstime)):'',
			   'xqe_year' => !empty($leavetime)?date('Y',strtotime($leavetime)):'',
			   'xqe_mo' => !empty($leavetime)?date('m',strtotime($leavetime)):'',
			   'cn_lang' => $cn_lang,
			   'en_lang' => $en_lang,
			   'en_nowtime_day' => date('d',time()),
			   'en_nowtime_year' => date('Y',time()),
			   'en_nowtime_month' => date('m',time()),
			    );
				
			return $data;
		}
		return array();

	}
	
		/**
	 * [_zaixiaozhengming 获取结业证明
	 * @return [type] [description]
	 */
	function _jieyezhengshu($student_info,$info){
		if(!empty($student_info)){
			$fields_all=explode(',',$info['fields']);
			
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				$major_infos=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				
				if(!empty($squad_info['classtime'])){
				    $openstime=date('Y-m-d',$squad_info['classtime']);
				}else{
				    $openstime='';
				}
				
				if(!empty($major_infos['termdays'])){
					$leavetime=date('Y-m-d',$squad_info['classtime']+$major_infos['termdays']*3600*24);
				}else{
				    
			    	$leavetime='';
				}
				$major_info=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				//出勤率
				//$checking=$this->get_student_rate($student_info['id'],$squad_info['majorid'],$squad_info['nowterm'],$squad_info['id']);
			}else{
				//$checking=100;
				$openstime='';
				$leavetime='';
			}
			//学习期限
			$openstime = '';
			$leavetime = '';
			//首先判断 学生表中的 学习 期限 若果 没有 再 计算
			$study_time = $this->db->select('studystarttime,studyendtime')->get_where('student_info','id = '.$student_info['userid'])->row();
			
			if(!empty($study_time->studystarttime)){
				$openstime = $study_time->studystarttime;
			}
			if(!empty($study_time->studyendtime)){
				$leavetime = $study_time->studyendtime;
			}
			if(empty($openstime)){
				$openstime = !empty($student_info['createtime'])?$student_info['createtime']:'';
			}
			
			//如果没有班级的话 就用 在学表的专业
			if(empty($major_info)){
				$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			}
			$title_cn = '';
			$title_en = '';
			if($student_info['sex'] == 1){
				$title_cn = ' 先生';
				$title_en = 'Ms. ';
			}else if($student_info['sex'] == 2){
				$title_cn = ' 女士';
				$title_en = 'Mr. ';
			}
			$birthday = '';
			$birthday_en = '';
			if(!empty($student_info['birthday'])){
				$birthday .= date('Y',strtotime($student_info['birthday'])).'年';
				$birthday .= date('n',strtotime($student_info['birthday'])).'月';
				$birthday .= date('j',strtotime($student_info['birthday'])).'日';
				
				$birthday_en .= date('M',strtotime($student_info['birthday'])).'. '.date('j',strtotime($student_info['birthday'])).', '.date('Y',strtotime($student_info['birthday']));
			}
			$leavetime_cn = '';
			$leavetime_en = '';
			
			if(!empty($leavetime)){
				$leavetime_cn .= date('Y',$leavetime).'年';
				$leavetime_cn .= date('n',$leavetime).'月';
				$leavetime_cn .= date('j',$leavetime).'日';
				$leavetime_en .= date('M',$leavetime).'. '.date('j',$leavetime).', '.date('Y',$leavetime);
			}
			$opentime_cn = '';
			$opentime_en = '';
			
			if(!empty($openstime)){
				$opentime_cn .= date('Y',$openstime).'年';
				$opentime_cn .= date('n',$openstime).'月';
				$opentime_cn .= date('j',$openstime).'日';
				$opentime_en .= date('M',$openstime).'. '.date('j',$openstime).', '.date('Y',$openstime);
			}
			$week = '';
			if(!empty($leavetime) && !empty($openstime)){
				$diff = abs($leavetime - $openstime);
				$week = floor($diff/(24*60*60*7));
			}
			$ennowtime = '';
			$now_year = date('Y',time());
			for($i = 0; $i < 4; $i++){
				$ennowtime .= $this->chinese_big[$now_year[$i]];
			}
			$ennowtime .= '年';
			$now_month = date('n',time());
			if($now_month <= 9){
				$ennowtime .= $this->chinese_big[$now_month];
			}
			if($now_month == 10){
				$ennowtime .= '十';
			}
			if($now_month == 11){
				$ennowtime .= '十一';
			}
			if($now_month == 12){
				$ennowtime .= '十二';
			}
			$ennowtime .='月';
			$now_day = date('j',time());
			
			if($now_day <= 9){
				$ennowtime .= $this->chinese_big[$now_day];
			}else{
				if($now_day % 10 == 0){
					$now_day_shang = $now_day / 10;
					if($now_day_shang == 1){
						$ennowtime .= '十';
					}else if($now_day_shang == 2){
						$ennowtime .='二十';
					}else if($now_day_shang == 3){
						$ennowtime .='三十';
					}
				}else{
					//判断一下 商
					$shang = intval($now_day / 10);
					
					$yu = $now_day % 10;
					if($shang == 1){
						$ennowtime .= '十';
						$ennowtime .= $this->chinese_big[$yu];
					}else if($shang == 2){
						$ennowtime .= '二十';
						$ennowtime .= $this->chinese_big[$yu];
					}else if($shang == 3){
						$ennowtime .= '三十';
						$ennowtime .= $this->chinese_big[$yu];
					}
				}
			}
			$ennowtime .= '日';
			
			$data=array(
			   'cn_nationality' =>!empty($student_info['nationality'])?$this->nationality['global_country_cn'][$student_info['nationality']]:'',
			   'cn_name' =>!empty($student_info['enname'])?$student_info['enname'].$title_cn:'',			  
			   'cn_sex' =>$this->sex[$student_info['sex']],			 
		       'cn_passport' =>$student_info['passport'],
			   'cn_birthday' =>!empty($birthday)?$birthday:'',
			   'en_name'=> !empty($student_info['enname'])?$title_en.$student_info['enname']:'',					 
		       'en_openstime' =>$opentime_en,
			   'en_passport' =>$student_info['passport'],		
			   'cn_nowtime' =>date('Y-m-d',time()),
			  // 'cn_checking' =>$checking.'%',
			   'en_birthday' =>!empty($birthday_en)?$birthday_en:'',
			   'cn_leavetime' =>$leavetime_cn,
			   'en_sex' =>$this->en_sex_3[$student_info['sex']],			  
			   'en_nowtime' =>$ennowtime,
			   'en_leavetime' =>$leavetime_en,
			   'time_degent' =>$week,
			   'en_nationality' =>!empty($student_info['nationality'])?$this->nationality['global_country'][$student_info['nationality']]:'',
			   'cn_openstime'=>$opentime_cn,
			   'cn_enrolltime'=>!empty($student_info['createtime'])?date('Y-m-d',$student_info['createtime']):'',
			   'cn_majorname'=>!empty($major_info['name'])?$major_info['name']:'',
			   'en_majorname'=>!empty($major_info['englishname'])?$major_info['englishname']:'',
			    );
			
			return $data;
		}
		return array();

	}
	/**
	 * [_zaixiaozhengming 获取在校证明字段
	 * @return [type] [description]
	 */
	function _lixiaozhengming($student_info,$info){
		if(!empty($student_info)){
			$fields_all=explode(',',$info['fields']);
			if(!empty($student_info['squadid'])){
				$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
				$major_infos=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				if(!empty($squad_info['classtime'])){
				    $openstime=date('Y-m',$squad_info['classtime']);
				}else{
				    $openstime='';
				}
				
				if(!empty($major_infos['termdays'])){
					$leavetime=date('Y-m',$squad_info['classtime']+$major_infos['termdays']*3600*24);
				}else{
				    
			    	$leavetime='';
				}
				$major_info=$this->db->get_where('major','id = '.$squad_info['majorid'])->row_array();
				//出勤率
				$checking=$this->get_student_rate($student_info['id'],$squad_info['majorid'],$squad_info['nowterm'],$squad_info['id']);
			}else{
				$checking=100;
				$openstime='';
				$leavetime='';
			}
			
			//学习期限
			$openstime = '';
			$leavetime = '';
			//首先判断 学生表中的 学习 期限 若果 没有 再 计算
			$study_time = $this->db->select('studystarttime,studyendtime')->get_where('student_info','id = '.$student_info['userid'])->row();
			
			if(!empty($study_time->studystarttime)){
				$openstime = $study_time->studystarttime;
			}
			if(!empty($study_time->studyendtime)){
				$leavetime = $study_time->studyendtime;
			}
			if(empty($openstime)){
				$openstime = !empty($student_info['createtime'])?$student_info['createtime']:'';
			}
			
			$cn_leavetime = $cn_enrolltime = $cn_openstime = $en_leavetime = $en_openstime = '';
			
			if(!empty($openstime)){
				 
				$cn_leavetime .=  date('Y',$openstime).'年';
				$cn_leavetime .= date('n',$openstime).'月';
				$cn_leavetime .= date('j',$openstime).'日';
				$cn_enrolltime = $cn_leavetime;
				$en_leavetime = date('M',$openstime).'. '.date('j',$openstime).', '.date('Y',$openstime);
			}
			
			if(!empty($leavetime)){
				 
				$cn_openstime .=date('Y',$leavetime).'年';
				$cn_openstime .= date('n',$leavetime).'月';
				$cn_openstime .= date('j',$leavetime).'日';
				$en_openstime = date('M',$leavetime).'. '.date('j',$leavetime).', '.date('Y',$leavetime);
			}
			
			$birthday = '';
			$birthday_en = '';
			if(!empty($student_info['birthday'])){
				$birthday .= date('Y',strtotime($student_info['birthday'])).'年';
				$birthday .= date('n',strtotime($student_info['birthday'])).'月';
				$birthday .= date('j',strtotime($student_info['birthday'])).'日';
				
				$birthday_en .= date('M',strtotime($student_info['birthday'])).'. '.date('j',strtotime($student_info['birthday'])).', '.date('Y',strtotime($student_info['birthday']));
			}
			
			//如果没有班级的话 就用 在学表的专业
			if(empty($major_info)){
				$major_info=$this->db->get_where('major','id = '.$student_info['majorid'])->row_array();
			}
			$cn_nowtime = '';
			$en_nowtime = '';
			$cn_nowtime .= date('Y',time()).'年';
				$cn_nowtime .= date('n',time()).'月';
				$cn_nowtime .= date('j',time()).'日';
				
				$en_nowtime .= date('M',time()).'. '.date('j',time()).', '.date('Y',time());
			$data=array(
			   'cn_nationality' =>$this->nationality['global_country_cn'][$student_info['nationality']],
			   'cn_name' =>!empty($student_info['name'])?$student_info['name']:$student_info['enname'],			  
			   'cn_sex' =>$this->sex[$student_info['sex']],			 
		       'cn_passport' =>$student_info['passport'],
			   'cn_birthday' =>$birthday,
			   'en_name'=> $student_info['enname'],				 
		       'en_openstime' =>$en_openstime,
			   'en_passport' =>$student_info['passport'],		
			   'cn_nowtime' =>$cn_nowtime,
			   'cn_checking' =>'',
			   'en_birthday' =>$birthday_en,
			   'cn_leavetime' => $cn_leavetime,
			   'en_sex' =>$this->en_sex[$student_info['sex']],			  
			   'en_nowtime' =>$en_nowtime,
			   'en_leavetime' =>$en_leavetime,
			   'en_nationality' =>$this->nationality['global_country'][$student_info['nationality']],
			   'cn_openstime'=> $cn_openstime,
			   'cn_enrolltime'=>$cn_enrolltime,
			   'cn_majorname'=>!empty($major_info['name'])?$major_info['name']:'',
			   'en_majorname'=>!empty($major_info['englishname'])?$major_info['englishname']:'',
			    );
			return $data;
		}
		return array();

	}


	/*获取学生出勤比例*/
	function get_student_rate($studentid,$majorid,$nowterm,$squadid){
		$total = $this->get_rate($majorid,$nowterm,$squadid);
		if($total == 0){
			$total = $this->get_ma_rate($majorid,$nowterm,$squadid);
		}
		$num = $this->get_type_num($studentid,$majorid,$squadid,$nowterm,1);
		if($num==0){
			return 100;
		}
		$spr =sprintf("%.2f",($total-$num)/$total);
		$spr = (float)$spr;
		$spr=($spr*100);
		return $spr;
	}
	/*获取学生迟到次数*/
	function get_type_num($studentid,$majorid,$squadid,$nowterm,$type){
		$this->db->select('count(*) as num');
		$this->db->where('studentid = '.$studentid.' AND majorid = '.$majorid.' AND squadid = '.$squadid.' AND nowterm = '.$nowterm.' AND type = '.$type);
		$data=$this->db->get('checking')->row_array();
		if(!empty($data)){
			return $data['num'];
		}
	}
	/*获取出勤率(班级)*/
	function get_rate($majorid,$nowterm,$squadid){
		$squad_spac = $this->get_squad_spac_data($squadid);
		$time_num = $squad_spac['classtime']+24*3600*$squad_spac['spacing'];
		if($squad_spac['spacing']){
			$num = 0;
			for ($i=$squad_spac['classtime']; $i < $time_num; $i+=24*3600) { 
				$week = date('w',$i);
				if($week == 0){
					$week = 7;
				}
				$nt = $this->get_day_count_course($majorid,$squadid,$nowterm,$week);
				$num+=$nt;
			}
			return $num;
		}else{
			return 0;
		}
		
	}
	/*获取出勤率（专业）*/
	function get_ma_rate($majorid,$nowterm,$squadid){
		$major_spac = $this->get_major_spac_data($majorid);
		$time_num = $major_spac['opentime']+24*3600*$major_spac['termdays'];
		if($major_spac['termdays']){
			$num = 0;
			for ($i=$major_spac['opentime']; $i < $time_num; $i+=24*3600) { 
				$week = date('w',$i);
				if($week == 0){
					$week = 7;
				}
				$nt = $this->get_day_count_course($majorid,$squadid,$nowterm,$week);
				$num+=$nt;
			}
			return $num;
		}else{
			return 0;
		}
		
	}
	/*获取班级的开班时间和学期跨度*/
	function get_squad_spac_data($squadid){
		$this->db->select('classtime,spacing');
		if(!empty($squadid)){
			$this->db->where('id',$squadid);
		}
		$data = $this->db->get('squad')->row_array();
		return $data;
	}
	/*获取专业的开学时间和学期天数*/
	function get_major_spac_data($major){
		$this->db->select('opentime,termdays');
		if(!empty($major)){
			$this->db->where('id',$major);
		}
		$data = $this->db->get('major')->row_array();
		return $data;
	}
	function get_day_count_course($majorid,$squadid,$nowterm,$week){
		$this->db->select('count(*) as num');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		if(!empty($nowterm)){
			$this->db->where('nowterm',$nowterm);
		}
		if(!empty($week)){
			$this->db->where('week',$week);
		}		
		$data=$this->db->get('scheduling')->row_array();
		return $data['num'];
	}
	/**
	 * 申请那一片的打印
	 */
	function apply(){
		$print_tempid=$this->input->get('print_tempid');
		$applyid=$this->input->get('applyid');
		$info=$this->db->get_where('print_template','id = '.$print_tempid)->row_array();
		$return_data=$this->_get_datas($info,$applyid);
		$this->load->view('/master/public/zust_print',array(
				'info'=>$info,
				'return_data'=>$return_data
			));
	}
	/**
	 * [_get_datas 获取数据]
	 * @return [type] [description]
	 */
	function _get_datas($info,$applyid){
		if(!empty($info)&&!empty($applyid)){
			$app_info=$this->db->get_where('apply_info','id = '.$applyid)->row_array();
			$user_info=$this->db->get_where('student_info','id = '.$app_info['userid'])->row_array();

			if($info['parentid']==2){
				$data=$this->_biao($app_info,$user_info,$info);
			}
			if($info['parentid']==7){
				$data=$this->_youdi($app_info,$user_info,$info);
			}
			if($info['parentid']==3){
				$data=$this->_tongzhishu($app_info,$user_info,$info);
			}
		}
		return $data;
	}
	/**
	 * [_biao 202表]
	 * @return [type] [description]
	 */
	function _biao($apply_info,$user_info,$info){
		$major_info=$this->db->get_where('major','id = '.$apply_info['courseid'])->row_array();
		$str = $this->db->select ( 'key,value' )->get_where ( 'apply_template_info', 'applyid = ' . $apply_info['id'] )->result_array ();
		foreach ( $str as $key => $v ) {
			$data [$v ['key']] = $v ['value'];
		}
		
		$studenttype = $this->db->get_where('degree_info','id = '.$major_info['degree'])->row();
		
		 //计算时间
		 $opentime = $major_info['opentime'];
		 $schooling = $major_info['schooling'];
		 $xzunit = $major_info['xzunit'];
		 if($xzunit == 1){
			 $x = 7*24*3600;
		 }else if($xzunit == 2){
			 $x = 30*24*3600;
		 }else if($xzunit == 3){
			 $x = 180*24*3600;
		 }else if($xzunit == 4){
			 $x = 365*24*3600;
		 }
		
		$endtime = $opentime + $schooling*$x;
		
		$data=array(
				'birth_place'=>!empty($data['placeofbirth'])?$data['placeofbirth']:'',
				'marital'=>!empty($user_info['marital'])?($user_info['marital']==1?'已婚':'未婚'):'未婚',
				'sex'=>!empty($user_info['sex'])?($user_info['sex']==1?'男':'女'):'',
				'passport'=>!empty($user_info['passport'])?$user_info['passport']:'',
				'majorid'=>!empty($major_info['name'])?$major_info['name']:'',
				'tel'=>!empty($user_info['tel'])?$user_info['tel']:'',
				'birthday_day'=>!empty($user_info['birthday'])?date('d',$user_info['birthday']):'',
				'birthday_month'=>!empty($user_info['birthday'])?date('m',$user_info['birthday']):'',
				'birthday_year'=>!empty($user_info['birthday'])?date('Y',$user_info['birthday']):'',
				'endtime_year' => !empty($major_info['opentime'])?date('Y',$major_info['opentime']):'',
				'endtime_month' => !empty($major_info['opentime'])?date('m',$major_info['opentime']):'',
				'endtime_day' => !empty($major_info['opentime'])?date('m',$major_info['opentime']):'',
				'nationality'=>$this->nationality['global_country_cn'][$user_info['nationality']],
				'lastname'=>!empty($user_info['lastname'])?$user_info['lastname']:'',
				'firstname'=>!empty($user_info['firstname'])?$user_info['firstname']:'',
				'enname'=>!empty($user_info['enname'])?$user_info['enname']:'',
				'name'=>!empty($user_info['chname'])?$user_info['chname']:'',
				'school'=>'浙江科技学院',
				'last_degreeid' =>!empty($data['HighestDegree'])?$data['HighestDegree']:'',
				'profession' =>!empty($data['Occupation'])?$data['Occupation']:'',
				'houseaddress' =>!empty($data['Homeaddress'])?$data['Homeaddress']:'',
				'referrals_unit' =>!empty($data['Recommending'])?$data['Recommending']:'',
				'finance_people' =>!empty($data['Efullname'])?$data['Efullname']:'',
				'tel' =>!empty($data['Cphone'])?$data['Cphone']:'',
				'bondsman' => '浙江科技学院',
				'bondsman_tel' => '0086-571-85070141',
				'studenttype' => !empty($studenttype->title)?$studenttype->title:'',
				'opentime_year' => date('Y',$opentime),
				'opentime_month' => date('m',$opentime),
				'opentime_year_1' => date('Y',$endtime),
				'opentime_month_1' => date('m',$endtime),
			);
			
		return $data;
	}
	/**
	 * [_biao 202表]
	 * @return [type] [description]
	 */
	function _youdi($apply_info,$user_info,$info){
		$major_info=$this->db->get_where('major','id = '.$apply_info['courseid'])->row_array();
		$data=array(
				'receiptaddress'=>!empty($user_info['birthplace'])?$user_info['birthplace']:'',
				'receipttel'=>!empty($user_info['tel'])?$user_info['tel']:'',
				'receiptname'=>!empty($user_info['chname'])?$user_info['chname']:'',
				'school'=>'浙江科技学院'
			);
		return $data;
	}
	/**
	 * [_tongzhishu 通知书]
	 * @return [type] [description]
	 */
	function offer(){
		$grf=$this->input->post();
		$result = $this->change_offer_status_model->get_one($grf['applyid']);
			//生成一个 e-offer的 pdf 通知书
			$this->load->library('sdyinc_print');
			// 开学时间
			$opening_date = $this->input->get('opening_date');
			//报到截止时间
			$report_end_time = $this->input->get('report_end_time');
			//学历名称
			$degree=$this->db->get_where('degree_info','id = '.$result->degree)->row_array();
			//编号     年份 学历编号 专业编号 学期 录取好
			$number='';
			$number=!empty($result->notification_number)?$result->notification_number:'';
			$user_info=$this->db->get_where('student_info','id = '.$result->userid)->row();
			//获取专业
			if($grf['print_tempid']=102){
				$data = array(
					'name' => !empty($user_info->firstname)||!empty($user_info->lastname) ? $user_info->firstname.' '.$user_info->lastname : '',
					'major' => !empty($result->englishname) ? $result->englishname : '',
					//'opentime' => !empty($result->opentime) ? date('Y-m-d', $result->opentime) : '',
					//'sendtime' => date('Y-m-d', time())
					'opening_date' => !empty($grf['studystime'])?$grf['studystime']:'',
					'report_end_time' => !empty($grf['studyetime'])?$grf['studyetime']:'',
					'now_time'=>date('Y-m-d',time()),
					'degree'=>$degree['entitle'],
					'number'=>$number,
					'address_in' => 'No.318,Liuhe Road,Hangzhou,Zhejiang Province'
				
				);
			}
		$info=$this->db->get_where('print_template','id = '.$grf['print_tempid'])->row_array();
		$this->load->view('/master/public/zust_print',array(
				'info'=>$info,
				'return_data'=>$data
			));
	}
	/**
	 * [_tongzhishu 通知书]
	 * @return [type] [description]
	 */
	function student_offer(){
		$grf=$this->input->post();
		
		$result = $this->db->get_where('student','id = '.$grf['studentid'])->row();
		
			//生成一个 e-offer的 pdf 通知书
			$this->load->library('sdyinc_print');
			// 开学时间
			$opening_date = $this->input->get('opening_date');
			//报到截止时间
			$report_end_time = $this->input->get('report_end_time');
			if(!empty($result->majorid)){
				$majorid=$result->majorid;
			}else{
				$majorid=$result->major;
			}
			$major_info=$this->db->get_where('major','id = '.$majorid)->row_array();

			//学历名称
			$degree=$this->db->get_where('degree_info','id = '.$major_info['degree'])->row_array();
			//编号     年份 学历编号 专业编号 学期 录取好
			$number='';
			//$apply_info=$this->db->get_where('apply_info','id = '.$result->applyid)->row_array();
			//$number=!empty($apply_info['notification_number'])?$apply_info['notification_number']:'';
			$opening_dates = '';
			$report_end_times = '';
			if(!empty($grf['studystime'])){
				$opening_dates = date('M',strtotime($grf['studystime'])).'. '.date('j',strtotime($grf['studystime'])).', '.date('Y',strtotime($grf['studystime']));
			}
			if(!empty($grf['studyetime'])){
				$report_end_times = date('M',strtotime($grf['studyetime'])).'. '.date('j',strtotime($grf['studyetime'])).', '.date('Y',strtotime($grf['studyetime']));
			}
			//获取专业
			if($grf['print_tempid']=102){
				$data = array(
					'name' => !empty($result->enname)?$result->enname: '',
					'major' => !empty($major_info['englishname']) ? $major_info['englishname'] : '',
					//'opentime' => !empty($result->opentime) ? date('Y-m-d', $result->opentime) : '',
					//'sendtime' => date('Y-m-d', time())
					'opening_date' => $opening_dates,
					'report_end_time' =>$report_end_times,
					'now_time'=>date('Y-m-d',time()),
					'degree'=>$degree['entitle'],
					'number'=>'',
					'address_in' => 'No.318,Liuhe Road,Hangzhou,Zhejiang Province'
				
				);
			}
		$info=$this->db->get_where('print_template','id = '.$grf['print_tempid'])->row_array();
		$this->load->view('/master/public/zust_print',array(
				'info'=>$info,
				'return_data'=>$data
			));
	}
	/**
	 * [print_shouju 打印收据]
	 * @return [type] [description]
	 */
	function print_shouju(){
		$grf=$this->input->post();
		$user_info=$this->db->get_where('student_info','id = '.$grf['userid'])->row_array();
		
			$data = array(
				'abstract'=>'摘要',
				'receiver'=>'收款人',
				'payee'=>'浙江科技学院',	
				'paytype'=>!empty($grf['paytype'])?$this->config_paytype[$grf['paytype']]:'',
				'daxiermb'=>!empty($grf['paid_in'])?$grf['paid_in']:'',
				'rmb'=>!empty($grf['paid_in'])?$grf['paid_in']:'',
				'paymentcontent'=>!empty($grf['type'])?$grf['type']:'',
				'payperson'=>!empty($user_info['enname'])?$user_info['enname']:'',
				'no'=>!empty($grf['proof_number'])?$grf['proof_number']:'',
				'time'=>date('Y-m-d',time()),
			
			);
		
		$info=$this->db->get_where('print_template','id = '.$grf['print_tempid'])->row_array();
		$this->load->view('/master/public/zust_print',array(
				'info'=>$info,
				'return_data'=>$data
			));
	}
}