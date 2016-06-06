<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
/**
 * 权限管理 教师管理
 *
 * @author zyj
 *        
 */
class Vacant_room_Model extends CI_Model {
	const T_ARTICLE = 'accommodation_info';
	const T_C = 'credentials';
	const T_CAMPUS = 'school_accommodation_campus'; // 院校校区表
	const T_BULIDING = 'school_accommodation_buliding'; // 院校校区内容表
	const T_PRICES = 'school_accommodation_prices'; // 院校校区宿舍价格表
	const T_PICTURES = 'school_accommodation_picture'; // 院校校区宿舍图片表
	const T_USER_ROOM='user_room';
	const T_STUDENT_INFO='student_info';

	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
	}
	/**
	 * [get_campus_info 获取校区
	 * @param  [type] $site_language [站点语言]
	 * @return [type]                [array]
	 */
	function get_campus_info(){
		
			$data=$this->db->get(self::T_CAMPUS)->result_array();
			return $data;
		return array();
	}
	/**
	 * [get_buliding_info 获取该校区下的楼房]
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	function get_buliding_info($cid){
		if(!empty($cid)){
			$this->db->where('columnid',$cid);
			$data=$this->db->get(self::T_BULIDING)->result_array();
			return $data;
		}
		return array();
	}
	/**
	 * [get_buliding_info 获取该校区下的楼房]
	 * @param  [type] $cid [description]
	 * @return [type]      [description]
	 */
	function get_buliding_floor($bid){
		if(!empty($bid)){
			$this->db->select('floor_num');
			$this->db->where('id',$bid);
			$data=$this->db->get(self::T_BULIDING)->row_array();
			if(!empty($data['floor_num'])){
				return $data['floor_num'];
			}
		}
		return 0;
	}
	/**
	 * [get_room_info 获取该楼的房间信息]
	 * @return [type] [description]
	 */
	function get_room_info($arr){
		if(!empty($arr)){
			$data=array();
			//获取该楼的信息
			$floor_num=$this->get_buliding_floor($arr['bulidingid']);
			if(!empty($floor_num)){
				for($i=1;$i<=$floor_num;$i++){
					$room_info=$this->get_where_room($arr['bulidingid'],$i);
					if(!empty($room_info)){
						foreach ($room_info as $k => $v) {
							$data[$i]['room_num']=count($room_info);
							if(empty($data[$i]['in_user_num'])){
								$data[$i]['in_user_num']=0;
							}
							if(empty($data[$i]['ensure_user_num'])){
								$data[$i]['ensure_user_num']=0;
							}
							if(empty($data[$i]['vacant_num'])){
								$data[$i]['vacant_num']=0;
							}
							if(empty($data[$i]['bed_num'])){
								$data[$i]['bed_num']=0;
							}
							$data[$i]['in_user_num']+=$v['in_user_num'];
							$data[$i]['ensure_user_num']+=$v['ensure_user_num'];
							$data[$i]['vacant_num']+=$v['maxuser']-$v['in_user_num'];
							$data[$i]['bed_num']+=$v['maxuser'];

						}
					}

				}
			}
			return $data;
		}
	}
	/**
	 * [get_where_room 获取该校区该楼层的房间]
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	function get_where_room($bid,$floor){
		if(!empty($bid)&&!empty($floor)){
			$this->db->where('bulidingid',$bid);
			$this->db->where('floor',$floor);
			return $this->db->get(self::T_PRICES)->result_array();
		}
		return array();
	}
	/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_campus_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_CAMPUS)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
	/**
	 * [get_campus_name 获取校区的名字]
	 * @return [type] [description]
	 */
	function get_buliding_name($id){
		if(!empty($id)){
			$this->db->select('name');
			$this->db->where('id',$id);
			$data=$this->db->get(self::T_BULIDING)->row_array();
			if(!empty($data['name'])){
				return $data['name'];
			}
		}
		return '';
	}
}