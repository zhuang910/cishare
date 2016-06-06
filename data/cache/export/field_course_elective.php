<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

/**
 * Created by CUCAS TEAM.
 * User: JunJie
 * E-Mail:zhangjunjie@cucas.cn
 * Date: 15-1-14
 * Time: 下午2:30
 */

return array(
	array('field'=>'course_elective.id','name'=>'序号'),
	array('field'=>'student_info.enname','name'=>'姓名（护照名）'),
	array('field'=>'student_info.chname','name'=>'中文姓名'),
	array('field'=>'squad.name as squadname','name'=>'班级'),
	array('field'=>'course.name','name'=>'选择课程'),
	);