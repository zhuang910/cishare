<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 基础类
 *
 * @author junjiezhang
 *        
 */
class CUCAS_Ext extends CI_Controller {
	/**
	 * 显示层定义
	 *
	 * @var string
	 */
	public $view = '';
	
	/**
	 * 根
	 */
	protected $root = '';
	/**
	 * 控制器
	 *
	 * @var string
	 */
	protected $module = '';
	
	/**
	 * 方法
	 *
	 * @var string
	 */
	protected $method = '';
	
	/**
	 * 全局路径
	 *
	 * @var string
	 */
	protected $zjjp = '';
	
	/**
	 * 控制器所在路径
	 *
	 * @var string
	 */
	protected $dqml = '';
	/**
	 * 构造函数
	 */
	function __construct() {
		
		parent::__construct ();
		// 初始变量
		$this->root = $this->uri->segment ( 1 );
		$this->module = $this->uri->rsegment ( 1 );
		$this->method = $this->uri->rsegment ( 2 );

		// 获取目录路径
		$this->zjjp = HINDEX . MASTER . mb_strcut ( $this->uri->uri_string (), 0, strpos ( $this->uri->uri_string (), $this->module ) );

		define ( 'IS_AJAX', $this->input->is_ajax_request () );
		
		$this->load->vars ( 'zjjp', $this->zjjp );

		
	}
	
	/**
	 * 显示模版
	 *
	 * @param array $data        	
	 * @param bool $tmp        	
	 */
	protected function _view($name = null, $data = array(), $tmp = false) {
		if ($name !== null) {
			return $this->load->view ( $this->view . $name, $data, $tmp );
		} else {
			return $this->load->view ( $this->view . $this->module . '_' . $this->method );
		}
	}
	/**
	 * 更新房间数
	 *
	 * @param array $data        	
	 * @param bool $tmp        	
	 */
	protected function grf_update_room() {
		//获取所有的房间
		$room_info=$this->db->get('school_accommodation_prices')->result_array();
		if(!empty($room_info)){
			foreach ($room_info as $k => $v) {
				//更新预订的人数
				$in_user_num=$this->db->select('count(*) as num')->where('roomid = '.$v['id'].' AND acc_state <> 4 AND acc_state <> 7 AND paystate <> 0 AND paystate <> 2')->get('accommodation_info')->row_array();
				$this->db->update('school_accommodation_prices',array('in_user_num'=>$in_user_num['num']),'id = '.$v['id']);
				//更新已住人数
				$ensure_user_num=$this->db->select('count(*) as num')->where('roomid = '.$v['id'].' AND acc_state = 6')->get('accommodation_info')->row_array();
				$this->db->update('school_accommodation_prices',array('ensure_user_num'=>$ensure_user_num['num']),'id = '.$v['id']);
			}
		}
	}
	/**
	 * [update_room_cost 根据房间每个学生的电费的余额更新房间的状态]
	 * @return [type] [description]
	 */
	protected function update_room_cost(){

		//获取所有的房间
		$room_info=$this->db->get('school_accommodation_prices')->result_array();
		$charge=CF('warning_line','',CONFIG_PATH);
		//循环每个房间 查找学生更新记录
		if(!empty($room_info)&&!empty($charge)){
			foreach ($room_info as $k => $v) {
				$user_info=$this->db->get_where('accommodation_info','roomid = '.$v['id'].' AND acc_state = 6')->result_array();

				if(!empty($user_info)){

					$cost_state['cost_state']=1;
					foreach ($user_info as $kk => $vv) {

						//获取该生交电费的费用
						 $user_e=$this->db->get_where('electric_info','userid = '.$vv['userid'])->result_array();
						 $e_total=0;
							if(!empty($user_e)){
								foreach ($user_e as $kkk => $vvv) {
									$e_total+=$vvv['paid_in'];
								}
							}
						//查询已经用的电费
						$u_e_total=0;
						$user_y_e=$this->db->get_where('room_electric_user','userid = '.$vv['userid'])->result_array();
						if(!empty($user_y_e)){
							foreach ($user_y_e as $kkkk => $vvvv) {
								$u_e_total+=$vvvv['money'];
							}
						}
						$result=$e_total-$u_e_total;
						
						if($result<0){
							//更新该房间已经处于提醒状态
							$cost_state['cost_state']=3;
						}elseif ($result<$charge['charge']) {
							$cost_state['cost_state']=2;
						}
					}
					$this->db->update('school_accommodation_prices',$cost_state,'id = '.$v['id']);
				}
				
			}
		}
		
	}
}

