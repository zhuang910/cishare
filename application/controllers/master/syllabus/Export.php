<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Export extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/syllabus/';
		$this->load->model($this->view.'export_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$mdata=$this->export_model->get_majorinfo();
		
        // 获取学历
        $mdata = $this->_get_major_by_degree($mdata);
		$tdata=$this->export_model->get_teacher();
		$hour=CF('hour','',CONFIG_PATH);
		$time=CF('hour_time','',CONFIG_PATH);
		$this->_view ('export_index',array(
			'mdata'=>$mdata,
			'tdata'=>$tdata,
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
		$nowterm=$this->export_model->get_major_nowterm($mid);
		$course=$this->export_model->get_course($mid);
		$data['nowterm']=$nowterm;
		$data['course']=$course;
		if(!empty($data['nowterm'])&&!empty($data['course'])){
			ajaxReturn ( $data, '', 1 );
		}
		
	}
	/**
	 * 获取该学期的专业
	 */
	function get_squad(){
		$mid=$this->input->get('mid');
		$term=$this->input->get('term');
		$squad=$this->export_model->get_squadinfo($mid,$term);

		//var_dump($squad);exit;
		if(!empty($squad)){
			ajaxReturn ( $squad, '', 1 );
		}
	}
	/**
	 * 获取排课的条件
	 */
	function get_condition(){
		$d=$this->input->post();
		$hour=CF('hour','',CONFIG_PATH);
		$data['hour']=$hour['hour'];
		if($d['squadid']=='0'){
			$data['scheduling']=$this->export_model->get_scheduling_term($d['majorid'],$d['seeterm']);
			$sinfo=array();
			foreach ($data['scheduling'] as $k => $v) {
				$sinfo[$k]=$this->export_model->get_sinfo($k);
			}
			$data['sinfo']=$sinfo;
			ajaxReturn($data,'',2);
		}
		if($d['majorid']!='0'&&$d['seeterm']!='0'&&$d['nowterm']!='0'){
			$data['majorname']=$this->export_model->get_major_name($d['majorid']);
			$data['squadname']=$this->export_model->get_squad_name($d['squadid']);
			$data['term']=$d['nowterm'];
			$data['scheduling']=$this->export_model->get_scheduling_info($d['majorid'],$d['nowterm'],$d['squadid']);
			
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}
		}
		
			ajaxReturn('','学期班级课程不能为空',0);
	}
	
	/**
	 * 按老师查询
	 * 
	 **/
	function select_teacher(){
		$tid=$this->input->get('tid');
		$term=$this->input->get('term');
		$tdata['scheduling']=$this->export_model->get_scheduling_t($tid,$term);
		$hour=CF('hour','',CONFIG_PATH);
		$tdata['hour']=$hour['hour'];
		
		ajaxReturn($tdata,'',1);

	}
	function get_s_nowterm(){
		$tid=$this->input->get('tid');
		$data=$this->export_model->get_s_term($tid);
		ajaxReturn($data,'',1);
	}

}