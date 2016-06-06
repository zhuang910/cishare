<?php
/**
 * 无限极分类
 * @author junjiezhang
 *
 */
class Tree {
	public $arr = array ();
	public $icon = array (
			'│',
			'├',
			'└' 
	);
	public $nbsp = "&nbsp;";
	public $ret = '';
	public $level = 0;
	public $pid = '';
	
	/**
	 * 构造函数
	 *
	 * @param unknown $arr        	
	 * @param string $pid        	
	 * @return boolean
	 */
	public function __construct($arr = array(), $pid = 'parent_id') {
		$this->arr = $arr;
		$this->ret = '';
		$this->pid = $pid;
		return is_array ( $arr );
	}
	
	/**
	 * 获取子项
	 * 
	 * @param unknown $bid        	
	 * @return Ambigous <boolean, multitype:multitype: >
	 */
	public function getchild($bid) {
		$a = $newarr = array ();
		if (is_array ( $this->arr )) {
			foreach ( $this->arr as $id => $a ) {
				if ($a [$this->pid] == $bid)
					$newarr [$id] = $a;
			}
		}
		return $newarr ? $newarr : false;
	}
	
	/**
	 * 组建
	 * 
	 * @param unknown $bid        	
	 * @param unknown $str        	
	 * @param number $sid        	
	 * @param string $adds        	
	 * @param string $strgroup        	
	 * @return string
	 */
	function get_tree($bid, $str, $sid = 0, $adds = '', $strgroup = '') {
		$number = 1;
		$child = $this->getchild ( $bid );
		if (is_array ( $child )) {
			$total = count ( $child );
			foreach ( $child as $id => $a ) {
				$j = $k = '';
				if ($number == $total) {
					$j .= $this->icon [2];
				} else {
					$j .= $this->icon [1];
					$k = $adds ? $this->icon [0] : '';
				}
				$spacer = $adds ? $adds . $j : '';
				@extract ( $a );
				if (empty ( $a ['selected'] )) {
					$selected = $id == $sid ? 'selected' : '';
				}
				@$parentid == 0 && $strgroup ? eval ( "\$newstr = \"$strgroup\";" ) : eval ( "\$newstr = \"$str\";" );
				$this->ret .= $newstr;
				$nbsp = $this->nbsp;
				$this->get_tree ( $id, $str, $sid, $adds . $k . $nbsp, $strgroup );
				$number ++;
			}
		}
		return $this->ret;
	}
}
?>