/**
 * 管理中心基础类
 *
 * @author junjiezhang
 *        
 */
class Master_Basic extends CUCAS_Ext {
	/**
	 * 管理员id
	 *
	 * @var number
	 */
	protected $adminid = false;
	
	/**
	 * 管理员 后台 语言
	 */
	protected $site_language_admin = array ();
	// 获取开启的语言 后台 调用
	protected $open_language = array ();
	/**
	 * 菜单
	 *
	 * @var array
	 */
	protected $menudata = array ();
	/**
	 * 内容管理菜单
	 *
	 * @var array
	 */
	public $column_nav = array ();
	/**
	 * 多语言跳转链接
	 */
	protected $url_grf = array ();

	/**
	 * 基础类构造函数
	 */

	function __construct() {
		parent::__construct ();

		// // 更新已入住的学生剩余的天数
		// $this->update_residue_days ();
		$this->column_nav = $this->get_column_arr ();
		$this->view = MASTER;
		// 默认语言是中文
		if (empty ( $_SESSION ['language'] )) {
			$_SESSION ['language'] = 'cn';
		}
		// 获取所有的语言
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['site_language'];
		// 获取 已经 开放的语言
		$open_l = CF ( 'site_language', '', CONFIG_PATH );
		// 组合新语言
		if (empty ( $open_l )) {
			$open_l = $l;
		}
		$this->open_language = $open_l;
		$this->site_language_admin = $open_l;
		$this->load->vars ( 'site_language_admin', $this->site_language_admin );
		// 设置站点语言切换数组
		$str = $_SERVER ['QUERY_STRING'];
		$arg_arr = explode ( '&', $str );
		foreach ( $this->site_language_admin as $k => $v ) {
			foreach ( $arg_arr as $kk => $vv ) {
				if (substr_count ( $vv, 'label_id=' ) > 0) {
					$arg_arr [$kk] = 'label_id=' . $k;
				}
			}
			if (empty ( $str )) {
				$jump_url = $_SERVER ['PHP_SELF'];
			} else {
				$jump_url = $_SERVER ['PHP_SELF'] . '?';
			}
			$arg_str = '';
			foreach ( $arg_arr as $key => $value ) {
				$arg_str .= $value . '&';
			}
			$this->url_grf [$v] = $jump_url . trim ( $arg_str, '&' );
		}
		$this->load->vars ( 'url_grf', $this->url_grf );
		// //设置当前选中的站点语言
		// $str_select=$_SERVER['QUERY_STRING'];
		// // var_dump($str_select);
		// $arg_arr_select=explode('&', $str_select);
		// // var_dump($arg_arr_select);
		// foreach ($arg_arr_select as $k => $v) {
		// foreach ($this->site_language_admin as $kk => $vv) {
		// if($v=='label_id='.$kk){
		// $_SESSION['language']=$kk;
		// }
		// }
		// }
		
		// 传递url参数
		$redirect_query_string = isset ( $_SERVER ['REDIRECT_QUERY_STRING'] ) ? $_SERVER ['REDIRECT_QUERY_STRING'] : '';
		$this->load->vars ( array (
				'uvar' => $redirect_query_string 
		) );
		// $this->menudata = CF ( 'menu' );
		
		// 验证是否挂起
		// if (! empty ( $_COOKIE ['master_user_hang'] ) && $_COOKIE ['master_user_hang'] == 'true') {
		// exit ( $this->_view ( '/public/hang', '', true ) );
		// }
		
		// 如果没有登录跳转到登录页面
		if (isset ( $_SESSION ['master_user_key'] ) && $_SESSION ['master_user_key'] === true) {
			// $this->adminid = $_SESSION ['master_user_info']->id;
			
			// // 权限验证
			// $powerflag = false;
			// $this->load->library ( 'power' );
			// $uri3 = $this->uri->segment ( 3 );
			// $uri4 = $this->uri->segment ( 4 );
			// $uri4 = ! empty ( $uri4 ) ? $uri4 : 'index';
			// $func = $this->zjjp . $uri3 . '/' . $uri4;
			// $powerflag = $this->power->checkpower ( $_SESSION ['master_user_info']->groupid, md5 ( $func ) );
			// // $powerflag = $this->power->checkpower ( 1, md5 ( $func ) );
			// if ($powerflag === false) {
			// $this->_no_access ();
			// }
			// // 当前点击菜单组合
			// $click_nav = intval ( $this->input->get ( 'cnav' ) ) ? intval ( $this->input->get ( 'cnav' ) ) : 0;
			// $click_left = intval ( $this->input->get ( 'cleft' ) ) ? intval ( $this->input->get ( 'cleft' ) ) : 0;
			// $click_menuid = intval ( $this->input->get ( 'cmenuid' ) ) ? intval ( $this->input->get ( 'cmenuid' ) ) : 0;
			
			// // 顶部菜单
			// $menu_nav = menu_tree ( 0, $this->menudata );
			
			// // 左侧菜单
			// if ($click_nav == 0) {
			// $menu_one = reset ( $menu_nav );
			// $click_nav = $menu_one ['menuid'];
			// }
			
			// $menu_left = menu_tree ( $click_nav, $this->menudata );
			
			// // 面包线
			// $this->load->vars ( 'cnav', $click_nav );
			// $this->load->vars ( 'menu_left', $menu_left );
			// $this->load->vars ( 'menu', $menu_nav );
			
			// 权限验证
			$this->adminid = $_SESSION ['master_user_info']->id;
			$powerflag = false;
			$this->load->library ( 'power' );
			$uri3 = $this->uri->segment ( 3 );
			// $uri4 = $this->uri->segment ( 4 );
			// $uri4 = ! empty ( $uri4 ) ? $uri4 : 'index';
			$func = $this->zjjp . $uri3;
			
			$powerflag = $this->power->checkpower ( $_SESSION ['master_user_info']->groupid, md5 ( $func ) );
			if ($powerflag === false && $func != '/master/core/index') {
				$this->power->_no_access ();
			}
			
			// 载入日志类
			
			$this->load->library ( 'adminlog' );
		} else {
			$this->_jump_login ();
		}
	}
	
