<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 基础类
 *
 * @author zhuangqianlin
 *        
 */
class BASE_Ext extends CI_Controller {
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
	protected $access_path = '';
	
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
		$this->access_path = HINDEX . ROOT . mb_strcut ( $this->uri->uri_string (), 0, strpos ( $this->uri->uri_string (), $this->module ) );

		define ( 'IS_AJAX', $this->input->is_ajax_request () );

		$this->load->vars ( 'access_path', $this->access_path );

		
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

}

class Home_Basic extends BASE_Ext {
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
 * 管理中心基础类
 *
 * @author zhuangqianlin
 *        
 */
class Admin_Basic extends BASE_Ext {
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
		$this->view = ROOT;

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
			// $func = $this->access_path . $uri3 . '/' . $uri4;
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
			$func = $this->access_path . $uri3;
			
			$powerflag = $this->power->checkpower ( $_SESSION ['master_user_info']->groupid, md5 ( $func ) );
			if ($powerflag === false && $func != '/admin/core/index') {
				//$this->power->_no_access ();
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
		echo '<script>window.parent.location.href="/admin/core/login";</script>';
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
		exit ( $this->load->view ( 'admin/public/alert', array (
				'msg' => $msg,
				'jump' => '/admin/core/login',
				'state' => $state,
				'sleep' => $sleep 
		), true ) );
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

}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */