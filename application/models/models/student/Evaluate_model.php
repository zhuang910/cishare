<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 *
 * @author zyj
 *        
 */
class Evaluate_Model extends CI_Model {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	const T_E_CLASS = 'evaluate_class';
	const T_E_ITEM = 'evaluate_item';
	const T_E_ITEM_INFO = 'evaluate_item_info';
	const T_E_STUDENT='evaluate_student';
	const T_E_STUDENT_FINISH='evaluate_student_finish';
	const T_STUDENT='student';
	const T_MAJOR='major';
	const T_PAIKE='scheduling';
	const T_TEACHER='teacher';
	const T_SQUAD ='squad';
	const T_COURSE='course';
	const T_E_S='electives_scheduling';
	const T_COURSE_E='course_elective';
	/**
	 * 获取该学生下所有的课程和老师
	 */
	function get_course_teacher($userid){
		if(!empty($userid)){
			//获取该学生所在的班级
			$user_info=$this->get_user_info($userid);
			if(!empty($user_info['squadid'])){
				//获取班级信息
				$squad_info=$this->get_squad_info($user_info['squadid']);
				//获取该班级所有的课程所带的老师
				$course_teacher=$this->get_course_teacher_info($squad_info['id'],$squad_info['nowterm']);
				//查看选修课有没有他的课
				$course_teachers=$this->get_course_teachers_info($userid,$squad_info['id'],$squad_info['nowterm']);
				$arr=array_merge($course_teacher,$course_teachers);
				return $arr;
			}
			
		}
	}
	/**
	 * [get_course_teachers_info 获取选修课的可]
	 * @return [type] [description]
	 */
	function get_course_teachers_info($userid,$sid,$term){
		if(!empty($userid)){
			$this->db->select('course_elective.*,squad.name as sname,course.name as cname,teacher.name as tname');
			$this->db->where('course_elective.squadid',$sid);
			$this->db->where('course_elective.term',$term);
			$this->db->where('course_elective.userid',$userid);
			$this->db->where('course_elective.state',1);
			$this->db->group_by('course_elective.teacherid');
			$this->db->join ( self::T_SQUAD, self::T_COURSE_E . '.squadid=' . self::T_SQUAD . '.id' );
			$this->db->join ( self::T_TEACHER, self::T_COURSE_E . '.teacherid=' . self::T_TEACHER . '.id' );
			$this->db->join ( self::T_COURSE, self::T_COURSE_E . '.courseid=' . self::T_COURSE . '.id' );
			$this->db->where('course.is_evaluate',1);
			return $this->db->get(self::T_COURSE_E)->result_array();
		}
	}
	/**
	 * [get_user_info 获取学生的信息]
	 * @return [type] [description]
	 */
	function get_user_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
	}
	/**
	 * [get_squad_info 获取班级信息]
	 * @return [type] [description]
	 */
	function get_squad_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_SQUAD)->row_array();
		}
	}
	/**
	 * [get_course_teacher_info 获取班级下的课程和老师]
	 * @return [type] [description]
	 */
	function get_course_teacher_info($sid,$term){
		if(!empty($sid)&&!empty($term)){
			$this->db->select('scheduling.*,major.name as mname,squad.name as sname,course.name as cname,teacher.name as tname');
			$this->db->where('scheduling.squadid',$sid);
			$this->db->where('scheduling.nowterm',$term);
			$this->db->group_by('scheduling.teacherid');
			$this->db->join ( self::T_MAJOR, self::T_PAIKE . '.majorid=' . self::T_MAJOR . '.id' );
			$this->db->join ( self::T_SQUAD, self::T_PAIKE . '.squadid=' . self::T_SQUAD . '.id' );
			$this->db->join ( self::T_TEACHER, self::T_PAIKE . '.teacherid=' . self::T_TEACHER . '.id' );
			$this->db->join ( self::T_COURSE, self::T_PAIKE . '.courseid=' . self::T_COURSE . '.id' );
			$this->db->where('course.is_evaluate',1);
			return $this->db->get(self::T_PAIKE)->result_array();
		}
	}
	/**
	 * 获取课程名字
	 */
	function get_course_name($cid,$puri){
		if(!empty($cid)){
			if($puri=='cn'){
				$this->db->select('name as name');
			}else{
				$this->db->select('englishname as name');
			}
			$this->db->where('id',$cid);
			$data=$this->db->get(self::T_COURSE)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * 获取课程名字
	 */
	function get_teacher_name($cid,$puri){
		if(!empty($cid)){
			if($puri=='cn'){
				$this->db->select('name as name');
			}else{
				$this->db->select('englishname as name');
			}
			$this->db->where('id',$cid);
			$data=$this->db->get(self::T_TEACHER)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * [get_class_info 获取评教的类]
	 * @return [type] [description]
	 */
	function get_class_info(){
		$this->db->where('state',1);
		$this->db->order_by('orderby');
		return $this->db->get(self::T_E_CLASS)->result_array();
	}
	/**
	 * [get_item_info 获取相应语言下的类下的项 ]
	 * @return [type] [description]
	 */
	function get_item_info($classid,$prui){
		$arr=array();
		if(!empty($classid)){
			$itemid_arr=$this->get_item_one($classid);
			if(!empty($itemid_arr)){
				foreach ($itemid_arr as $k => $v) {
					$arr[$v['orderby']]=$this->get_item_info_one($v['id'],$prui);

				}
			}
		}
		return $arr;
	}
	/**
	 * 获取一条评教项
	 */
	function get_item_one($classid){
		if(!empty($classid)){
			$this->db->where('classid',$classid);
			$this->db->order_by('orderby asc');
			return $this->db->get(self::T_E_ITEM)->result_array();
		}
	}
	/**
	 * [get_item_info_one 获取语言评教信息
	 * @return [type] [description]
	 */
	function get_item_info_one($id,$puri){
		if(!empty($id)){
			$this->db->where('itemid',$id);
			$this->db->where('site_language',$puri);
			return $this->db->get(self::T_E_ITEM_INFO)->row_array();
		}
	}
	/**
	 * [save_evaluate_student 保存学生评教信息]
	 * @return [type] [description]
	 */
	function save_evaluate_student($data){
		if($data){
			$this->delete_eav_student($data);
			$this->db->insert(self::T_E_STUDENT,$data);
			return $this->db->insert_id();
		}
	}
	/**
	 * [delete_eav_student 删除该学生的记录]
	 * @return [type] [description]
	 */
	function delete_eav_student($data){
		if(!empty($data)){
			$where="userid = {$data['userid']} AND courseid = {$data['courseid']} AND teacherid = {$data['teacherid']}";
			$this->db->delete ( self::T_E_STUDENT, $where);
		}
	}
		/**
	 * [check_student_evaluate 查看课程老师评教的完成情况]
	 * @return [type] [description]
	 */
	function check_student_evaluate($userid,$arr){
		// var_dump($arr);exit;
		if(!empty($userid)&&!empty($arr)){
			$this->db->select('count(*) as num');
			$this->db->where('userid',$userid);
			$this->db->where('majorid',$arr['majorid']);
			$this->db->where('squadid',$arr['squadid']);
			$this->db->where('term',$arr['nowterm']);
			$this->db->where('courseid',$arr['courseid']);
			$this->db->where('teacherid',$arr['teacherid']);
			$data=$this->db->get(self::T_E_STUDENT)->row_array();
			if(!empty($data)){
				return $data['num'];
			}
		}
		return 0;
	}
	/**
	 * [save_student_finish 保存学生的完成]
	 * @return [type] [description]
	 */
	function save_student_finish($data){
		if(!empty($data)){
			$this->db->insert(self::T_E_STUDENT_FINISH,$data);
			return $this->db->insert_id();
		}
	}
	/**
	 * [check_eva_finish 检查学生是否完成了]
	 * @param  [type] $userid [description]
	 * @param  [type] $year   [description]
	 * @return [type]         [description]
	 */
	function check_eva_finish($userid,$year){
		if(!empty($userid)&&!empty($year)){
			$this->db->select('count(*) as num');
			$this->db->where('userid',$userid);
			$this->db->where('year',$year);
			$data=$this->db->get(self::T_E_STUDENT_FINISH)->row_array();
			return $data['num'];
		}
		return 0;
	}
	/**
	 * [get_eva_student_info 查找该学生对该课程的评教信息]
	 * @return [type] [description]
	 */
	function get_eva_student_info($userid,$courseid,$teacherid,$year){
		if(!empty($userid)&&!empty($courseid)&&!empty($teacherid)&&!empty($year)){
			$this->db->where('userid',$userid);
			$this->db->where('courseid',$courseid);
			$this->db->where('teacherid',$teacherid);
			$this->db->where('year',$year);
			return $this->db->get(self::T_E_STUDENT)->row_array();
		}
	}	
	/**
	 * [get_stu_year_eav 获取该学生对当年的评教信息
	 * @return [type] [description]
	 */
	function get_stu_year_eav($userid,$year,$prui){
		if(!empty($userid)&&!empty($year)){
			if($prui=='en'){
				$this->db->select('evaluate_student.*,teacher.englishname as tname,course.englishname as cname');
			}else{
				$this->db->select('evaluate_student.*,teacher.name as tname,course.name as cname');
			}
			
			$this->db->where('evaluate_student.userid',$userid);
			$this->db->where('evaluate_student.year',$year);
			$this->db->join ( self::T_TEACHER, self::T_E_STUDENT . '.teacherid=' . self::T_TEACHER . '.id' );
			$this->db->join ( self::T_COURSE, self::T_E_STUDENT . '.courseid=' . self::T_COURSE . '.id' );
			return $this->db->get(self::T_E_STUDENT)->result_array();
		}
	}
	/**
	 * [get_student_all_score 获取答题的总分]
	 * @return [type] [description]
	 */
	function get_eav_all_score($item,$puri){
		$item_arr=json_decode($item,true);
		$score=0;
		foreach ($item_arr as $k => $v) {
			if(strstr($k,'item')){
				$id_arr=explode('item', $k);
				//获取该题的总分数
				$score+=$this->get_item_score_one($id_arr['1']);
			}
		}
		return $score;
	}
	/**
	 * [get_item_score_one 获取该题的分数]
	 * @return [type] [description]
	 */
	function get_item_score_one($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_E_ITEM_INFO)->row_array();
			if(!empty($data['answer_score'])){
				$all_score=0;
				$score_arr=json_decode($data['answer_score'],true);
				foreach ($score_arr as $k => $v) {
					if($all_score<$v){
						$all_score=$v;
					}
				}
				return $all_score;
			}
		}
	}
	/**
	 * [get_student_all_score 获取该学的答题的总分数]
	 * @return [type] [description]
	 */
	function get_student_all_score($item){
		$stu_score=0;
		if(!empty($item)){
			$item_arr=json_decode($item,true);
			foreach ($item_arr as $k => $v) {
				if(strstr($k,'item')){
					$score_arr=explode('-grf-', $v);
					$stu_score+=$score_arr[1];
				}
			}
		}
		return $stu_score;
	}
	/**
	 * [get_score_grade 算出总分数的非常好 好 一般 差]
	 * @return [type] [description]
	 */
	function get_score_grade($all,$bufen){
		if(!empty($all)&&!empty($bufen)){
			$num=$bufen/$all;
			$num=sprintf("%.2f", $num)*100;
			if($num>80){
				return 1;
			}elseif($num>60){
				return 2;
			}elseif($num>40){
				return 3;
			}elseif($num>20){
				return 4;
			}else{
				return 5;
			}

		}
		return 0;
	}
	/**
	 * [get_class_text 查出文本的题目和答案]
	 * @return [type] [description]
	 */
	function get_class_text($item){
		if(!empty($item)){
			$arr=array();
			$item_arr=json_decode($item,true);
			foreach ($item_arr as $k => $v) {
				if(strstr($k,'class')){
					$class_arr=explode('class', $k);
					$class_info=$this->get_item_class($class_arr[1]);
					$class_info['answer']=$v;
					$arr[]=$class_info;
				}
			}
			return $arr;
		}
		return array();
	}
	/**
	 * [get_item_class 获取类的相关的信息]
	 * @return [type] [description]
	 */
	function get_item_class($cid){
		if(!empty($cid)){
			$this->db->where('type',2);
			$this->db->where('id',$cid);
			return $this->db->get(self::T_E_CLASS)->row_array();
		}
	}
	/**
	 * [qiangzhigaixiang_id 强制更改id]
	 * @return [type] [description]
	 */
	function qiangzhigaixiang_id($puri,$arr){
		if(!empty($puri)&&!empty($arr)){
			 $stu_answer=json_decode($arr['item'],true);
			 foreach ($stu_answer as $k => $v) {
			 	if(strstr($k,'item')){
			 		$item_arr=explode('item', $k);
			 		//获取该题的项id
			 		$itemid=$this->get_iteminfo_itemid($item_arr[1]);
			 		$id=$this->get_iteminfo_id($itemid,$puri);
			 		$stu_answer['item'.$id]=$v;
			 	}
			 }
			 return $stu_answer;
		}
	}
	//获取该题的项id
	function get_iteminfo_itemid($id){
		if(!empty($id)){
			$this->db->select('itemid');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_E_ITEM_INFO)->row_array();
			if(!empty($data)){
				return $data['itemid'];
			}
		}
		return 0;
	}
	/**
	 * [get_iteminfo_id 获取该题id]
	 * @return [type] [description]
	 */
	function get_iteminfo_id($itemid,$puri){
		if(!empty($itemid)){
			$this->db->select('id');
			$this->db->where('itemid',$itemid);
			$this->db->where('site_language',$puri);
			$data=$this->db->get(self::T_E_ITEM_INFO)->row_array();
			if(!empty($data)){
				return $data['id'];
			}
		}
		return 0;
	}
	/**
	 * [check_student_squad 查看学生有没有分班]
	 * @return [type] [description]
	 */
	function check_student_squad($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$data=$this->db->get(self::T_STUDENT)->row_array();
			if(empty($data)){
				return 1;
			}else{
				if(empty($data['squadid'])){
					return 1;
				}
			}

		}
		return 0;
	}

    /**
     * 获取小题的信息
     */
    function get_student_item_info($info){
        if(!empty($info)){
            $return_arr=array();
            $arr=json_decode($info,true);
            foreach($arr as $k=>$v){
                if(strstr($k,'item')){
                    $class_arr=explode('item', $k);
                    $class_info=$this->get_item_item($class_arr[1]);
                    $answer=explode('-grf-',$v);
                    $class_info['answer']=$answer[0];
                    $return_arr[]=$class_info;
                }
            }
           return $return_arr;
        }
    }

    /**
     * 获取该题的信息
     */
    function get_item_item($id){
        if(!empty($id)){
            $this->db->where('id',$id);
            return $this->db->get(self::T_E_ITEM_INFO)->row_array();
        }
    }
}