	/**
	 * 跳转到登录
	 */
	private function _jump_login() {
		echo '<script>window.parent.location.href="/master/core/login";</script>';
	}
	
	/**
	 * 返回无权限
	 */
	private function _no_access() {
		if ($this->input->is_ajax_request () === true) {
			ajaxReturn ( '', '没有权限', 0 );
		} else {
			$this->_alert ( '没有权限', 0, 3 );
		}
	}
	
	/**
	 * 提示页面
	 *
	 * @param string $msg
	 *        	消息
	 * @param number $state
	 *        	状态 1 成功 2 错误
	 * @param number $sleep
	 *        	等待跳转时间 默认 3秒
	 */
	protected function _alert($msg, $state = 0, $sleep = 3) {
		exit ( $this->load->view ( 'master/public/alert', array (
				'msg' => $msg,
				'jump' => '/master/core/login',
				'state' => $state,
				'sleep' => $sleep 
		), true ) );
	}
	// 获取栏目列表
	function get_column_arr() {
		$this->load->model ( '/master/cms/column_model', 'model' );
		return $this->model->get_column_info_nav ();
	}
	/**
	 * [insert_history 更改住宿历史表记录]
	 * @return [type] [description]
	 */
	function insert_history($id,$state){
		if(!empty($id)&&!empty($state)){
			//入库
			$info=$this->db->get_where('accommodation_info','id = '.$id)->row_array();
			if(!empty($info)){
				$arr=array(
					'acc_id'=>$id,
					'userid'=>$info['userid'],
					'campusid'=>$info['campid'],
					'buildingid'=>$info['buildingid'],
					'floor'=>$info['floor'],
					'roomid'=>$info['roomid'],
					'state'=>$state,
					'createtime'=>time(),
					'adminid'=>$_SESSION ['master_user_info']->id,
					);
				$this->db->insert('accommodation_history',$arr);
			}
		}
	}
	// // 更新已入住的学生剩余的天数
	// function update_residue_days() {
	// 	$this->load->model ( '/master/enrollment/acc_dispose_quarterage_model' );
	// 	// 获取所有的已入住的学生
	// 	$user_info = $this->acc_dispose_quarterage_model->get_all_acc_user ();
		
