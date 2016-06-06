<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
/**
 * 栏目管理
 *
 * @author junjiezhang
 *        
 */
class Column extends Master_Basic {
	/**
	 * 栏目管理
	 *
	 * @var array
	 */
	protected $type;
	// protected $module = array ();
	protected $lang=array();
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/cms/';
		
		$this->load->model ( $this->view . 'column_model' );
		
		// $this->module = CF ( 'module', '', 'application/cache/' );
		// $this->load->vars ( 'module', $this->module );
	}
	
	/**
	 * 首页
	 */
	function index() {
		$array=$this->column_model->get_column_info();
		$arr=$this->column_nav;
	// var_dump($arr[0][0]);exit;

		$str="<tr>
				<td class='center'>
					<input type='text' name='orderby_\$id' value='\$orderby' style='width:50px;height:25px;'>
				</td>
				<td>
					\$id
				</td>
				<td class='hidden-480'>\$spacer - \$title</td>
				<td>\$mtitle</td>
				<td><a title='添加子栏目' class='blue' href='/master/cms/column/add?id=\$id'>
					<i class='ace-icon fa fa-plus bigger-130'></i>
					</a>
					<a title='修改栏目' class='blue' href='/master/cms/column/edit?id=\$id'>
					<i class='ace-icon fa fa-pencil bigger-130'></i>
					</a>
					<a title='删除栏目' class='red' href='javascript:;' onclick='del(\$id)'>
					<i class='ace-icon fa fa-trash-o bigger-130'></i>
					</a>
			</td>
			</tr>";
		$this->load->library ( 'Tree' );

		$Tree = new Tree ( $array );
		$Tree->icon = array ('&nbsp;│  ', '&nbsp;├─ ', '&nbsp;└─ ' );
		$Tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$select_categorys = $Tree->get_tree ( 0, $str );
		// echo '<select>'.$select_categorys.'</select>';die;
		$this->_view ( 'column_index' ,array(
			'select_categorys'=>! empty ($select_categorys)?$select_categorys:'',
			));
	}
	/**
	 * 添加栏目
	 */
	function get_html() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		$o = intval ( trim ( $this->input->get ( 'o' ) ) );


		if (! empty ( $id ) && $o == 2) {
			$result = $this->column_model->get_one ( $id );
		}

		if($o == '2'){
			foreach($this->site_language_admin as $ln => $v){
				$ln = strtolower($v);
				$path = APPPATH.'language/'.$ln.'/nav_lang.php';
				$lang_content = file_get_contents($path);
				if($lang_content){
					$lang_arr = explode("\r\n",$lang_content);
					if(is_array($lang_arr)){
						foreach($lang_arr as $lang_set){
							if(strpos($lang_set,'$lang') === false)
								continue;
							list($key,$val) = explode('=',$lang_set);
							$val = str_replace(array("'",';'),array('',''),$val);
							preg_match_all('/\[\'(.*)\'\]/',$key,$zjj);
							$k = $zjj[1][0];
							$that_k = 'nav_'.$id;
							if($k === $that_k){
								$result->lang[$v] = $val;
							}
						}
					}
				}
			}
		}

		$html = $this->_view ( 'news_get_html', array (
				'info' => ! empty ( $result ) ? $result : array (),
				'id' => ! empty ( $id ) ? $id : '',
				'o' => ! empty ( $o ) ? $o : '' 
		), true );
		ajaxReturn ( $html, '', 1 );
	}
	/**
	 *添加
	 **/	
	function add(){
		if(!empty($this->site_language_admin)){
			$language=$this->site_language_admin;
		}
		$parent_id=$this->input->get('id');
		$array=$this->column_model->get_column_info();
		$str="<option \$selected value='\$id'>\$spacer - \$title</option>";
		$this->load->library ( 'Tree' );
		$Tree = new Tree ( $array );
		$Tree->icon = array ('&nbsp;│  ', '&nbsp;├─ ', '&nbsp;└─ ' );
		$Tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$select_categorys = $Tree->get_tree ( 0, $str ,$parent_id);
		$module_info=$this->column_model->get_module_info();
		//判断当前开启的是那一个 主题
		$on = CF('theme','',CONFIG_PATH);
		//单页模型 模版
		$template_pages = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 2')->result_array();
		//列表模型 模版
		$template_lists = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 3')->result_array();
		//ppt模型 模版
		$template_ppt = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 4')->result_array();
		//图集模型 模版
		$template_images = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 5')->result_array();
		$this->_view ( 'column_edit', array (
			'select_categorys'=>$select_categorys,
			'module_info'=>$module_info,
			'template_pages' => !empty($template_pages)?$template_pages:array(),
			'template_lists' => !empty($template_lists)?$template_lists:array(),
			'template_ppt' => !empty($template_ppt)?$template_ppt:array(),
			'template_images' => !empty($template_images)?$template_images:array(),
			'language'=>!empty($language)?$language:array()
		) );
	}
	
	
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->_save_data ();
		if (! empty ( $data )) {
			$id = $this->column_model->save ( null, $data );
			if(!empty($id)&&!empty($this->lang)){
				foreach($this->lang as $ln => $v){
					$ln = strtolower($ln);
					$path=APPPATH.'language/'.$ln;
					if(!is_dir($path)){
						mk_dir($path);
					}
					$path = APPPATH.'language/'.$ln.'/nav_lang.php';
					if(file_exists($path)){
						$lang_content = file_get_contents($path);
						if($lang_content){
							$lang_arr = explode("\r\n",$lang_content);
							if(is_array($lang_arr)){
								$is = false;
								$str = "<?php";
								foreach($lang_arr as $lang_set){
									if(strpos($lang_set,'$lang') === false)
										continue;
									list($key,$val) = explode('=',$lang_set);
									preg_match_all('/\[\'(.*)\'\]/',$key,$zjj);
									$k = $zjj[1][0];
									$that_k = 'nav_'.$id;
									if($k === $that_k){
										$str .= "\r\n".trim($key).' = \''.trim($v)."';";
										$is = true;
									}else{
										$str .= "\r\n".trim($key).' = '.trim($val);
									}
								}
								if($is === false){
									$str .= "\r\n".'$'."lang ['nav_{$id}'] = '".trim($v)."';";
								}
								file_put_contents($path,$str);
							}
						}
					}else{
						$str = "<?php";
						$str .= "\r\n".'$'."lang ['nav_{$id}'] = '".trim($v)."';";
						file_put_contents($path,$str);
					}
					
				}
			}
			if ($id) {
				$this->_cache();
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	
	/**
	 * 编辑
	 */
	function edit() {
		if(!empty($this->site_language_admin)){
			$language=$this->site_language_admin;
		}
		// 获取文章id
		$id = intval ( $this->input->get ( 'id' ) );
		//上级栏目
		$parent_id=$this->column_model->get_one($id);
		if ($id) {
			$info = ( object ) $this->column_model->get_one ( $id );
		}
		$array=$this->column_model->get_column_info();
		$str="<option \$selected value='\$id'>\$spacer - \$title</option>";
		$this->load->library ( 'Tree' );
		$Tree = new Tree ( $array );
		$Tree->icon = array ('&nbsp;│  ', '&nbsp;├─ ', '&nbsp;└─ ' );
		$Tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$select_categorys = $Tree->get_tree ( 0, $str ,$parent_id->parent_id);
		$module_info=$this->column_model->get_module_info();
		$on = CF('theme','',CONFIG_PATH);
		//单页模型 模版
		$template_pages = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 2')->result_array();
		//列表模型 模版
		$template_lists = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 3')->result_array();
		//ppt模型 模版
		$template_ppt = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 4')->result_array();
		//图集模型 模版
		$template_images = $this->db->select('name,id,theme_id,module_id')->get_where('theme_file','theme_id = '.$on['on'].' AND module_id = 5')->result_array();
		foreach($this->site_language_admin as $ln => $v){
				$ln = strtolower($v);
				if(!is_dir(APPPATH.'language/'.$ln)){
					continue;
				}
				$path = APPPATH.'language/'.$ln.'/nav_lang.php';
				$lang_content = file_get_contents($path);
				if($lang_content){
					$lang_arr = explode("\r\n",$lang_content);
					if(is_array($lang_arr)){
						foreach($lang_arr as $lang_set){
							if(strpos($lang_set,'$lang') === false)
								continue;
							list($key,$val) = explode('=',$lang_set);
							$val = str_replace(array("'",';'),array('',''),$val);
							preg_match_all('/\[\'(.*)\'\]/',$key,$zjj);
							$k = $zjj[1][0];
							$that_k = 'nav_'.$id;
							if($k === $that_k){
								$result->lang[$v] = $val;
							}
						}
					}
				}
			}
		$this->_view ( 'column_edit', array (
				'info' => ! empty ( $info ) ? $info : array (),
				'select_categorys'=>$select_categorys,
				'module_info'=>$module_info,
				'template_pages' => !empty($template_pages)?$template_pages:array(),
				'template_lists' => !empty($template_lists)?$template_lists:array(),
				'template_ppt' => !empty($template_ppt)?$template_ppt:array(),
				'template_images' => !empty($template_images)?$template_images:array(),
				'language'=>!empty($language)?$language:array(),
				'result'=>!empty($result)?$result:array(),
		) );
	}
	/**
	 * 获取保存数据
	 */
	private function _save_data() {
		$time = time ();
		$return = array ();
		$data = $this->input->post ();
		if (! empty ( $data )) {
			foreach ( $data as $key => $value ) {
				if ($key == 'id' && empty ( $value )) {
					unset ( $data [$key] );
				}elseif($key=='lang'){
					$this->lang=$value;
					unset ( $data [$key] );
					continue;

				}
				$data [$key] = trim ( $value );
				if ($key == 'createtime') {
					$data ['createtime'] = strtotime ( $value );
					$data['createtime']=!empty($data ['createtime'])?$data ['createtime']:time();
				}
			}
		}
		
		$data ['lasttime'] = $time;
		$data ['adminid'] = $this->adminid;
		return $data;
	}
	/**
	 * 删除 关联表中数据也会删除
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		$is_sub_column=$this->column_model->is_sub_column_num($id);
		if($is_sub_column==1){
			ajaxReturn('','请先删除子栏目',0);
		}
		if ($id) {
			$is = $this->column_model->delete ( $id );
			if ($is === true) {
				$this->_cache();
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * 更新
	 */
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data = $this->_save_data ();
			if(!empty($id)&&!empty($this->lang)){
				foreach($this->lang as $ln => $v){
					$ln = strtolower($ln);
					$path=APPPATH.'language/'.$ln;
					if(!is_dir($path)){
						mk_dir($path);
					}
					$path = APPPATH.'language/'.$ln.'/nav_lang.php';
					if(file_exists($path)){
						$lang_content = file_get_contents($path);
						if($lang_content){
							$lang_arr = explode("\r\n",$lang_content);
							if(is_array($lang_arr)){
								$is = false;
								$str = "<?php";
								foreach($lang_arr as $lang_set){
									if(strpos($lang_set,'$lang') === false)
										continue;
									list($key,$val) = explode('=',$lang_set);
									preg_match_all('/\[\'(.*)\'\]/',$key,$zjj);
									$k = $zjj[1][0];
									$that_k = 'nav_'.$id;
									if($k === $that_k){
										$str .= "\r\n".trim($key).' = \''.trim($v)."';";
										$is = true;
									}else{
										$str .= "\r\n".trim($key).' = '.trim($val);
									}
								}
								if($is === false){
									$str .= "\r\n".'$'."lang ['nav_{$id}'] = '".trim($v)."';";
								}
								file_put_contents($path,$str);
							}
						}
					}else{
						$str = "<?php";
						$str .= "\r\n".'$'."lang ['nav_{$id}'] = '".trim($v)."';";
						file_put_contents($path,$str);
					}
					
				}
			}
			// 保存基本信息
			$this->column_model->save ( $id, $data );
			$this->_cache();
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 *排序
	 **/
	function orderby(){
		$data=$this->input->post();
		$this->column_model->update_orderby ( $data );
		ajaxReturn('','',1);
	}
	/**
	 * [_cache 生成栏目缓存]
	 * @return [type] [description]
	 */
	function _cache(){
			$data=$this->column_model->get_column_infos();
			$arr=array();
			foreach ($data as $k => $v) {
				$arr[$v['id']]=$v;
			}
		    CF ( 'nav_column', $arr, CONFIG_PATH );
	}
}