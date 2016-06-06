<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 *
 * @author zyj
 *
 */
class Course_Model extends CI_Model
{
    const T_ARTICLE = 'major';
    const T_C = 'major_content';
    const T_I = 'course_images';
    const T_APP = 'apply_info';

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 查询数据是否存在
     */
    function get_info_one($where = null)
    {
        if ($where != null) {
            return $this->db->where($where)->get(self::T_ARTICLE)->result_array();
        }
    }

    /**
     * 统计条数
     *
     * @param array $field
     * @param array $condition
     */
    function count($condition, $programaids = null)
    {
        if (is_array($condition) && !empty ($condition)) {
            if (!empty ($condition ['where'])) {
                $this->db->where($condition ['where']);
            }
            if ($programaids !== null) {
                $this->db->where('columnid in(' . $programaids . ')');
            }
            return $this->db->from(self::T_ARTICLE)->count_all_results();
        }
        return 0;
    }

    /**
     * 获取一条
     *
     * @param number $id
     */
    function get_one($where = null)
    {
        if ($where != null) {
            $base = array();
            $base = $this->db->where($where)->limit(1)->get(self::T_ARTICLE)->result_array();
            if ($base) {
                return $base [0];
            }
            return array();
        }
    }

    /**
     * 获取多条数据
     *
     * @param number $id
     */
    function get_course($where = null, $limit = null)
    {
        if ($where != null) {
            $base = array();
            if ($limit != null) {
                $base = $this->db->where($where)->order_by('orderby DESC, englishname ASC')->limit($limit)->get(self::T_ARTICLE)->result_array();
            } else {
                $base = $this->db->where($where)->order_by('orderby DESC, englishname ASC')->get(self::T_ARTICLE)->result_array();
            }

            if ($base) {
                foreach ($base as $k => $v) {
                    $ids [] = $v ['id'];
                }
                $where_img = 'majorid IN (' . implode(',', $ids) . ')';
                $img = $this->db->where($where_img)->order_by('orderby DESC')->group_by('majorid')->get('major_images')->result_array();
                if (!empty ($img)) {
                    foreach ($img as $val) {
                        $imgs [$val ['majorid']] = $val ['image'];
                    }
                }

                foreach ($base as $key => $value) {
                    if (!empty ($imgs [$value ['id']])) {
                        $base [$key] ['img'] = $imgs [$value ['id']];
                    }
                }
                return $base;
            }
            return array();
        }
    }

    /**
     * 获取多条数据
     *
     * @param number $id
     */
    function get_courses($where = null, $limit = null)
    {
        if ($where != null) {
            $base = array();
            if ($limit != null) {
                $base = $this->db->where($where)->limit($limit)->get(self::T_C)->result_array();
            } else {
                $base = $this->db->where($where)->get(self::T_C)->result_array();
            }

            if ($base) {
                return $base;
            }
            return array();
        }
    }

    /**
     * 获取一条
     *
     * @param number $id
     */
    function get_one_content($where = null)
    {
        if ($where != null) {
            $base = array();
            $base = $this->db->where($where)->limit(1)->get(self::T_C)->row();
            if ($base) {
                return $base;
            }
            return array();
        }
    }

    /**
     * 获取图片
     */
    function get_images($where = null)
    {
        if ($where != null) {
            $img = $this->db->select('*')->where($where)->order_by('orderby DESC,id DESC')->get(self::T_I)->result_array();
            if ($img) {
                return $img;
            } else {
                return false;
            }
        }
    }

    /**
     * 获取数据
     */
    function get_course_base($where = null, $limit = 0, $offset = 0, $orderby = 'language DESC')
    {
        $data = array();

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if ($orderby !== null) {
            $this->db->order_by('language DESC');
        }

        if ($where != null) {
            $data = $this->db->select('*')->get_where(self::T_ARTICLE, $where)->result_array();
        }
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
    function getall($where = 'id > 0', $select = '*', $offset = '0', $size = '10', $orderby = 'id DESC')
    {
        $res = array();
        $query = $this->db->select($select)->order_by($orderby)->get_where(self::T_ARTICLE, $where, $size, $offset);
        if ($query->num_rows() > 0) {
            $res = $query->result_array();
        }
        return $res;
    }

    /**
     * 保存基本信息
     *
     * @param number $id
     * @param array $data
     */
    function save($id = null, $data = array())
    {
        if (!empty ($data)) {
            if ($id == null) {
                $this->db->insert(self::T_ARTICLE, $data);
                return $this->db->insert_id();
            } else {
                $this->db->update(self::T_ARTICLE, $data, 'id = ' . $id);
            }
        }
    }

    /**
     * 删除
     */
    function delete($where = null)
    {
        if ($where != null) {
            $this->db->delete(self::T_ARTICLE, $where);
            return true;
        }
        return false;
    }

    /**
     * 获取学生拒绝后的专业
     */
    function get_ont_course($userid)
    {
        if (!empty($userid)) {
            $this->db->select('courseid');
            $this->db->where('userid', $userid);
            $this->db->where('state', 4);
            return $this->db->get(self::T_APP)->result_array();
        }
        return array();
    }
}