<?php

/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-8-21
 * Time: 下午5:34
 */
class push_mail_model extends CI_Model
{
	const T_PUSH_MAIL_CONF = 'push_mail_config';

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 统计发件配置
	 *
	 * @param null $where 条件
	 */
	function count_config($where = null)
	{
		if ($where !== null) {
			$this->db->where($where);
		}
		return $this->db->from(self::T_PUSH_MAIL_CONF)->count_all_results();
	}

	/**
	 * 获取发件配置
	 *
	 * @param null $where   条件
	 * @param int  $limit   条数
	 * @param int  $offset  偏移量
	 * @param null $orderby 排序
	 */
	function get_config($where = null, $limit = 0, $offset = 0, $orderby = null)
	{
		if ($where !== null) {
			$this->db->where($where);
		}
		if ($limit !== 0 && $offset > 0) {
			$this->db->limit($limit, $offset);
		}
		if ($orderby !== null) {
			$this->db->order_by($orderby);
		}
		return $this->db->from(self::T_PUSH_MAIL_CONF)->get()->result();
	}

	/**
	 * 获取配置一条 根据ID
	 *
	 * @param int $id
	 */
	function get_config_one($id = 0)
	{
		if ($id !== 0) {
			return $this->db->get_where(self::T_PUSH_MAIL_CONF, 'id = ' . $id, 1, 0)->row();
		}
	}

	/**
	 * 保存配置
	 *
	 * @param array $data 数据
	 * @param int   $id   唯一ID
	 *
	 * @return mixed
	 */
	function save_config($data = array(), $id = 0)
	{
		if (!empty($data) && is_array($data)) {
			if ($id != 0) {
				return $this->db->update(self::T_PUSH_MAIL_CONF, $data, 'id = ' . $id);
			} else {
				return $this->db->insert(self::T_PUSH_MAIL_CONF, $data);
			}
		}
	}

	/**
	 * 删除
	 *
	 * @param int $id 唯一ID
	 *
	 * @return mixed
	 */
	function delete_config($id = 0)
	{
		if ($id !== 0) {
			return $this->db->delete(self::T_PUSH_MAIL_CONF, 'id = ' . $id);
		}
	}
} 