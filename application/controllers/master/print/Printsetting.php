<?php
defined('BASEPATH') or exit ('No direct script access allowed');

/**
 * 通知邮件配置
 *
 * @author JJ
 *
 */
class Printsetting extends Master_Basic
{
	protected $programaids_course = array ();
	public $programaid_parent = 0;
	/**
	 * 基础类构造函数
	 */
	function __construct()
	{
		parent::__construct();
		$this->view = 'master/print/';
		$this->load->model($this->view . 'printsetting_model');
		$this->load->library('upload');
	
		
	}

	/**
	 * 配置主页
	 */
	function index()
	{	
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
				
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->printsetting_model->count ( $condition );
			$output ['aaData'] = $this->printsetting_model->get ( $fields, $condition );
			foreach ( $output ['aaData'] as $item ) {
				$item->operation = '
						<a class="btn btn-xs btn-info" href="' . $this->zjjp .'printsetting'. '/template_manage?id=' . $item->id .'" title="模板管理">模板管理</a>
						<a class="btn btn-xs btn-info btn-white" href="/master/print/printsetting/fieldset?print_templateid='.$item->id.'" title="编辑字段">编辑字段</a>
				';
				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				*/
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('printsetting_index');		
	}
	
	function add($parentid) {
		$this->_view ('printsetting_edit',array(
				'parentid'=>$parentid,
			));
	}

	function edit(){

		$id=intval($this->input->get('id'));
		if($id){
			$where="id={$id}";
			$info=$this->printsetting_model->get_one($where);
			if(empty($info)){
				ajaxReturn('','该学院不存在',0);
			}
		}
		$this->_view ( 'printsetting_edit', array (
					'info' => $info ,
			) );
	}


	/**
	 * 更新
	 */
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		if ($id) {
			$data=$this->input->post();
			
			
			// 保存基本信息
			$this->printsetting_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	/**
	 * 插入
	 */
	function insert() {
		
		$data = $this->input->post();
		//var_dump($data);exit;
		if (! empty ( $data )) {
			
			$id = $this->printsetting_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	/**
	 * 编辑模板页
	 */
	function template(){

		$id=intval($this->input->get('id'));
		$parentid=intval($this->input->get('parentid'));
		$fields=$this->printsetting_model->get_template_fields($parentid);
		$info =$this->printsetting_model->get_template_info($id);
		$width=$this->get_px($info['dpi'],$info['width']);
		$height=$this->get_px($info['dpi'],$info['height']);
		$this->_view ('template_add',array(
			'id'=>$id,
			'info'=>$info,
			'fields'=>$fields,
			'width'=>$width,
			'height'=>$height
			));
	}
	//获取像素值
	function get_px($dpi=null,$num){
		if($dpi!=null){
			$x=0.353*$dpi/72;
			$px=$num/$x;
			return $px;
		}
		return 0;
	}
	/**
	 * 编辑模板
	 */
	function settemplate(){
		$data=$this->input->post();
		$act = $this->input->post('act');
		if($act === 'print_upload'){
			$id = (int) $this->input->post('id');
			$info =$this->printsetting_model->get_template_info($id);
			$base_path = '/uploads/ptint_template/' . date ( 'Ym' ) . '/' . date ( 'd' );
			$base_file_name = $id.time().'_temp.jpg';
			$str=$_SERVER['DOCUMENT_ROOT'].$base_path;
			if(!is_dir($str)){
				mk_dir($str);
			}
			$srcSize = getimagesize($_FILES['bg']['tmp_name']);
			$imgpath=$str.'/'.$base_file_name;
			$base_path .= '/'.$base_file_name;
			$cut_w=$this->get_px($info['dpi'],$info['cut_width']);
			$cut_h=$this->get_px($info['dpi'],$info['cut_height']);
			$width=$this->get_px($info['dpi'],$info['width']);
			$height=$this->get_px($info['dpi'],$info['height']);
			$width=$width*$info['multiple'];
			$height=$height*$info['multiple'];
			// $width=$srcSize[0];
			// $height=$srcSize[1];
			$thum_width=$width-($cut_w*2);
			$thum_height=$height-($cut_h*2);;
			 
			        
			        /* 创建真彩色的临时文件,指定大小 */
			        // $tmpImage = imagecreatetruecolor($width,$height);
			        $tmpImage = imagecreatetruecolor($width,$height);

			        // 原图片的资源
			        $srcImageSource = imagecreatefromjpeg($_FILES['bg']['tmp_name']);
			        // 重新采样拷贝图像并调整大小
			        imagecopyresampled( $tmpImage,$srcImageSource,0,0,$cut_w,$cut_h,
			            $width,$height,$srcSize[0]-($cut_w*2),$srcSize[1]-($cut_h*2));
			        /* 生成 JPEG 格式的新文件,存放在test目录下 */
			        // 以时间戳作为文件名

			       // $dstPath = realpath(".").$imgpath;

			        if( imagejpeg($tmpImage,$imgpath) )
			        {
			            /* 最后删除原文件 */
			             unlink($_FILES['bg']['tmp_name']);
			        }
			       // $imgpath=trim($imgpath,'.');
				if($this->printsetting_model->save_config_lableimg($id,$base_path)){
					echo '<script language="javascript">';
					echo 'parent.call_flash("bg_add", "' . $base_path . '");';
					echo '</script>';
				}else{
					return '插入图片失败';
				}
			
			
		}

		$config_lable=$data['config_lable'];
		if(!empty($data['config_lable'])){
			$this->printsetting_model->save_config_lable($data['id'],$config_lable);
			 return header("Location:".$this->zjjp."printsetting"); 
		}else{

			return "插入失败";
		}
			
	}

	function delimg(){
		$id=$this->input->get('id');
		$this->printsetting_model->update_img($id);
		echo json_encode(array(
			'error' => 0,
		    'message' => '',
		    'content' => 3
		));
	}
	/**
	 *删除模板
	 **/
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->printsetting_model->delete ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 *
	 *设置模板字段
	 **/
	function fieldset(){
		$print_templateid=$this->input->get('print_templateid');
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_fields ();
			$print_templateid=$this->input->get('print_templateid');
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->printsetting_model->count_fields ( $condition ,$print_templateid);
			$output ['aaData'] = $this->printsetting_model->get_fields ( $fields, $condition ,$print_templateid);
			foreach ( $output ['aaData'] as $item ) {
				$item->print_templateid=$this->printsetting_model->get_print_template($item->print_templateid);
				$item->operation = '
					<a class="green" href="javascript:;" onclick="pub_alert_html(\'' . $this->zjjp . 'printsetting/fieldsadd?id=' . $item->id . '&s=1&print_templateid='.$print_templateid.'\')" title="编辑"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a title="删除" href="javascript:;" onclick="del_fields(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';

				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				*/
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}

		$this->_view ('fieldset_index',array(
				'print_templateid'=>$print_templateid
			));	
	}
	
	/**
	 *
	 *弹出添加字段页面
	 **/
	function fieldsadd() {
		$print_templateid=$this->input->get('print_templateid');
		$id=$this->input->get('id');
		if(!empty($id)){
			$info=$this->db->get_where('print_fields','id = '.$id)->row();
		}
		$html = $this->_view ( 'fieldsset', array (
				'print_templateid'=>!empty($print_templateid)?$print_templateid:0,
				'id'=>!empty($id)?$id:0,
				'fields_info'=>!empty($info)?$info:array()
			), true );
		ajaxReturn ( $html, '', 1 );
	}
	/**
	 * 字段插入
	 */
	function fieldsinsert() {
		
		$data = $this->input->post();
		if(!empty($data['id'])){
			$id=$data['id'];
			unset($data['id']);
		}
		if (! empty ( $data )) {
			if(!empty($id)){
				$this->printsetting_model->save_fields ( $id, $data );
				ajaxReturn ( '', '添加成功', 1 );
			}else{
				$id = $this->printsetting_model->save_fields ( null, $data );
				if ($id) {
					ajaxReturn ( '', '添加成功', 1 );
				}
			}
			
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	/**
	 *删除字段
	 **/
	function del_fields() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			$where = "id = {$id}";
			$is = $this->printsetting_model->delete_fields ( $where );
			if ($is === true) {
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 *
	 *模板管理
	 **/
	function template_manage(){
		$id=$this->input->get('id');
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			$parentid=$this->input->get('parentid');
			// 查询条件组合
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->printsetting_model->count_template_manage ( $condition ,$parentid);
			$output ['aaData'] = $this->printsetting_model->get_template_manage ( $fields, $condition ,$parentid);
			foreach ( $output ['aaData'] as $item ) {
				$item->operation = '
					<a class="green" href="' . $this->zjjp .'printsetting'. '/edit?id=' . $item->id .'" title="编辑"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a class="green" href="' . $this->zjjp .'printsetting'. '/set_template?parentid='.$item->parentid.'&id=' . $item->id .'" title="设置打印模板"><i class="ace-icon fa  fa-cog bigger-130"></i></a>
					<a title="删除" href="javascript:;" onclick="del(' . $item->id . ')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
				';

				/*
				 * $item->operation = ' <a title="查看" class="btn btn-small btn-success" href="javascript:pub_alert_html(\'' . $this->zjjp . '/edit?id=' . $item->id . '\',true,true);"><i class="icon-edit"></i></a> <a title="审核" class="btn btn-small btn-success" href="javascript:pub_alert_confirm(this,\'确定要修改吗？\',\'' . $this->zjjp . '/editstate?id=' . $item->id . '\');"><i class="icon-remove"></i></a> ';
				*/
			}
			//var_dump($output);die;	
			exit ( json_encode ( $output ) );
		}
		$this->_view ('template_manage_index',array(
				'id'=>$id,
			));	
	}
	

	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
				'id',
				'name',
				'parentid'
		);
	}
	/**
	 * 设置字段表字段
	 */
	private function _set_lists_fields() {
		return array (
				'id',
				'name',
				'fieldsvalue',
				'print_templateid'
		);
	}
	/**
	 * [set_template 设置打印模板]
	 */
	function set_template(){
		$id=$this->input->get('id');
		$parentid=$this->input->get('parentid');
		if(!empty($id)&&!empty($parentid)){
			$info=$this->db->get_where('print_template','id = '.$id)->row_array();
			//字段信息
			$fields_info=$this->db->get_where('print_fields','print_templateid = '.$parentid)->result_array();
			$this->_view('test_temp',array(
				'id'=>$id,
				'fields_info'=>!empty($fields_info)?$fields_info:array(),
				'parentid'=>$parentid,
				'info'=>$info
				));
		}
	}
	/**
	 * [test_temp 页面]
	 * @return [type] [description]
	 */
	function test_temp(){
		$this->_view ('test_temp');	
	}
	/**
	 * [save_template 保存模板]
	 * @return [type] [description]
	 */
	function save_template(){
		$id=$this->input->get('id');
		$data=$this->input->post();
		if(!empty($data['content'])){
			$fields_str='';
			$weizhi_fields=array();
			$lodop_content=explode(';', $data['content']);
			foreach ($lodop_content as $k => $v) {
				//处理字段
				if(strstr($v,'ADD_PRINT_TEXTA')){
					$sf=strpos( $v,'(');
					$ef=strrpos($v,')');
					$file_name=substr($v, $sf+1,$ef-$sf-1);
					$fields_arr=explode(',', $file_name);
					$fields_str.=trim($fields_arr[0],'"').',';
					$weizhi_fields[trim($fields_arr[0],'"')]=array('x'=>$fields_arr[1],'y'=>$fields_arr[2]);
				}
				// exit;
				//处理内容

				if(strstr($v,'ADD_PRINT_SETUP_BKIMG')){

					$base_path = '/uploads/ptint_template/' . date ( 'Ym' ) . '/' . date ( 'd' );
					$base_file_name =  build_order_no ().'_temp.jpg';
					$str=$_SERVER['DOCUMENT_ROOT'].$base_path;
					if(!is_dir($str)){
						mk_dir($str);
					}
					$webimgpath=$base_path.'/'.$base_file_name;
					$imgpath=$str.'/'.$base_file_name;
					//获取上传的本地图片
					$s=strpos( $v,'"');
					$e=strrpos($v,'"');
					$loca_img_path=substr($v, $s+1,$e-$s-1);
					$data_img='';
					if(!strstr($loca_img_path,'<img')){

						//组合本地地址
						$loca_img_path_arr=explode("\\", $loca_img_path);
						if(!empty($loca_img_path_arr)){
							$zhen_loca_img_path='';
							foreach ($loca_img_path_arr as $key => $value) {
								$zhen_loca_img_path.=$value.'/';
							}
							$filepath =  mb_convert_encoding(trim($zhen_loca_img_path,'/'), 'gbk', 'utf-8');
							// $filepath=trim($zhen_loca_img_path,'/');
							
							$data_img=$webimgpath;
							//替换字符串
							$webimgpath="<img border='0' src='".$webimgpath."'>";
							
							
						}
					}
					
				}
			}
			//更新数据库
			$update=array(
				'lodop_content'=>$data['content'],
				'fields'=>trim($fields_str,','),
				'seat_fields'=>json_encode($weizhi_fields)
				);
			$this->db->update('print_template',$update,'id = '.$id);
			$loca_img_path=!empty($loca_img_path)?$loca_img_path:0;
			
			
				
				if(strchr($loca_img_path,'/uploads/ptint_template')){
					$loca_img_path=0;
				}
			ajaxReturn($loca_img_path,'',1);
		}
		ajaxReturn('','',0);
	}

	
	/**
	 * [shangchuan description]
	 * @return [type] [description]
	 */
	function shangchuan(){
		$imgdata=$this->input->post('imageBuffer');
		$id=$this->input->post('id');
		//开始更新数据库
		$info=$this->db->get_where('print_template','id = '.$id)->row_array();
		$imgdata = base64_decode($imgdata);
		//生成文件夹
		$base_path = '/uploads/ptint_template/' . date ( 'Ym' ) . '/' . date ( 'd' );
		$base_file_name =  build_order_no ().'_temp.jpg';
		$str=$_SERVER['DOCUMENT_ROOT'].$base_path;
		if(!is_dir($str)){
			mk_dir($str);
		}
		$webimgpath=$base_path.'/'.$base_file_name;


		$save_file_path = $str.'/'.$base_file_name;
		$this->load->helper('file');
		write_file($save_file_path, $imgdata);

		
		// var_dump($info['lodop_content']);exit;
		
		$lodop_content=explode(';', $info['lodop_content']);
		foreach ($lodop_content as $k => $v) {
			if(strstr($v,'ADD_PRINT_SETUP_BKIMG')){
				$updata['lodop_content']=str_replace($v,"LODOP.ADD_PRINT_SETUP_BKIMG(\"<img border='0' src='".$webimgpath."'>\")",$info['lodop_content']);
			
			}
		}
		$updata['img']=$webimgpath;
		$this->db->update('print_template',$updata,'id = '.$id);
	}
}