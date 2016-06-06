<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 接机
 *
 * @author zyj
 *        
 */
class Pickup extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		
		$this->load->model ( $this->view . 'pickup_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$flag_isshoufei = 0;
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			$pickup = CF ( 'pickup', '', CONFIG_PATH );
			//if (! empty ( $pickup['pickup'] ) && $pickup ['pickup'] == 'yes') {
				// 显示 制度状态
			//	$flag_isshoufei = 1;
			//}
			$array = array (
					'0' => '<span class="label label-important">未支付</span>',
					'1' => '<span class="label label-success">支付成功</span>',
					'2' => '<span class="label label-important">支付失败</span>',
					'3' => '<span class="label label-important">待审核</span>' 
			);
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );

            $field = array('id','name','flightno','numbers','remark','subtime','state');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->pickup_model->count ( $condition );
			$output ['aaData'] = $this->pickup_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				$item->personal = '姓名：' . $item->name . '<br />';
				$item->personal .= '邮箱:' . $item->email . '<br />';
				if ($item->sex == 1) {
					$item->personal .= '性别: 男<br />';
				} else {
					$item->personal .= '性别: 女<br />';
				}
				$item->personal .= '国籍:' . $nationality [$item->nationality] . '<br />';
				// 获取 专业 信息
				$majorid = $this->db->select('courseid')->limit(1)->order_by('id DESC')->get_where('apply_info','userid = '.$item->userid)->row();
				if(!empty($majorid)){
					$major_info = $this->db->select('name,englishname')->get_where('major','id = '.$majorid->courseid)->row();
					if(!empty($major_info->name)){
							$item->personal .= '专业：'.$major_info->name.'<br />';
					}
					
				}
				$item->filght = '航班号：' . $item->flightno . '<br />';
				$item->filght .= '离开机场：' . $item->fairport . '<br />';
				$item->filght .= '到达机场：' . $item->tairport . '<br />';
				$item->arrivetime = ! empty ( $item->arrivetime ) ? date ( 'Y-m-d H:i:s', $item->arrivetime ) : '';
				$item->filght .= '预计到达时间：' . $item->arrivetime . '<br />';
				
				$item->myinfo = '随行人数：' . $item->numbers . '<br />';
				$item->myinfo .= '本人电话：' . $item->tel . '<br />';
				$item->myinfo .= '本人手机：' . $item->mobile . '<br />';
				$item->myinfo .= '学校名称或是到达地点：<br />' . $item->schoolname . '<br />';
				$item->state = $this->_get_lists_state ( $item->state );
				$item->subtime = ! empty ( $item->subtime ) ? date ( 'Y-m-d H:i:s', $item->subtime ) : '';
				$item->sex = $this->_get_lists_sex ( $item->sex );
				$item->remark = $item->remark;
				$item->operation = '
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red" title="删除" id="del"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
					';
				
				if ($state == 1) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  class="red"  title="不通过" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
				} else if ($state == 2) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)" title="通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				}
				
				if ($state == 0) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  title="不通过" class="red" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)"  title="通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				}
				/*if($item->paystate!=1){
					$item->operation .= '<a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'pickup/onsite?userid='.$item->userid.'&id=' . $item->id . '&s=1\')"  title="现场缴费" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				}*/
				$item->operation .= '<br /><a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'pickup/dolinkinfo?id=' . $item->id . '&s=1\')"  title="添加接机人信息" id="upstate">添加接机人信息</a>';
				if (!empty($item->registeration_fee)) {
					$item->state .= $array [$item->paystate];
				}
				$item->linkinfo = nl2br($item->linkinfo);
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'pickup_index' );
	}
	
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$this->load->library ( 'sdyinc_email' );
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$result = $this->pickup_model->save_audit ( $id, $state );
			if ($result === true) {
				$teacherlog = $this->pickup_model->get_one ( 'id = ' . $id );
				$statelog = array (
						'处理中',
						'通过',
						'不通过' 
				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了接机用户' . $teacherlog->email . '的状态信息为' . $statelog [$state],
						'ip' => get_client_ip (),
						'lasttime' => time () ,
						'type' => 3
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				// 发邮件
				$val_arr = array (
						'email' => $teacherlog->email 
				);
				$MAIL = new sdyinc_email ();
				
				if ($state == 1) {
					
					$MAIL->dot_send_mail ( 19, $teacherlog->email, $val_arr );
				} else {
					$MAIL->dot_send_mail ( 20, $teacherlog->email, $val_arr );
				}
				
				ajaxReturn ( '', '更改成功', 1 );
			} else {
				ajaxReturn ( '', '', 0 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->pickup_model->get_one ( $where );
			$is = $this->pickup_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了接机用户' . $info->email . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time () ,
						'type' => 3
				);
				if (! empty ( $datalog )) {
					$this->adminlog->savelog ( $datalog );
				}
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'nationality',
				'userid',
				'email',
				'sex',
				'tel',
				'mobile',
				'subtime',
				'state',
				'paystate',
				'ordernumber',
				'numbers',
				'flightno',
				'fairport',
				'tairport',
				'arrivetime',
				'pmobile',
				'schoolname',
				'remark',
				'registeration_fee',
				'linkinfo'
		);
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">待审核</span><br />',
					'<span class="label label-success">通过</span><br />',
					'<span class="label label-important">不通过</span><br />' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	
	/**
	 * 获取管理员状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_sex($statecode = null) {
		if ($statecode != null && $statecode != 0) {
			$statemsg = array (
					'-1' => '未填写',
					'1' => '男',
					'2' => '女' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	/**
	 * [onsite 现场缴费]
	 * @return [type] [html]
	 */
	function onsite(){
		$s = intval ( $this->input->get ( 's' ) );
		$userid=$this->input->get('userid');
		$id=intval ( $this->input->get ( 'id' ) );
		$type=3;
		$url='/master/enrollment/pickup/do_onsite';
		$jump_url='/master/enrollment/pickup';
		if (! empty ( $s )) {
			$html = $this->_view ( 'onsite', array (
				'userid'=>$userid,
				'id'=>$id,
				'type'=>$type,
				'url'=>$url,
				'jump_url'=>$jump_url
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	
		/**
	 * [onsite 添加接机人信息]
	 * @return [type] [html]
	 */
	function dolinkinfo(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval ( $this->input->get ( 'id' ) );
		$url='/master/enrollment/pickup/savelinkinfo';
		$jump_url='/master/enrollment/pickup';
		//查取 信息
		$info = $this->db->get_where('pickup_info','id = '.$id)->row();
		if (! empty ( $s )) {
			$html = $this->_view ( 'dolinkinfo', array (
				'id'=>$id,
				'url'=>$url,
				'jump_url'=>$jump_url,
				'info' => !empty($info)?$info:''
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [do_onsite 现场缴费]
	 * @return [type] [ajax]
	 */
	function do_onsite(){
		$data=$this->input->post();
		if(!empty($data)){
			$result=$this->pickup_model->pay_change_state($data);
			if($result){
				$results=$this->pickup_model->insert_pay_record($data);
				if($results){
					ajaxReturn('','提交成功',1);
				}
			}
		}
		ajaxReturn('','未知错误',0);
	}
	
	function savelinkinfo(){
		$linkinfo = trim($this->input->post('linkinfo'));
		$id = trim($this->input->post('id'));
		if($id){
			$flag = $this->db->update('pickup_info',array('linkinfo' => $linkinfo),'id = '.$id);
			if($flag){
				ajaxReturn('','提交成功',1);
			}
		}
		ajaxReturn('','提交失败',0);
	}
	
	
	
	function acc(){
		
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_fields ();
			
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );

            $field = array('id','name','applynumber','sex','email','starttime','endtime','state');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->pickup_model->counts ( $condition );
			$output ['aaData'] = $this->pickup_model->gets ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$state = $item->state;
				if($item->state == 0){
					$item->state = '待审核';
				}else if($item->state == 1){
					$item->state = '通过';
				}else{
					$item->state = '不通过';
				}
				
				if ($item->sex == 1) {
					$item->sex .= '男';
				} else {
					$item->sex .= ' 女';
				}
				$item->nationality = $nationality [$item->nationality];
				
				$item->operation = '
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="red" title="删除" id="del"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
					';
				
				if ($state == 1) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  class="red"  title="不通过" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
				} else if ($state == 2) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)" title="通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				}
				
				if ($state == 0) {
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  title="不通过" class="red" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
					$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)"  title="通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				}
				
				
				if($item->starttime && $item->endtime){
					$item->subtime = date('Y-m-d H:i:s',$item->starttime).'--'. date('Y-m-d H:i:s',$item->endtime);
				}else{
					$item->subtime = '';
				}
				
				
				
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'acc_index' );
	}
	
		/**
	 * 设置列表字段
	 */
	private function _set_lists_fields() {
		return array (
				'id',
				'name',
				'nationality',
				'email',
				'sex',
				'tel',
				'state',
				'applynumber',
				'starttime',
				'endtime',
				
		);
	}
	
		/**
	 * 修改管理员的状态
	 */
	function upstate_acc() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$this->db->update('acc_info',array('state' => $state),'id = '.$id);
			ajaxReturn('','',1);
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
		
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del_acc() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$this->db->delete('acc_info','id = '.$id);
			ajaxReturn('','',1);
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
}

