<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 名师团队管理
 *
 * @author zyj
 *        
 */
class Schoollife_Model extends CI_Model {
	const T_ARTICLE = 'article_info'; // 事件活动
	const T_S = 'students_dynamic'; // 学员风采 精彩分享
	const T_SF = 'school_facilities'; // 校园设施
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	
	/**
	 * 获取一条
	 *
	 * @param number $id        	
	 */
	function get_one($where = null) {
		if ($where != null) {
			$base = array ();
			$base = $this->db->where ( $where )->limit ( 1 )->get ( self::T_ARTICLE )->row ();
			if ($base) {
				return $base;
			}
			return array ();
		}
	}
	
	/**
	 * 统计数量(总页数、总条数)
	 * @table 表名
	 * @where 条件
	 * @size 查询多少条
	 */
	function counts($where, $size = 9) {
		$count = $this->db->get_where ( self::T_ARTICLE, $where )->num_rows ();
		$data = array (
				'allcount' => $count,
				'pagecount' => ceil ( $count / $size ) 
		);
		return $data;
	}
	
	/**
	 * 得到多条信息 默认降序
	 * @table 表名
	 * @where 条件
	 * @select 查询字段
	 * @offset 从第几条开始查询
	 * @size 查询多少条
	 * @orderby 排序
	 */
	function getall($where = 'id > 0', $select = '*', $offset = '0', $size = '9', $orderby = 'createtime DESC') {
		$res = array ();
		$query = $this->db->select ( $select )->order_by ( $orderby )->get_where ( self::T_ARTICLE, $where, $size, $offset );
		if ($query->num_rows () > 0) {
			$res = $query->result_array ();
		}
		return $res;
	}
}