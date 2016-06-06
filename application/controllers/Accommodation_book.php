<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 申请表
 *
 * @author zyj
 *        
 */
class Accommodation_book extends Home_Basic {
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 主页
	 */
	function index() {
		// 价格id
		$priceid = intval ( trim ( $this->input->get ( 'priceid' ) ) );
		$userinfo = array ();
		$flag_accommodation = 0;
		$flag_apply = 0;
		$nationality = CF ( 'nationality', '', 'application/cache/' );
		if (! empty ( $_SESSION ['student'] ['userinfo'] )) {
			$userinfo = $_SESSION ['student'] ['userinfo'];
			// 查看是否有申请 并且是 被录取了 才能显示提交表单
			$apply_all = $this->db->select ( '*' )->limit ( 1 )->order_by ( 'applytime DESC' )->get_where ( 'apply_info', 'paystate = 1 AND state >= 7 AND userid = ' . $userinfo ['id'] )->result_array ();
			if (! empty ( $apply_all )) {
				$flag_accommodation = 1;
			}
			
			// 还得查看一下 如果已经申请了 就不能显示 表单 而是 跳到 给人中心的 住宿去
			$apply_acc = $this->db->select ( '*' )->limit ( 1 )->get_where ( 'accommodation_info', 'userid = ' . $userinfo ['id'] )->result_array ();
			if (! empty ( $apply_acc )) {
				$flag_apply = 1;
				$pay_success = '/' . $this->puri . '/student/student/accommodation';
				echo '<script>window.location.href="' . $pay_success . ' "</script>';
				die ();
			}
		}
		// 校区
		$camp = $this->_camp ();
		if (! empty ( $priceid )) {
			// 根据价格id 获取 信息 type campid buildingid registeration_fee
			$data_zyj = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id = ' . $priceid )->result_array ();
			if (! empty ( $data_zyj )) {
				$type = $data_zyj [0] ['id'];
				$campid = $data_zyj [0] ['columnid'];
				$buildingid = $data_zyj [0] ['bulidingid'];
				// 校区
				$zyj_camp = $this->db->select ( '*' )->get_where ( 'school_accommodation_campus', 'id = ' . $campid )->result_array ();
				$zyj_camp_name = $zyj_camp [0] ['name'];
				// 楼
				$zyj_build = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $buildingid )->result_array ();
				$zyj_build_name = $zyj_build [0] ['name'];
				// 房间类型
				$zyj_type = lang ( 'root_type_' . $data_zyj [0] ['campusid'] ) . ' RMB ' . $data_zyj [0] ['prices'];
				
				$zyj_room = $zyj_camp_name . '-' . $zyj_build_name . '-' . $zyj_type;
				
				$zyj_data ['type'] = $type;
				$zyj_data ['campid'] = $campid;
				$zyj_data ['buildingid'] = $buildingid;
				$zyj_data ['zyj_room'] = $zyj_room;
			}
		}
		
