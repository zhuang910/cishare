<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 接机
 *
 * @author grf
 *        
 */
class Acc_apply extends Master_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->grf_update_room();
		$this->load->model ( $this->view . 'acc_apply_model' );
	}
	
	/**
	 * 主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		$flag_isshoufei = 0;
		// 如果是ajax请求则返回json数据列表
		if ($this->input->is_ajax_request () === true) {
			//biaoqian
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '1';
			//状态筛选
			if(!empty($label_id)&&$label_id==5){
				$where='emphasis=1';
			}else{
				$where='acc_state='.$label_id;
			}
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			
			$nationality = CF ( 'nationality', '', 'application/cache/' );
			$stay = CF ( 'stay', '', CONFIG_PATH );
			if (! empty ( $stay ) && in_array ( $stay ['stay'], array (
					'yes',
					'yespledge' 
			) )) {
				// 显示 制度状态
				$flag_isshoufei = 1;
			}
			$array = array (
					'0' => '<span class="label label-important">未支付</span>',
					'1' => '<span class="label label-success">支付成功</span>',
					'2' => '<span class="label label-important">支付失败</span>',
					'3' => '<span class="label label-important">待审核</span>' 
			);
			
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );

            $field = array('','id','userid','buildingid','accendtime','remark','subtime','acc_state');
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
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_apply_model->count ( $condition,$where );
			$output ['aaData'] = $this->acc_apply_model->get ( $fields, $condition ,$where);
			foreach ( $output ['aaData'] as $item ) {
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->userid.'">';
				$acc_state=$item->acc_state;
				$user_info=$this->db->where('id',$item->userid)->get('student_info')->row_array();
				$item->personal = '姓名：' . $user_info['enname'] . '<br />';
				$item->personal .= '邮箱:' . $user_info['email'] . '<br />';
				if ($user_info['sex'] == 1) {
					$item->personal .= '性别: 男<br />';
				} else {
					$item->personal .= '性别: 女<br />';
				}
				$item->personal .= '国籍:' . $nationality [$user_info['nationality']] . '<br />';
				
				$item->buildinginfo = $this->get_building ( $item->campid, $item->buildingid, $item->type ,$item->roomid);
				
				$item->applyinfo = '入住时长:' . $item->accendtime . '<br />';
				$item->accstarttime = ! empty ( $item->accstarttime ) ? date ( 'Y-m-d H:i:s', $item->accstarttime ) : '';
				$item->applyinfo .= '预计住宿时间:' . $item->accstarttime . '<br />';
				$item->remark = $item->remark;
				if ($item->tj == 0) {
					$item->applyinfo .= '是否接受调剂:否<br />';
				} else {
					$item->applyinfo .= '是否接受调剂:是<br />';
				}
				
				$item->state = $this->_get_lists_state ( $item->acc_state );
				$item->subtime = ! empty ( $item->subtime ) ? date ( 'Y-m-d H:i:s', $item->subtime ) : '';
				
				$item->operation = '
					<a href="javascript:;" onclick="del(' . $item->id . ')" class="btn btn-xs btn-info">删除</a>
					';
				/****新增功能****/
				//未交押金
				if ($acc_state == 1) {
					$item->operation .= '<a href="javascript:;" onclick="labour_affirm(' . $item->id . ',2)"  class="btn btn-xs btn-info btn-white" >人工确认到账</a>';
                    $item->operation .= '<a href="javascript:;" onclick="labour_affirm(' . $item->id . ',4)"  class="btn btn-xs btn-info btn-white">预定失败</a>';
                }
				//处理中
				if ($acc_state == 2) {
					$item->operation .= '<a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'acc_apply/allocation_room?id='.$item->id. '&s=1\')"  class="btn btn-xs btn-info btn-white">分配房间</a>';
				}
				//预订成功
				if ($acc_state == 3&&$item->emphasis==0) {
					//标记为重点关注
					$item->operation .= '<a href="javascript:;" onclick="zhongdian('.$item->id.',1)"  class="btn btn-xs btn-info btn-white">标记为重点关注</a>';

				}
				if($item->emphasis==1){
					$item->operation .= '<a href="javascript:;" onclick="zhongdian('.$item->id.',0)"  class="btn btn-xs btn-info btn-white">取消重点关注</a>';
				}
				/********/
				// if ($state == 1) {
				// 	$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  class="red"  title="不通过" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
				// } else if ($state == 2) {
				// 	$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)" title="通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				// }
				
				// if ($state == 0) {
				// 	$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',2)"  title="不通过" class="red" id="upstate"><i class="ace-icon glyphicon glyphicon-remove red"></i></a>';
				// 	$item->operation .= '<a href="javascript:;" onclick="upstate(' . $item->id . ',1)"  title="通过" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				// }
				// if($item->paystate!=1){
				// 	$item->operation .= '<a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'acc_apply/onsite?userid='.$item->userid.'&id=' . $item->id . '&s=1\')"  title="现场缴费" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				// 	$item->operation .= '<a href="javascript:;"onclick="pub_alert_html(\'' . $this->zjjp . 'acc_apply/onsite?userid='.$item->userid.'&id=' . $item->id . '&s=1\')"  title="现场缴费" id="upstate"><i class="ace-icon fa fa-check green bigger-130"></i></a>';
				// }
				// if ($flag_isshoufei == 1) {
				// 	$item->state .= $array [$item->paystate];
				// }
			}
			exit ( json_encode ( $output ) );
		}
		
		$this->_view ( 'acc_apply_index',array(
				'label_id' => $label_id,
			) );
	}
	/**
	 * [zhongdian 重点关注]
	 * @return [type] [description]
	 */
	function zhongdian(){
		$id=$this->input->get('id');
		$state=$this->input->get('state');
		if(!empty($id)){
			$this->db->update('accommodation_info',array('emphasis'=>$state),'id = '.$id);
			ajaxReturn('','',1);
		}
			ajaxReturn('','',0);
	}
	/**
	 * 修改管理员的状态
	 */
	function upstate() {
		$id = intval ( $this->input->get_post ( 'id' ) );
		$state = intval ( $this->input->get_post ( 'state' ) );
		if (! empty ( $id )) {
			$this->load->library ( 'sdyinc_email' );
			$result = $this->acc_apply_model->save_audit ( $id, $state );
			
			if ($result === true) {
				$teacherlog = $this->acc_apply_model->get_one ( 'id = ' . $id );
				if ($state == 2) {
					// 查看房间的数量
					if (! empty ( $teacherlog->campid ) && ! empty ( $teacherlog->buildingid ) && ! empty ( $teacherlog->type )) {
						$room_count = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id > 0 AND bulidingid  = ' . $teacherlog->buildingid . ' AND id = ' . $teacherlog->type )->result_array ();
						if (! empty ( $room_count [0] )) {
							if (! empty ( $room_count [0] ['isroomset'] ) && $room_count [0] ['isroomset'] == 1) {
								$count = $room_count [0] ['roomcount'] + 1;
								$this->db->update ( 'school_accommodation_prices', array (
										'roomcount' => $count 
								), 'bulidingid  = ' . $teacherlog->buildingid . ' AND id = ' . $teacherlog->type );
							}
						}
					}
				}
				
				$statelog = array (
						' ',
						'未交押金',
						'处理中',
						'预订成功' ,
						'预订失败' ,
						'重点关注' 

				);
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '修改了住宿用户' . $teacherlog->email . '的状态信息为' . $statelog [$acc_state],
						'ip' => get_client_ip (),
						'lasttime' => time (),
						'type' => 4 
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
					
					$MAIL->dot_send_mail ( 17, $teacherlog->email, $val_arr );
				} else {
					$MAIL->dot_send_mail ( 18, $teacherlog->email, $val_arr );
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
	 * [labour_affirm_upstate 人工确认交押金]
	 * @return [type] [description]
	 */
	function labour_affirm_upstate(){
		$id=intval($this->input->get('id'));
		$acc_state=intval($this->input->get('acc_state'));
		
		if (! empty ( $id )) {
			$this->load->library ( 'sdyinc_email' );
			$result = $this->acc_apply_model->save_acc_state ( $id, $acc_state );
			//这里没更新一个状态就差去历史表
			$this->ainsert_history($id,$acc_state);
            if($acc_state==4){
                $this->grf_update_room();

            }
			ajaxReturn('','',1);
		}
	}
	/**
	 * [emphasis_student 标记为重点关注对象]
	 * @return [type] [description]
	 */
	function emphasis_student(){
		$id=intval($this->input->get('id'));
		$acc_state=intval($this->input->get('acc_state'));
		if (! empty ( $id )) {
			$this->load->library ( 'sdyinc_email' );
			$result = $this->acc_apply_model->save_acc_state ( $id, $acc_state );
			ajaxReturn('','',1);
		}
	}
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		if ($id) {
			$where = "id = {$id}";
			$info = ( object ) $this->acc_apply_model->get_one ( $where );
			$is = $this->acc_apply_model->delete ( $where );
			if ($is === true) {
				// 写入日志
				$datalog = array (
						'adminid' => $_SESSION ['master_user_info']->id,
						'adminname' => $_SESSION ['master_user_info']->username,
						'title' => '管理员' . $_SESSION ['master_user_info']->username . '于' . date ( 'Y-m-d H:i:s', time () ) . '删除了住宿用户' . $info->email . '的信息',
						'ip' => get_client_ip (),
						'lasttime' => time (),
						'type' => 4 
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
				'userid',
				'accstarttime',
				'type',
				'tj',
				'campid',
				'buildingid',
				'roomid',
				'accendtime',
				'subtime',
				'acc_state',
				'paystate',
				'remark' ,
				'emphasis'
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
					' ',
					'<span class="label label-important">未交押金</span><br />',
					'<span class="label label-success">处理中</span><br />',
					'<span class="label label-success">预订成功</span><br />',
					'<span class="label label-important">预订失败</span><br />',
					'<span class="label label-success">重点关注</span><br />' 
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
	 * 获取住宿信息
	 *
	 * @param string $campid        	
	 * @param string $buildingid        	
	 * @param string $type        	
	 */
	function get_building($campid = null, $buildingid = null, $type = null,$roomid=null) {
		$data = null;
		if ($campid != null && $buildingid != null && $roomid != null) {
			$publics = CF ( 'publics', '', CONFIG_PATH );
			// 校区
			$camp = $this->db->select ( '*' )->get_where ( 'school_accommodation_campus', 'id = ' . $campid )->row ();
			
			// 住宿楼
			$building = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $buildingid . ' AND columnid = ' . $campid )->row ();
			// 房间类型
			$room = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'bulidingid = ' . $buildingid . ' AND id = ' . $roomid )->row ();
			$data = '校区：' . $camp->name . '<br />';
			$data .= '住宿楼：' . $building->name . '<br />';
			$data .= '房间名：' . $room->name . '<br />';
			$data .= '房型：' . $publics ['room'] [$room->campusid] . ' RMB ' . $room->prices . ' 单位: 人/天<br />';
		}
		return $data;
	}
	/**
	 * [onsite 现场缴费]
	 * @return [] [html]
	 */
	function onsite(){
		$s = intval ( $this->input->get ( 's' ) );
		$userid=$this->input->get('userid');
		$id=intval ( $this->input->get ( 'id' ) );
		$type=5;
		$url='/master/enrollment/acc_apply/do_onsite';
		$jump_url='/master/enrollment/acc_apply';
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
	 * [do_onsite 现场缴费]
	 * @return [type] [ajax]
	 */
	function do_onsite(){
		$data=$this->input->post();
		if(!empty($data)){
			$result=$this->acc_apply_model->pay_change_state($data);
			if($result){
				$results=$this->acc_apply_model->insert_pay_record($data);
				if($results){
					ajaxReturn('','提交成功',1);
				}
			}
		}
		ajaxReturn('','未知错误',0);
	}
	/**
	 * [allocation_room 分配房间弹窗]
	 * @return [type] [description]
	 */
	function allocation_room(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval ( $this->input->get ( 'id' ) );
		$where=' id = '.$id;
		$acc_info=$this->acc_apply_model->get_one($where);
		//获取校区名
		$campus_name=$this->acc_apply_model->get_campus_name($acc_info->campid);
		//楼层名字
		$building_name=$this->acc_apply_model->get_buliding_name($acc_info->buildingid);
		//房间名字
		$room_name=$this->acc_apply_model->get_room_name($acc_info->roomid);
		//房间满没满
		$is_man=$this->acc_apply_model->get_room_manmeiman($acc_info->roomid);
        if($is_man==1){
            //查询预定的房间没有没他的
            $ids=$this->db->select('id')->where('roomid ='.$acc_info->roomid.' AND paystate = 1')->get('accommodation_info')->result_array();
            if(!empty($ids)){
                foreach($ids as $k=>$v){
                    if($v['id']==$id){
                        $is_man=0;
                    }
                }
            }
        }
		if (! empty ( $s )) {
			$html = $this->_view ( 'allocation_room', array (
				'id'=>$id,
				'acc_info'=>$acc_info,
				'roomid'=>$acc_info->roomid,
				'campus_name'=>$campus_name,
				'building_name'=>$building_name,
				'room_name'=>$room_name,
				'campusid'=>$acc_info->campid,
				'buildingid'=>$acc_info->buildingid,
				'floor'=>$acc_info->floor,
				'is_tj'=>0,
				'is_man'=>$is_man
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [allocation_student 分配学生到房间]
	 * @return [type] [description]
	 */
	function allocation_student(){
		$userid=intval($this->input->get('userid'));
		$roomid=intval($this->input->get('roomid'));
		$acc_id=intval($this->input->get('id'));
		$campusid=intval ( $this->input->get ( 'campusid' ) );
		$buildingid=intval ( $this->input->get ( 'buildingid' ) );
		$floor=intval ( $this->input->get ( 'floor' ) );
		$is_tj=intval ( $this->input->get ( 'is_tj' ) );

		if(!empty($userid)&&!empty($roomid)){
			$id=$this->acc_apply_model->insert_user_room($userid,$roomid);
			if(!empty($id)){
				//更新房间的的状态判断满不满和已经预订了多少人
				$this->acc_apply_model->update_room_shate($roomid);
				$email=$this->acc_apply_model->get_user_email($userid);
				if(!empty($email)){
					//修改状态
					$acc_state_arr['acc_state']=3;
					$acc_state_arr['campid']=$campusid;
					$acc_state_arr['roomid']=$roomid;
					$acc_state_arr['buildingid']=$buildingid;
					$acc_state_arr['floor']=$floor;
					if(!empty($is_tj)){
						$this->insert_history($acc_id,8);
					}
					$this->insert_history($acc_id,3);
					$this->db->update('accommodation_info',$acc_state_arr,'id='.$acc_id);
					$this->load->library ( 'sdyinc_email' );
					// 发邮件
					$val_arr = array (
							'email' => '123',
					);
					$MAIL = new sdyinc_email ();
						
					$MAIL->dot_send_mail ( 31, $email, $val_arr );
				}
				
				ajaxReturn('','',1);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [before_student 预定失败学生]
	 * @return [type] [description]
	 */
	function before_student(){
		$userid=intval($this->input->get('userid'));
		$acc_id=intval($this->input->get('id'));
		if(!empty($userid)&&!empty($acc_id)){
			//获取学生邮箱
			$email=$this->acc_apply_model->get_user_email($userid);
			if(!empty($email)){
				//修改状态
				$acc_state_arr['acc_state']=4;
				$this->db->update('accommodation_info',$acc_state_arr,'id='.$acc_id);
				//更新房间预订人数
				$this->grf_update_room();
				$this->load->library ( 'sdyinc_email' );
				// 发邮件
				$val_arr = array (
						'scsc' => '中华人民共和国',
				);
				$MAIL = new sdyinc_email ();
					
				$MAIL->dot_send_mail ( 32, $email, $val_arr );
			}
			
			ajaxReturn('','',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [adjust 调剂页面]
	 * @return [type] [description]
	 */
	function adjust(){
		$id=intval($this->input->get('id'));
		$where=' id = '.$id;
		$acc_info=$this->acc_apply_model->get_one($where);
		$campus_info=$this->acc_apply_model->get_campus_info();
		$this->_view ( 'adjust_index' ,array(
			'campus_info'=>$campus_info,
			'acc_info'=>$acc_info
			));
	}
	/**
	 * [get_buliding 获取该校区的住宿楼]
	 * @return [type] [description]
	 */
	function get_buliding(){
		$cid=intval($this->input->get('cid'));
		if(!empty($cid)){
			$data=$this->acc_apply_model->get_buliding_info($cid);
			ajaxReturn($data,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [get_buliding_floor 获取楼房的层数]
	 * @return [type] [description]
	 */
	function get_buliding_floor(){
		$bid=intval($this->input->get('bid'));
		if(!empty($bid)){
			$data=$this->acc_apply_model->get_buliding_floor($bid);
			ajaxReturn($data,'',1);
		}
		ajaxReturn('','',0);
	}
	/**
	 * [adjust_sure 筛选出来的房间]
	 * @return [type] [description]
	 */
	function adjust_sure(){
		$data=$this->input->post();
		
		if(empty($data['campusid'])){
			ajaxReturn('','请选择校区',0);
		}
		if(empty($data['bulidingid'])){
			ajaxReturn('','请选择宿舍楼',0);
		}
		if(empty($data['floor'])){
			ajaxReturn('','请选择楼层',0);
		}
		
		$room=$this->acc_apply_model->get_where_room($data);
		
		if(!empty($room)){
			$arr['room']=$room;
			$arr['userid']=$data['userid'];
			$arr['id']=$data['id'];
			$arr['campusid']=$data['campusid'];
			$arr['bulidingid']=$data['bulidingid'];
			$arr['floor']=$data['floor'];
			ajaxReturn($arr,'',1);
		}
		ajaxReturn('','该楼层下还没有房间','');
	}
	function adjust_allocation_room(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval ( $this->input->get ( 'id' ) );
		$campusid=intval ( $this->input->get ( 'campusid' ) );
		$buildingid=intval ( $this->input->get ( 'bulidingid' ) );
		$roomid=intval ( $this->input->get ( 'roomid' ) );
		$floor=intval ( $this->input->get ( 'floor' ) );
		$where=' id = '.$id;
		$acc_info=$this->acc_apply_model->get_one($where);
		//获取校区名
		$campus_name=$this->acc_apply_model->get_campus_name($campusid);
		//楼层名字
		$building_name=$this->acc_apply_model->get_buliding_name($buildingid);
		//房间名字
		$room_name=$this->acc_apply_model->get_room_name($roomid);
		//房间满没满
		$is_man=$this->acc_apply_model->get_room_manmeiman($acc_info->roomid);
        if($is_man==1){
            //查询预定的房间没有没他的
            $ids=$this->db->select('id')->where('roomid ='.$acc_info->roomid.' AND paystate = 1')->get('accommodation_info')->result_array();
            if(!empty($ids)){
                foreach($ids as $k=>$v){
                    if($v['id']==$id){
                        $is_man=0;
                    }
                }
            }
        }
		if (! empty ( $s )) {
			$html = $this->_view ( 'allocation_room', array (
				'id'=>$id,
				'acc_info'=>$acc_info,
				'roomid'=>$roomid,
				'campus_name'=>$campus_name,
				'building_name'=>$building_name,
				'room_name'=>$room_name,
				'campusid'=>$campusid,
				'buildingid'=>$buildingid,
				'floor'=>$floor,
				'biaoji'=>1,
				'is_tj'=>1,
				'is_man'=>$is_man
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
}

