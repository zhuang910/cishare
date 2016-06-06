<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 前台课程
 *
 * @author JJ
 *        
 */
class Accommodation extends Student_Basic {
	protected $bread_line = null;
	protected $zyj_nav = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		$this->load->model ( 'student/accommodation_model' );
		$this->load->model ( 'student/fee_model' );
		$this->grf_update_room();

	}
	
	/**
	 */
	function index() {
		$tiaoji=$this->input->get('tiaoji');
		if(empty($tiaoji)){
			$tiaoji=0;
		}
        //检查是否能转当前页面
        $is=$this->db->where('userid = '.$_SESSION ['student'] ['userinfo'] ['id'])->get('accommodation_info')->result_array();
        $is_jump=1;
        if(!empty($is)){
            foreach($is as $k=>$v){
                if($v['acc_state']==4){
                    $is_jump=1;
                }elseif($v['acc_state']==7){
                	$is_jump=1;
                }else{
                    $is_jump=0;
                }
            }
        }
        if($is_jump==0&&$tiaoji==0){
            echo '<script type="text/javascript">window.location.href="/'.$this->puri.'/student/student/accommodation"</script>';
        }
		$bid=intval(trim($this->input->get('bid')));
		$cid=intval(trim($this->input->get('cid')));
		if(empty($bid)){
			$bid=0;
		}
		if(empty($cid)){
			$cid=0;
		}
		$bulid_info=array();
		$room_info=array();
		$bulid_info_one=array();
		if($bid!=0&&$cid!=0){
			//获取该校区下的楼房
			$bulid_info=$this->accommodation_model->get_buliding_info($cid);
			//获取该校区下该漏侧下的所有
			$room_info=$this->accommodation_model->get_camp_bulid_room($cid,$bid);
			//获取该楼层
			$where="id = ".$bid;
			$bulid_info_one=$this->accommodation_model->get_buliding_one($where);
		}
		// var_dump($room_info);
		//编辑好的房间信息
		$room_info_hao=array();
		if(!empty($bulid_info_one['floor_num'])&&!empty($room_info)){
			for ($i=1; $i <= $bulid_info_one['floor_num']; $i++) { 
				foreach ($room_info as $k => $v) {
					if($i==$v['floor']){
						$room_info_hao[$i][]=$v;
					}
				}
			}
		}
		// var_dump($room_info_hao);exit;
		$campus_info=$this->accommodation_model->get_campus_info();
		$this->load->view ( '/student/acc_index',array(
			'campus_info'=>$campus_info,
			'bid'=>$bid,
			'cid'=>$cid,
			'room_info'=>$room_info,
			'bulid_info'=>$bulid_info,
			'bulid_info_one'=>$bulid_info_one,
			'room_info_hao'=>$room_info_hao,
			'tiaoji'=>$tiaoji
			));
	}
	/**
	 * [get_buliding 获取该校区的住宿楼]
	 * @return [type] [description]
	 */
	function get_buliding(){
		$cid=intval($this->input->get('cid'));
		if(!empty($cid)){
			$data=$this->accommodation_model->get_buliding_info($cid);
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
			$data=$this->accommodation_model->get_buliding_floor($bid);
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
		$room=$this->accommodation_model->get_where_room($data);
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
	/**
	 * [book_room 预订房间弹框]
	 * @return [type] [description]
	 */
	function book_room(){
		$tiaoji=$this->input->get('tiaoji');
		if(empty($tiaoji)){
			$tiaoji=0;
		}
		$roomid=intval(trim($this->input->get('roomid')));
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//用户信息
		$user_info=$this->accommodation_model->get_user_info($userid);
		//房间信息
		$room_info=$this->accommodation_model->get_room_info_one($roomid);
		//查看已经预订成功的学生
		$student_info=$this->accommodation_model->get_student_info($roomid);
		$nationality = CF ( 'nationality', '', 'application/cache/' );

		$this->load->view ( '/student/book_room', array(
			'user_info'=>$user_info,
			'room_info'=>$room_info,
			'student_info'=>$student_info,
			'nationality'=>$nationality,
			'tiaoji'=>$tiaoji
				) );
	}
	/**
	 * [sub_acc_book 提交页面]
	 * @return [type] [description]
	 */
	function sub_acc_book(){
		$data=$this->input->post();
		
		if(!empty($data)){
			// //组合字段
			// $data['accstarttime']=strtotime($data['accstarttime']);
			// $data['accendtime']=mysql_real_escape_string($data['accendtime']);
			// $data['remark']=mysql_real_escape_string($data['remark']);
			// $id=$this->accommodation_model->save_accommodation_info($data);
			//先生成收支表的一个记录
			//组合收支表的字段
			$budget=array(
				'userid'=>$data['userid'],
				'budget_type'=>1,
				'type'=>4,
				'payable'=>$data['accendtime']*$data['registeration_fee'],
				'paystate'=>0,
				'createtime'=>time()
				);
			$budgetid=$this->fee_model->insert_budget($budget);
			$max_cucasid = build_order_no ();
			//再生成所有订单表
			$order_info=array(
					'budget_id'=>$budgetid,
					'createtime'=>time(),
					'ordernumber'=>'ZUST'.$max_cucasid,
					'ordertype'=>4,
					'userid'=>$data['userid'],
					'ordermondey'=>$data['accendtime']*$data['registeration_fee'],
					'paystate'=>0

				);
			$orderid=$this->fee_model->insert_order($order_info);
			//在生成所有住宿主表
			$data['order_id']=$orderid;
			$data['subtime']=time();
			$data['applytime']=time();
			$data['acc_state']=1;
			$accid=$this->fee_model->insert_acc($data);

			//再生成住宿历史表
			$acc_history=array(
					'acc_id'=>$accid,
					'userid'=>$data['userid'],
					'campusid'=>$data['campid'],
					'buildingid'=>$data['buildingid'],
					'floor'=>$data['floor'],
					'roomid'=>$data['roomid']


				);
			$historyid=$this->fee_model->insert_history($acc_history);

			//查看押金有没有开
			//判断用不用交住宿押金   如果用就把住宿押金交上
			$is_yajin=CF('acc_pledge','',CONFIG_PATH);
			// var_dump($is_yajin);exit;
			if($is_yajin['acc_pledge']=='yes'){
				//生成住宿押金的收支表记录
				//组合收支表的字段
				$budget=array(
					'userid'=>$_SESSION['student'] ['userinfo'] ['id'],
					'budget_type'=>1,
					'type'=>10,
					'payable'=>$is_yajin['acc_pledgemoney'],
					'paystate'=>0,
					'createtime'=>time()
					);
				$budgetid=$this->fee_model->insert_budget($budget);
				$max_cucasid = build_order_no ();
				//再生成所有订单表
				$order_info=array(
						'budget_id'=>$budgetid,
						'createtime'=>time(),
						'ordernumber'=>'ZUST'.$max_cucasid,
						'ordertype'=>10,
						'userid'=>$_SESSION['student'] ['userinfo'] ['id'],
						'ordermondey'=>$is_yajin['acc_pledgemoney'],
						'paystate'=>0
					);
				$order_id=$this->fee_model->insert_order($order_info);
				//在生成所有住宿押金主表
				$pledge_data['order_id']=$order_id;
				$pledge_data['acc_id']=$accid;
				$pledge_data['userid']=$_SESSION['student'] ['userinfo'] ['id'];
				$pledge_data['campusid']=$data['campid'];
				$pledge_data['buildingid']=$data['buildingid'];
				$pledge_data['floor']=$data['floor'];
				$pledge_data['roomid']=$data['roomid'];
				$pledge_data['payable']=$is_yajin['acc_pledgemoney'];
				$pledge_data['pay']=$is_yajin['acc_pledgemoney'];
				$pledge_data['isproof']=1;
				$pledge_data['payable']=$is_yajin['acc_pledgemoney'];
				$pledge_data['state']=3;
				$pledge_data['paytime']=time();
				$pledge_data['createtime']=time();
				$pledge_id=$this->fee_model->insert_pledge($pledge_data);
			}
			if(!empty($historyid)){
                //更新房间的预定人数
				ajaxReturn('','预订成功',1);
			}
		}
	}
	/**
	 * [sub_acc_book_tiaoji 学生端调剂房间]
	 * @return [type] [description]
	 */
	function sub_acc_book_tiaoji(){
		$data=$this->input->post();
		$acc_id=$data['acc_id'];
		$roomid=$data['roomid'];
		$campusid=$data['campid'];
		$userid=$data['userid'];
		$buildingid=$data['buildingid'];
		$floor=$data['floor'];
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
			$acc_state_arr['acc_state']=3;
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
			$acc_state_arr['acc_state']=3;
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
			ajaxReturn('','',1);		
	}
	/**
	 * [repairs 保修]
	 * @return [type] [description]
	 */
	function repairs(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//用户信息
		$user_info=$this->accommodation_model->get_user_info($userid);
		$email='';
		if(!empty($user_info['email'])){
			$email=$user_info['email'];
		}
		//获取该学生的房间信息
		$acc_info=$this->accommodation_model->get_acc_info($userid);
		 $this->load->view ( '/student/repairs_page', array(
			'acc_info'=>$acc_info,
			'userid'=>$userid,
			'email'=>$email
				) );
		//ajaxReturn ( $html, '', 1 );
	}
	/**
	 * [sub_baoxiu 插入保修的数据]
	 * @return [type] [description]
	 */
	function sub_baoxiu(){
		$data=$this->input->post();
		if(empty($data['remark'])){
			ajaxReturn('','',2);
		}
		if(!empty($data)){
			$id=$this->accommodation_model->save_baoxiu($data);
			if(!empty($id)){
				ajaxReturn('','',1);
			}
		}
		ajaxReturn('','',0);
	}
	/**
	 * [repairs 保修页面]
	 * @return [type] [description]
	 */
	function repairs_page(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//保修信息
		$r_info=$this->accommodation_model->get_user_repairs_info($userid,0);
		$this->load->view ( '/student/acc_repairs_index',array(
			'r_info'=>$r_info
			));	
	}
	/**
	 * [repairs_page_in 处理用]
	 * @return [type] [description]
	 */
	function repairs_page_in(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//保修信息
		$r_info=$this->accommodation_model->get_user_repairs_info($userid,1);
		$this->load->view ( '/student/acc_repairs_index',array(
			'r_info'=>$r_info
			));	
	}
	/**
	 * [repairs_page_out 处理完的
	 * @return [type] [description]
	 */
	function repairs_page_out(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		//保修信息
		$r_info=$this->accommodation_model->get_user_repairs_info($userid,2);
		$this->load->view ( '/student/acc_repairs_index',array(
			'r_info'=>$r_info
			));	
	}
	/**
	 * [look_repairs 查看保修]
	 * @return [type] [description]
	 */
	function look_repairs(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$id=intval(trim($this->input->get('id')));
		//查看权限
		$is=$this->look_quanxian($userid,$id);
		if($is>0){
			$remark=$this->accommodation_model->get_baoxiu_remark($id);
			$html = $this->load->view ( '/student/repairs_page', array(
				'remark'=>$remark
				), true );
		ajaxReturn ( $html, '', 1 );
		}else{
			ajaxReturn('','',2);
		}
	}
	/**
	 * [look_quanxian 查看有没有权限]
	 * @return [type] [description]
	 */
	function look_quanxian($userid,$id){
		if(!empty($userid)&&!empty($id)){
			$is=$this->accommodation_model->check_quanxian($userid,$id);
			return $is;
		}
		return 0;
	}
	/**
	 * [delete_baoxiu 删除保修服务]
	 * @return [type] [description]
	 */
	function delete_baoxiu(){
		$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		$id=intval(trim($this->input->get('id')));
		//查看权限
		$is=$this->look_quanxian($userid,$id);
		if($is>0){
			$id_de=$this->accommodation_model->delete_repairs($id);
			if($id_de==true){
				ajaxReturn ( '', '', 1 );
			}
		}else{
			ajaxReturn('','',2);
		}
		ajaxReturn('','',0);
	}
    /**
     * 学生删除一条住宿的信息
     */
    function delete_acc(){
        $id=intval($this->input->get('id'));
        //缺少frebug攻击
        if(!empty($id)){
            $this->db->delete('accommodation_info','id = '.$id);
            ajaxReturn('','',1);
        }
    }
    /**
     * [look_cause 查看失败原因]
     * @return [type] [description]
     */
    function look_cause(){
    	$id=$this->input->get('id');
    	if(!empty($id)){
    		$info=$this->db->get_where('credentials','id = '.$id)->row_array();
    		$this->load->view ( '/student/c_cause',array(
			'info'=>!empty($info['cause'])?$info['cause']:''
			));	
    	}
    }
	
	/**
	*
	* 房间详情
	*/
	function deatil(){
		$id = intval($this->input->get('builiding'));
		if($id){
			//内容
			$data = $this->db->get_where('school_accommodation_prices_info','roomid = '.$id." AND site_language = '{$this->puri}'")->row();
			// 校区 楼的ID
			$c_b = $this->db->get_where('school_accommodation_prices','id = '.$id)->row();
			
			$b = $this->db->get_where('school_accommodation_buliding','id = '.$c_b->bulidingid)->row();
			$c = $this->db->get_where('school_accommodation_campus','id = '.$b->columnid)->row();
			
			$this->load->view ( '/student/acc_detail',array(
				'data'=>!empty($data)?$data:'',
				'c'=>!empty($c)?$c:'',
				'b'=>!empty($b)?$b:'',
				'c_b'=>!empty($c_b)?$c_b:'',
			));	
		}
		
		
	}
}