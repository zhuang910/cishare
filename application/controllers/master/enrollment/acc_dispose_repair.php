	<?php
	defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
	
	/**
	 * 后台首页
	 *
	 * @author JJ
	 *        
	 */
	class Acc_dispose_repair extends Master_Basic {
		/**
		 * 基础类构造函数
		 */
		function __construct() {
			parent::__construct ();
			$this->view = 'master/enrollment/';
			$this->load->model ( $this->view . 'acc_dispose_repair_model' );
		
		}
		
		/**
		 * 后台主页
		 */
		function index() {
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '0';
			$flag_isshoufei = 0;
			if ($this->input->is_ajax_request () === true) {
				// 设置查询字段
				
				$fields = $this->_set_lists_field ();
				$label_id = $this->input->get ( 'label_id' );
				$label_id = ! empty ( $label_id ) ? $label_id : '0';
				
				// 查询条件组合
				$condition = dateTable_where_order_limit ( $fields );
				$condition['where']='state = '.$label_id;
				$output ['sEcho'] = intval ( $_GET ['sEcho'] );
				
				$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_dispose_repair_model->count ( $condition );
				$output ['aaData'] = $this->acc_dispose_repair_model->get ( $fields, $condition );
				
				foreach ( $output ['aaData'] as $item ) {
					$state=$item->state;
					$item->state = $this->_get_lists_state ( $item->state );
					if(!empty($item->userid)){
						$item->name=$this->acc_dispose_repair_model->get_user_name($item->userid);
					}
					if(!empty($item->adminid)){
						$item->name=$this->acc_dispose_repair_model->get_admin_name($item->adminid);
					}
					//获取校区名
                    $item->campusid = "校区名称:".$this->acc_dispose_repair_model->get_campus_name($item->campusid);
					//楼层名字
                    $item->campusid.='<br>楼宇名称:'.$this->acc_dispose_repair_model->get_buliding_name($item->buildingid);
                    $item->campusid.='<br>楼层:'.$this->acc_dispose_repair_model->get_room_name($item->roomid);
                    $item->campusid.='<br>第'.$item->floor.'层';
					$item->createtime=date('Y-m-d',$item->createtime);
					if($state==0||$state==1){
						$item->operation = '
							<a class="green" title="更改状态" href="javascript:;" onclick="change_state('.$item->id.','.$state.')"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
							<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
						';
					}else{
						$item->operation = '<a href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
						';
					}

					
				}
				// var_dump($output);die;
				exit ( json_encode ( $output ) );
			}
		
			$this->_view ( 'acc_dispose_index', array(
				'label_id' => $label_id,
				));
		}
		/**
		 * [change_state 更新状态]
		 * @return [type] [description]
		 */
		function change_state(){
			$id=intval($this->input->get('id'));
			$state=intval($this->input->get('state'));
			//更新该维修状态
			$arr['state']=$state+1;
			$this->db->update('repairs_info',$arr,'id = '.$id);
			ajaxReturn('','',1);
		}
		/**
		 * 设置列表字段
		 */
		private function _set_lists_field() {
			return array (
					"id",
					"userid",
					'adminid',
					"campusid",
					"buildingid",
					'email',
					"floor",
					"roomid",
					'state',
					"createtime",
                'remark'
			);
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
						'<span class="label label-important">未处理</span>',
						'<span class="label label-success">处理中</span>' ,
						'<span class="label label-success">已处理</span>' 
				);
				return $statemsg [$statecode];
			}
			return;
		}
	/**
	 * [add 添加编辑页面]
	 */
	function add(){
		$s = intval ( $this->input->get ( 's' ) );
		$campus_info=$this->acc_dispose_repair_model->get_campus_info();
		if (! empty ( $s )) {
			$html = $this->_view ( 'acc_dispose_add', array (
					'campus_info'=>$campus_info,
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post ();
		if (! empty ( $data )) {
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$data['email']=$_SESSION['master_user_info']->email;
			$data['state']=0;
			$id = $this->acc_dispose_repair_model->save ( null, $data );
			if ($id) {
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	//编辑
	function edit() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id={$id}";
			$info = $this->acc_dispose_repair_model->get_one ( $where );
			if (empty ( $info )) {
				ajaxReturn ( '', '该书籍不存在', 0 );
			}
			$s = intval ( $this->input->get ( 's' ) );
			if (! empty ( $s )) {
				$html = $this->_view ( 'add_books', array (
					'info'=>$info
					), true );
				ajaxReturn ( $html, '', 1 );
			}
		}
		
	}
	//更新
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		$data=$this->input->post();
		if ($id) {
			// 保存基本信息
			$this->acc_dispose_repair_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	//删除
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$is = $this->acc_dispose_repair_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [get_buliding 获取房间]
	 * @return [type] [description]
	 */
	function get_room(){
		$bid=intval($this->input->get('bid'));
		$floor=intval($this->input->get('floor'));
		if(!empty($bid)&&!empty($floor)){
			$data=$this->acc_dispose_repair_model->get_room_info($bid,$floor);
			if(!empty($data)){
				ajaxReturn($data,'',1);
			}else{
				ajaxReturn('','该楼层下没有房间',0);
			}
		}
		ajaxReturn('','',0);
	}
}