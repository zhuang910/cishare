<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 用户管理
 *
 * @author junjiezhang
 *        
 */
class Commission_Model extends CI_Model {
	const T_APP='apply_info';
	const T_STU_INFO='student_info';
	const T_MAJOR='major';
	const T_DEGREE='degree_info';
	const T_MAJOR_T='major_tuition';
	const T_T_R='commission_record';
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * 获取列表数据
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function get($field, $condition,$agency_id,$label_id) {
		if($label_id==0){
				$ids=$this->get_apply_ids();
			}
		if (is_array ( $field ) && ! empty ( $field )) {
			
			$this->db->select ('apply_info.id,apply_info.number,apply_info.courseid as majorid,student_info.chname,student_info.enname,student_info.nationality,student_info.passport,major.degree,major.language,apply_info.commission,');
			
			if (is_array ( $condition ) && ! empty ( $condition )) {
				if (! empty ( $condition ['where'] )) {
					
					$this->db->where ( $condition ['where'] );
				}
				
				if (! empty ( $condition ['orderby'] )) {
					$this->db->order_by ( $condition ['orderby'] );
				}
				
				$this->db->limit ( $condition ['limit'], $condition ["offset"] );
			}
			if($agency_id!=0){
				$this->db->where('apply_info.agency_id',$agency_id);
			}
			$this->db->join ( self::T_MAJOR, self::T_APP . '.courseid=' . self::T_MAJOR . '.id' );
			$this->db->join ( self::T_STU_INFO, self::T_APP . '.userid=' . self::T_STU_INFO . '.id' );
			if($label_id==1){
				$this->db->join ( self::T_T_R, self::T_APP . '.id=' . self::T_T_R . '.applyid' );
			}

			$this->db->where('apply_info.is_agency',1);
            if($label_id==0&&!empty($ids)){
                $this->db->where('apply_info.id NOT IN ('.$ids.')');
            }
			return $this->db->get ( self::T_APP )->result ();
		}
		return array ();
	}
	
	/**
	 * 统计条数
	 *
	 * @param array $field        	
	 * @param array $condition        	
	 */
	function count($condition,$agency_id,$label_id) {
		if($label_id==0){
				$ids=$this->get_apply_ids();
			}
		if (is_array ( $condition ) && ! empty ( $condition )) {
			if (! empty ( $condition ['where'] )) {
				$this->db->where ( $condition ['where'] );
			}
			if($agency_id!=0){
				$this->db->where('apply_info.agency_id',$agency_id);
			}
			if($label_id==1){
				$this->db->join ( self::T_T_R, self::T_APP . '.id=' . self::T_T_R . '.applyid' );
			}

			$this->db->join ( self::T_MAJOR, self::T_APP . '.courseid=' . self::T_MAJOR . '.id' );
			$this->db->join ( self::T_STU_INFO, self::T_APP . '.userid=' . self::T_STU_INFO . '.id' );
            if($label_id==0&&!empty($ids)){
                $this->db->where('apply_info.id NOT IN ('.$ids.')');
            }
			return $this->db->from ( self::T_APP )->count_all_results ();
		}
		return 0;
	}
	/**
	 * [get_apply_ids 获取已经交费申请]
	 * @return [type] [description]
	 */
	function get_apply_ids(){
		$this->db->select('applyid');
		$data=$this->db->get(self::T_T_R)->result_array();
		$str='';
		if(!empty($data)){
			foreach ($data as $k => $v) {
				$str.="'".$v['applyid']."',";
			}
		}
		return trim($str,',');
	}
	/**
	 * [get_student_degree 获取学生所选专业的学历类型]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	function get_student_degree($id){
		if(!empty($id)){
			$this->db->select('title');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_DEGREE)->row_array();
			if(!empty($data)){
				return $data['title'];
			}
		}
		return '';
	}
	/**
	 * [get_student_tuition 获取专业的学费默认是第一学期]
	 * @param  [type] $mid [专业id]
	 * @return [type]      [学费]
	 */
	function get_student_tuition($mid){
		if(!empty($mid)){
			$this->db->where('majorid',$mid);
			$this->db->order_by('term asc');
			$data=$this->db->get(self::T_MAJOR_T)->row_array();
			if(!empty($data['tuition'])){
				return $data['tuition'];
			}
		}
		return '';
	}
	function edit_apply_commission($data){
		if(!empty($data)){
			$arr['commission']=$data['value'];
			$this->db->update ( self::T_APP, $arr, 'id = ' . $data['pk']);
		}
	}
	/**
	 * [get_tuition_state 获取交佣金状态]
	 * @param  [type] $aid [description]
	 * @return [type]      [description]
	 */
	function get_tuition_state($aid){
		if(!empty($aid)){
			$this->db->select('count(*) as num');
			$this->db->where('applyid',$aid);
			$data=$this->db->get(self::T_T_R)->row_array();
			return $data['num'];
		}
	}
	/**
	 * [insert_commission_record 插入记录]
	 * @param  [type] $aid [description]
	 * @return [type]      [description]
	 */
	function insert_commission_record($aid,$yongjin){
		if(!empty($aid)){
			$data['commission']=$yongjin;
			$data['applyid']=$aid;
			$data['createtime']=time();
			$data['adminid']=$_SESSION['master_user_info']->id;
			$this->db->insert ( self::T_T_R, $data );
			return $this->db->insert_id ();
		}
	}
	/**
	 * [delete 删除缴费记录]
	 * @return [type] [description]
	 */
	function delete($aid){
		if(!empty($aid)){
			
			$this->db->delete( self::T_T_R, 'applyid='.$aid );
			return 1;
		}
		return 0;
	}
	/**
	 * [update_beizhu 更新备注]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function update_beizhu($data){
		if(!empty($data)){
			$aid=$data['aid'];
			unset($data['aid']);
			$this->db->update ( self::T_T_R, $data, 'applyid='.$aid);
		}
	}
	function get_commission_info($aid){
		if(!empty($aid)){
			$this->db->select('remark');
			$this->db->where('applyid',$aid);
			$data=$this->db->get(self::T_T_R)->row_array();
			return $data['remark'];
		}
		return '';
	}
	/**
	 * [get_apply_yongjin 获取该条数据的佣金]
	 * @return [type] [description]
	 */
	function get_apply_yongjin($aid){
		if(!empty($aid)){
			$this->db->select('commission');
			$this->db->where('id',$aid);
			$data=$this->db->get(self::T_APP)->row_array();
			return $data['commission'];
		}
		return '';
	}
}