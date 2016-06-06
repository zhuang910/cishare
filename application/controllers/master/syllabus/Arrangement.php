<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 排课
 * 
 * @author grf
 *        
 */
class Arrangement extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/syllabus/';
		$this->load->model($this->view.'arrangement_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$mdata=$this->arrangement_model->get_majorinfo();
		
        // 获取学历
        $mdata = $this->_get_major_by_degree($mdata);
		
		$hour=CF('hour','',CONFIG_PATH);
		$time=CF('hour_time','',CONFIG_PATH);
		$this->_view ('arrangement_index',array(
			'mdata'=>$mdata,
			'hour'=>$hour,
			'time'=>$time
			));
	}
	private function _get_major_by_degree($major_lists = array()){
        $temp = array();
        if(!empty($major_lists)){
           
			$degree = $this->db->order_by('orderby DESC')->get('degree_info','id > 0')->result_array();
			
            foreach($degree as $key => $item){
                foreach($major_lists as $info){
                    if($info->degree == $item['id']){
                        $temp[$key]['degree_title'] = $item['title'];
                        $temp[$key]['degree_major'][] = $info;
                    }
                }
            }
        }
        return $temp;
    }
	/**
	 * 获取该专业学期
	 */
	public function get_nowterm($mid){
		$nowterm=$this->arrangement_model->get_major_nowterm($mid);
		$course=$this->arrangement_model->get_course($mid);
		$data['nowterm']=$nowterm;
		if(!empty($course)){
			$data['course']=$course;
			ajaxReturn ( $data, '', 1 );
		}else{
			ajaxReturn($data,'该专业还没有课程',2);
		}
		
		
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		$squad=$this->arrangement_model->get_squadinfo($mid,$term);

		//var_dump($squad);exit;
		if(!empty($squad)){
			ajaxReturn ( $squad, '', 1 );
		}else{
			ajaxReturn('','该专业没有班级',2);
		}
	}
	/**
	 * 获取排课的条件
	 */
	function get_condition(){
		$d=$this->input->post();
		//var_dump($d);exit;
		if($d['majorid']!='0'&&$d['courseid']!='0'&&$d['squadid']!='0'&&$d['nowterm']!='0'&&$d['expectterm']!='0'){
			$data['hourinfo']=$this->hourinfo($d['majorid'],$d['courseid'],$d['expectterm']);

			$usablehour=$this->arrangement_model->get_c_h($d['courseid'],$d['expectterm']);
			$data['scheduling']=$this->arrangement_model->get_scheduling_info($d['majorid'],$d['expectterm'],$d['squadid']);
			//var_dump($data['scheduling']);exit;
			$data['usablehour']=$usablehour;
			if(empty($data['usablehour'])&&empty($data['scheduling'])){
				$data['tip']='还没有老师可用的时间,请';
				$data['a']='设置老师';
				$data['tips']='的可用时间';
			}
			$hour=CF('hour','',CONFIG_PATH);
			$data['hour']=$hour['hour'];
			$teacher=$this->arrangement_model->get_t_h($d['courseid'],$d['expectterm']);
			//var_dump($teacher);exit;
			$data['teacher']=$teacher;
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}
		}
		
			ajaxReturn('','学期班级课程不能为空',0);
	}
	/**
	 * 弹出框
	 */
	 function popup(){
	 	$id=intval($this->input->get('id'));
		$week=intval($this->input->get('week'));
		$knob=intval($this->input->get('knob'));
		$majorid=intval($this->input->get('majorid'));
		$courseid=intval($this->input->get('courseid'));
		$nowterm=intval($this->input->get('termnum'));
		$squadid=intval($this->input->get('squadid'));
		$teacherinfo=$this->arrangement_model->get_teacher_info($courseid,$week,$knob,$nowterm);
		//班级的容纳人数
		$num_day=$this->arrangement_model->get_squad_day($squadid);
		$classroominfo=$this->arrangement_model->get_classroom_info($week,$knob,$num_day,$nowterm);
		//获取当前老师的当前课程当前教室当前学期 当前专业 当前班级的可用时间
		//帅选教室不可用
		if(!empty($classroominfo)){
			foreach ($classroominfo as $k => $v) {
				$num=$this->db->get_where('classroom_disabled_time','classroomid = '.$v['id'].' AND week = '.$week.' AND knob = '.$knob)->row_array();
				if(!empty($num)){
					unset($classroominfo[$k]);
				}
			}
		}
		
	 	if(!empty($week)){
			$html = $this->_view ( 'popup', array(
				'id'=>$id,
				'classroominfo'=>$classroominfo,
				'nowterm'=> $nowterm,
				'majorid' =>$majorid,
				'week'=>$week,
				'knob' => $knob,
				'squadid'=>$squadid,
				'courseid'=>$courseid,
				'teacherinfo'=>$teacherinfo,
				), true );
				ajaxReturn ( $html, '', 1 );
		}
	 }
	 /*
	 *添加排课
	 *
	 */
	function add(){
		
		$data = $this->input->post();
		if (! empty ( $data )) {
			$knob_arr=array();
			if(!empty($data['day_knob'])){
				$knob_arr=$data['day_knob'];
				unset($data['day_knob']);
			}	
			$id = $this->arrangement_model->save ( $data ,$knob_arr);
			if(is_array($id)){
				$olddata=$this->arrangement_model->get_scheduling_one($id['id']);
				ajaxReturn ( $olddata, '修改成功', 1 );
			}
			else{
				$olddata=$this->arrangement_model->get_scheduling_one($id);

				ajaxReturn ( $olddata, '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	/**
	 * 计算每周最少上几节课
	 * 计算老师课程时间差     	
	 */
	function hourinfo($mid,$cid,$term){
	
		$numdays=$this->arrangement_model->count_t_c($mid,$cid);
		$hour= $this->arrangement_model->countcourse($mid,$cid);
		//var_dump($data['majorid']);exit;
		//该课程的每周的可用时间
		$usablehour=$this->arrangement_model->get_c_h($cid,$term);
		$data['hour']=$hour;
		$data['numdays']=$numdays;
		$data['usablehour']=$usablehour;
		return $data;
		
		
	}
	/**
	 *删除 
	 * */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$info=$this->arrangement_model->get_one ( $id );
			$is = $this->arrangement_model->delete ( $where );
			$arr=array();
			if(!empty($info['merge'])){
				for($i=1;$i<$info['merge'];$i++){
					if($info['week']==1){
						$td='1';
					}else{
						$td=($info['week']-1).$info['knob']+$i;
					}

					$arr[$td]=$info['week'].$info['knob']+$i;
					$info['knobs']=$info['knob']+$i;
					$this->arrangement_model->delete_sub ($info);
				}
				$data['merge']=$arr;
				$data['start']=$info['week'].$info['knob'];
				ajaxReturn($data,'',2);
			}
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [get_day_hour 获取当前老师的在当天的可用时间]
	 * @return [type] [description]
	 */
	function get_day_hour(){
		$data=$this->input->post();
		if(!empty($data)){
			$day_info=$this->arrangement_model->get_day_hour_info($data);
			if(!empty($day_info)){
				ajaxReturn($day_info,'',1);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_term_course 获取该学期下的课程]
	 * @return [type] [description]
	 */
	function get_term_course(){
		$data=$this->input->post();
		if(!empty($data['majorid'])&&!empty($data['expectterm'])){
			//获取该专业下的课程
			$course=$this->arrangement_model->get_course($data['majorid']);
			if(!empty($course)){
				foreach ($course as $k => $v) {
					if(empty($v['term_start'])){
						unset($course[$k]);
					}else{
						$term_arr=json_decode($v['term_start'],true);
						if(!in_array($data['expectterm'], $term_arr)){
							unset($course[$k]);
						}
					}
					
				}
				if(!empty($course)){
					ajaxReturn($course,'',1);
				}else{
					ajaxReturn('','该学期还没有开课的课程',0);
				}
			}else{
				ajaxReturn('','该专业下还没有关联课程',0);
			}
		}
			ajaxReturn('','未知错误',0);
	}
}