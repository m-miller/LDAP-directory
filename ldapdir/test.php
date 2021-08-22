<?php


//  function multipledepts()

	$dept = "12345";
	$basequery = "SELECT * FROM wp_person WHERE";
	$endquery = " ORDER BY lastname";
 	$addquery = " reporting_unit_code = %d";
	$orstr = " OR ";
	if ( is_array($dept) ) {
  		$ds = explode (',', $dept);
 		foreach ( $ds as $k=> $dim ) {
			// build query for multiple departments
			if ($k > 1) {
				$str .= $addquery;
				$endstr .= $ds[$k];

			} elseif ($k < count($ds)) {
				$str .= $addquery . $orstr;
			} else {
				$str = $addquery;
				$endstr = $ds[$k];
			}
		} else { // not array, single value
		$d = trim($dept);
		$str = $basequery . $addquery . $endquery;
	}
		echo $str;
// unset values
//