	// 	// 循环每个学生
	// 	foreach ( $user_info as $k => $v ) {
	// 		// 计算住了多少天
	// 		$few_days = floor ( (time () - $v ['accstarttime']) / (3600 * 24) );
	// 		// 一共能住多少天
	// 		// 获取房间的价格
	// 		$room_prices = $this->acc_dispose_quarterage_model->get_room_prices ( $v ['roomid'] );
	// 		$tal_days = floor ( $v ['acc_money'] / $room_prices );
	// 		// 计算剩余的天数插曲表中
	// 		$residue_days = $tal_days - $few_days;
	// 		$arr ['residue_days'] = $residue_days;
	// 		$this->db->update ( 'accommodation_info', $arr, 'id = ' . $v ['id'] );
	// 	}
	// }
}

/**
 * 前台学生 基础类
 *
 * @author Bruce zhang
 *        
 */
class Student_Basic extends CUCAS_Ext {
	protected $_lang = null;
	protected $puri = null;
	protected $open_language = null;
	protected $where_lang = null;
	protected $re_url = null;
	protected $function_on_off = array ();
    /**
     * @学生是否通过申请
     */
    protected $is_apply=0;
    /**
     * 查看学生是否在学
     */
    protected $is_student=0;
    /**
     * @var 用户id
     */
    protected $userid=0;
    /**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
        $this->userid=!empty($_SESSION ['student'] ['userinfo'] ['id'])?$_SESSION ['student'] ['userinfo'] ['id']:0;
        //加载学生端student类
        $this->load->model ( '/student/student_model', 'stu_model' );
        //查询该学生是否通过申请了
        $this->is_apply=$this->check_apply();
        $this->load->vars ( 'is_user_apply', $this->is_apply );
        //查看学生是否在学
        $this->is_student=$this->check_stident();
        $this->load->vars('is_user_student',$this->is_student);
		// 返回url 登录注册 使用
		$backurl = urlencode ( $_SERVER ['REQUEST_URI'] );
		$this->load->vars ( 'backurl', $backurl );
		$this->re_url = urlencode ( 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] . '?' . $_SERVER ['QUERY_STRING'] );
		// 获取语言
		$this->_lang = $this->config->item ( 'language' );
		// 确定URI1 是那一个 要追加的
		if (! empty ( $this->_lang ) && $this->_lang == 'chinese') {
			$this->puri = 'cn';
		} else {
			$this->puri = 'en';
		}
		if (! empty ( $_SESSION ['student'] ['userinfo'] )) {
			$this->load->vars ( 'userinfo', $_SESSION ['student'] ['userinfo'] );
		}
		$this->lang->load ( 'nav', $this->_lang );
		$this->lang->load ( 'public', $this->_lang );
		$this->lang->load ( 'user', $this->_lang );
		$this->load->helper ( 'language' );
		$this->load->vars ( 'puri', $this->puri );
		
		// 获取所有的语言
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['site_language'];
		// 获取 已经 开放的语言
		$open_l = CF ( 'site_language', '', CONFIG_PATH );
		// 组合新语言
		if (empty ( $open_l )) {
			$open_l = $l;
		}
		$this->open_language = $open_l;
		foreach ( $open_l as $lk => $lv ) {
			$this->site_language_open [$lk] = $l [$lk];
		}
		
		$web_l = array (
				'en' => '1',
				'cn' => '2',
				'zyj' => '3',
				'jyz' => '4' 
		);
		
		$this->load->vars ( 'web_l', $web_l );
		
		$this->load->vars ( 'site_language_open', $this->site_language_open );
		// 作为查询条件
		$this->where_lang = $web_l [$this->puri];
		$this->load->vars ( 'where_lang', $this->where_lang );
		
		// 查询开关设置 申请 接机 住宿
		$index_config = CF ( 'index_config', '', CONFIG_PATH );
		$this->load->vars ( 'index_config', $index_config );
		
		$this->function_on_off = CF ( 'function_on_off', '', CONFIG_PATH );
		$this->load->vars ( 'function_on_off', $this->function_on_off );
	}
	
	/**
	 * 跳转到登录
	 */
	private function _jump_login() {
		echo '<script>window.parent.location.href="/master/core/login";</script>';
	}
	
