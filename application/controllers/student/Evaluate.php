<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}
/**
 * 前台 学生 控制器
 *
 * @author zyj
 *        
 */
class Evaluate extends Student_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
        // 判断一下是否是 在学学生
        $is_student = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array();

        if(empty($is_student)){
            echo '<script>window.location.href="/'.$this->puri.'/student/index"</script>';
        }
		$this->load->model ( 'student/evaluate_model' );
	}
	
	/**
	 * 主页
	 */
	function index($year=null) {
		if($year==null){
			$year=date('Y',time());
		}
		//获取该学生当年的评教信息
		$userid=$_SESSION['student']['userinfo']['id'];
		$eav_info=$this->evaluate_model->get_stu_year_eav($userid,$year,$this->puri);
		//获取这些题的总分数
		foreach ($eav_info as $k => $v) {
			//该学生大题的总分数
			$all_score=$this->evaluate_model->get_eav_all_score($v['item'],$this->puri);
			//查出该学生答题的分数
			$stu_score=$this->evaluate_model->get_student_all_score($v['item']);
            //查出小题的信息
            $item_info=$this->evaluate_model->get_student_item_info($v['item']);
			//算出学生的非常好 好 一般 差
			$eav_info[$k]['grade']=$this->evaluate_model->get_score_grade($all_score,$stu_score);
			//查出文本的题
			$class_text=$this->evaluate_model->get_class_text($v['item']);
			$eav_info[$k]['text']=$class_text;
            $eav_info[$k]['item_info']=$item_info;
		}
		$this->load->view ( 'student/student_evaluate_index',array(
				'eav_info'=>$eav_info,
				'year'=>$year
			) );
	}

}

