<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Retreat_fee_Model extends CI_Model {
	const T_MAJOR='major';
	const T_STUDENT='student';
	const T_MAJOR_TUITION='major_tuition';
	const T_ACC='accommodation_info';
	const T_TUITION_INFO='tuition_info';//学费信息表
	const T_ELECTRIC_INFO='electric_info';
	const T_QUA_INFO='quarterage_info';
	const T_ACC_PLE_INFO='acc_pledge_info';
	const T_APP='apply_info';
	const T_SCH_INFO = 'scholarship_info';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_STUDENT_REBUILD='student_rebuild';//重修费用表
	const T_STU_BAR_CARD='student_barter_card';//换证费表
	const T_TUITION_DETAIL='tuition_info_detail';//学费详情表
	const T_CRE='credentials';//凭据表
	const T_MAJOR_COURSE='major_course';
	const T_COURSE_BOOKS='course_books';
	const T_BOOKS='books';
	const T_BOOKS_FEE='books_fee';//书费表
	const T_ORDERBY='apply_order_info';//所有订单表
	const T_SQUAD='squad';//班级表
	const T_RETREAT_FEE='retreat_fee';//学生退费表




	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * [retreat_tuition 获取该学生剩余的学费]
	 * @param  [type] $userid [description]
	 * @return [type]         [description]
	 */
	function get_user_retreat_tuition($userid){
		if(!empty($userid)){
			//获取该学生的信息
			$user_info=$this->get_student_info($userid);
			//获取该学生的当前学期
			if(!empty($user_info['squadid'])){
				$squad_info=$this->get_squad_info($user_info['squadid']);
			}

			//获取该学期交的学费
			$tuition=$this->get_term_tuition($squad_info['nowterm']);
			$now_tuition=$tuition['tuition'];

			//如果有有换证费和重修费就减去
			if(!empty($tuition['tuition'])){
				//获取该学费的不缴费用和重修费用
				$now_tuition=$this->get_tuition($tuition);
			}

			//获取该专业的学期跨度
			$major_info=$this->get_major_info($user_info['major']);
			//学期跨度
			
			//已经上的天数
			$day=time()-$squad_info['classtime'];
			$day=ceil($day/24/3600);
			$avg_tuition=$now_tuition/$major_info['termdays'];
			$avg_tuition=floor($avg_tuition);
			$retreat_tuition=$now_tuition-$avg_tuition*$day;

			return $retreat_tuition;
		}
	}
	/**
	 * [get_student_info 获取该学生的在学信息]
	 * @return [type] [description]
	 */
	function get_student_info($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			return $this->db->get(self::T_STUDENT)->row_array();
		}
	}
	/**
	 * [get_squad_info 获取该班级的信息]
	 * @return [type] [description]
	 */
	function get_squad_info($squadid){
		if(!empty($squadid)){
			$this->db->where('id',$squadid);
			return $this->db->get(self::T_SQUAD)->row_array();
		}
	}
	/**
	 * [get_term_tuition 获取当前所交的学费]
	 * @return [type] [description]
	 */
	function get_term_tuition($term){
		if(!empty($term)){
			$this->db->where('nowterm',$term);
			return $this->db->get(self::T_TUITION_INFO)->row_array();
		}
	}
	/**
	 * [get_major_info 获取该学生专业的信息]
	 * @return [type] [description]
	 */
	function get_major_info($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			return $this->db->get(self::T_MAJOR)->row_array();
		}
	}
	/**
	 * [get_tuition 获取减去补缴后的费用]
	 * @return [type] [description]
	 */
	function get_tuition($arr){
		if(!empty($arr)){
			$this->db->where('tuitionid',$arr['id']);
			$data=$this->db->get(self::T_TUITION_DETAIL)->row_array();

			if(!empty($data['rebuild_money'])){
				$arr['tuition']=$arr['tuition']-$data['rebuild_money'];
			}
			if(!empty($data['barter_card_money'])){
				$arr['tuition']=$arr['tuition']-$data['barter_card_money'];
			}
			return $arr['tuition'];
		}
	}
	/**
	 * [get_user_retreat_acc 获取剩余的住宿费z]
	 * @return [type] [description]
	 */
	function get_user_retreat_acc($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			$data=$this->db->get(self::T_ACC)->row_array();
			if(!empty($data)){
				//获取房间的价格
				$room_price=$this->get_room_price($data['roomid']);
				$retreat_acc=$room_price*$data['residue_days'];
				return $retreat_acc;
			}
		}
		return 0;
	}
	/**
	 * [get_room_price 获取房间的价格
	 * @return [type] [description]
	 */
	function get_room_price($id){
		if(!empty($id)){
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_PRICES)->row_array();
		}
		if(!empty($data['prices'])){
			return $data['prices'];
		}
		return 0;
	}
	/**
	 * [get_user_retreat_acc_pledge 获取退费的住宿押金]
	 * @return [type] [description]
	 */
	function get_user_retreat_acc_pledge($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			$data=$this->db->get(self::T_ACC)->row_array();
			if(!empty($data)){
				return $data['acc_pledge_money'];
			}
		}
		return 0;
	}
	/**
	 * [get_user_retreat_acc_pledge 获取退费的电费]
	 * @return [type] [description]
	 */
	function get_user_retreat_electric($userid){
		if(!empty($userid)){
			$this->db->where('userid',$userid);
			$this->db->where('acc_state',6);
			$data=$this->db->get(self::T_ACC)->row_array();
			if(!empty($data)){
				return $data['electric_money'];
			}
		}
		return 0;
	}
	/**
	 * [retreat_student 学生退费]
	 * @return [type] [description]
	 */
	function retreat_student($data){
		//保存退费表	
		if(!empty($data)){
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert(self::T_RETREAT_FEE,$data);
			$id=$this->db->insert_id();
		}
		//设置该学生已经为校外住宿
		if(!empty($id)){
			$arr['acc_state']=7;
			$this->db->update(self::T_ACC,$arr,'userid = '.$data['userid'].' AND acc_state=6');
		}
		//设置学生为已经毕业或者离开
		if(!empty($id)){
			$arr['state']=3;
			$this->db->update(self::T_STUDENT,$arr,'userid = '.$data['userid']);
		}
		return $id;

	}
}