	/**
	 * 消息显示
	 *
	 * @param string $msg        	
	 * @param string $url        	
	 * @param int $mark        	
	 * @param int $time        	
	 * @param bool $ajax        	
	 * @return void
	 */
	protected function user_msg($msg, $url = '', $mark = 0, $time = 1, $ajax = FALSE) {
		if ($ajax || IS_AJAX) {
			exit ( cu_json ( ($mark ? 1 : 0), $msg, $url ) );
		} else {
			$win = $this->load->view ( 'msg', array (
					'msg' => $msg,
					'url' => $url,
					'time' => $time,
					'mark' => $mark,
					'meta_name' => lang ( 'tips' ) 
			) );
		}
	}
	
	/**
	 * 获取状态
	 */
	function get_apply_state($cid,$userid=null) {
		if($userid==null){
			$userid=$_SESSION ['student'] ['userinfo'] ['id'];
		}
		$this->load->model ( 'fillingoutforms_model', 'model' );
		$is = $this->model->get_apply_info ( $cid, $userid );
		if (! empty ( $is )) {
			return $is;
		}
		return false;
	}
	
	/**
	 * 更新状态
	 *
	 * @param number $cid        	
	 */
	function update_apply_state($cid = 0, $uid = 0, $data = null, $where = null) {
		if ($cid && $uid && ! empty ( $where )) {
			$this->load->library ( 'cimongo/cimongo' );
			$this->load->model ( 'fillingoutforms_model', 'model' );
			$apply = $this->model->get_apply_info ( $cid, $this->uid );
			if (! empty ( $apply )) {
				$this->cimongo->set ( array (
						$where => ( int ) $data 
				) )->where ( array (
						'id' => ( int ) $apply->id 
				) )->update ( 'apply_state' );
			}
			if ($data == 1) {
				$UDB = $this->load->database ( 'cucasuser', true );
				$UDB->update ( 'apply', array (
						'isatt' => $data 
				), 'id = ' . $apply->id );
			} else {
				$UDB = $this->load->database ( 'cucasuser', true );
				$UDB->update ( 'apply', array (
						'isatt' => 0 
				), 'id = ' . $apply->id );
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 验证附件上传状态
	 *
	 * @param number $pid
	 *        	// 附件模版id
	 * @param number $cid
	 *        	// 课程id
	 */
	function check_atta_state($pid = 0, $cid = 0, $ischeck = false) {
		if ($pid && $cid) {
			$this->load->library ( 'cimongo/cimongo' );
			$attachement_item = $this->apply_model->get_attachment_item ( $pid );
			$c = count ( $attachement_item );
			$i = 0;
			foreach ( $attachement_item as $item ) {
				$is = $this->cimongo->where ( array (
						'attId' => ( int ) $item ['aTopic_id'],
						'class_id' => ( int ) $cid,
						'uid' => ( int ) $this->uid 
				) )->limit ( 1 )->get ( 'cucas_apply_attachment' )->row ();
				if (! empty ( $is )) {
					$i ++;
				}
			}
			
			$s = 0;
			if ($i == 0) {
				return 0;
			}
			
			if ($c >= $i && $ischeck == false) {
				return 2;
			} else if ($c > $i) {
				return 2;
			}
			
			if ($c == $i && $i != 0 && $ischeck == true) {
				return 1;
			}
		}
		return 0;
	}
    /**
     * 检查是否申请通过了
     */
    function check_apply(){

        $is=$this->stu_model->check_student_apply($this->userid);
        return $is;
    }

    /**
     * 检查学生是否在学
     */
    function check_stident(){
        $is=$this->stu_model->check_student_student($this->userid);
        return $is;
    }
}
/**
 * 前台老师 基础类
 *
 * @author Bruce zhang
 *        
 */
class Teacher_Basic extends CUCAS_Ext {
	protected $_lang = null;
	protected $puri = null;
	protected $open_language = null;
	protected $where_lang = null;
	protected $re_url = null;
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		// 返回url 登录注册 使用
		$backurl = urlencode ( $_SERVER ['REQUEST_URI'] );
		$this->load->vars ( 'backurl', $backurl );
		$this->re_url = urlencode ( 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] . '?' . $_SERVER ['QUERY_STRING'] );
		// 获取语言
		$this->_lang = $this->config->item ( 'language' );
		// 确定URI1 是那一个 要追加的
		if (! empty ( $this->_lang ) && $this->_lang == 'chinese') {
			$this->puri = 'cn';
		} else {
			$this->puri = 'en';
		}
		if (! empty ( $_SESSION ['student'] ['userinfo'] )) {
			$this->load->vars ( 'userinfo', $_SESSION ['student'] ['userinfo'] );
		}
		$this->lang->load ( 'nav', $this->_lang );
		$this->lang->load ( 'public', $this->_lang );
		$this->lang->load ( 'user', $this->_lang );
		$this->load->helper ( 'language' );
		$this->load->vars ( 'puri', $this->puri );
		
		// 获取所有的语言
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['site_language'];
		// 获取 已经 开放的语言
		$open_l = CF ( 'site_language', '', CONFIG_PATH );
		// 组合新语言
		if (empty ( $open_l )) {
			$open_l = $l;
		}
		$this->open_language = $open_l;
		foreach ( $open_l as $lv ) {
			$this->site_language_open [$lv] = $l [$lv];
		}
		
		$web_l = array (
				'en' => '1',
				'cn' => '2',
				'zyj' => '3',
				'jyz' => '4' 
		);
		
		$this->load->vars ( 'web_l', $web_l );
		
		$this->load->vars ( 'site_language_open', $this->site_language_open );
		// 作为查询条件
		$this->where_lang = $web_l [$this->puri];
		$this->load->vars ( 'where_lang', $this->where_lang );
		
		// 查询开关设置 申请 接机 住宿
		$index_config = CF ( 'index_config', '', CONFIG_PATH );
		$this->load->vars ( 'index_config', $index_config );
	}
	
	/**
	 * 跳转到登录
	 */
	private function _jump_login() {
		echo '<script>window.parent.location.href="/master/core/login";</script>';
	}
	
	/**
	 * 消息显示
	 *
	 * @param string $msg        	
	 * @param string $url        	
	 * @param int $mark        	
	 * @param int $time        	
	 * @param bool $ajax        	
	 * @return void
	 */
	protected function user_msg($msg, $url = '', $mark = 0, $time = 1, $ajax = FALSE) {
		if ($ajax || IS_AJAX) {
			exit ( cu_json ( ($mark ? 1 : 0), $msg, $url ) );
		} else {
			$win = $this->load->view ( 'msg', array (
					'msg' => $msg,
					'url' => $url,
					'time' => $time,
					'mark' => $mark,
					'meta_name' => lang ( 'tips' ) 
			) );
		}
	}
	
	/**
	 * 获取状态
	 */
	function get_apply_state($cid) {
		$this->load->model ( 'fillingoutforms_model', 'model' );
		$is = $this->model->get_apply_info ( $cid, $_SESSION ['student'] ['userinfo'] ['id'] );
		if (! empty ( $is )) {
			return $is;
		}
		return false;
	}
	
	/**
	 * 更新状态
	 *
	 * @param number $cid        	
	 */
	function update_apply_state($cid = 0, $uid = 0, $data = null, $where = null) {
		if ($cid && $uid && ! empty ( $where )) {
			$this->load->library ( 'cimongo/cimongo' );
			$this->load->model ( 'fillingoutforms_model', 'model' );
			$apply = $this->model->get_apply_info ( $cid, $this->uid );
			if (! empty ( $apply )) {
				$this->cimongo->set ( array (
						$where => ( int ) $data 
				) )->where ( array (
						'id' => ( int ) $apply->id 
				) )->update ( 'apply_state' );
			}
			if ($data == 1) {
				$UDB = $this->load->database ( 'cucasuser', true );
				$UDB->update ( 'apply', array (
						'isatt' => $data 
				), 'id = ' . $apply->id );
			} else {
				$UDB = $this->load->database ( 'cucasuser', true );
				$UDB->update ( 'apply', array (
						'isatt' => 0 
				), 'id = ' . $apply->id );
			}
			return true;
		}
		return false;
	}
	
	/**
	 * 验证附件上传状态
	 *
	 * @param number $pid
	 *        	// 附件模版id
	 * @param number $cid
	 *        	// 课程id
	 */
	function check_atta_state($pid = 0, $cid = 0, $ischeck = false) {
		if ($pid && $cid) {
			$this->load->library ( 'cimongo/cimongo' );
			$attachement_item = $this->apply_model->get_attachment_item ( $pid );
			$c = count ( $attachement_item );
			$i = 0;
			foreach ( $attachement_item as $item ) {
				$is = $this->cimongo->where ( array (
						'attId' => ( int ) $item ['aTopic_id'],
						'class_id' => ( int ) $cid,
						'uid' => ( int ) $this->uid 
				) )->limit ( 1 )->get ( 'cucas_apply_attachment' )->row ();
				if (! empty ( $is )) {
					$i ++;
				}
			}
			
			$s = 0;
			if ($i == 0) {
				return 0;
			}
			
			if ($c >= $i && $ischeck == false) {
				return 2;
			} else if ($c > $i) {
				return 2;
			}
			
			if ($c == $i && $i != 0 && $ischeck == true) {
				return 1;
			}
		}
		return 0;
	}
}
/**
 * 前台中介 基础类
 *
 * @author Bruce zhang
 *        
 */
class Agency_Basic extends CUCAS_Ext {
	
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		// 返回url 登录注册 使用
		$backurl = urlencode ( $_SERVER ['REQUEST_URI'] );
		$this->load->vars ( 'backurl', $backurl );
	}
	
