<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Acc_dispose_quarterage extends Master_Basic {
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
		$this->load->model ( $this->view . 'acc_dispose_quarterage_model' );
		
		
		// 求学生的数量
	}
	
	/**
	 * 后台主页
	 */
	function index() {
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			// 翻页
			$limit = "";
			$offset = "";
			if (isset ( $_GET ['iDisplayStart'] ) && $_GET ['iDisplayLength'] != '-1') {
				$offset = intval ( $_GET ['iDisplayStart'] );
				$limit = intval ( $_GET ['iDisplayLength'] );
			}
			$label_id = $this->input->get ( 'label_id' );
			$label_id = ! empty ( $label_id ) ? $label_id : '1';
			$putup_day=CF('warning_line','',CONFIG_PATH);
			//状态筛选
			// if(!empty($label_id)&&$label_id==3){
			// 	$where='residue_days < 0';
			// }elseif(!empty($label_id)&&$label_id==2){
			// 	$where='residue_days <'.$putup_day['putup_day'].' AND residue_days > 0';

			// }elseif(!empty($label_id)&&$label_id==1){
				$where='zust_accommodation_info.id <> ""';
			// }

            // 查询条件组合
            $condition = dateTable_where_order_limit ( $fields );

            // 排序
            $orderby = $condition['orderby'];
            if(!empty($condition['where'])) {
                $where .= ' AND ' . $condition['where'];
            }

			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_dispose_quarterage_model->count ( $where);
			$output ['aaData'] = $this->acc_dispose_quarterage_model->get ( $where,$limit, $offset, $orderby);
			// echo $this->db->last_query();exit;
			foreach ( $output ['aaData'] as $key=>$item ) {
				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->userid.'">';
				//获取校区名
				$item->campid=$this->acc_dispose_quarterage_model->get_campus_name($item->campid);
				//楼层名字
				$item->buildingid=$this->acc_dispose_quarterage_model->get_buliding_name($item->buildingid);
				$item->roomid=$this->acc_dispose_quarterage_model->get_room_name($item->roomid);
				$item->floor='第'.$item->floor.'层';
				$item->acc_money=$this->_get_acc_money($item->id);
				$this->db->update('accommodation_info',array('residual_amount'=>$item->acc_money),'id = '.$item->id);
				$item->residue_days=$this->_get_residue_days($item->id);
				if(!empty($label_id)&&$label_id==2){
					if($putup_day['putup_day']<$item->residue_days||$item->acc_money<0){
						unset($output ['aaData'][$key]);
					}

				}
				if(!empty($label_id)&&$label_id==3){
					if($item->residue_days>0){
						unset($output ['aaData'][$key]);
					}

				}
				$item->nationality=$this->acc_dispose_quarterage_model->get_nationality($item->nationality);


			}
			$output ['aaData']=array_values($output ['aaData']);
			exit ( json_encode ( $output ) );
		}
		//国籍
		$nationality=CF('public','',CACHE_PATH);
		$this->_view ( 'acc_dispose_quarterage_index', array (
				'label_id' => $label_id,
				'nationality'=>$nationality
		) );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'',
				'zust_accommodation_info.id',
				'enname',
				'email',
				'nationality',
				'passport',
				'campid',
				'buildingid',
				'floor',
				'roomid',
				''
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
	 * [_get_acc_money 获取剩余的钱数]
	 * @return [type] [description]
	 */
	function _get_acc_money($id){
		if(!empty($id)){
			$acc_info=$this->db->get_where('accommodation_info','id = '.$id)->row();
			//获取该房间的信息
			$room_info=$this->db->get_where('school_accommodation_prices','id = '.$acc_info->roomid)->row_array();
			$now_time=time();
			if($now_time>$acc_info->accstarttime){
				$now_day=ceil(($now_time-$acc_info->accstarttime)/(3600*24));
				return ($acc_info->accendtime-$now_day)*$room_info['prices'];
			}else{
				return $acc_info->accendtime*$room_info['prices'];
			}
		}
	}
	/**
	 * [_get_residue_days 获取剩余的天数]
	 * @return [type] [description]
	 */
	function _get_residue_days($id){
		if(!empty($id)){
			$acc_info=$this->db->get_where('accommodation_info','id = '.$id)->row();
			//获取该房间的信息
			$now_time=time();
			if($now_time>$acc_info->accstarttime){
				$now_day=ceil(($now_time-$acc_info->accstarttime)/(3600*24));
				return $acc_info->accendtime-$now_day;
			}else{
				return $acc_info->accendtime;;
			}
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
			$userid=$this->acc_dispose_quarterage_model->get_userid_arr($data['sid']);
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

		$id = $this->acc_dispose_quarterage_model->save_message($data);

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
			$emailarr=$this->acc_dispose_quarterage_model->get_email_user_arr($data['sid']);
		}else{
			$emailarr=$this->acc_dispose_quarterage_model->get_email_arr($data['sid']);
		}
		$emailstr='';
		foreach ($emailarr as $k => $v) {
			$emailstr.=$v.',';
		}
		$adrset=$this->acc_dispose_quarterage_model->get_addresserset();
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
		$id = $this->acc_dispose_quarterage_model->save_email($data);
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
}