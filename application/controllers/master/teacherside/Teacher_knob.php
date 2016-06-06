<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Teacher_knob extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/teacherside/';
		$this->load->model($this->view.'teacher_knob_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
			//$teacherid=$this->input->get('teacherid');
		if(!empty($_SESSION ['master_user_info']->id)){
			$userid=$_SESSION ['master_user_info']->id;
		}
		
		$teacherid=$this->teacher_knob_model->get_teacherid($userid);

		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			$teacherid=$_GET ['teacherid'];
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->teacher_knob_model->count ( $condition ,$teacherid);
			$output ['aaData'] = $this->teacher_knob_model->get ( $fields, $condition ,$teacherid);
			foreach ( $output ['aaData'] as $item ) {
			
				$item->operation = '
					<a title="设置可用时间" class="green" href="' . $this->zjjp .'teacher_knob'. '/addtime?teacherid=' . $teacherid .'&courseid='.$item->courseid.'"><i class="normal-icon ace-icon fa fa-clock-o pink bigger-130"></i></a>
					<a title="删除" href="javascript:;" onclick="del('.$item->id.')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
					
				';
				
			}
			exit ( json_encode ( $output ) );
		}
		$teacher_info=$this->teacher_knob_model->get_teacher_info($teacherid);
		$this->_view ('teacher_knob_index',array(
			'teacherid'=>$teacherid,
			'teacher_info'=>$teacher_info,
			));
	}
	function edit(){
		
		$id=intval($this->input->get('id'));
		if($id){
			$where="id={$id}";
			$info=$this->teacher_knob_model->get_one($where);
			if(empty($info)){
				ajaxReturn ( '', '该课程不存在', 0 );
			}
		}

		$majorid=$this->teacher_knob_model->get_majorid($info->courseid);
		$course_info=$this->get_course($majorid['majorid']);
		$major_info=$this->teacher_knob_model->get_major_info();
		$teacherid=$this->input->get('teacherid');
		$this->_view ( 'teacher_knob_edit', array (
					'teacherid'=>$teacherid,
					'major_info'=>$major_info,
					'info' => $info ,
					'majorid'=>$majorid,
					'course_info'=>$course_info
			) );
	}
		
	
	function add() {

		$teacherid=$this->input->get('teacherid');
		$courseinfo=$this->teacher_knob_model->get_course();
		$tcinfo=$this->teacher_knob_model->get_t_c($teacherid);
		$this->_view ('teacher_knob_edit',array(
			'teacherid'=>$teacherid,
			'courseinfo'=>$courseinfo,
			'tcinfo'=>$tcinfo,
			));
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->teacher_knob_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data=$this->input->post();
			unset($data['majorid']);
			
			// 保存基本信息
			$this->teacher_knob_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		
		$data = $this->input->post();
		
		if (! empty ( $data )) {
			
			$id = $this->teacher_knob_model->save ( $data );
			if($id=='del'){
				ajaxReturn ( '', '删除成功', 1 );
			}
			if ($id) {
				
				ajaxReturn ( $id, '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'courseid',
				
				
		);
	}


	/**
	 * 获取课程信息
	 *
	 *         	
	 * @return array
	 */
	function get_course($majorid){
		
			if($data=$this->teacher_knob_model->get_course_info($majorid)){
				if ($this->input->is_ajax_request () === true){
					ajaxReturn($data,'',1);
				}else{
					return $data;
				}
			}
		
		ajaxReturn('','该专业没有课程',0);
	}
	function addtime(){
		$hour=CF('hour','',CONFIG_PATH);

		$teacherid=intval($this->input->get('teacherid'));
		$courseid=intval($this->input->get('courseid'));
		$teacher_info=$this->teacher_knob_model->get_teacher_info($teacherid);
		$timeinfo=$this->teacher_knob_model->get_time_info($teacherid,$courseid);
		$course_info=$this->teacher_knob_model->get_course_one($courseid);
		$this->_view ('teacher_knob_addtime',array(
			'hour'=>$hour,
			'teacherid'=>$teacherid,
			'courseid'=>$courseid,
			'timeinfo'=>$timeinfo,
			'teacher_info'=>$teacher_info,
			'course_info'=>$course_info,
			));
	}
	function insert_time(){
		$data=$this->input->get();
		$result=$this->teacher_knob_model->insert_all($data);
		ajaxReturn($result,'',1);

	}
//添加课程页面
	function add_course(){
		$courseinfo = $this->teacher_knob_model->get_course_limit ();

		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$teacherid = $this->input->get ( 'teacherid' );
			$mcinfo = $this->teacher_knob_model->get_t_c ( $teacherid );
			// var_dump($mcinfo);exit;
			$html = $this->_view ( 'setcourse', array (
					'teacherid' => $teacherid,
					'courseinfo' => $courseinfo,
					'mcinfo' => $mcinfo ,
					'up'=>0,
					'next'=>1
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
		//课程下一页
	function next_course(){
		$num=$this->input->get('page');
		$teacherid = $this->input->get ( 'teacherid' );
		$search=$this->input->post();
		if(!empty($search['search'])){
			$data=$this->teacher_knob_model->get_search_courseinfo($search['search']);
			$num_count=count($data);
			if($num>$num_count/10){
					ajaxreturn('','已经是最后一页了',0);
				}
			$s=$num*10;
			$e=$s+10;
			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->teacher_knob_model->get_t_c ( $teacherid );
			$arrs['c']=$arr;
			$arrs['num']=$num+1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}else{
			$data=$this->teacher_knob_model->get_course();
			$num_count=count($data);
			if($num>$num_count/10){
					ajaxreturn('','已经是最后一页了',0);
				}
			$s=$num*10;
			$e=$s+10;
			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->teacher_knob_model->get_t_c ( $teacherid );
			$arrs['c']=$arr;
			$arrs['num']=$num+1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}
		
	}
	//课程上一页
	function up_course(){
		$num=$this->input->get('page');
		$teacherid = $this->input->get ( 'teacherid' );
		$search=$this->input->post();
		if(!empty($search['search'])){
			$data=$this->teacher_knob_model->get_search_courseinfo($search['search']);
			$num_count=count($data);
			$e=$num*10;
			$s=$e-10;

			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->teacher_knob_model->get_t_c ( $teacherid );
			$arrs['c']=$arr;
			$arrs['num']=$num-1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}else{
			$data=$this->teacher_knob_model->get_course();
			$num_count=count($data);
			$e=$num*10;
			$s=$e-10;

			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->teacher_knob_model->get_t_c ( $teacherid );
			$arrs['c']=$arr;
			$arrs['num']=$num-1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}
		
	}
//课程搜索
	function get_search_course(){
		$data=$this->input->post();
		$teacherid = $this->input->get ( 'teacherid' );
		$mcinfo = $this->teacher_knob_model->get_t_c ( $teacherid );
		if(!empty($data['search'])){
			$result=$this->teacher_knob_model->get_search_courseinfo_limit($data['search']);
			if(!empty($result)){
				$arrs['c']=$result;
				$arrs['mc']=$mcinfo;
				$arrs['num']=0;
				ajaxReturn($arrs,'',1);
			}else{
				ajaxReturn('','没有该课程',0);
			}
		}else{
			$courseinfo = $this->teacher_knob_model->get_course_limit ();
			$arrs['c']=$courseinfo;
			$arrs['mc']=$mcinfo;
			$arrs['num']=0;
			ajaxReturn($arrs,'',1);
		}
	}
}