	/**
	 * 跳转到登录
	 */
	private function _jump_login() {
		echo '<script>window.parent.location.href="/master/core/login";</script>';
	}
	
	/**
	 * 提示页面
	 *
	 * @param string $msg
	 *        	消息
	 * @param number $state
	 *        	状态 1 成功 2 错误
	 * @param number $sleep
	 *        	等待跳转时间 默认 3秒
	 */
	protected function _alert($msg, $state = 0, $sleep = 3) {
		exit ( $this->load->view ( 'master/public/alert', array (
				'msg' => $msg,
				'jump' => '/master/core/login',
				'state' => $state,
				'sleep' => $sleep 
		), true ) );
	}
}
class Home_Basic extends CUCAS_Ext {
	protected $_lang = null;
	protected $puri = null;
	protected $nav = array ();
	protected $open_language = null;
	protected $where_lang = null;
	protected $function_on_off = array ();
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		// 返回url 登录注册 使用
		$backurl = urlencode ( $_SERVER ['REQUEST_URI'] );
		$this->load->vars ( 'backurl', $backurl );
		// 设置语言
		$this->_lang = $this->config->item ( 'language' );
		// 确定URI1 是那一个 要追加的
		if (! empty ( $this->_lang ) && $this->_lang == 'chinese') {
			$this->puri = 'cn';
		} else {
			$this->puri = 'en';
		}
		$this->lang->load ( 'nav', $this->_lang );
		$this->lang->load ( 'public', $this->_lang );
		$this->lang->load ( 'user', $this->_lang );
		$this->load->helper ( 'language' );
		$this->load->vars ( 'puri', $this->puri );
		
