<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Classroom extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/basic/';
		$this->load->model($this->view.'classroom_model');
	}
	
	/**
	 * 后台主页
	 */
	function index() {

		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段

			$fields = $this->_set_lists_field ();
				
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->classroom_model->count ( $condition );
			$output ['aaData'] = $this->classroom_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->state = $this->_get_lists_state ( $item->state );
				$item->operation = '
					<a href="javascript:pub_alert_html(\'/master/basic/classroom/set_hour?id=' . $item->id  . '\');" class="btn btn-xs btn-info" title="设置不可用时间段">
					设置不可用时间段
					</a>
					<a class="btn btn-xs btn-info btn-white" href="' . $this->zjjp .'classroom'. '/edit?id=' . $item->id .'">编辑</a>
					<a href="javascript:;" onclick="del('.$item->id.')" class="btn btn-xs btn-info btn-white">删除</a>
				';
				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				*/
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('classroom_index');
	}
	function edit(){
		$id=intval($this->input->get('id'));
		if($id){
			$where="id={$id}";
			$info=$this->classroom_model->get_one($where);
			if(empty($info)){
				echo '该学院不存在';
			}
		}
		$this->_view ( 'classroom_edit', array (
					
					'info' => $info ,
					
			) );
	}
		
	
	function add() {


		$this->_view ('classroom_edit');
	}
	
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->classroom_model->delete ( $where );
			if ($is === true) {
				$this->classroom_model->delete_guanlian ( $id);
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post();
		
		if (! empty ( $data )) {
			
			$id = $this->classroom_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data=$this->input->post();
			
			
			// 保存基本信息
			$this->classroom_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'englishname',
				'address',
				'size',
				'state',
				
		);
	}

		/**
	 * 获取文章状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">禁用</span>',
					'<span class="label label-success">正常</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	/**
	 * [set_hour 设置不可用时间段]
	 */
	function set_hour(){
		$id = intval ( $this->input->get ( 'id' ) );
        //查看该教室已经设置的不可用时间段
        $no_info=$this->db->where('classroomid',$id)->get('classroom_disabled_time')->result_array();
        $hour=CF('hour','',CONFIG_PATH);
		$html = $this->_view ( 'set_room_hour', array (
			'hour'=>$hour,
            'classroomid'=>$id,
            'no_info'=>$no_info
		), true );
		ajaxReturn ( $html, '', 1 );

	}

    /**
     * 插入教室的不可用时间
     */
    function set_time(){
        $data=$this->input->post();
        //先查询该时间段有没有安排课程
        $num=$this->classroom_model->check_time($data);
        if($num>0){
            ajaxReturn('','该时间段已经安排课程',0);
        }
        $arr['classroomid']=$data['classroomid'];
        $arr['week']=$data['week'];
        $arr['knob']=$data['knob'];
        $arr['createtime']=time();
        $arr['adminid']=$_SESSION['master_user_info']->id;
        $this->db->insert('classroom_disabled_time',$arr);
        $cid=$this->db->insert_id();
        if(!empty($cid)){
            ajaxReturn ( '', '', 1 );
        }
        ajaxReturn ( '', '', 0 );
    }

    /**
     * 删除不可用时间段
     */
    function delete_time(){
        $id=$this->input->get('id');
        if(!empty($id)){
            $this->db->delete('classroom_disabled_time','id ='.$id);
            ajaxReturn('','',1);
        }
        ajaxReturn('','',0);
    }
}