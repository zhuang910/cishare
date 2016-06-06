<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * 后台首页
 * 
 * @author JJ
 *        
 */
class Squad extends Master_Basic {
	/**
	 * 基础类构造函数
	 */
	function __construct() {
		parent::__construct ();
		$this->view = 'master/major/';
		$this->load->model($this->view.'squad_model');
	}

	/**
	 * 后台主页
	 */
	function index() {
			$majorid=$this->input->get('id');
			
		if ($this->input->is_ajax_request () === true) {
			// 设置查询字段
			$fields = $this->_set_lists_field ();
			$majorid=$_GET ['id'];
			// 查询条件组合
			
			$condition = dateTable_where_order_limit ( $fields );
			$output ['sEcho'] = intval ( $_GET ['sEcho'] );
			$output ['iTotalRecords'] = $output ['iTotalDisplayRecords'] = $this->squad_model->count ( $condition,$majorid);
			$output ['aaData'] = $this->squad_model->get($fields,$condition,$majorid);
				//var_dump($output['aaData']);exit;	
			foreach ( $output ['aaData'] as $item ) {
				$item->classtime=date('Y-m-d',$item->classtime);
				$item->state = $this->_get_lists_state ( $item->state );
                $squ_count=$this->db->get_where('squad','majorid = '.$item->majorid.' AND nowterm = '.$item->nowterm)->result_array();
                $item->operation = '

					<a class="green" href="' . $this->zjjp .'squad'. '/edit?id=' . $item->id .'&majorid=' . $item->majorid .'&nowterm='.$item->nowterm.'"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
					<a href="javascript:;" onclick="del('.$item->id.')" class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>

					<a class="green" href="' . $this->zjjp .'squad'. '/add?majorid=' . $item->majorid .'&nowterm='.$item->nowterm.'"><i class="ace-icon ace-icon fa fa-plus center bigger-110 blue"></i></a>
					|';
                    if(!empty($squ_count)){
                        $item->operation .= '<a style="cursor:pointer;" href="javascript:;" onclick="show(this,'.$item->nowterm.','.$item->id.','.$item->majorid.')">展开</a>'.count($squ_count).'个班级';
                    }
					$item->nowterm='第'.$item->nowterm.'学期';
				$item->majorid=$this->squad_model->get_majorname($item->majorid);
					
			
			}
		
			exit ( json_encode ( $output ) );
		}
		$this->_view ('squad_index',array(
			'majorid'=>$majorid,
		));
	}
		function nowterm_squad(){
			$nowterm= intval ( $this->input->get ( 'nowterm' ) );
			$majorid= intval ( $this->input->get ( 'majorid' ) );
			$d= $this->squad_model->get_nowterm_squad($nowterm,$majorid);
			$data=array();
			foreach ($d as $key => $value) {
				$arr=array();
				foreach ($value as $k => $v) {
					if($k=='classtime'){

						$arr[$k]=date('Y-m-d',$v);
					}else{
						$arr[$k]=$v;
					}
				}
				$data[]=$arr;
			}

			if(count($data)>1){
				ajaxReturn($data,'',1);
			}else{
				ajaxReturn('','没有该数据',0);
			}

			
		}

	function add() {
		$majorid=$this->input->get('majorid');
		$nowterm=$this->input->get('nowterm')?$this->input->get('nowterm'):'';
		$mdata=$this->squad_model->get_majorinfo($majorid);
		$teacher_name = $this->squad_model->get_teacher_name();
		$this->_view ('squad_edit',array(
				'mdata' => $mdata,
				'nowterm'=>$nowterm,
				'majorid'=>$majorid,
				'teacher_name'=>$teacher_name
			));
	}
	
	/**
	 * 设置列表字段
	 */
	private function _set_lists_field() {
		return array (
			  'nowterm',
		  'id',
		  'majorid',
		  'name',
		  'englishname',
		
		  'classtime',
		  'spacing',
		  'maxuser',
		  'state',
				
		);
	}

		/**
	 * 获取文章状态
	 *
	 * @param string $statecode        	
	 * @param string $stateindexcode        	
	 * @return string
	 */
	private function _get_lists_state($statecode = null) {
		if ($statecode != null) {
			$statemsg = array (
					'<span class="label label-important">已结束</span>',
					'<span class="label label-success">未结束</span>' 
			);
			return $statemsg [$statecode];
		}
		return;
	}
	public function get_nowterm($id){
		$nowterm=$this->squad_model->get_major_nowterm($id);
		//var_dump($nowterm);exit;
		ajaxReturn ( $nowterm, '', 1 );
	}
	/**
	 * 插入
	 */
	function insert() {
		$data = $this->input->post();
		//var_dump($data);exit;
		if (! empty ( $data )) {
			
			$id = $this->squad_model->save ( null, $data );
			if ($id) {
				
				ajaxReturn ( 'back', '添加成功', 1 );
			}
		}
		ajaxReturn ( '', '添加失败', 0 );
	}
	/**
	 * [del 删除]
	 * @return [type] [description]
	 */
	function del() {
		$id = intval ( $this->input->get ( 'id' ) );
		
		if ($id) {
			
			$is = $this->squad_model->delete ( $id);
			if ($is === true) {
				$this->squad_model->delete_guanlian ( $id);
				ajaxReturn ( '', '删除成功', 1 );
			}
		}
		ajaxReturn ( '', '删除失败', 0 );
	}
	/**
	 * [edit 编辑]
	 * @return [type] [description]
	 */
	function edit(){
	

		$id=intval($this->input->get('id'));
		if($id){
			$where="id={$id}";
			$info=$this->squad_model->get_one($where);
			if(empty($info)){
				ajaxReturn('','该学院不存在',0);
			}
		}
		$nowterm=$this->input->get('nowterm')?$this->input->get('nowterm'):'';
		$majorid=$this->input->get('majorid');
		$teacher_name = $this->squad_model->get_teacher_name();
		$mdata=$this->squad_model->get_majorinfo($majorid);
		$this->_view ( 'squad_edit', array (
					'nowterm'=>$nowterm,
					'info' => $info ,
					'mdata' => $mdata,
					'majorid'=>$majorid,
					'teacher_name'=>$teacher_name
			) );
	}
	/**
	 * [update 更新]
	 * @return [type] [description]
	 */
	function update() {
		$id = intval ( $this->input->post ( 'id' ) );
		
		if ($id) {
			$data=$this->input->post();
			
			
			// 保存基本信息
			$this->squad_model->save ( $id, $data );
			ajaxReturn ( '', '更新成功', 1 );
		}
		ajaxReturn ( '', '更新失败', 0 );
	}
	
}