		// 获取所有的语言
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['site_language'];
		// 获取 已经 开放的语言
		$open_l = CF ( 'site_language', '', CONFIG_PATH );
	}
}

/**
 * 前台学生 基础类
 *
 * @author Bruce zhang
 *        
 */
class Society_Basic extends CUCAS_Ext {
	protected $_lang = null;
	protected $puri = null;
	protected $open_language = null;
	protected $where_lang = null;
	protected $re_url = null;
	protected $function_on_off = array ();
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		// 返回url 登录注册 使用
		$backurl = urlencode ( $_SERVER ['REQUEST_URI'] );
		$this->load->vars ( 'backurl', $backurl );
		$this->re_url = urlencode ( 'http://' . $_SERVER ['HTTP_HOST'] . $_SERVER ['PHP_SELF'] . '?' . $_SERVER ['QUERY_STRING'] );
		// 获取语言
		$this->_lang = $this->config->item ( 'language' );
		// 确定URI1 是那一个 要追加的
		if (! empty ( $this->_lang ) && $this->_lang == 'chinese') {
			$this->puri = 'cn';
		} else {
			$this->puri = 'en';
		}
		if (! empty ( $_SESSION ['society'] ['userinfo'] )) {
			$this->load->vars ( 'society', $_SESSION ['society'] ['userinfo'] );
		}
		$this->lang->load ( 'nav', $this->_lang );
		$this->lang->load ( 'public', $this->_lang );
		$this->lang->load ( 'user', $this->_lang );
		$this->load->helper ( 'language' );
		$this->load->vars ( 'puri', $this->puri );
		
