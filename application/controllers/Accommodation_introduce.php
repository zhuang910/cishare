<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 申请表
 *
 * @author zyj
 *        
 */
class Accommodation_introduce extends Home_Basic {
	
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
		$camp = $this->_camp ();
		$this->load->view ( 'home/accommodation_introduce_index', array (
				'camp' => $camp 
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
				$datas [$v ['id']] ['name'] = ! empty ( $v ['name'] ) ? $v ['name'] : '';
				$datas [$v ['id']] ['info'] = ! empty ( $v ['info'] ) ? $v ['info'] : '';
				$ids [] = $v ['id'];
			}
			$where = 'columnid IN (' . implode ( ',', $ids ) . ')';
			$build = $this->db->select ( '*' )->order_by ( 'orderby ASC' )->get_where ( 'school_accommodation_buliding', $where )->result_array ();
			foreach ( $datas as $key => $val ) {
				foreach ( $build as $bk => $bv ) {
					if ($bv ['columnid'] == $key) {
						$data [$key] ['build'] [] = $bv;
						$data [$key] ['name'] = $val ['name'];
						$data [$key] ['info'] = $val ['info'];
					}
				}
			}
		}
		return $data;
	}
	
	/**
	 * 房间以及价格
	 */
	function building() {
		$publics = CF ( 'publics', '', CONFIG_PATH );
		$bulidingid = intval ( trim ( $this->input->get ( 'bulidingid' ) ) );
		if ($bulidingid) {
			$info = $this->db->select ( '*' )->get_where ( 'school_accommodation_buliding', 'id = ' . $bulidingid )->row ();
			$buildimg = $this->_buildingimg ( $bulidingid );
			$buildprice = $this->_buildprice ( $bulidingid );
			
		}
		
		$this->load->view ( 'home/accommodation_introduce_building', array (
				'info' => ! empty ( $info ) ? $info : array (),
				'buildimg' => ! empty ( $buildimg ) ? $buildimg : array (),
				'buildprice' => ! empty ( $buildprice ) ? $buildprice : array (),
				'type' => $publics ['room'] 
		) );
	}
	
	/**
	 * 获取房间类型
	 */
	function _buildingimg($id = null) {
		$data = array ();
		if ($id != null) {
			$data = $this->db->select ( '*' )->order_by ( 'orderby DESC' )->get_where ( 'school_accommodation_picture', 'bulidingid = ' . $id )->result_array ();
		}
		
		return $data;
	}
	
	/**
	 * 获取房间类型
	 */
	function _buildprice($id = null) {
		$data = array ();
		if ($id != null) {
			$data = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'bulidingid = ' . $id )->result_array ();
		}
		return $data;
	}
	
	/**
	 * 获取楼宇
	 */
	function _building($id) {
		$data = array ();
		$build = $this->db->select ( '*' )->order_by ( 'orderby ASC' )->get_where ( 'school_accommodation_buliding', 'id > 0 ' )->result_array ();
		var_dump ( $build );
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
	 * 获取楼宇
	 */
	function _room($id) {
		$publics = CF ( 'publics', '', CONFIG_PATH );
		
		$data = array ();
		$build = $this->db->select ( '*' )->get_where ( 'school_accommodation_prices', 'id > 0 AND bulidingid  = ' . $id )->result_array ();
		
		if (! empty ( $build )) {
			foreach ( $build as $k => $v ) {
				$data [$v ['id']] = $publics ['room'] [$v ['campusid']];
			}
		}
		
		return $data;
	}
	
	/**
	 * 保存
	 */
	function save() {
		$data = $this->input->post ();
		var_dump ( $data );
	}
}