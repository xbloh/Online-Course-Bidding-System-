<?php
class sort {
	function title($a, $b)
	{
	    return strcmp($a->title,$b->title);
	}


	function bootstrap($a, $b)
	{
		return strcmp($a,$b);
	}
	

	function sort_it($list,$sorttype)
	{
		usort($list,array($this,$sorttype));
		return $list;
	}
}

?>