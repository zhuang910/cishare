<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Created by CUCAS TEAM.
 * User: JunJie
 * E-Mail:zhangjunjie@cucas.cn
 * Date: 15-1-14
 * Time: 下午3:30
 */

class Export_Model extends CI_Model{
	const BOOKS = 'books';
	const T_MAJOR= 'major';
	const T_TEACHER='teacher';
	const T_COURSE='course';
	const T_SQUAD= 'squad';
	const T_MAJOR_COURSE='major_course';
	const T_TEACHER_COURSE='teacher_course';
	const T_CLASSROOM='classroom';
	const T_SCHEDULING='scheduling';
	const T_STUDENT='student';
	const T_SCORE='score';
	const T_EVALUATE_STUDENT = 'evaluate_student';
	const T_SET_SCORE = 'set_score';
	const T_STUDENT_INFO = 'student_info';
	const T_BUDGET = 'budget';
	const T_OUT_TIME = 'out_room';
	const T_ROOM = 'school_accommodation_prices';
	const T_ADMIN_INFO	 = 'admin_info';
	const T_ACC_INFO='accommodation_info';

	public $jie_now = 0;
	public $jie = 0;

	function __construct()
	{
		parent::__construct();

		$max_jie_info = CF ( 'max_jie_info', '', 'application/cache/' );
		$this->jieshu = $max_jie_info['jieshu'];
		$this->jieshu_now = $max_jie_info['jieshu_now'];
	}

	/*
	*获取数据表中的信息
	*/
	function get_data($form_id,$export_join,$filed_str){
		
		if(!empty($export_join[$form_id])){
			$this->db->select($filed_str);
			foreach ($export_join[$form_id] as $k => $v) {
				$ex_str[] = $v;
				}
				for ($i=0; $i < count($export_join[$form_id]) ; $i++) { 
					$this->db->join($ex_str[$i]['table'.$i],$ex_str[$i]['where'.$i]);
				}
		$data = $this->db->get($form_id)->result_array();
		return $data;
		}else{
			$this->db->select($filed_str);
			$data = $this->db->get($form_id)->result_array();
		return $data;
		}
	}
	/*
	*拼凑表格的头部格式
	*/
	function _get_tou($field_lists){
		if(!empty($field_lists)){
			foreach ($field_lists as $m => $n) {
				 	$data[] = array('val'=>$n['name']);
			}
			return $data;
		}
	}
	/*
	*拼凑表格的数据格式
	*insurance_info , course_elective , books_fee
	*/
	function get_info_body($da){
		$arr=array();
		if($da){
			foreach ($da as $k => $v) {
				$arr[$k]=array_values($v);	
			}
		}
		$return_data=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				for ($i=0; $i <count($v) ; $i++) { 
					$return_data[$k+1][$i]=array('val'=>$v[$i]);
				}
			}
		}
		return $return_data;
		
	}

	/*
	*拼凑表格的数据格式
	*student checking
	*/
	function _get_body($arr_set,$da,$score){
		$arr=array();
		if($da){
			foreach ($da as $k => $v) {
				$arr[$k]=array_values($v);
				$scour_n = 0;
				$num = 0;
				if(!empty($arr_set)){
					foreach ($arr_set as $kk => $vv) {
						if(!empty($score)){
							$is='ehr';
							foreach ($score as $key => $value) {
								if($v['id'] == $value['studentid']){
									if($vv==$value['courseid']){
										$scour_n += $value['score'];
										$num++;
										$is=$value['score'];
										break;
									}
								}
							}
							if($is!='ehr'){
								$arr[$k][]=$is;
							}else{
								$arr[$k][]='';
							}
						}

					}
				}
				
				if(!empty($score)){
					$arr[$k][] = floor($scour_n / $num);
				}
			}
		}
		$return_data=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				for ($i=0; $i <count($v) ; $i++) { 
					$return_data[$k+1][$i]=array('val'=>$v[$i]);
				}
			}
		}
		return $return_data;
		
	}	
function type_num($studentid,$majorid,$squadid,$nowterm,$starttime,$endtime,$type){
	if(!empty($starttime) && !empty($endtime)){
		$starttime=strtotime($starttime);
		$endtime = strtotime($endtime);
	}
	$this->db->select('count(*) as num');
	$this->db->where('studentid = '.$studentid.' AND majorid = '.$majorid.' AND squadid = '.$squadid.' AND nowterm = '.$nowterm.' AND type = '.$type.' AND date >= '.$starttime.' AND date<'.$endtime);
	$data=$this->db->get('checking')->row_array();
	if(!empty($data)){
		return $data['num'];
	}
}
function type_chuqin($majorid,$squadid,$nowterm,$starttime,$endtime){
	$courseid = null;
	if(!empty($starttime)){
		$starttime=strtotime($starttime);
	}
	if(!empty($endtime)){
		$endtime=strtotime($endtime);
	}
	$num=0;
	for ($i=$starttime; $i < $endtime; $i+=24*3600) { 
		$week=date('w',$i);
		if($week==0){
			$week=7;
		}
		$sb=$this->get_day_count_course($majorid,$squadid,$nowterm,$week,$courseid);
		$num+=$sb;
	}
	return $num;
}

