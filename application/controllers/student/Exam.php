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
class Exam extends Student_Basic {
	
	/**
	 * 构造函数
	 */
	function __construct() {
		parent::__construct ();
		is_studentlogin ();
		// 判断一下是否是 在学学生
		$is_student = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		
		if (empty ( $is_student )) {
			echo '<script>window.location.href="/' . $this->puri . '/student/index"</script>';
		}
		$this->load->model ( 'student/exam_model' );
	}
	
	/**
	 * 试卷列表页
	 */
	function index() {
		// 查询所有的可以用的试卷 1 全部 2 专业 学位 能对的上的
		$student_zx = $this->db->select ( '*' )->get_where ( 'student', 'state = 1 AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->row ();
		// 查询专业的 学位
		$major = $this->db->select ( 'degree' )->get_where ( 'major', 'id = ' . $student_zx->majorid )->row ();
		$result1 = $this->exam_model->get_paper_info ( 'state = 1 AND scope_all = 1' );
		
		$result2 = $this->exam_model->get_paper_info ( 'state = 1 AND scope_all = 0 AND degreeid = ' . $major->degree . ' AND majorid = ' . $student_zx->majorid );
		
		// 查询 我参加的测试
		$data = $this->db->select ( '*' )->get_where ( 'examination_info', 'userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] )->result_array ();
		if (! empty ( $data )) {
			foreach ( $data as $k => $v ) {
				$join_info [] = $v ['paperid'];
			}
		}
		$this->load->view ( 'student/exam_index', array (
				'result1' => ! empty ( $result1 ) ? $result1 : array (),
				'result2' => ! empty ( $result2 ) ? $result2 : array (),
				'join_info' => ! empty ( $join_info ) ? $join_info : array () 
		) );
	}
	
	/**
	 * 开始测试
	 */
	function dostart() {
		$id = intval ( trim ( $this->input->get ( 'id' ) ) );
		if ($id) {
			$flag_true = $this->exam_model->get_one_answer ( 'paperid = ' . $id . ' AND userid = ' . $_SESSION ['student'] ['userinfo'] ['id'] );
			
			// 根据试卷 id 去 查询答题
			$question_big = $this->exam_model->get_question_big ( 'state = 1 AND paperid = ' . $id );
			
			// 跟均大题id 去查询 小题的信息
			if (! empty ( $question_big )) {
				foreach ( $question_big as $k => $v ) {
					$ids [] = $v ['id'];
				}
				
				// 查询小题
				$question_small_all = $this->exam_model->get_question_small ( 'state = 1 AND groupid IN (' . implode ( ',', $ids ) . ')' );
				if (! empty ( $question_small_all )) {
					foreach ( $question_big as $key => $val ) {
						foreach ( $question_small_all as $keys => $vals ) {
							if ($vals ['groupid'] == $val ['id']) {
								$question_big [$key] ['childs'] [] = $vals;
							}
						}
					}
				}
			}
			
			if (! empty ( $flag_true )) {
				$answerAll = ! empty ( $flag_true ['answer'] ) ? ( array ) json_decode ( $flag_true ['answer'] ) : array ();
				$answer = array ();
				foreach ( $answerAll as $zk => $zv ) {
					$arr = explode ( '_', $zk );
					$answer [$arr [1]] = $zv;
				}
				
				$this->load->view ( 'student/exam_dostart_true', array (
						'question' => ! empty ( $question_big ) ? $question_big : array (),
						'pageid' => $id,
						'flag_true' => $flag_true,
						'answer' => $answer 
				) );
			} else {
				$this->load->view ( 'student/exam_dostart', array (
						'question' => ! empty ( $question_big ) ? $question_big : array (),
						'pageid' => $id,
						'time' => time () 
				) );
			}
		}
	}
	
	/**
	 * 提交测试
	 */
	function dosave() {
		$data = $this->input->post ();
		$pageid = ! empty ( $data ['pageid'] ) ? $data ['pageid'] : '';
		$time = ! empty ( $data ['time'] ) ? $data ['time'] : '';
		$used_time = ! empty ( $data ['used_time'] ) ? $data ['used_time'] : '';
		unset ( $data ['used_time'] );
		unset ( $data ['pageid'] );
		unset ( $data ['time'] );
		if ($pageid && ! empty ( $data )) {
			$answer = $data;
			$dataA = array (
					'paperid' => $pageid,
					'userid' => $_SESSION ['student'] ['userinfo'] ['id'],
					'submittime' => time (),
					'answer' => json_encode ( $data ),
					'time' => $time,
					'used_time' => ! empty ( $used_time ) ? $used_time : 0 
			);
			
			$flag = $this->exam_model->save_answer ( $dataA );
			if ($flag) {
				$score = $this->_set_score ( $pageid, $answer );
				$this->exam_model->update_answer ( 'id = ' . $flag, array (
						'score' => ! empty ( $score ['score'] ) ? $score ['score'] : 0,
						'finish_state' => ! empty ( $score ['finish_state'] ) ? $score ['finish_state'] : 0 
				) );
				ajaxReturn ( $score, '', 1 );
			} else {
				ajaxReturn ( '', lang ( 'exam_save_error' ), 0 );
			}
		} else {
			ajaxReturn ( '', lang ( 'exam_save_error' ), 0 );
		}
	}
	
	/**
	 * 打分
	 */
	function _set_score($pageid = null, $answer = null) {
		$score = array ();
		$scoreAll = 0;
		$finish_state = 0;
		if ($pageid != null && $answer != null) {
			// 通过s试卷id 去查询 所有的 题号 和 答案
			// 根据试卷 id 去 查询大题
			$question_big = $this->exam_model->get_question_big ( 'state = 1 AND paperid = ' . $pageid );
			
			// 跟均大题id 去查询 小题的信息
			if (! empty ( $question_big )) {
				foreach ( $question_big as $k => $v ) {
					$ids [] = $v ['id'];
				}
				
				// 查询小题
				$question_small_all = $this->exam_model->get_question_small ( 'state = 1 AND groupid IN (' . implode ( ',', $ids ) . ')' );
				if (! empty ( $question_small_all )) {
					
					foreach ( $question_small_all as $keys => $vals ) {
						if ($vals ['topic_type'] == 2) {
							$right_answer [$vals ['topic_type'] . '_' . $vals ['id']] = json_decode ( $vals ['more_correct_answer'] );
						} else {
							$right_answer [$vals ['topic_type'] . '_' . $vals ['id']] = $vals ['one_correct_answer'];
						}
						
						$right_answer_score [$vals ['topic_type'] . '_' . $vals ['id']] = $vals ['score'];
					}
					
					foreach ( $answer as $ak => $av ) {
						$ak_type = explode ( '_', $ak );
						
						if ($ak_type [0] == 1) {
							// 单选
							if ($av == $right_answer [$ak]) {
								$scoreAll += $right_answer_score [$ak];
							}
						} else if ($ak_type [0] == 2) {
							// 多选 判断 两个数组的茶集
							$ak_diff = array_diff ( $av, $right_answer [$ak] );
							if (empty ( $ak_diff )) {
								$scoreAll += $right_answer_score [$ak];
							}
						}
					}
					
					// 计算百分比
					if (count ( $right_answer ) == count ( $answer )) {
						$finish_state = '100%';
					} else {
						$finish_state = sprintf ( '%.2f', (count ( $answer ) / count ( $right_answer )) * 100 ) . '%';
					}
				}
			}
		}
		return $score = array (
				'score' => $scoreAll,
				'finish_state' => $finish_state 
		);
	}
}

