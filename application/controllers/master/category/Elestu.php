	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Elestu extends Master_Basic {
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/student/';
			$this->load->model ( $this->view . 'elestu_model' );
		}
		
		/**
		 * 后台主页
		 */
		function index() {
			$cid=$this->input->get('cid');
			$label_id=$this->input->get('label_id');
			if(empty($label_id)){
				$label_id=0;
			}
			if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				$fields = $this->_set_lists_field ();
				$cid=$this->input->get('cid');
				$label_id=$this->input->get('label_id');
				if(empty($label_id)){
					$label_id=0;
				}
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->elestu_model->count ( $condition,$cid ,$label_id);
				$output ['aaData'] = $this->elestu_model->get ( $fields, $condition ,$cid,$label_id);
				foreach ( $output ['aaData'] as $item ) {
					$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->id.'">';
					$item->majorsquad=$this->elestu_model->get_major_squad($item->sid);
					$item->paike='星期'.$item->week.'的第'.$item->knob.'节课';
					$item->operation=' ';
					$item->operation.='<a href="javascript:;" onclick="queren('.$item->id.')"  title="确认选课"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
					$item->operation.='<a href="javascript:;"  onclick="pub_alert_html(\'' . $this->zjjp . '/elestu/shiabi_page?id=' . $item->id . '&s=1\')"  title="选课失败"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
					
					$item->state=$this->_get_lists_state($item->state);
					$item->nowterm='第'.$item->nowterm.'学期';
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
			
			$this->_view ( 'elestu_index' ,array(
					'cid'=>$cid,
					'label_id'=>$label_id
				));
		}
		/**
		 * [shiabi_page 报名失败页面]
		 * @return [type] [description]
		 */
		function shiabi_page(){
		$id=$this->input->get('id');
		$info=$this->db->get_where('course_elective','id = '.$id)->row_array();
		$html = $this->load->view('master/student/shibai',array(
              'id'=>$id,
              'info'=>!empty($info)?$info:array()
            ),true);
        ajaxReturn($html,'',1);
		}
		/**
		 * [shiabi 选课失败]
		 * @return [type] [description]
		 */
		function shiabi(){
			$data=$this->input->post();
			if(!empty($data)){
				$this->db->update('course_elective',array('state'=>2,'remark'=>$data['remark']),'id = '.$data['id']);
				ajaxReturn('','',1);
			}
			ajaxReturn('','',0);
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					'zust_course_elective.id',
					'zust_course_elective.teacherid',
					'zust_course_elective.classroomid',
					'zust_course_elective.week',
					'zust_course_elective.knob', 
					'zust_course_elective.state', 
					'zust_student.name',
					'zust_student.enname',
					'zust_student.passport',
					'zust_teacher.name',
					'zust_course.name',
				
			);
		}
	/**
	 *
	 *设置开始结束时间
	 **/
	function paike(){
		$data=$this->input->post();
		if(!empty($data['sid'])){
			foreach ($data['sid'] as $k => $v) {
				
				//更改状态
				$arr['state']=1;
				$this->db->update('course_elective',$arr,'id = '.$v);
			}
		}
		ajaxReturn('','',1);
	}
	function scheduling(){
		$data=$this->input->post();
		if(!empty($data)){
			$this->elestu_model->save_scheduling($data);
		}
		ajaxReturn('','',1);
	}
	/**
	 *
	 *学生一条做修改
	 **/
	function edit(){
		$s = intval ( $this->input->get ( 's' ) );
		$cid=intval ( $this->input->get ( 'cid' ) );
		//获取该选课课程的排课信息
		$info=$this->elestu_model->get_electives_info($cid);
		$nowterm=intval($this->input->get ( 'nowterm' ));
		$userid=intval($this->input->get ( 'userid' ));
		$user_info=$this->elestu_model->get_scheduling_info($cid,$userid,$nowterm);
		$hour=CF('hour','',CONFIG_PATH);
		if (! empty ( $s )) {
			$html = $this->_view ( 'elestu_edit', array (
				'courseid'=>$cid,
				'hour'=>$hour,
				'info'=>$info,
				'userid'=>$userid,
				'nowterm'=>$nowterm,
				'user_info'=>$user_info
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [edit_scheduling 编辑单挑的学生数据]
	 * @return [type] [description]
	 */
	function edit_scheduling(){
		$data=$this->input->post();
		if(!empty($data)){
			$id=$this->elestu_model->save_edit_scheduling($data);
			ajaxReturn($id,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [del 删除一条]
	 * @return [type] [description]
	 */
	function del(){
		$data=$this->input->post();
		if(!empty($data)){
			$this->elestu_model->del_scheduling($data);
		}
		ajaxReturn('','',1);
	}
	/**
	 * [queren 确认]
	 * @return [type] [description]
	 */
	function queren(){
		$id=intval(trim($this->input->get('id')));
		if(!empty($id)){

			//更改状态
			$arr['state']=1;
			$this->db->update('course_elective',$arr,'id = '.$id);
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	/**
		 * 获取书籍状态
		 *
		 * @param string $statecode        	
		 * @param string $stateindexcode        	
		 * @return string
		 */
		private function _get_lists_state($statecode = null) {
			if ($statecode != null) {
				$statemsg = array (
						'<span class="label label-important">未确认</span>',
						'<span class="label label-success">已确认</span>' ,
						'<span class="label label-important">失败</span>',

				);
				return $statemsg [$statecode];
			}
			return;
		}
}