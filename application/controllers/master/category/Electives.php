	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Electives extends Master_Basic {
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/student/';
			$this->load->model ( $this->view . 'electives_model' );
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

                $field = array('','id','name','englishname','hour','credit','starttime','endtime');
                // 排序
                $orderby = null;
                if (isset ( $_GET ['iSortCol_0'] )) {
                    for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
                        if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
                            $orderby = $field [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
                        }
                    }
                }
                $condition['orderby'] = $orderby;

				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->electives_model->count ( $condition );
				$output ['aaData'] = $this->electives_model->get ( $fields, $condition );
				foreach ( $output ['aaData'] as $item ) {
					$num=$this->electives_model->get_electives_number($item->id);
					$item->number='<a href="/master/student/elestu/index?cid='.$item->id.'">'.$num.'</a>';
					$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
					if(!empty($item->starttime)){
						$item->starttime=date('Y-m-d',$item->starttime);
					}
					if(!empty($item->endtime)){
						$item->endtime="<span style='color:red'>".date('Y-m-d',$item->endtime)."</sqan>";
					}
					$item->operation = '
					<a class="btn btn-xs btn-info" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'electives/course_time/?courseid=' . $item->id . '&s=1\')">选课排课</a>
				';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
			
			$this->_view ( 'electives_index' );
		}
		/**
		 * 获取课程状态
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
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'id',
					'name',
					'englishname',
					'hour',
					'credit',
					'starttime',
					'endtime',
					'size',
					'term_start' 
			);
		}

	/**
	 *
	 *设置开始结束时间
	 **/
	function settime(){
		$s = intval ( $this->input->get ( 's' ) );
		$data=$this->input->post();
		$ids=json_encode($data['sid']);
		if (! empty ( $s )) {
			$html = $this->_view ( 'set_time', array (
				'ids'=>$ids
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [save_time 设置开始结束时间]
	 * @return [type] [description]
	 */
	function save_time(){
		$data=$this->input->post();
		if(!empty($data)){
			$this->electives_model->set_course_time($data);
			ajaxReturn('','',1);
		}

	}
	/**
	 * [course_time 选修课可用时间页面]
	 * @return [type] [description]
	 */
	function course_time(){
		$hour=CF('hour','',CONFIG_PATH);
		$courseid=intval($this->input->get('courseid'));
		$s = intval ( $this->input->get ( 's' ) );
		//选课的排课信息
		$electives=$this->electives_model->get_electives_info($courseid);
		//关联课程的老师的可用时间
		$t_time=$this->electives_model->get_teacher_time($courseid);
		if (! empty ( $s )) {
			$html = $this->_view ( 'course_time', array (
				'courseid'=>$courseid,
				'hour'=>$hour,
				't_time'=>$t_time,
				'electives'=>$electives
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	 /**
	  * [get_teacher_room 获取老师和教室]
	  * @return [type] [description]
	  */
	 function get_teacher_room(){
	 	$week=intval($this->input->get('week'));
	 	$knob=intval($this->input->get('knob'));
	 	$cid=intval($this->input->get('cid'));
	 	$tid=$this->input->get('tid');
	 	$t_info=$this->electives_model->get_teacher_info_arr($tid);
	 	$room=$this->electives_model->get_classroom($cid,$week,$knob);
	 	if(!empty($t_info)){
	 		$data['teacher']=$t_info;
	 		$data['room']=$room;
	 		$data['week']=$week;
	 		$data['knob']=$knob;
	 		$data['courseid']=$cid;
	 		$data['tid']=$tid;
	 		ajaxReturn($data,'',1);
	 	}else{
	 		ajaxReturn('','',0);
	 	}
	 }
	 /**
	  * [save_electives 保存选修排课信息]
	  * @return [type] [description]
	  */
	 function save_electives(){
	 	$data=$this->input->post();
	 	if(!empty($data)){
	 		$id=$this->electives_model->save($data);
	 		$t_name=$this->electives_model->get_teacher_name($data['teacherid']);
	 		$r_name=$this->electives_model->get_classroom_name($data['classroomid']);
	 		$arr['id']=$id;
	 		$arr['tname']=$t_name;
	 		$arr['rname']=$r_name;
	 		$arr['week']=$data['week'];
	 		$arr['knob']=$data['knob'];
	 		ajaxReturn($arr,'',1);
	 	}
	 }
	 /**
	  * [del 删除排课]
	  * @return [type] [description]
	  */
	 function del(){
	 	$id=intval($this->input->get('id'));
	 	if(!empty($id)){
	 		$this->electives_model->delete($id);
	 		ajaxReturn('','删除成功',1);
	 	}
	 }
}