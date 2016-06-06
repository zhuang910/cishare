<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *
 */
class Acc_in extends Master_Basic {
	protected $_size = 3;
	protected $_count = 0;
	protected $_countpage = 0;
	protected $data_student = array ();
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->load->model ( $this->view . 'acc_in_model' );
		$this->grf_update_room();
	}

	/**
	 * 后台主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '3';
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$field = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}
			//biaoqian
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '3';
			//状态筛选
			if(!empty($label_id)&&$label_id==1){
				$where='acc_state <> 3 AND acc_state <> 6';
			}else{
				$where='acc_state='.$label_id;
			}

            if (isset ( $_GET ['sSearch'] ) && $_GET ['sSearch'] != "") {
                $where .= " AND (";
                for($i = 0; $i < count ( $field ); $i ++) {
                    if(!empty($field [$i])) {
                        if (isset ($_GET ['bSearchable_' . $i]) && $_GET ['bSearchable_' . $i] == "true") {

                            $where .= $field [$i] . " LIKE '%" . mysql_real_escape_string($_GET ['sSearch']) . "%' OR ";
                        }
                    }
                }
                $where = substr_replace ( $where, "", - 3 ) . ')';
            }


			$sSearch_0 =  $this->input->get ( 'sSearch_0' ) ;
			if (! empty ( $sSearch_0 )) {
				$where .= " AND zust_accommodation_info.id = '{$sSearch_0}' ";
			}
			$sSearch_1 =  $this->input->get ( 'sSearch_1' ) ;
			if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
				chname LIKE '%{$sSearch_1}%'
				)
				";
			}
			$sSearch_2 =  $this->input->get ( 'sSearch_2' ) ;
			if (! empty ( $sSearch_2 )) {
				$where .= "
				AND (
				email LIKE '%{$sSearch_2}%'
				)
				";
			}
			$sSearch_3 =  $this->input->get ( 'sSearch_3' ) ;
			if (! empty ( $sSearch_3 )) {
				$where .= "
				AND (
				sex ='{$sSearch_3}'
				)
				";
			}
			$sSearch_4 =  $this->input->get ( 'sSearch_4' ) ;
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				nationality ='{$sSearch_4}'
				)
				";
			}
			$sSearch_5 =  $this->input->get ( 'sSearch_5' ) ;
			if (! empty ( $sSearch_5 )) {
				$where .= "
				AND (
				passport LIKE '%{$sSearch_5}%'
				)
				";
			}
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_in_model->count ( $where);
			$output ['aaData'] = $this->acc_in_model->get ( $where,$limit, $offset, 'id DESC');
			foreach ( $output ['aaData'] as $item ) {
				$item->sex=!empty($item->sex)?$item->sex==1?'男':'女':'';
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->userid.'">';
				$item->nationality=$this->acc_in_model->get_nationality($item->userid);
				$item->applytime=!empty($item->applytime)?date('Y-m-d',$item->applytime):'';
				$item->operation = '';
				if($item->acc_state==3){
					$item->operation = '

						<a class="btn btn-xs btn-info" href="' . $this->zjjp . 'acc_in' . '/look_info?id='.$item->id.'&userid=' . $item->userid . '">查看个人信息</a>
						<a onclick="pub_alert_html(\'' . $this->zjjp . 'acc_in/ensure_in?id='.$item->id. '&s=1\')" class="btn btn-xs btn-info btn-white" href="javascript:;">确认入住</a>
					';
				}
				if($item->acc_state!=3&&$item->acc_state!=6){
					$item->operation = '
					<a class="btn btn-xs btn-info" href="' . $this->zjjp . 'acc_in' . '/look_info?id='.$item->id.'&userid=' . $item->userid . '">查看个人信息</a>
						<a href="/master/enrollment/acc_in/adjust?id='.$item->id.'" class="btn btn-xs btn-info btn-white" href="javascript:;">查看空房</a>
					';
				}



			}
			exit ( json_encode ( $output ) );
		}



		//国籍
		$nationality=CF('public','',CACHE_PATH);
		$this->_view ( 'acc_in_index', array (
				'nationality'=>$nationality,
				'label_id'=>$label_id
		) );
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'zust_accommodation_info.id',
				'enname',
				'userid',
				'nationality',
				'email',
				'passport',
				'sex',
				'accstarttime',
				'type',
				'tj',
				'campid',
				'buildingid',
				'roomid',
				'applytime',
				'accendtime',
				'subtime',
				'state',
				'acc_state',
				'paystate',
				'remark'
		);
	}

	/**
	 * 获取状态
	 */
	function get_state($state = null) {
		if ($state != null) {
			$stateArray = array (
					0=>'',
					1 => '<span class="label label-success">在校</span>',
					2 => '<span class="label label-success">转学</span>',
					3 => '<span class="label label-success">正常离开</span>',
					4 => '<span class="label label-success">非正常离开</span>',
					5 => '<span class="label label-success">休学</span>',
					6 => '<span class="label label-success">申请</span>',
					7 => '<span class="label label-success">已报到</span>',
					8 => '<span class="label label-success">未报到</span>' ,
			);
			return $stateArray [$state];
		} else {
			return false;
		}
	}
	/**
	 * 获取学生状态
	 */
	function get_student_state($state =null) {
		if($state!=null){
			$stateArray = array (
					1 => '<span class="label label-success">正常</span>',
					2 => '<span class="label label-danger">异常</span>',

			);
			return $stateArray [$state];
		}else{
			return '<span class="label label-success">正常</span>';
		}

	}


	/**
	 * [send_message 批量发站内信]
	 * @return [type] [description]
	 */
	function send_message(){
		$data=$this->input->post();
		if(!empty($data['is_userid'])){
			$userid=$data['sid'];
		}else{
			$userid=$this->acc_in_model->get_userid_arr($data['sid']);
		}

		$idstr='';
		foreach ($userid as $k => $v) {
			$idstr.=$v.',';
		}
		$this->_view ('customemessage_send',array(
			'ids'=>$userid,
			'idstr'=>$idstr
			));
	}
	function insert_message(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;

		$id = $this->acc_in_model->save_message($data);

			if ($id == true) {
			 $this->send_messages($data,$content);

				ajaxReturn('', '添加成功', 1);
			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送消息
	 */
	function send_messages($data,$content){

		$senttoid=explode(',',$data['sentto']);
		$this->load->library('sdyinc_message');
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_message->custom_message($v,$data['title'],$content);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * [send_email 批量发邮件]
	 * @return [type] [description]
	 */
	function send_email(){
		$data=$this->input->post();
		if(!empty($data['is_userid'])){
			$emailarr=$this->acc_in_model->get_email_user_arr($data['sid']);
		}else{
			$emailarr=$this->acc_in_model->get_email_arr($data['sid']);
		}
		$emailstr='';
		foreach ($emailarr as $k => $v) {
			$emailstr.=$v.',';
		}
		$adrset=$this->acc_in_model->get_addresserset();
		$this->_view ('customemail_edit',array(
				'addresserset'=>$adrset,
				'emailarr'=> $emailarr,
				'emailstr'=>$emailstr
			));
	}
	function insert_email(){
		$data=$this->input->post();
		$content=$this->input->post('content');
		$data['sendtime']=time();
		$data['content']=$content;
		$id = $this->acc_in_model->save_email($data);
			if ($id == true) {
				$this->send_emails($data,$content);

			} else {
				ajaxReturn('', '添加失败', 0);
			}
	}
	/**
	 * 自定义发送邮件
	 */
	function send_emails($data,$content){


		$this->load->library('sdyinc_email');
		$senttoid=explode(',',$data['sentto']);
		foreach ($senttoid as $k => $v) {
			$this->sdyinc_email->do_send_mail($v,$data['addresserset'],$data['title'],$content,$data['reply_to']);
		}
		ajaxreturn('','操作成功',1);
	}
	/**
	 * [look_info 查看个人信息]
	 * @return [type] [description]
	 */
	function look_info(){
		$userid=intval(trim($this->input->get('userid')));
		$id=intval(trim($this->input->get('id')));
		if(!empty($userid)){
			$where=" id = {$userid}";
			$lists=$this->acc_in_model->get_user_one($where);
		}
		//收费信息
		if(!empty($id)){
			$where=" id = {$id}";
			$acc_info=$this->acc_in_model->get_one($where);
		}
		//住宿费押金状态
		$acc_pledge=$this->db->get_where('acc_pledge_info','userid = '.$userid,' AND state = 1 AND is_retreat <> 1')->row_array();
		//电费押金状态
		$electric_pledge=$this->db->get_where('electric_pledge','userid = '.$userid.' AND paystate = 1 AND is_retreat <> 1')->row_array();
		$nationality=CF('public','',CACHE_PATH);
		$this->_view ( 'acc_in_look_info', array (
				'nationality'=>$nationality,
				'lists'=>$lists,
				'acc_info'=>$acc_info,
				'acc_pledge'=>!empty($acc_pledge)?$acc_pledge:array(),
				'electric_pledge'=>!empty($electric_pledge)?$electric_pledge:array(),
		) );
	}
	/**
	 * [ensure_in 确认入住弹框]
	 * @return [type] [description]
	 */
	function ensure_in(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval ( $this->input->get ( 'id' ) );
		$where=' id = '.$id;
		$acc_info=$this->acc_in_model->get_acc_one($where);
		//获取校区名
		$campus_name=$this->acc_in_model->get_campus_name($acc_info->campid);
		//楼层名字
		$building_name=$this->acc_in_model->get_buliding_name($acc_info->buildingid);
		//房间名字
		$room_info=$this->acc_in_model->get_room_name($acc_info->roomid);
		$publics=CF('publics','',CONFIG_PATH);
		//获取已经入住的学生
		if(!empty($room_info['maxuser'])&&$room_info['maxuser']>1){
			$in_user=$this->acc_in_model->get_in_user($acc_info->roomid);

		}
		$nationality=CF('public','',CACHE_PATH);
		if (! empty ( $s )) {
			$html = $this->_view ( 'acc_in_ensure_in', array (
				'id'=>$id,
				'acc_info'=>$acc_info,
				'roomid'=>$acc_info->roomid,
				'campus_name'=>$campus_name,
				'building_name'=>$building_name,
				'room_info'=>$room_info,
				'publics'=>$publics,
				'in_user'=>!empty($in_user)?$in_user:array(),
				'nationality'=>$nationality,
				'campusid'=>$acc_info->campid,
				'buildingid'=>$acc_info->buildingid,
				'floor'=>$acc_info->floor
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}
	/**
	 * [ensure_in_user 确认学生入住]
	 * @return [type] [description]
	 */
	function ensure_in_user(){
		$userid=intval($this->input->get('userid'));
		if(!empty($userid)){
			//c查看学生是否在学
			$num=$this->db->select('count(*) as num')->where("userid = {$userid} AND state = 1")->get('student')->row_array();
			if($num['num']<=0){
				ajaxReturn('','该学生没有报道',2);
			}
		}
		$roomid=intval($this->input->get('roomid'));
		$acc_id=intval($this->input->get('id'));
		$campusid=intval ( $this->input->get ( 'campusid' ) );
		$buildingid=intval ( $this->input->get ( 'buildingid' ) );
		$floor=intval ( $this->input->get ( 'floor' ) );
		if(!empty($userid)&&!empty($roomid)){
			$this->acc_in_model->update_acc_is_in($acc_id);
			$id=$this->acc_in_model->insert_user_room($userid,$roomid,$campusid,$buildingid,$floor);
			if(!empty($id)){
				//更新房间的的状态判断满不满和已经住了多少人
				// $this->acc_in_model->update_room_shate($roomid);
				$email=$this->acc_in_model->get_user_email($userid);
				if(!empty($email)){
					//更新订单调剂后的后信息已经入住
					$acc_state_arr['acc_state']=6;
					$this->db->update('accommodation_info',$acc_state_arr,'id='.$acc_id);
					//插入历史记录表
					//更新到在学婊里字段校内住宿
					$student_arr['acc_state']=1;
					$this->db->update('student',$student_arr,'userid='.$userid);
					//更新房间入住预订人数
					$this->grf_update_room();
					//更新历史表
					$this->insert_history($acc_id,6);
					//修改状态
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
	}
	/**
	 * [adjust 调剂页面]
	 * @return [type] [description]
	 */
	function adjust(){
		$id=intval($this->input->get('id'));
		$where=' id = '.$id;
		$acc_info=$this->acc_in_model->get_one($where);
		$campus_info=$this->acc_in_model->get_campus_info();
		$this->_view ( 'acc_in_adjust_index' ,array(
			'campus_info'=>$campus_info,
			'acc_info'=>$acc_info
			));
	}
		function adjust_acc_instudent(){
		$s = intval ( $this->input->get ( 's' ) );
		$id=intval ( $this->input->get ( 'id' ) );
		$campusid=intval ( $this->input->get ( 'campusid' ) );
		$buildingid=intval ( $this->input->get ( 'bulidingid' ) );
		$roomid=intval ( $this->input->get ( 'roomid' ) );
		$floor=intval ( $this->input->get ( 'floor' ) );
		$where=' id = '.$id;
		$acc_info=$this->acc_in_model->get_one($where);
		//获取校区名
		$campus_name=$this->acc_in_model->get_campus_name($campusid);
		//楼层名字
		$building_name=$this->acc_in_model->get_buliding_name($buildingid);

		//房间名字
		$room_name=$this->acc_in_model->get_room_name($roomid);

		if (! empty ( $s )) {
			$html = $this->_view ( 'acc_in_allocation_room', array (
				'id'=>$id,
				'acc_info'=>$acc_info,
				'roomid'=>$roomid,
				'campus_name'=>$campus_name,
				'building_name'=>$building_name,
				'room_name'=>$room_name,
				'campusid'=>$campusid,
				'buildingid'=>$buildingid,
				'floor'=>$floor
				), true );
			ajaxReturn ( $html, '', 1 );
		}
	}

	/**
	 * [adjust_acc_instudent 调剂提交上来的学学生信息]
	 * @return [type] [description]
	 */
	function adjust_ensure_instudent(){
		$userid=intval($this->input->get('userid'));
		$roomid=intval($this->input->get('roomid'));
		$acc_id=intval($this->input->get('id'));
		$campusid=intval ( $this->input->get ( 'campusid' ) );
		$buildingid=intval ( $this->input->get ( 'buildingid' ) );
		$floor=intval ( $this->input->get ( 'floor' ) );
		if(!empty($userid)&&!empty($roomid)){
			$id=$this->acc_in_model->insert_user_room($userid,$roomid,$campusid,$buildingid,$floor);
			if(!empty($id)){
				//更新房间的的状态判断满不满和已经住了多少人
				// $this->acc_in_model->update_room_shate($roomid);
				$email=$this->acc_in_model->get_user_email($userid);
				if(!empty($email)){
					//调剂费用
					$acc_info=$this->db->get_where('accommodation_info','id='.$acc_id)->row_array();
					//算出还有多少天费用
					if($acc_info['accstarttime']>time()){
						//查询该房间的价格
						$old_room_info=$this->db->get_where('school_accommodation_prices','id = '.$acc_info['roomid'])->row_array();
						$total_price=$acc_info['accendtime']*$old_room_info['prices'];
						$new_room_info=$this->db->get_where('school_accommodation_prices','id = '.$roomid)->row_array();
						$accendtime=floor($total_price/$new_room_info['prices']);

						//更新订单调剂后的后信息已经入住
						$acc_state_arr['acc_state']=6;
						$acc_state_arr['campid']=$campusid;
						$acc_state_arr['roomid']=$roomid;
						$acc_state_arr['buildingid']=$buildingid;
						$acc_state_arr['floor']=$floor;
						$acc_state_arr['accendtime']=$accendtime;
						$acc_state_arr['floor']=$floor;
						$acc_state_arr['registeration_fee']=$new_room_info['prices'];
						$this->db->update('accommodation_info',$acc_state_arr,'id='.$acc_id);
						//更新学生表校内住宿
						$this->db->update('student',array('acc_state'=>1),'userid='.$userid);
					}else{
						//查询该房间的价格
						$old_room_info=$this->db->get_where('school_accommodation_prices','id = '.$acc_info['roomid'])->row_array();
						//已经过了几天了
						$yijing_day=ceil((time()-$acc_info['accstarttime'])/24/3600);
						$total_price=($acc_info['accendtime']-$yijing_day)*$old_room_info['prices'];
						$new_room_info=$this->db->get_where('school_accommodation_prices','id = '.$roomid)->row_array();
						$accendtime=floor($total_price/$new_room_info['prices']);

						//更新订单调剂后的后信息已经入住
						$acc_state_arr['acc_state']=6;
						$acc_state_arr['campid']=$campusid;
						$acc_state_arr['roomid']=$roomid;
						$acc_state_arr['buildingid']=$buildingid;
						$acc_state_arr['floor']=$floor;
						$acc_state_arr['accendtime']=$accendtime;
						$acc_state_arr['floor']=$floor;
						$acc_state_arr['registeration_fee']=$new_room_info['prices'];

						$this->db->update('accommodation_info',$acc_state_arr,'id='.$acc_id);
						//更新学生表校内住宿
						$this->db->update('student',array('acc_state'=>1),'userid='.$userid);
					}

					//更新订单表

					//修改状态
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
		//校区 楼宇 层数 房间 入住的时间 插入入住学生的表


		//更新房间表现在住的人数

		//发邮件

		//更新订单表的校区 楼宇 层数 房间  标记为已分配房间的订单


		//插入历史表

	}
}