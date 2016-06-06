<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Acc_dispose_student extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/enrollment/';
		$this->load->model ( $this->view . 'acc_dispose_student_model' );
		$this->grf_update_room();
	}
	
	/**
	 * 后台学生管理
	 */
	function index() {
		//列出在学的学生   添加字段标记为校内住宿和校外住宿
		$label_id = $this->input->get ( 'label_id' );
		$label_id = ! empty ( $label_id ) ? $label_id : '1';
		$campid = $this->input->get ( 'campid' );
		$campid = ! empty ( $campid ) ? $campid : '0';
		$b_id = $this->input->get ( 'bid' );
		$b_id = ! empty ( $b_id ) ? $b_id : '0';
		$floor = $this->input->get ( 'floor' );
		$floor = ! empty ( $floor ) ? $floor : '0';
		$r_id = $this->input->get ( 'rid' );
		$r_id = ! empty ( $r_id ) ? $r_id : '0';
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
			$label_id = ! empty ( $label_id ) ? $label_id : '1';
			$campid = $this->input->get ( 'campid' );
			$campid = ! empty ( $campid ) ? $campid : '0';
			$b_id = $this->input->get ( 'bid' );
			$b_id = ! empty ( $b_id ) ? $b_id : '0';
			$floor = $this->input->get ( 'floor' );
			$floor = ! empty ( $floor ) ? $floor : '0';
			$r_id = $this->input->get ( 'rid' );
			$r_id = ! empty ( $r_id ) ? $r_id : '0';
			$where='zust_student.acc_state='.$label_id;
			if($campid!=0){
				$where.=' AND zust_accommodation_info.campid='.$campid;
			}
			if($b_id!=0){
				$where.=' AND zust_accommodation_info.buildingid='.$b_id;
			}
			if($floor!=0){
				$where.=' AND zust_accommodation_info.floor='.$floor;
			}
			if($r_id!=0){
				$where.=' AND zust_accommodation_info.roomid='.$r_id;
			}
			$sSearch_0 =  $this->input->get ( 'sSearch_0' ) ;
			if (! empty ( $sSearch_0 )) {
				$where .= "
				AND 
				zust_student.id LIKE '%{$sSearch_0}%'
				
				";
			}
			$sSearch_3 =  $this->input->get ( 'sSearch_3' ) ;
			if (! empty ( $sSearch_3 )) {
				$where .= "
				AND (
				zust_student.sex ='{$sSearch_3}'
				)
				";
			}
			$sSearch_4 =  $this->input->get ( 'sSearch_4' ) ;
			if (! empty ( $sSearch_4 )) {
				$where .= "
				AND (
				zust_student.nationality ='{$sSearch_4}'
				)
				";
			}
			$sSearch_5 =  $this->input->get ( 'sSearch_5' ) ;
			if (! empty ( $sSearch_5 )) {
				$where .= "
				AND (
				zust_student.passport LIKE '%{$sSearch_5}%'
				OR 
				zust_student.name LIKE '%{$sSearch_5}%'
				OR 
				zust_student.email LIKE '%{$sSearch_5}%'
				)
				
				";
			}
            // 排序
            $orderby = null;
            if (isset ( $_GET ['iSortCol_0'] )) {
                for($i = 0; $i < intval ( $_GET ['iSortingCols'] ); $i ++) {
                    if ($_GET ['bSortable_' . intval ( $_GET ['iSortCol_' . $i] )] == "true") {
                        $orderby = $field [intval ( $_GET ['iSortCol_' . $i] )] . ' ' . mysql_real_escape_string ( $_GET ['sSortDir_' . $i] );
                    }
                }
            }

			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->acc_dispose_student_model->count ( $where,$label_id);
			$output ['aaData'] = $this->acc_dispose_student_model->get ( $where,$limit, $offset, $orderby,$label_id);

			foreach ( $output ['aaData'] as $item ) {
				$item->sex=!empty($item->sex)?$item->sex==1?'男':'女':'';

				$item->checkbox='<input type="checkbox" name="sid[]" value="'.$item->userid.'">';
				$item->nationality=$this->acc_dispose_student_model->get_nationality($item->nationality);
				$item->student_info="英文名:{$item->enname}<br />邮箱:{$item->email}<br />性别:{$item->sex}<br / >护照号:{$item->passport}<br / >国家:{$item->nationality}";
				if(!empty($item->campid)){
					$item->campid=$this->acc_dispose_student_model->get_campus_name($item->campid);
				}
				if(!empty($item->buildingid)){
					$item->buildingid=$this->acc_dispose_student_model->get_buliding_name($item->buildingid);
				}
				if(!empty($item->floor)){
					$item->floor='第'.$item->floor.'层';
				}
				if(!empty($item->roomid)){
					$item->roomid=$this->acc_dispose_student_model->get_room_name($item->roomid);
					$item->roomid=$item->roomid['name'];
				}
				if($label_id==1){
					$item->operation = '
							<a href="/master/enrollment/acc_in/adjust?id='.$item->id.'"  class="btn btn-xs btn-info">调剂</a>
						<a onclick="pub_alert_html(\'' . $this->zjjp . 'acc_dispose_student/out_room?userid='.$item->userid. '&s=1\')" class="btn btn-xs btn-info btn-white" href="javascript:;">退房</a>
					';
				}
				if($label_id==2){
					$item->operation = '
						<a class="btn btn-xs btn-info" href="/master/enrollment/acc_dispose_student/outside_student?userid='.$item->userid. '">设置校外住宿信息</a>
					';
				}
			}
			exit ( json_encode ( $output ) );
		}
		//获取全部的校区
		$camp_info=$this->acc_dispose_student_model->get_campus_info();
		$b_info=array();
		if($campid!=0){
			$b_info=$this->acc_dispose_student_model->get_building_info($campid,0);
		}
		$b_one_info=array();
		if($b_id!=0){
			$b_one_info=$this->acc_dispose_student_model->get_building_info($campid,$b_id);
		}
		$r_info=array();
		if($b_id!=0&&$floor!=0){
			$r_info=$this->acc_dispose_student_model->get_room_info($b_id,$floor);
		}
		//国籍
		$nationality=CF('public','',CACHE_PATH);
		$this->_view ( 'acc_dispose_student_index', array (
				'nationality'=>$nationality,
				'label_id'=>$label_id,
				'camp_info'=>$camp_info,
				'campid'=>$campid,
				'b_info'=>$b_info,
				'b_id'=>$b_id,
				'b_one_info'=>$b_one_info,
				'floor'=>$floor,
				'r_info'=>$r_info,
				'r_id'=>$r_id
		) );
	}
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
                '',
				'zust_student.id',
				'zust_student.enname',
				'zust_student.campid',
				'zust_student.buildingid',
				'zust_student.floor',
				'zust_student.roomid',
				''
		);
	}
	//退房管理
	function out_room(){
		$s = intval ( $this->input->get ( 's' ) );
		$userid=intval ( $this->input->get ( 'userid' ) );
		$where=' userid = '.$userid.' AND acc_state = 6';
		$acc_info=$this->acc_dispose_student_model->get_acc_one($where);
		//获取校区名
		$campus_name=$this->acc_dispose_student_model->get_campus_name($acc_info->campid);
		//楼层名字
		$building_name=$this->acc_dispose_student_model->get_buliding_name($acc_info->buildingid);
		//房间名字
		$room_info=$this->acc_dispose_student_model->get_room_name($acc_info->roomid);

		//计算出电费思密达
		$shou_dian=$this->db->get_where('budget','userid = '.$userid.' AND budget_type = 1 AND type = 7 AND paystate =1')->result_array();
		$zheng_electric=0;
		if(!empty($shou_dian)){
			foreach ($shou_dian as $key => $value) {
				$zheng_electric+=$value['paid_in'];
			}
		}
		$tui_dian=$this->db->get_where('budget','userid = '.$userid.' AND budget_type = 2 AND type = 7')->result_array();
		$tui_electric=0;
		if(!empty($tui_dian)){
			foreach ($tui_dian as $key => $value) {
				$tui_electric+=$value['true_returned'];
			}
		}
		//查询用过的电费
		$yijing=$this->db->get_where('room_electric_user','userid = '.$userid)->result_array();
		$yijing_money=0;
		if(!empty($yijing)){
			foreach ($yijing as $kj => $vj) {
				$yijing_money+=$vj['money'];
			}
		}
		$electric_money=$zheng_electric-$tui_electric-$yijing_money;

		//计算住宿费的剩余
		$acc_money=$this->acc_dispose_student_model->get_surplus_acc_money($acc_info);



		//电费押金
		$shou_dian_pledge=$this->db->get_where('budget','userid = '.$userid.' AND budget_type = 1 AND type = 14 AND paystate =1')->row_array();
		$tui_dian_pledge=$this->db->get_where('budget','userid = '.$userid.' AND budget_type = 2 AND type = 14')->row_array();
		$zheng_dian=!empty($shou_dian_pledge['paid_in'])?$shou_dian_pledge['paid_in']:0;
		$tui_dian=!empty($tui_dian_pledge['true_returned'])?$tui_dian_pledge['true_returned']:0;
		$dian_pledge=$zheng_dian-$tui_dian;
		//住宿押金
		$shou_acc_pledge=$this->db->get_where('budget','userid = '.$userid.' AND budget_type = 1 AND type = 10 AND paystate =1')->row_array();
		$tui_acc_pledge=$this->db->get_where('budget','userid = '.$userid.' AND budget_type = 2 AND type = 10')->row_array();
		$zheng_acc=!empty($shou_acc_pledge['paid_in'])?$shou_acc_pledge['paid_in']:0;
		$tui_acc=!empty($tui_acc_pledge['true_returned'])?$tui_acc_pledge['true_returned']:0;
		$acc_pledge=$zheng_acc-$tui_acc;

		if (! empty ( $s )) {
			$html = $this->_view ( 'out_student', array (
				'userid'=>$userid,
				'acc_info'=>$acc_info,
				'roomid'=>$acc_info->roomid,
				'campus_name'=>$campus_name,
				'building_name'=>$building_name,
				'room_info'=>$room_info,
				'campusid'=>$acc_info->campid,
				'buildingid'=>$acc_info->buildingid,
				'floor'=>$acc_info->floor,
				'acc_money'=>$acc_money,
				'acc_pledge'=>$acc_pledge,
				'dian_pledge'=>$dian_pledge,
				'electric_money'=>$electric_money
				), true );
			ajaxReturn ( $html, '', 1 );
		}

		//计入历史记录
	}
	/**
	 * [get_acc_fee 动态获取退的房钱]
	 * @return [type] [description]
	 */
	function get_acc_fee(){
		$out_time=$this->input->get('out_time');
		$id=$this->input->get('id');
		$yuan=$this->input->get('yuan');

		if(empty($out_time)){
			ajaxReturn($yuan,'',0);
		}
		$acc_info=$this->db->get_where('accommodation_info','id = '.$id)->row_array();
		$out_time=strtotime($out_time);
		if($acc_info['accstarttime']>$out_time){
			ajaxReturn($yuan,'退房时间不能小于入住时间',0);
		}
		$time=($out_time-$acc_info['accstarttime'])/24/3600+1;
		$total_money=$acc_info['accendtime']*$acc_info['registeration_fee'];
		$bufen_money=$time*$acc_info['registeration_fee'];
		if($total_money<$bufen_money){
			ajaxReturn($yuan,'费用不足退到所选日期',0);
		}
		ajaxReturn($total_money-$bufen_money,'',1);
	}
	/**
	 * [ture_out_room 确认退房]
	 * @return [type] [description]
	 */
	function ture_out_room(){
		$data=$this->input->post();
		$type=$this->input->get('type');
		$accid=$data['accid'];
		unset($data['accid']);

		//保存到学生退房的那个表
		if(!empty($data)){
			// $id=$this->acc_dispose_student_model->save_out_room($data);
			$id=1;
			if(!empty($id)){
				//更新订单状态=7
				$arr['acc_state']=7;
				$this->db->update('accommodation_info',$arr,'id = '.$accid);
				//更新在学表为校外住宿
				$student_arr['acc_state']=$type;
				$this->db->update('student',$student_arr,'userid = '.$data['userid']);
				$this->grf_update_room();

				//获取学生的学期
				$student_info=$this->db->get_where('student','userid = '.$data['userid'])->row_array();
				if(!empty($student_info['squadid'])){
					$squad_info=$this->db->get_where('squad','id = '.$student_info['squadid'])->row_array();
					if(!empty($squad_info['nowterm'])){
						$term=$squad_info['nowterm'];
					}
				}else{
					$term=1;
				}
				//更新收支表
				if(!empty($data['ture_acc_money'])){
					//住宿费
					$this->tui_budget($data['userid'],4,$term,$data['ought_acc_money'],$data['ture_acc_money']);
				}
				if(!empty($data['ture_electric_pledge_money'])){
					//电费押金
					$this->tui_budget($data['userid'],14,$term,$data['ought_electric_pledge_money'],$data['ture_electric_pledge_money']);
					//电费押金状态
					$electric_pledge=$this->db->get_where('electric_pledge','userid = '.$data['userid'].' AND paystate = 1 AND is_retreat <> 1')->row_array();
					$this->db->update('electric_pledge',array('is_retreat'=>1),'id = '.$electric_pledge['id']);
				}
				if(!empty($data['ture_electric_money'])){
					//电费表
					$this->tui_budget($data['userid'],7,$term,$data['ought_electric_money'],$data['ture_electric_money']);
					//插入相应的电费相减
					$this->db->insert('electric_info',array('userid'=>$data['userid'],'paid_in'=>$data['ture_electric_money']-$data['ture_electric_money']*2,'paystate'=>1,'paytype'=>0,'paytime'=>time(),'createtime'=>time(),'adminid'=>$_SESSION ['master_user_info']->id));
					
				}
				
				if(!empty($data['ture_acc_pledge_money'])){
					//住宿费押金
					$this->tui_budget($data['userid'],10,$term,$data['ought_acc_pledge_money'],$data['ture_acc_pledge_money']);
					//住宿费押金状态
					$acc_pledge=$this->db->get_where('acc_pledge_info','userid = '.$data['userid'].' AND state = 1 AND is_retreat <> 1')->row_array();
					$this->db->update('acc_pledge_info',array('is_retreat'=>1),'id = '.$acc_pledge['id']);
					

				}
				
				

				if($type==2){
					$num=7;
				}elseif($type==3){
					$num=10;
				}else{
					$num=17;
				}
				//更新历史表
				$this->insert_history($accid,$num);
				//插入学生退房表
				$data['out_room_cause']=$type;
				$this->acc_dispose_student_model->save_out_room($data);
				ajaxReturn('','',1);
			}
			

		}
		ajaxReturn('','',0);
	}
	/**
	 * [tui_budget 插入退费表]
	 * @return [type] [description]
	 */
	function tui_budget($userid,$type,$term,$should_returned,$true_returned){
		$data=array(
			'userid'=>$userid,
			'budget_type'=>2,
			'type'=>$type,
			'term'=>$term,
			'should_returned'=>$should_returned,
			'true_returned'=>$true_returned,
			'returned_time'=>time(),
			'createtime'=>time(),
			'adminid'=>$_SESSION ['master_user_info']->id
			);
		$this->db->insert('budget',$data);
	}
	/**
	 * [outside_student 设置校外住宿的学生信息]
	 * @return [type] [description]
	 */
	function outside_student(){
		$userid=intval($this->input->get('userid'));
		$where='userid = '.$userid;
		$info=$this->acc_dispose_student_model->get_one_lan_info($where);
		
		$this->_view ( 'edit_outside_student', array (
				'userid'=>$userid,
				'info'=>$info
		) );
	}
	/**
	 * [insert_landlord_info 插入房东信息]
	 * @return [type] [description]
	 */
	function insert_landlord_info(){
		$data=$this->input->post();
		if(!empty($data)){
			$ids=$this->acc_dispose_student_model->save_landlord_info($data);
			if($ids){
				ajaxReturn('','',1);
			}
		}
		ajaxReturn('','',0);
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
}