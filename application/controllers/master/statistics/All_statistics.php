<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class All_statistics extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/statistics/';
		$this->load->model($this->view.'apply_statistics_model');
	}
	function _get_tou($type){
		//获取学生的类别
		$changdegree_info=$this->db->get_where('degree_info','state = 1 AND isdegree = 1')->result_array();
		$duandegree_info=$this->db->get_where('degree_info','state = 1 AND isdegree = 0')->result_array();
		//组合头部
		$tou_info=array(
				'id'=>'序号',
				'name'=>$type,
			);
		if(!empty($changdegree_info)){
			$chang_id='';
			foreach ($changdegree_info as $k => $v) {
				$tou_info['degree'.$v['id']]=$v['title'];
				$chang_id.=$v['id'].',';
			}
			$tou_info[$chang_id.'chang']='长期生小计';
		}
		if(!empty($duandegree_info)){
			$duan_id='';
			foreach ($duandegree_info as $k => $v) {
				$tou_info['degree'.$v['id']]=$v['title'];
				$duan_id.=$v['id'].',';
			}
			$tou_info[$duan_id.'duan']='短期生小计';
		}
		$tou_info['total']='合计';
		return $tou_info;
	}
	/**
	 * 后台主页
	 */
	function nationality() {
		$tou_info=$this->_get_tou('国别');
		//开始组合申请信息
		//获取大洲的配置文件
		$continents=CF('continents','',CACHE_PATH);
		$html='';
		if(!empty($continents)){
			foreach ($continents as $k => $v) {
				if($v['zhou']==true){
					$html.=$this->_get_one($v['ids'],$tou_info);
					
					$html.='<tr>';
					//大洲的合计
					$duan=0;
					$chang=0;
					foreach ($tou_info as $key => $value) {
						if($key=='id'){
							$html.='<td colspan="2" style="color:red">'.$v['name'].'</td>';
						}elseif($key=='name'){
							continue;
						}elseif($key=='total'){
							$total=$chang+$duan;
							$html.='<td>'.$total.'</td>';
						}elseif(strstr($key,'duan')){
							$duan=$this->_get_duan($v['ids'],$key);
							$html.='<td>'.$duan.'</td>';
						}elseif(strstr($key,'chang')){
							$chang=$this->_get_chang($v['ids'],$key);
							$html.='<td>'.$chang.'</td>';	
						}else{
							$num=$this->_get_guo($v['ids'],$key);
							$html.='<td>'.$num.'</td>';
						}
					}		
					$html.='</tr>';
				}
				
			}
		}
		// var_dump($app_info);exit;
		$this->_view ('nationality_index',array(
			'tou_info'=>$tou_info,
			'html'=>$html
			));
	}
	/**
	 * [_get_na_num ]
	 * @return [type] [description]
	 */
	function _get_guo($guojia,$degreestr){
		if(!empty($guojia)&&!empty($degreestr)){
			//组合条件  该学历下的专业 该国籍下的学生 录取的申请
			$degree_arr=explode('degree', $degreestr);
			$major_info=$this->db->get_where('major','degree = '.$degree_arr[1])->result_array();
			$major_ids=array();
			if(!empty($major_info)){
				foreach ($major_info as $key => $value) {
					$major_ids[]=$value['id'];
				}
			}
			//获取用户的id
			$user_info=$this->db->get_where('student_info','nationality IN ( '.$guojia.')')->result_array();
			$user_ids=array();
			if(!empty($user_info)){
				foreach ($user_info as $key => $value) {
					$user_ids[]=$value['id'];
				}
			}
			if(!empty($major_ids)&&!empty($user_ids)){
				$apply_info=$this->_get_user_apply($major_ids,$user_ids);
				return count($apply_info);
			}
		}
		return 0;
	}
	/**
	 * [_get_duan 获取]
	 * @return [type] [description]
	 */
	function _get_chang($ids,$key){
		$where=str_replace(",chang","",$key);
		if(!empty($ids)&&!empty($where)){
			//组合条件  该学历下的专业 该国籍下的学生 录取的申请
			$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
			$major_ids=array();
			if(!empty($major_info)){
				foreach ($major_info as $key => $value) {
					$major_ids[]=$value['id'];
				}
			}
			//获取用户的id
			$user_info=$this->db->get_where('student_info','nationality IN ( '.$ids.')')->result_array();
			$user_ids=array();
			if(!empty($user_info)){
				foreach ($user_info as $key => $value) {
					$user_ids[]=$value['id'];
				}
			}
			if(!empty($major_ids)&&!empty($user_ids)){
				$apply_info=$this->_get_user_apply($major_ids,$user_ids);
				return count($apply_info);
			}
		}
		return 0;
	}
	/**
	 * [_get_duan 获取]
	 * @return [type] [description]
	 */
	function _get_duan($ids,$key){
		$where=str_replace(",duan","",$key);
		if(!empty($ids)&&!empty($where)){
			//组合条件  该学历下的专业 该国籍下的学生 录取的申请
			$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
			$major_ids=array();
			if(!empty($major_info)){
				foreach ($major_info as $key => $value) {
					$major_ids[]=$value['id'];
				}
			}
			//获取用户的id
			$user_info=$this->db->get_where('student_info','nationality IN ( '.$ids.')')->result_array();
			$user_ids=array();
			if(!empty($user_info)){
				foreach ($user_info as $key => $value) {
					$user_ids[]=$value['id'];
				}
			}
			if(!empty($major_ids)&&!empty($user_ids)){
				$apply_info=$this->_get_user_apply($major_ids,$user_ids);
				return count($apply_info);
			}
		}
		return 0;
	}
	/**
	 * [_get_one ]
	 * @return [type] [description]
	 */
	function _get_one($ids,$tou_info){
		$public=CF('public','',CACHE_PATH);
		$na_arra=explode(',', $ids);
		$html='';
		foreach ($na_arra as $k => $v) {
			$html.='<tr>';
			$duan=0;
			$chang=0;
			foreach ($tou_info as $key => $value) {
				if($key=='id'){
						$html.='<td>'.$v.'</td>';
					}elseif($key=='name'){
						$html.='<td>'.$public['global_country_cn'][$v].'</td>';
					}elseif($key=='total'){
						$total=$chang+$duan;
						$html.='<td>'.$total.'</td>';
					}elseif(strstr($key,'duan')){
						$duan=$this->_duan($v,$key);
						$html.='<td>'.$duan.'</td>';
					}elseif(strstr($key,'chang')){
						$chang=$this->_chang($v,$key);
						$html.='<td>'.$chang.'</td>';	
					}else{
						$num=$this->_get_na_num($v,$key);
						$html.='<td>'.$num.'</td>';
					}
			}
			$html.='</tr>';
		}
		 return $html;
	}
	/**
	 * [_duan 获取duanqi]
	 * @param  [type] $guojia    [description]
	 * @param  [type] $degreestr [description]
	 * @return [type]            [description]
	 */
	function _chang($guojia,$degreestr){
		$where=str_replace(",chang","",$degreestr);
		if(!empty($guojia)&&!empty($degreestr)){
			//组合条件  该学历下的专业 该国籍下的学生 录取的申请
			$degree_arr=explode('degree', $degreestr);
			$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
			$major_ids=array();
			if(!empty($major_info)){
				foreach ($major_info as $key => $value) {
					$major_ids[]=$value['id'];
				}
			}
			//获取用户的id
			$user_info=$this->db->get_where('student_info','nationality = '.$guojia)->result_array();
			$user_ids=array();
			if(!empty($user_info)){
				foreach ($user_info as $key => $value) {
					$user_ids[]=$value['id'];
				}
			}
			if(!empty($major_ids)&&!empty($user_ids)){
				$apply_info=$this->_get_user_apply($major_ids,$user_ids);
				return count($apply_info);
			}
		}
		return 0;
	}
	/**
	 * [_duan 获取duanqi]
	 * @param  [type] $guojia    [description]
	 * @param  [type] $degreestr [description]
	 * @return [type]            [description]
	 */
	function _duan($guojia,$degreestr){
		$where=str_replace(",duan","",$degreestr);
		if(!empty($guojia)&&!empty($degreestr)){
			//组合条件  该学历下的专业 该国籍下的学生 录取的申请
			$degree_arr=explode('degree', $degreestr);
			$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
			$major_ids=array();
			if(!empty($major_info)){
				foreach ($major_info as $key => $value) {
					$major_ids[]=$value['id'];
				}
			}
			//获取用户的id
			$user_info=$this->db->get_where('student_info','nationality = '.$guojia)->result_array();
			$user_ids=array();
			if(!empty($user_info)){
				foreach ($user_info as $key => $value) {
					$user_ids[]=$value['id'];
				}
			}
			if(!empty($major_ids)&&!empty($user_ids)){
				$apply_info=$this->_get_user_apply($major_ids,$user_ids);
				return count($apply_info);
			}
		}
		return 0;
	}
	/**
	 * [_get_na_num ]
	 * @return [type] [description]
	 */
	function _get_na_num($guojia,$degreestr){
		if(!empty($guojia)&&!empty($degreestr)){
			//组合条件  该学历下的专业 该国籍下的学生 录取的申请
			$degree_arr=explode('degree', $degreestr);
			$major_info=$this->db->get_where('major','degree = '.$degree_arr[1])->result_array();
			$major_ids=array();
			if(!empty($major_info)){
				foreach ($major_info as $key => $value) {
					$major_ids[]=$value['id'];
				}
			}
			//获取用户的id
			$user_info=$this->db->get_where('student_info','nationality = '.$guojia)->result_array();
			$user_ids=array();
			if(!empty($user_info)){
				foreach ($user_info as $key => $value) {
					$user_ids[]=$value['id'];
				}
			}
			if(!empty($major_ids)&&!empty($user_ids)){
				$apply_info=$this->_get_user_apply($major_ids,$user_ids);
				return count($apply_info);
			}
		}
		return 0;
	}
	/**
	 * [_get_user_apply description]
	 * @return [type] [description]
	 */
	function _get_user_apply($major_ids,$user_ids){
		$this->db->where('(state = 7 or state = 8)');
		$this->db->where_in('courseid',$major_ids);
		$this->db->where_in('userid',$user_ids);
		return $this->db->get('apply_info')->result_array();
	}
	/**
	 * 按专业统计
	 */
	function major_statistics(){
		$tou_info=$this->_get_tou('学院');
		$major_info=$this->db->get_where('major','state = 1')->result_array();
		$html='';
		if(!empty($major_info)){
			foreach ($major_info as $k => $v) {
				$html.='<tr>';
				if(!empty($tou_info)){
					$chang=0;
					$duan=0;
					foreach ($tou_info as $key => $value) {
						if($key=='id'){
							$html.='<td>'.$k.'</td>';
						}elseif($key=='name'){
							$html.='<td>'.$v['name'].'</td>';
						}elseif($key=='total'){
							$total=$chang+$duan;
							$html.='<td>'.$total.'</td>';
						}else{
							$num=$this->_get_major($v['id'],$key);
							$html.='<td>'.$num.'</td>';
							if(strstr($key,'duan')){
								$duan=$num;
							}elseif(strstr($key,'chang')){
								$chang=$num;
							}
						}
						
					}
				}
				$html.='</tr>';
			}
		}
		$this->_view ('major_index',array(
			'tou_info'=>$tou_info,
			'html'=>$html
			));
	}
	/**
	 * [_get_major 获取专业数]
	 * @return [type] [description]
	 */
	function _get_major($majorid,$key){
		if(!empty($majorid)&&!empty($key)){
			if(strstr($key,'degree')){
				$key_arr=explode('degree', $key);
				//查询所有的申请
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','id = '.$majorid.' AND degree = '.$key_arr[1])->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					// echo $this->db->last_query();
					// var_dump($apply_info);exit;
					return count($apply_info);

				}	
			}elseif(strstr($key,'chang')){
				$where=str_replace(",chang","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','id = '.$majorid.' AND degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					// echo $this->db->last_query();
					// var_dump($apply_info);exit;
					return count($apply_info);

				}	
			}elseif(strstr($key,'duan')){
				$where=str_replace(",duan","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','id = '.$majorid.' AND degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					// echo $this->db->last_query();
					// var_dump($apply_info);exit;
					return count($apply_info);

				}
			}
			
		}
		return 0;
	}
	/**
	 * 按学院统计
	 */
	function faculty_statistics(){
		$tou_info=$this->_get_tou('学院');
		//获取所有的学院
		$faculty_info=$this->db->get_where('faculty','state = 1')->result_array();
		$html='';
		if(!empty($faculty_info)){
			foreach ($faculty_info as $k => $v) {
				$html.='<tr>';
				if(!empty($tou_info)){
					$chang=0;
					$duan=0;
					foreach ($tou_info as $key => $value) {
						if($key=='id'){
							$html.='<td>'.$k.'</td>';
						}elseif($key=='name'){
							$html.='<td>'.$v['name'].'</td>';
						}elseif($key=='total'){
							$total=$chang+$duan;
							$html.='<td>'.$total.'</td>';
						}else{
							$num=$this->_get_faculty($v['id'],$key);
							$html.='<td>'.$num.'</td>';
							if(strstr($key,'duan')){
								$duan=$num;
							}elseif(strstr($key,'chang')){
								$chang=$num;
							}
						}
						
					}
				}
				$html.='</tr>';
			}
		}
		$this->_view ('faculty_index',array(
			'tou_info'=>$tou_info,
			'html'=>$html
			));
	}
	/**
	 * [_get_faculty 获取学院的数]
	 * @return [type] [description]
	 */
	function _get_faculty($facultyid,$key){
		if(!empty($facultyid)&&!empty($key)){
			if(strstr($key,'degree')){
				$key_arr=explode('degree', $key);
				//查询所有的申请
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','facultyid = '.$facultyid.' AND degree = '.$key_arr[1])->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					// echo $this->db->last_query();
					// var_dump($apply_info);exit;
					return count($apply_info);

				}	
			}elseif(strstr($key,'chang')){
				$where=str_replace(",chang","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','facultyid = '.$facultyid.' AND degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					// echo $this->db->last_query();
					// var_dump($apply_info);exit;
					return count($apply_info);

				}	
			}elseif(strstr($key,'duan')){
				$where=str_replace(",duan","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','facultyid = '.$facultyid.' AND degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					// echo $this->db->last_query();
					// var_dump($apply_info);exit;
					return count($apply_info);

				}
			}
			
		}
		return 0;
	}
	/**
	 * [_get_apply_info 获取所有的申请录取或者说事已经入学]
	 * @return [type] [description]
	 */
	function _get_apply_info($where){
		//获取已经被录取的申请信息
		return $this->db->get_where('apply_info','state = 7 OR state = 8 ')->result_array();
	}
	/**
	 * 按经费统计
	 */
	function funds_statistics(){
		$tou_info=$this->_get_tou('奖学金名称');
		//获取所有的学院
		$sch_info=$this->db->get_where('scholarship_info','state = 1 AND apply_state = 2')->result_array();
		$html='';
		if(!empty($sch_info)){
			foreach ($sch_info as $k => $v) {
				$html.='<tr>';
				if(!empty($tou_info)){
					$chang=0;
					$duan=0;
					foreach ($tou_info as $key => $value) {
						if($key=='id'){
							$html.='<td>'.$k.'</td>';
						}elseif($key=='name'){
							$html.='<td>'.$v['title'].'</td>';
						}elseif($key=='total'){
							$total=$chang+$duan;
							$html.='<td>'.$total.'</td>';
						}else{
							$num=$this->_get_funds($v['id'],$key);
							$html.='<td>'.$num.'</td>';
							if(strstr($key,'duan')){
								$duan=$num;
							}elseif(strstr($key,'chang')){
								$chang=$num;
							}
						}
						
					}
				}
				$html.='</tr><tr>';
			}
			$chang=0;
			$duan=0;
			foreach ($tou_info as $key => $value) {
				if($key=='id'){
					$html.='<td>'.count($sch_info).'</td>';
				}elseif($key=='name'){
					$html.='<td>自费</td>';
				}elseif($key=='total'){
					$total=$chang+$duan;
					$html.='<td>'.$total.'</td>';
				}else{
					$num=$this->_zifei($key);
					$html.='<td>'.$num.'</td>';
					if(strstr($key,'duan')){
						$duan=$num;
					}elseif(strstr($key,'chang')){
						$chang=$num;
					}
				}
			}		
			$html.='</tr>';

		}
		$this->_view ('funds_index',array(
			'tou_info'=>$tou_info,
			'html'=>$html
			));
	}
	/**
	 * [_get_funds 获取经费的数]
	 * @return [type] [description]
	 */
	function _get_funds($scid,$key){
		if(!empty($scid)&&!empty($key)){
			if(strstr($key,'degree')){
				$key_arr=explode('degree', $key);
				//查询所有的申请
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','degree = '.$key_arr[1])->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					if(!empty($apply_info)){
						foreach ($apply_info as $k => $v) {
							$info=$this->db->get_where('applyscholarship_info','applyid = '.$v['id'].' AND scholarshipid = '.$scid)->row_array();
							if(empty($info)){
								unset($apply_info[$k]);
							}
						}
					}
					return count($apply_info);

				}	
			}elseif(strstr($key,'chang')){
				$where=str_replace(",chang","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					if(!empty($apply_info)){
						foreach ($apply_info as $k => $v) {
							$info=$this->db->get_where('applyscholarship_info','applyid = '.$v['id'].' AND scholarshipid = '.$scid)->row_array();
							if(empty($info)){
								unset($apply_info[$k]);
							}
						}
					}
					return count($apply_info);

				}	
			}elseif(strstr($key,'duan')){
				$where=str_replace(",duan","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					if(!empty($apply_info)){
						foreach ($apply_info as $k => $v) {
							$info=$this->db->get_where('applyscholarship_info','applyid = '.$v['id'].' AND scholarshipid = '.$scid)->row_array();
							if(empty($info)){
								unset($apply_info[$k]);
							}
						}
					}
					return count($apply_info);

				}
			}
			
		}
		return 0;
	}
	/**
	 * [_zifei 自费]
	 * @return [type] [description]
	 */
	function _zifei($key){
		if(!empty($key)){
			if(strstr($key,'degree')){
				$key_arr=explode('degree', $key);
				//查询所有的申请
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','degree = '.$key_arr[1])->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					if(!empty($apply_info)){
						foreach ($apply_info as $k => $v) {
							$info=$this->db->get_where('applyscholarship_info','applyid = '.$v['id'])->row_array();
							if(!empty($info)){
								unset($apply_info[$k]);
							}
						}
					}
					return count($apply_info);

				}	
			}elseif(strstr($key,'chang')){
				$where=str_replace(",chang","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					if(!empty($apply_info)){
						foreach ($apply_info as $k => $v) {
							$info=$this->db->get_where('applyscholarship_info','applyid = '.$v['id'])->row_array();
							if(!empty($info)){
								unset($apply_info[$k]);
							}
						}
					}
					return count($apply_info);

				}	
			}elseif(strstr($key,'duan')){
				$where=str_replace(",duan","",$key);
				//获取所有的该学院和该学历的专业
				$major_info=$this->db->get_where('major','degree IN ( '.$where.')')->result_array();
				//组合where条件
				if(!empty($major_info)){
					$where='';
					foreach ($major_info as $k => $v) {
						$where.=$v['id'].',';
					}
					$where=trim($where,',');
					$apply_info=$this->db->get_where('apply_info','(state = 7 OR state = 8 ) AND courseid IN ('.$where.')')->result_array();
					if(!empty($apply_info)){
						foreach ($apply_info as $k => $v) {
							$info=$this->db->get_where('applyscholarship_info','applyid = '.$v['id'])->row_array();
							if(!empty($info)){
								unset($apply_info[$k]);
							}
						}
					}
					return count($apply_info);

				}
			}
			
		}
		return 0;
	}
}