		// 房间类型
		// $roomtype = $this->_roomtype ();
		$this->load->view ( 'home/accommodation_book_index', array (
				'userinfo' => $userinfo,
				'nationality' => $nationality,
				'camp' => ! empty ( $camp ) ? $camp : '',
				'flag_accommodation' => $flag_accommodation,
				'flag_apply' => $flag_apply,
				'zyj_data' => ! empty ( $zyj_data ) ? $zyj_data : array () 
		) );
	}
	
	/**
	 * 校区
	 */
	function _camp() {
		$data = array ();
		$camp = $this->db->select ( '*' )->order_by ( 'orderby ASC' )->get_where ( 'school_accommodation_campus', 'id > 0 AND site_language = ' . $this->where_lang )->result_array ();
		if (! empty ( $camp )) {
			foreach ( $camp as $k => $v ) {
				$data [$v ['id']] = $v ['name'];
			}
		}
		return $data;
	}
	
	/**
	 * 弹出登录页面
	 */
	function login_accommodeation() {
		
		// 价格id
		$priceid = intval ( trim ( $this->input->get ( 'priceid' ) ) );
		// 未登录 直接弹出登录
		$html = $this->load->view ( 'home/login_accommodeation', array (
				'priceid' => ! empty ( $priceid ) ? $priceid : '' 
		), true );
		if (! empty ( $_SESSION ['student'] ['userinfo'] )) {
			$url = '/' . $this->puri . '/accommodation_book?priceid=' . $priceid;
			ajaxReturn ( $url, '', 2 );
		}
		ajaxReturn ( $html, '', 1 );
	}
	
	/**
	 * 选择住宿楼
	 */
	function select_building() {
		$campid = intval ( trim ( $this->input->get ( 'campid' ) ) );
		if ($campid) {
			$build = $this->_building ( $campid );
			if (! empty ( $build )) {
				$html = '<option value="">--Please Select--</option>';
				foreach ( $build as $k => $v ) {
					$html .= '<option value=' . $k . '>' . $v . '</option>';
				}
				ajaxReturn ( $html, '', 1 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 获取楼宇
	 */
	function _building($id) {
		$data = array ();
		$build = $this->db->select ( '*' )->order_by ( 'orderby ASC' )->get_where ( 'school_accommodation_buliding', 'id > 0 AND columnid  = ' . $id )->result_array ();
		if (! empty ( $build )) {
			foreach ( $build as $k => $v ) {
				$data [$v ['id']] = $v ['name'];
			}
		}
		return $data;
	}
	
	/**
	 * 选择住宿楼
	 */
	function select_room() {
		$buildingid = intval ( trim ( $this->input->get ( 'buildingid' ) ) );
		if ($buildingid) {
			$build = $this->_room ( $buildingid );
			if (! empty ( $build )) {
				$html = '<option value="">--Please Select--</option>';
				foreach ( $build as $k => $v ) {
					$html .= '<option value=' . $k . '>' . $v . '</option>';
				}
				ajaxReturn ( $html, '', 1 );
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 选择住宿楼
	 */
	function select_info() {
		$buildingid = intval ( trim ( $this->input->get ( 'buildingid' ) ) );
		$campid = intval ( trim ( $this->input->get ( 'campid' ) ) );
		$type = intval ( trim ( $this->input->get ( 'type' ) ) );
		if ($buildingid && $campid && $type) {
			$roomtype = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'bulidingid = ' . $buildingid . ' AND id = ' . $type )->row ();
			$data = ! empty ( $roomtype->remark ) ? $roomtype->remark : '';
			ajaxReturn ( $data, '', 1 );
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
	
	/**
	 * 获取楼宇
	 */
	function _room($id) {
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		$data = array ();
		$build = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id > 0 AND bulidingid  = ' . $id )->result_array ();
		
		if (! empty ( $build )) {
			foreach ( $build as $k => $v ) {
				$data [$v ['id']] = $publics ['room'] [$v ['campusid']] . ' RMB ' . $v ['prices'] . ' per person per day';
			}
		}
		return $data;
	}
	
	/**
	 * 保存
	 */
	function save() {
		$data = $this->input->post ();
		// 是否收费
		$flag_isshoufei = 0;
		// 是否 走 计算的方式
		$flag_jisuan = 0;
		// 钱
		$money = 0;
		// 单位
		$danwei = '';
		if (! empty ( $data )) {
			if (empty ( $data ['type'] )) {
				ajaxReturn ( '', '', 0 );
			}
			if (! empty ( $data ['accstarttime'] )) {
				$data ['accstarttime'] = strtotime ( $data ['accstarttime'] );
			}
			$data ['userid'] = $_SESSION ['student'] ['userinfo'] ['id'];
			$data ['email'] = $_SESSION ['student'] ['userinfo'] ['email'];
			$stay = CF ( 'stay', '', CONFIG_PATH );
			// 如果没有设置的 话 就不收费
			if (! empty ( $stay )) {
				// 首先确定收费
				if ($stay ['stay'] == 'yes') {
					$flag_isshoufei = 1;
					$flag_jisuan = 1;
				}
				if ($stay ['stay'] == 'yespledge') {
					$flag_isshoufei = 1;
					$money = $stay ['staymoney'];
					if ($stay ['stayway'] == 'stayrmb') {
						$danwei = 'RMB';
					}
					if ($stay ['stayway'] == 'stayusd') {
						$danwei = 'USD';
					}
				}
			}
			$apply_all = $this->db->select ( '*' )->limit ( 1 )->order_by ( 'applytime DESC' )->get_where ( 'apply_info', 'paystate = 1 AND state >= 7  AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
			// schooling xzunit
			$major = $this->db->select ( '*' )->get_where ( 'major', 'id =' . $apply_all [0] ['courseid'] )->row ();
			// 如果学制是 学期就按照 半年收费 否则是按照 一年收费
			
			if (! empty ( $major->xzunit ) && $major->xzunit == 3) {
				$acc_date = 120;
			} else {
				$acc_date = 240;
			}
			$acc_price = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id =' . $data ['type'] )->row ();
			if ($flag_isshoufei == 1 && $flag_jisuan == 1) {
				$money = $acc_date * $acc_price->prices;
				$danwei = 'RMB';
			}
			
			// 查看房间的数量
			if (! empty ( $data ['campid'] ) && ! empty ( $data ['buildingid'] ) && ! empty ( $data ['type'] )) {
				$room_count = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id > 0 AND bulidingid  = ' . $data ['buildingid'] . ' AND id = ' . $data ['type'] )->result_array ();
				if (! empty ( $room_count [0] )) {
					if (! empty ( $room_count [0] ['isroomset'] ) && $room_count [0] ['isroomset'] == 1) {
						if ($room_count [0] ['roomcount'] <= 0) {
							ajaxReturn ( '', '', 0 );
						}
					}
				}
			}
			
			if ($flag_isshoufei == 0) {
				$data ['subtime'] = time ();
				// 直接表单提交
				$this->db->insert ( 'accommodation_info', $data );
				$flag = $this->db->insert_id ();
				if ($flag) {
					if (! empty ( $room_count )) {
						$count = $room_count [0] ['roomcount'] - 1;
						$this->db->update ( 'school_accommodation_prices', array (
								'roomcount' => $count 
						), 'bulidingid = ' . $data ['buildingid'] . ' AND id = ' . $data ['type'] );
					}
					$urls = '/' . $this->puri . '/student/student/accommodation';
					ajaxReturn ( $urls, '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			} else {
				// 提交表单 跳到支付去
				$data ['registeration_fee'] = $money;
				$data ['danwei'] = $danwei;
				$data ['applytime'] = time ();
				$data ['subtime'] = time ();
				$data ['paystate'] = 0;
				$data ['paytime'] = 0;
				$data ['isproof'] = 0;
				$this->db->insert ( 'accommodation_info', $data );
				$acc_id = $this->db->insert_id ();
				if ($acc_id) {
					if (! empty ( $room_count )) {
						$count = $room_count [0] ['roomcount'] - 1;
						$this->db->update ( 'school_accommodation_prices', array (
								'roomcount' => $count
						), 'bulidingid = ' . $data ['buildingid'] . ' AND id = ' . $data ['type'] );
					}
					// $applyid = cucas_base64_encode ( $acc_id . '-4' );
					// $url = '/' . $this->puri . '/pay_pa/index?applyid=' . $applyid;
					// ajaxReturn ( $url, '', 1 );
					$urls = '/' . $this->puri . '/student/student/accommodation';
					ajaxReturn ( $urls, '', 1 );
				} else {
					ajaxReturn ( '', '', 0 );
				}
			}
		} else {
			ajaxReturn ( '', '', 0 );
		}
	}
}