function get_day_count_course($majorid,$squadid,$nowterm,$week,$courseid,$courseid = null){
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
		if($courseid != null){
			$this->db->where('courseid',$courseid);
		}
		if(!empty($week)){
			$this->db->where('week',$week);
		}		
		$data=$this->db->get(self::T_SCHEDULING)->row_array();
		return $data['num'];
}
/*
	*拼凑表格的数据格式
	*student checking
	*/
	function _get_check_body($set_field,$da,$majorid,$squadid,$nowterm,$starttime,$endtime){
		$arr=array();
		foreach ($da as $key => $v) {
			foreach ($set_field as $kk => $vv) {
				if($vv == 'checking.type_one'){
					$da[$key]['type_one']=$this->type_num($v['id'],$majorid,$squadid,$nowterm,$starttime,$endtime,1);
				}
				if($vv == 'checking.type_two'){
					$da[$key]['type_two']=$this->type_num($v['id'],$majorid,$squadid,$nowterm,$starttime,$endtime,2);
				}
				if($vv == 'checking.type_three'){
					$da[$key]['type_three']=$this->type_num($v['id'],$majorid,$squadid,$nowterm,$starttime,$endtime,3);
				}
				if(in_array('student.id',$set_field)){
					$da[$key]['id'] = $da[$key]['id'];
				}else{
					unset($da[$key]['id']);
				}
			}
			$total=$this->type_chuqin($majorid,$squadid,$nowterm,$starttime,$endtime);
			$fen_one = $this->type_num($v['id'],$majorid,$squadid,$nowterm,$starttime,$endtime,1);
			$he = $fen_one;
			$spr =sprintf("%.2f",($total-$he)/$total);
			$spr = (float)$spr;
			$da[$key]['lv']=($spr*100).'%';

		}
		if($da){
			foreach ($da as $k => $v) {
					$arr[$k]=array_values($v);
				}
		}
		$return_data=array();
		if(!empty($arr)){
			foreach ($arr as $k => $v) {
				for ($i=0; $i < count($v) ; $i++) { 
					$return_data[$k+1][$i]=array('val'=>$v[$i]);
				}
			}
		}
		return $return_data;
		
	}
	/*
	*books_fee student squad books 获取数据
	*/

	function get_books_data($form_id,$export_join,$filed_str){
		if(!empty($export_join[$form_id])){
			$this->db->select($filed_str);
			foreach ($export_join[$form_id] as $k => $v) {
					$ex_str[] = $v;
				}
			for ($i=0; $i < count($export_join[$form_id]) ; $i++) { 
					$this->db->join($ex_str[$i]['table'.$i],$ex_str[$i]['where'.$i]);			
			}
		$data = $this->db->get($form_id)->result_array();
		$data_squadid = $this->db->select('name,id')->get_where('squad','id > 0 AND state = 1')->result_array();
		if(!empty($data_squadid)){
			foreach ($data_squadid as $key => $value) {
				$data_squad[$value['id']] = $value['name'];
			}
		}

		$data_booksid = $this->db->select('name,id')->get_where('books','id > 0 AND state = 1')->result_array();
		if(!empty($data_booksid)){
			foreach ($data_booksid as $key => $value) {
				$data_books[$value['id']] = $value['name'];
			}
		}

		if(!empty($data)){
			foreach ($data as $key => $value) {
				if(!empty($value['squadid']) && !empty($data_squad) && !empty($data_squad[$value['squadid']])){
					$data[$key]['squadid'] = $data_squad[$value['squadid']];
				}else{
					$data[$key]['squadid'] = '';
				}

				if(!empty($value['book_ids'])){
					if(strstr($value['book_ids'],',')){
						
						$book_ids_flag = explode(',', $value['book_ids']);
						$data[$key]['book_ids'] = '';
						foreach ($book_ids_flag as $k => $v) {
							if(!empty($data_books) && !empty($data_books[$v])){

								$data[$key]['book_ids'] .= $data_books[$v].',';
							}
						}
						$data[$key]['book_ids'] = trim($data[$key]['book_ids'],',');
					}else{
						if(!empty($data_books) && !empty($data_books[$value['book_ids']])){
							$data[$key]['book_ids'] = '';
								$data[$key]['book_ids'] = $data_books[$value['book_ids']];
							}
					}

				}else{
					$data[$key]['book_ids'] = '';
				}
			}

		}
		return $data;
		}else{
			$this->db->select($filed_str);
			$data = $this->db->get($form_id)->result_array();
		return $data;
		}
	}

	/**
	 * 获取专业的所有学期
	 */
	function get_major_nowterm($id){
		$this->db->where('id=',$id);
		
		$nowterm=$this->db->get(self::T_MAJOR)->result_array();
		 $arr=array();
		 for($i=1;$i<=$nowterm[0]['termnum'];$i++){
		 	$arr[]=$i;
		 }
		return $arr;
		
	}
	/**
	 * 获取专业的课程
	 */
	function get_course($mid){
		$this->db->select('course.id,course.name as cname');
		$this->db->join(self::T_MAJOR ,self::T_MAJOR.'.id='.self::T_MAJOR_COURSE.'.majorid');
		$this->db->join(self::T_COURSE ,self::T_COURSE.'.id='.self::T_MAJOR_COURSE.'.courseid');
		$this->db->where('major_course.majorid',$mid);

		return $this->db->get(self::T_MAJOR_COURSE )->result_array();
	}
	function get_squad_num($majorid,$nowterm){
		$this->db->select ( 'count("*") as squadnum');
		$this->db->where('majorid',$majorid);
		$this->db->where('nowterm',$nowterm);
		 $data=$this->db->get(self::T_SQUAD)->row_array();
		 return $data['squadnum'];
	}

	/**
	 * 获取专业该学期的班级
	 */
	function get_squadinfo($mid,$term){
		$this->db->where('majorid',$mid);
		$this->db->where('nowterm',$term);
		return $this->db->get(self::T_SQUAD)->result_array();
	}
	/**
	 * 
	 * 获取学生
	 * */

	function get_stu_score(){
		return $this->db->get(self::T_SCORE)->result_array();
	}
	/**
	 * 
	 * 获取详细搜索出来的学生
	 * */
	function get_student_one($squadid,$k,$v){
		$this->db->select('id,studentid,name,email,enname');
		$this->db->where('squadid',$squadid);	
		$this->db->where($k,$v);	
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
	 * 
	 * 获取学生
	 * */
	function get_studentinfo($sid){
		$this->db->select('id,studentid,name,email,enname');
		$this->db->where('squadid',$sid);
		return $this->db->get(self::T_STUDENT)->result_array();
	}
	/**
     * 获取一条老师的半颗信息
     */
    function get_laoshixinxi($data){
        if(!empty($data)){
            $this->db->select('scheduling.*,teacher.*');
            $this->db->where('scheduling.majorid',$data['majorid']);
            $this->db->where('scheduling.courseid',$data['courseid']);
            $this->db->where('scheduling.nowterm',$data['nowterm']);
            $this->db->where('scheduling.squadid',$data['squadid']);
            $this->db->join(self::T_TEACHER ,self::T_TEACHER.'.id='.self::T_SCHEDULING.'.teacherid');
            $this->db->group_by('scheduling.teacherid');
            return $this->db->get(self::T_SCHEDULING)->result_array();
        }
    }
    /**
	 * 获取专业信息
	 */
	function get_majorinfo(){
		return $this->db->get(self::T_MAJOR)->result();
	}
	
	/*
	*拼凑课程表格的头部格式
	*/
	function _get_course_tou($field_lists,$get_course_tou = ''){
		if(!empty($field_lists) && !empty($get_course_tou)){
			foreach ($field_lists as $m => $n) {
				$data[] = array('val'=>$n['name']);
			}
			if(!empty($get_course_tou)){
				foreach ($get_course_tou as $k => $v) {
					$data[] = array('val'=>$v['name']);
				}
			}
			return $data;
		}
	}

	/*
	*获取课程成绩信息
	*/
	function get_course_data($squadid,$table_name,$filed_str){
		if(!empty($filed_str)){
			$this->db->select($filed_str);
			if(!empty($squadid)){
				$this->db->where('squadid',$squadid);
			}
			$data = $this->db->get($table_name)->result_array();
			return $data;
		}
	}

	function get_course_score($majorid,$nowterm,$squadid,$scoretype,$set){
		$this->db->select('score,studentid,courseid');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($nowterm)){
			$this->db->where('term',$nowterm);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		if(!empty($scoretype)){
			$this->db->where('scoretype',$scoretype);
		}
		if(!empty($set) && is_array($set)){
			$this->db->where_in('courseid',$set);
			return $this->db->get('score')->result_array();
		}
		
	}

	/*
	*获取checking表中的数据
	*/
	function get_checking_data($filed_str,$majorid,$squadid,$nowterm,$starttime){
		if(!empty($filed_str)){
			$select = 'student.id,';
			foreach ($filed_str as $key => $value) {
				if($value === 'student.id'){
					$select = 'student.id,';
				}
				if($value === 'student.nationality'){
					$select .= 'student.nationality,';

				}
				if($value === 'student.enname'){
					$select .= 'student.enname,';
				}
				if($value === 'student.name'){
					$select .= 'student.name';
				}
			}
			$this->db->select($select);
			if($majorid){
				$this->db->where('student.majorid',$majorid);
			}
			if($squadid){
				$this->db->where('student.squadid',$squadid);
			}
		
			$data = $this->db->get('student')->result_array();
			return $data;
		}
	}

	/*
	*获取班主任名字
	*/
	function get_teacher_name($teacherid){
		$this->db->select('teacher');
		if(!empty($teacherid)){
			$this->db->where('id',$teacherid);
		}
		$teacher_data = $this->db->get(self::T_SQUAD)->row_array();
		$teacher_name = $this->get_name($teacher_data['teacher']);
		return $teacher_name['name'];
	}
	function get_name($id){
		$this->db->select('name');
		if(!empty($teacher_data)){
			$this->db->where('id',$id);
		}
		return $this->db->get(self::T_TEACHER)->row_array();	
	}

	/*
	*获取学生评教班级汇总表的数据
	*/
	function get_squ_data($majorid,$nowterm,$year){
		$this->db->select('id as squad_id,squadid as squad_name,year as evaluat_count,courseid as course_name,teacherid as teacher_name,item as evaluate_student_item');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($nowterm)){
			$this->db->where('term',$nowterm);
		}
		if(!empty($year)){
			$this->db->where('year',$year);
		}
		$this->db->group_by('squadid,majorid,year');
		$data = $this->db->get(self::T_EVALUATE_STUDENT)->result_array();
		return $data;
	}
	/*右侧数据信息*/
	function get_rig_data($majorid,$nowterm,$year){
		$this->db->select('id as squad_id,squadid as squad_name,year as evaluat_count,courseid as course_name,teacherid as teacher_name,item as evaluate_student_item');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($nowterm)){
			$this->db->where('term',$nowterm);
		}
		if(!empty($year)){
			$this->db->where('year',$year);
		}
		$data = $this->db->get(self::T_EVALUATE_STUDENT)->result_array();
		return $data;
	}
	/*获取班级id*/
	function get_squ_id($squadid){
		$this->db->select('id');
		if(!empty($squadid)){
			$this->db->where('id',$squadid);
		}
		$data = $this->db->get(self::T_SQUAD)->row_array();
		return $data['id'];
	}
	/*获取班级的名字*/
	function get_squ_name($squadid){
		$this->db->select('name');
		if(!empty($squadid)){
			$this->db->where('id',$squadid);
		}
		$data = $this->db->get(self::T_SQUAD)->row_array();
		return $data['name'];
	}
	/*count评教人数*/
	function get_eva_num($squadid){
		$this->db->select('count(*) as num');
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$data = $this->db->get(self::T_EVALUATE_STUDENT)->row_array();
		return $data['num'];
	}
	/*获取课程名称*/
	function get_course_name($courseid){
		$this->db->select('name');
		if(!empty($courseid)){
			$this->db->where('id',$courseid);
		}
		$data = $this->db->get(self::T_COURSE)->row_array();
		return $data['name'];
	}
	/*获取老师名称*/
	function get_tea_name($teacherid){
		$this->db->select('name');
		if(!empty($teacherid)){
			$this->db->where('id',$teacherid);
		}
		$data = $this->db->get(self::T_TEACHER)->row_array();
		return $data['name'];
	}
	/*计算评教分*/
	function get_eva_item($str){
		if(!empty($str)){
			$resu = json_decode($str,true);
		}
		$arr = array();
		$num = 0;
		foreach ($resu as $key => $value) {
			if(strstr($key,'item')){
				$arr = explode('-grf-', $value);
				$num += $arr[1];
			}
		}
		return $num;
	}
	function get_rig_str($rig_data,$squadid){
		if(!empty($rig_data)){
			foreach ($rig_data as $key => $value) {
				if($value['squad_name'] == $squadid){
					$this->db->select('course.name as course_name,teacher.name as teacher_name,evaluate_student.item as evaluate_student_item');
					$this->db->where('course.id',$value['course_name']);
					$this->db->where('teacher.id',$value['teacher_name']);
					$this->db->join('course','course.id=evaluate_student.courseid');
					$this->db->join('teacher','teacher.id=evaluate_student.teacherid');
					$data[] = $this->db->get(self::T_EVALUATE_STUDENT)->row_array();
				}
			}
			foreach ($data as $xx => $vv) {
				if($vv==null){
					continue;
				}
				$data[$xx]['evaluate_student_item'] = $this->get_eva_item($vv['evaluate_student_item']);
			}
			
			return $data;
		}
	}
	/*****************************/
	function _get_squ_body($rig_data,$field_lists,$filed_str,$da,$majorid,$nowterm,$year){
		$arr = array();
		foreach ($da as $k => $v) {
			$da[$k]['squad_id'] = $this->get_squ_id($v['squad_name']);
			$da[$k]['squad_name'] = $this->get_squ_name($v['squad_name']);
			$da[$k]['evaluat_count'] = $this->get_eva_num($v['squad_name']);
			$da[$k]['squ_rig'] = $this->get_rig_str($rig_data,$v['squad_name']);
		}
			// for($i=0;$i< count($da); $i++) {
			// 	if(in_array('squad_id', $filed_str) == false){
			// 		unset($da[$i]['squad_id']);
			// 	}
			// 	if(in_array('squad_name', $filed_str) == false){
			// 		unset($da[$i]['squad_name']);
			// 	}
			// 	if(in_array('evaluat_count', $filed_str) == false){
			// 		unset($da[$i]['evaluat_count']);
			// 	}
			// }
			// 	foreach ($da as $xx => $vv) {
			// 		if(in_array('course_name', $filed_str) == false){
			// 			unset($da[$xx]['squ_rig']['val']['course_name']);
			// 		}
			// 		if(in_array('teacher_name', $filed_str) == false){
			// 			unset($da[$xx]['squ_rig']['val']['teacher_name']);
			// 		}
			// 		if(in_array('evaluate_student_item', $filed_str) == false){
			// 			unset($da[$xx]['squ_rig']['val']['evaluate_student_item']);
			// 		}
			// 	}
				foreach ($da as $jks => $wls) {
					unset($da[$jks]['course_name']);
					unset($da[$jks]['teacher_name']);
					unset($da[$jks]['evaluate_student_item']);
				}
		return $da;
		
	}
	/*获取学生评教教师的信息*/
	function get_teacher_data($majorid,$year){
		$this->db->select('id,teacherid,item');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($year)){
			$this->db->where('year',$year);
		}
		$this->db->group_by('teacherid');
		$data = $this->db->get(self::T_EVALUATE_STUDENT)->result_array();
		foreach ($data as $key => $value) {
			$data[$key]['id'] = $value['teacherid'];
			$data[$key]['teacherid'] = $this->get_tea_name($value['teacherid']);
			$data[$key]['item'] = $this->get_eva_teacher_item($value['teacherid']);
		}
		return $data;
	}
	/*获取评教分*/
	function get_eva_teacher_item($teacherid){
		$this->db->select('item');
		if(!empty($teacherid)){
			$this->db->where('teacherid',$teacherid);
		}
		$data = $this->db->get(self::T_EVALUATE_STUDENT)->result_array();
		foreach ($data as $key => $value) {
			$data[$key]['item'] = $this->get_eva_item($value['item']);
		}
		return $data;
	}
	/*语言生成绩统计score*/
	function get_score_data($majorid,$squadid){
		$this->db->select('id,nationality,enname,name');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$data = $this->db->get(self::T_STUDENT)->result_array();
		return $data;
	}
	/*获取排课课程的名称*/
	function get_score_course_data($majorid,$squadid){
		$this->db->select('courseid');
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$this->db->group_by('courseid');
		$data = $this->db->get(self::T_SCHEDULING)->result_array();
		foreach ($data as $key => $value) {
			$data[$key]['id'] = $value['courseid'];
			$data[$key]['courseid'] = $this->get_course_name($value['courseid']);
		}
		return $data;
	}
	/*获取考试类型的数据*/
	function get_exam($studentid,$id,$typeid,$majorid,$term,$squadid){
		$this->db->select('score');
		if(!empty($studentid)){
			$this->db->where('studentid',$studentid);
		}
		if(!empty($id)){
			$this->db->where('courseid',$id);
		}
		if(!empty($typeid)){
			$this->db->where('scoretype',$typeid);
		}
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($term)){
			$this->db->where('term',$term);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$data = $this->db->get(self::T_SCORE)->row_array();
		if($data){
			return $data['score'];
		}else{
			return '-';
		}
	}

	
	/*获取课堂表现分*/
	function get_class($studentid,$id,$majorid,$term,$squadid,$typeids){
		if(!empty($typeids)){
			$score=0;
			$age=0;
			foreach ($typeids as $key => $value) {
				$s=$this->get_type_course_score($studentid,$id,$majorid,$term,$squadid,$value);
				if($s!='shh'){
					$score+=$s;
					$age+=1;
				}
			}
			if(!empty($score)){
				return sprintf("%.2f", $score/$age);
			}
		}
		return 0;
	}
	function get_type_course_score($studentid,$id,$majorid,$term,$squadid,$typeid){
		$this->db->select('show_score');
		if(!empty($studentid)){
			$this->db->where('studentid',$studentid);
		}
		if(!empty($id)){
			$this->db->where('courseid',$id);
		}
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($term)){
			$this->db->where('term',$term);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		if(!empty($typeid)){
			$this->db->where('scoretype',$typeid);
		}
		$data = $this->db->get(self::T_SCORE)->row_array();
		if($data){
			return $data['show_score'];
		}else{
			return 'shh';
		}
	}
	/*获取学生出勤比例*/
	function get_student_rate($studentid,$majorid,$nowterm,$squadid,$courseid){
		$total = $this->get_rate($majorid,$nowterm,$squadid,$courseid);
		if($total == 0){
			$total = $this->get_ma_rate($majorid,$nowterm,$squadid,$courseid);
		} 
		$num = $this->get_type_num($studentid,$majorid,$squadid,$nowterm,1,$courseid);
		if($num != 0){
			$spr =sprintf("%.2f",($total-$num)/$total);
			$spr = (float)$spr;
			$spr=($spr*100);
			return $spr;
		}else{
			return 100;
		}	
	}
	/*获取学生迟到次数*/
	function get_type_num($studentid,$majorid,$squadid,$nowterm,$type,$courseid){
		$this->db->select('count(*) as num');
		$this->db->where('courseid = '.$courseid.' AND studentid = '.$studentid.' AND majorid = '.$majorid.' AND squadid = '.$squadid.' AND nowterm = '.$nowterm.' AND type = '.$type);
		$data=$this->db->get('checking')->row_array();
		if(!empty($data)){
			return $data['num'];
		}
	}
	/*获取出勤率(班级)*/
	function get_rate($majorid,$nowterm,$squadid,$courseid){
		$squad_spac = $this->get_squad_spac_data($squadid);
		$time_num = $squad_spac['classtime']+24*3600*$squad_spac['spacing'];
		if($squad_spac['spacing']){
			$num = 0;
			for ($i=$squad_spac['classtime']; $i < $time_num; $i+=24*3600) { 
				$week = date('w',$i);
				if($week == 0){
					$week = 7;
				}
				$nt = $this->get_day_count_course($majorid,$squadid,$nowterm,$week,$courseid);
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
				$nt = $this->get_day_count_course($majorid,$squadid,$nowterm,$week,$courseid);
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
		$data = $this->db->get(self::T_SQUAD)->row_array();
		return $data;
	}
	/*获取专业的开学时间和学期天数*/
	function get_major_spac_data($major){
		$this->db->select('opentime,termdays');
		if(!empty($major)){
			$this->db->where('id',$major);
		}
		$data = $this->db->get(self::T_MAJOR)->row_array();
		return $data;
	}
	/*获得考试类型的数量*/
	function get_set_score_num(){
		$this->db->select('count(*) as num');
		$data = $this->db->get(self::T_SET_SCORE)->row_array();	
		return $data['num'];
	}

	/**/
	function get_per_data($studentid,$id,$typeid,$majorid,$term,$squadid){
		$this->db->select('score');
		if(!empty($studentid)){
			$this->db->where('studentid',$studentid);
		}
		if(!empty($id)){
			$this->db->where('courseid',$id);
		}
		if(!empty($typeid)){
			$this->db->where('scoretype',$typeid);
		}
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($term)){
			$this->db->where('term',$term);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$data = $this->db->get(self::T_SCORE)->row_array();
		$per = $this->get_per($typeid);
		if(!empty($data['score'])){
			$per_num = $data['score']*$per;
		}else{
			$per_num = 0;
		}
		return $per_num;
			
	}
	/*获得考试类型所占的百分比*/
	function get_per($id){
		$this->db->select('scores_of');
		if(!empty($id)){
			$this->db->where('id',$id);
		}
		$data = $this->db->get(self::T_SET_SCORE)->row_array();
		return $data['scores_of'];
	}
	/*获取专业名称*/
	function get_major_name($id){
		$this->db->select('name');
		if(!empty($id)){
			$this->db->where('id',$id);
		}
		$data = $this->db->get(self::T_MAJOR)->row_array();
		return $data['name'];
	}
	//获取所有学生评教的分数
	function get_tea_course($squadid,$courseid,$teacherid,$year){
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
			$this->db->where('courseid',$courseid);
			$this->db->where('teacherid',$teacherid);
			$this->db->where('year',$year);
			$data=$this->db->get(self::T_EVALUATE_STUDENT)->result_array();
			$score=0;
			foreach ($data as $key => $v) {
					if(!empty($v['item'])){
						$arr=json_decode($v['item'],true);
						if(!empty($arr)){
							foreach ($arr as $kk => $vv) {
								if(strstr($kk, 'item')){
									$hh=explode('-grf-', $vv);
									$score+=$hh[1];
								}
							}
						}
					}
			
			}	
			return $score/count($data);
		}
	}

	/*从student表中获取userid*/
	function get_budget_data($majorid,$squadid){
		if(!empty($majorid)){
			$this->db->where('majorid',$majorid);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$data = $this->db->get(self::T_STUDENT)->result_array();
		return $data;
	}
	/*获取学生的信息*/
	function get_user_info($id){
		$this->db->select('enname,chname,nationality,sex,birthday,passport');
		if(!empty($id)){
			$this->db->where('id',$id);
		}
		return $this->db->get(self::T_STUDENT_INFO)->row_array();
	}
	//获取类别费用的总和
	function _get_type_fee($userid,$term,$type,$budget_type,$paytype){
		$datas['money']='';
		$datas['remark']='';
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('term',$term);
			$this->db->where('type',$type);
			$this->db->where('budget_type',$budget_type);
			if($paytype!=0){
				$this->db->where('paytype',$paytype);
			}
			$data=$this->db->get(self::T_BUDGET)->result_array();
			$money='';
			$remark='';
			$type_name=$this->type_name();
			if(!empty($data)){
				foreach ($data as $key => $value) {
					if($budget_type==1){
						$money+=$value['paid_in'];
					}elseif($budget_type==2){
						$money+=$value['true_returned'];
					}
					$remark.=$type_name[$type].'--'.$value['remark'].'。';
				}
				$datas['money']=$money;
				$datas['remark']=$remark;
				return $datas;
			}else{
				return $datas;
			}
		}
		return $datas;
	}
	function type_name(){
		return  array( 
		    	1 => '申请费',
		    	2 => '押金',
		    	3 => '接机费',
		    	4 => '住宿费',
		    	5 => '入学押金',
		    	6 => '学费',
		    	7 => '电费',
		    	8 => '书费',
		    	9 => '保险费',
		    	10 => '住宿押金费',
		    	11 => '换证费',
		    	12 => '重修费',
		    	13 => '床品费',
		    	14 => '电费押金',
		    	15 => '申请减免学费',
		    	16=>'奖学金费用'
		    );
	}
	
	function get_student_info($opentime,$endtime){
		if(!empty($opentime) && !empty($endtime)){
			$opentime = strtotime($opentime);
			$endtime = strtotime($endtime);
			$this->db->group_by('userid');
			return $this->db->get_where('credentials','updatetime >= '.$opentime.' AND updatetime <= '.$endtime.' AND state = 1')->result_array();
		}else{
			return '';
		}
		
	}
	/**
	 * 获取凭据类型的金钱
	 * */
	function _get_type_cre_fee($userid,$item,$update_opentime,$update_endtime){
		if(!empty($update_opentime)){
			$update_opentime = strtotime($update_opentime);
		}
		if(!empty($update_endtime)){
			$update_endtime = strtotime($update_endtime);
		}
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('updatetime >=',$update_opentime);
			$this->db->where('updatetime <=',$update_endtime);
			$this->db->where('item',$item);
			$data=$this->db->get('credentials')->result_array();
			$money=0;
			if(!empty($data)){
				foreach ($data as $k => $v) {
					$money+=$v['amount'];
				}
				return $money;
			}else{
				return '';
			}
		}
		return '';
	}
	/*获取班级下的所有课程*/
	function get_score_coursename($id){
		$this->db->select('id,name');
		if(!empty($id)){
			$this->db->where('id',$id);
		}
		return $this->db->get(self::T_COURSE)->result_array();
	}
	/*获取排课表中的老师id*/
	function get_teacher_score_name($nowterm,$squadid,$courseid){
		$this->db->select('teacherid');
		if(!empty($nowterm)){
			$this->db->where('nowterm',$nowterm);
		}
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		if(!empty($courseid)){
			$this->db->where('courseid',$courseid);
		}
		$this->db->group_by('teacherid');
		$data = $this->db->get(self::T_SCHEDULING)->row_array();
		$tea_name = $this->get_tea_name($data['teacherid']);
		return $tea_name;
	}
	/*获取班级id*/
	function get_nt_cou($squadid){
		if(!empty($squadid)){
			$this->db->where('squadid',$squadid);
		}
		$this->db->group_by('courseid');
		return $this->db->get(self::T_SCORE)->result_array();
	}
	/*从out_room表中获取userid*/
	function get_acc_data($opentime,$endtime){
		if(!empty($opentime)){
			$opentime = strtotime($opentime);
		}
		if(!empty($endtime)){
			$endtime = strtotime($endtime);
		}
		$this->db->where('out_time >='.$opentime.' AND out_time <= '.$endtime);
		return $this->db->get(self::T_OUT_TIME)->result_array();
	}
	/*获取学生信息*/
	function get_acc_student($userid){
		$this->db->select('id,name,enname,nationality,majorid');
		if(!empty($userid)){
			$this->db->where('userid',$userid);
		}
		$data = $this->db->get(self::T_STUDENT)->row_array();
		foreach ($data as $key => $value) {
			if($key == 'majorid'){
				$data['majorid'] = $this->get_major_name($value);
			}
		}
		return $data;
	}
	/*获取房间名*/
	function get_room_name($roomid){
		$this->db->select('name');
		if(!empty($roomid)){
			$this->db->where('id',$roomid);
		}
		$data = $this->db->get(self::T_ROOM)->row_array();
		return $data['name'];
	}
	/*获取管理员名称*/
	function get_admin_name($id){
		$this->db->select('nikename');
		if(!empty($id)){
			$this->db->where('id',$id);
		}
		$data = $this->db->get(self::T_ADMIN_INFO)->row_array();
		return $data['nikename'];
	}
	/*查询学生信息*/
	function get_student_budget($s_field,$s_name){
		$this->db->select('id,enname,chname,nationality,sex,birthday,passport,studentid');
		if(!empty($s_field)){
			if($s_field == 'chname'){
				$this->db->like('chname',$s_name);
			}
			if($s_field == 'enname'){
				$this->db->like('enname',$s_name);
			}
			if($s_field == 'email'){
				$this->db->like('email',$s_name);
			}
			if($s_field == 'passport'){
				$this->db->like('passport',$s_name);
			}
			if($s_field == 'studentid'){
				$this->db->like('studentid',$s_name);
			}
			return $this->db->get(self::T_STUDENT_INFO)->result_array();
		}
	}
	//获取学生报道I是件
	function get_createtime($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get_where(self::T_STUDENT)->row_array();
		}
	}
	/**
	 *获取该学生的房间名称 
	 * */
	function get_user_room_name($userid){
		if(!empty($userid)){
			//查询学生已经入住的房间
			$acc_info=$this->_get_acc_info($userid);
			if(!empty($acc_info)){
				$room_info=$this->get_room_name($acc_info['roomid']);
				if(!empty($room_info)){
					return $room_info;
				}
			}
		}
		return '';
	}
	/**
	 * 获取房间申请信息
	 * */
	function _get_acc_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			return $this->db->get(self::T_ACC_INFO)->row_array();
		}
	}
	//获取类别费用的总和
	function _get_stu_type_fee($userid,$type,$paytype,$opentime,$endtime){
		if(!empty($opentime)){
			$opentime = strtotime($opentime);
		}
		if(!empty($endtime)){
			$endtime = strtotime($endtime);
		}
		$datas['money']='';
		$datas['remark']='';
		if(!empty($userid)){
			$this->db->where('userid',$userid);
		
			if(!empty($type)){
				$this->db->where('type',$type);
			}
			if($paytype!=0){
				$this->db->where('paytype',$paytype);
			}
			if(!empty($opentime)){
				$this->db->where('createtime >= ',$opentime);
			}
			if(!empty($endtime)){
				$this->db->where('createtime <= ' ,$endtime);
			}
			
			$data=$this->db->get(self::T_BUDGET)->result_array();
			$money='';
			$remark='';
			$type_name=$this->type_name();
			if(!empty($data)){
				foreach ($data as $key => $value) {
					$money+=$value['paid_in'];
					$remark.=$type_name[$type].'--'.$value['remark'].'。';
				}
				$datas['money']=$money;
				$datas['remark']=$remark;
				return $datas;
			}else{
				return $datas;
			}
		return $datas;
		}
	}
}	