		// 获取所有的语言
		$configuration = CF ( 'configuration', '', CONFIG_PATH );
		$l = $configuration ['site_language'];
		// 获取 已经 开放的语言
		$open_l = CF ( 'site_language', '', CONFIG_PATH );
		// 组合新语言
		if (empty ( $open_l )) {
			$open_l = $l;
		}
		$this->open_language = $open_l;
		foreach ( $open_l as $lk => $lv ) {
			$this->site_language_open [$lk] = $l [$lk];
		}
		
		$web_l = array (
				'en' => '1',
				'cn' => '2',
				'zyj' => '3',
				'jyz' => '4' 
		);
		
		$this->load->vars ( 'web_l', $web_l );
		
		$this->load->vars ( 'site_language_open', $this->site_language_open );
		// 作为查询条件
		$this->where_lang = $web_l [$this->puri];
		$this->load->vars ( 'where_lang', $this->where_lang );
		
		// 查询开关设置 申请 接机 住宿
		$index_config = CF ( 'index_config', '', CONFIG_PATH );
		$this->load->vars ( 'index_config', $index_config );
		
		$this->function_on_off = CF ( 'function_on_off', '', CONFIG_PATH );
		$this->load->vars ( 'function_on_off', $this->function_on_off );
	}
	
	/**
	 * 消息显示
	 *
	 * @param string $msg        	
	 * @param string $url        	
	 * @param int $mark        	
	 * @param int $time        	
	 * @param bool $ajax        	
	 * @return void
	 */
	protected function user_msg($msg, $url = '', $mark = 0, $time = 1, $ajax = FALSE) {
		if ($ajax || IS_AJAX) {
			exit ( cu_json ( ($mark ? 1 : 0), $msg, $url ) );
		} else {
			$win = $this->load->view ( 'msg', array (
					'msg' => $msg,
					'url' => $url,
					'time' => $time,
					'mark' => $mark,
					'meta_name' => lang ( 'tips' ) 
			) );
		}
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */