<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Teacher_course extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/teacher/';
		$this->load->model($this->view.'teacher_course_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		
			//$teacherid=$this->input->get('teacherid');
			$teacherid=$_GET ['teacherid'];

		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			$teacherid=$_GET ['teacherid'];
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->teacher_course_model->count ( $condition ,$teacherid);
			$output ['aaData'] = $this->teacher_course_model->get ( $fields, $condition ,$teacherid);
			foreach ( $output ['aaData'] as $item ) {
				$item->type=$this->teacher_course_model->get_course_type($item->courseid);
				$item->operation = '
					<a title="设置可用时间" class="btn btn-xs btn-info" href="' . $this->zjjp .'teacher_course'. '/addtime?teacherid=' . $teacherid .'&courseid='.$item->courseid.'">设置可用时间</a>
					<a class="btn btn-xs btn-info btn-white" title="编辑教学大纲" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'teacher_course/outline?id=' . $item->id . '&s=1\')">编辑教学大纲</a>
					<a title="删除" href="javascript:;" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>
					
				';
				
			}
			exit ( json_encode ( $output ) );
		}
		$teacher_info=$this->teacher_course_model->get_teacher_info($teacherid);
		$this->_view ('teacher_course_index',array(
			'teacherid'=>$teacherid,
			'teacher_info'=>$teacher_info,
			));
	}
	/**
	 * [outline 教学大纲页面]
	 * @return [type] [description]
	 */
	function outline(){
		$id=intval($this->input->get('id'));
		$t_c_info=$this->teacher_course_model->get_t_c_info($id);

		// var_dump($t_c_info);exit;
		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			// var_dump($mcinfo);exit;
			$html = $this->_view ( 'outline', array (
					't_c_info'=>$t_c_info,
			), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [edit_outline 编辑教学大纲]
	 * @return [type] [description]
	 */
	function edit_outline(){
		$data=$this->input->post();
		if(!empty($data)){
			$is=$this->teacher_course_model->set_teacher_outline($data);
			if($is==1){
				ajaxReturn('','',1);
			}
		}

	}
	//编辑
	function edit(){
		
		$id=intval($this->input->get('id'));
		if($id){
			$where="id={$id}";
			$info=$this->teacher_course_model->get_one($where);
			if(empty($info)){
				ajaxReturn ( '', '该课程不存在', 0 );
			}
		}

		$majorid=$this->teacher_course_model->get_majorid($info->courseid);
		$course_info=$this->get_course($majorid['majorid']);
		$major_info=$this->teacher_course_model->get_major_info();
		$teacherid=$this->input->get('teacherid');
		$this->_view ( 'teacher_course_edit', array (
					'teacherid'=>$teacherid,
					'major_info'=>$major_info,
					'info' => $info ,
					'majorid'=>$majorid,
					'course_info'=>$course_info
			) );
	}
		
	
	function add() {

		$teacherid=$this->input->get('teacherid');
		$courseinfo=$this->teacher_course_model->get_course();
		$tcinfo=$this->teacher_course_model->get_t_c($teacherid);
		$this->_view ('teacher_course_edit',array(
			'teacherid'=>$teacherid,
			'courseinfo'=>$courseinfo,
			'tcinfo'=>$tcinfo,
			'up'=>0,
			'next'=>1
			));
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->teacher_course_model->delete_guanlian ( $id );
			if ($is === true) {
				 $this->teacher_course_model->delete ( $where );
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
			$this->teacher_course_model->save ( $id, $data );
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
			
			$id = $this->teacher_course_model->save ( $data );
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
		
			if($data=$this->teacher_course_model->get_course_info($majorid)){
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
		$teacher_info=$this->teacher_course_model->get_teacher_info($teacherid);
		$timeinfo=$this->teacher_course_model->get_time_info($teacherid,$courseid);
		$course_info=$this->teacher_course_model->get_course_one($courseid);
		$this->_view ('teacher_course_addtime',array(
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
		$result=$this->teacher_course_model->insert_all($data);
		ajaxReturn($result,'',1);

	}
    function inser_timegeng(){
        $hour=CF('hour','',CONFIG_PATH);
        $teacherid=$this->input->get('teacherid');
        $courseid=$this->input->get('courseid');
        $s=$this->input->get('s');
        $insert_arr=array();
        if($s==1){
            $insert_arr['teacherid']=$teacherid;
            $insert_arr['courseid']=$courseid;
            $insert_arr['state']=1;
            foreach($hour['hour'] as $k=>$v) {
                for ($i = 1; $i <= 7; $i++) {
                    $insert_arr['week']=$i;
                    $insert_arr['knob']=$v;
                    $info_one=$this->db->get_where('teacher_course','teacherid = '.$teacherid.' AND courseid = '.$courseid.' AND week = '.$i.' AND knob = '.$v)->row_array();
                    if(empty($info_one)){
                        $this->db->insert('teacher_course',$insert_arr);
                    }else{
                        if($info_one['state']==0){
                            $this->db->update('teacher_course',array('state'=>1),'id = '.$info_one['id']);
                        }
                    }
                }
            }
        }else{
            $insert_arr['teacherid']=$teacherid;
            $insert_arr['courseid']=$courseid;
            $insert_arr['state']=0;
            foreach($hour['hour'] as $k=>$v) {
                for ($i = 1; $i <= 7; $i++) {
                    $insert_arr['week']=$i;
                    $insert_arr['knob']=$v;
                    $info_one=$this->db->get_where('teacher_course','teacherid = '.$teacherid.' AND courseid = '.$courseid.' AND week = '.$i.' AND knob = '.$v)->row_array();
                    if(!empty($info_one)){
                        $this->db->update('teacher_course',array('state'=>0),'id = '.$info_one['id']);
                    }
                }
            }
        }


    }
	//添加课程页面
	function add_course(){
		$courseinfo = $this->teacher_course_model->get_course_limit ();

		$s = intval ( $this->input->get ( 's' ) );
		if (! empty ( $s )) {
			$teacherid = $this->input->get ( 'teacherid' );
			$mcinfo = $this->teacher_course_model->get_t_c ( $teacherid );
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
			$data=$this->teacher_course_model->get_search_courseinfo($search['search']);
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
			$mcinfo = $this->teacher_course_model->get_t_c ( $teacherid );
			$arrs['c']=$arr;
			$arrs['num']=$num+1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}else{
			$data=$this->teacher_course_model->get_course();
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
			$mcinfo = $this->teacher_course_model->get_t_c ( $teacherid );
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
			$data=$this->teacher_course_model->get_search_courseinfo($search['search']);
			$num_count=count($data);
			$e=$num*10;
			$s=$e-10;

			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->teacher_course_model->get_t_c ( $teacherid );
			$arrs['c']=$arr;
			$arrs['num']=$num-1;
			$arrs['mc']=$mcinfo;
			ajaxreturn($arrs,'',1);
		}else{
			$data=$this->teacher_course_model->get_course();
			$num_count=count($data);
			$e=$num*10;
			$s=$e-10;

			$arr=array();
			foreach ($data as $k => $v) {
				if(($k+1)>$s&&($k+1)<=$e){
					$arr[]=$v;
				}
			}
			$mcinfo = $this->teacher_course_model->get_t_c ( $teacherid );
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
		$mcinfo = $this->teacher_course_model->get_t_c ( $teacherid );
		if(!empty($data['search'])){
			$result=$this->teacher_course_model->get_search_courseinfo_limit($data['search']);
			if(!empty($result)){
				$arrs['c']=$result;
				$arrs['mc']=$mcinfo;
				$arrs['num']=0;
				ajaxReturn($arrs,'',1);
			}else{
				ajaxReturn('','没有该课程',0);
			}
		}else{
			$courseinfo = $this->teacher_course_model->get_course_limit ();
			$arrs['c']=$courseinfo;
			$arrs['mc']=$mcinfo;
			$arrs['num']=0;
			ajaxReturn($arrs,'',1);
		}
	}
}