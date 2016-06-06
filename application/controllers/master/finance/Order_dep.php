<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 *
 * @author JJ
 *        
 */
class Order_dep extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/finance/';
		$this->load->model ( $this->view . 'order_model' );
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
			if (isset ( $_POST ['iDisplayStart'] ) && $_POST ['iDisplayLength'] != '-1') {
				$offset = intval ( $_POST ['iDisplayStart'] );
				$limit = intval ( $_POST ['iDisplayLength'] );
			}
				
			$where = 'ordertype = 5';
			if ($label_id == 1) {
				$where .= ' AND paystate = 1';
			} else {
				$where .= ' AND paystate != 1';
			}
				
			$like = array ();
				
			$sSearch = mysql_real_escape_string ( $this->input->post ( 'sSearch' ) );
			if (! empty ( $sSearch )) {
				$where .= "
				AND (
				id LIKE '%{$sSearch}%'
				OR
				ordernumber LIKE '%{$sSearch}%'
				OR
				ordermondey LIKE '%{$sSearch}%'
				OR
				paytype LIKE '%{$sSearch}%'
				OR
				FROM_UNIXTIME(`paytime`,'%Y-%m-%d') LIKE '%{$sSearch}%'
				OR paystate LIKE '%{$sSearch}%'
			
				)
				";
			}
				
			$sSearch_0 = mysql_real_escape_string ( $this->input->post ( 'sSearch_0' ) );
			if (! empty ( $sSearch_0 )) {
			$where .= "
					AND (
				id LIKE '%{$sSearch_0}%'
				OR
					ordernumber LIKE '%{$sSearch_0}%'
					OR
					ordermondey LIKE '%{$sSearch_0}%'
					OR
					paytype LIKE '%{$sSearch_0}%'
					OR
					FROM_UNIXTIME(`paytime`,'%Y-%m-%d') LIKE '%{$sSearch_0}%'
					OR paystate LIKE '%{$sSearch_0}%'
			
					)
					";
				}
					
				$sSearch_1 = mysql_real_escape_string ( $this->input->post ( 'sSearch_1' ) );
				if (! empty ( $sSearch_1 )) {
				$where .= "
				AND (
						paytype = '{$sSearch_1}'
				)
							";
			}
			$sSearch_2 = mysql_real_escape_string ( $this->input->post ( 'sSearch_2' ) );
			if (! empty ( $sSearch_2 )) {
			$where .= "
			AND (
					id LIKE '%{$sSearch_2}%'
					OR
					ordernumber LIKE '%{$sSearch_2}%'
					OR
			ordermondey LIKE '%{$sSearch_2}%'
			OR
			paytype LIKE '%{$sSearch_2}%'
			OR
			FROM_UNIXTIME(`paytime`,'%Y-%m-%d') LIKE '%{$sSearch_2}%'
			OR paystate LIKE '%{$sSearch_2}%'
				
			)
			";
			}
				
			$sSearch_3 = mysql_real_escape_string ( $this->input->post ( 'sSearch_3' ) );
			if (! empty ( $sSearch_3 )) {
			$where .= "
			AND (
			id LIKE '%{$sSearch_3}%'
			OR
			ordernumber LIKE '%{$sSearch_3}%'
			OR
			ordermondey LIKE '%{$sSearch_3}%'
				OR
					paytype LIKE '%{$sSearch_3}%'
					OR
			FROM_UNIXTIME(`paytime`,'%Y-%m-%d') LIKE '%{$sSearch_3}%'
			OR paystate LIKE '%{$sSearch_3}%'
				
			)
			";
			}
				
			$sSearch_4 = mysql_real_escape_string ( $this->input->post ( 'sSearch_4' ) );
			if (! empty ( $sSearch_4 )) {
			$where .= "
			AND (
			id LIKE '%{$sSearch_4}%'
			OR
			ordernumber LIKE '%{$sSearch_4}%'
			OR
			ordermondey LIKE '%{$sSearch_4}%'
			OR
			paytype LIKE '%{$sSearch_4}%'
			OR
			FROM_UNIXTIME(`paytime`,'%Y-%m-%d') LIKE '%{$sSearch_4}%'
			OR paystate LIKE '%{$sSearch_4}%'
				
			)
			";
			}
			// 输出
			$output ['sEcho'] = intval ( $_POST ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->order_model->count_apply ( $where );
			$output ['aaData'] = $this->order_model->get_apply ( $where, $limit, $offset, 'id DESC' );
				
			foreach ( $output ['aaData'] as $item ) {
				$item->userid = $this->order_model->get_username ( $item->userid, $item->ordertype );
				
				$item->paytype = $this->order_model->get_paytype ( $item->paytype );
				$item->ordermondey = $this->order_model->get_money ( $item->applyid, $item->ordertype );
				if (! empty ( $item->paytime )) {
					$item->paytime = date ( 'Y-m-d', $item->paytime );
				} else {
					$item->paytime = '';
				}
				$item->paystate = $this->order_model->get_paystate ( $item->paystate );
			}
			// var_dump($output);die;
			exit ( json_encode ( $output ) );
		}
		$this->_view ( 'order_index', array (
				'ordername' => '押金订单',
				'label_id' => $label_id,
				'control' => 'order_dep' 
		) );
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'userid',
				'ordernumber',
				'ordertype',
				'ordermondey',
				'paytype',
				'paytime',
				'paystate',
				'applyid' 
		);
	}
}