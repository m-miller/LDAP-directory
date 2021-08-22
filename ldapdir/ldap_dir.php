<?php


class ldapDir {
	
	protected static $instance = null;
	
	public function __construct() {
		add_action ( 'wp_enqueue_scripts',  array ( $this, 'add_ldapdir_css' ) );
		add_action ( 'admin_menu', array ( $this,  'ldapdir_Menu' ) );
		add_action ( 'pre_post_update', 'changePost' );
	} // end construct
	
	public function add_ldapdir_css() {
		wp_register_style ( 'ldapdir', plugins_url ( 'ldapdir.css', __FILE__ ) );
		wp_enqueue_style ( 'ldapdir' );
	}

	public function ldapdir_Menu() {
		add_options_page ( 'LDAP Directory Options', 'LDAP Directory Options', 'manage_options', 'ldap_dir', array ( $this,  'ldapdiroptions' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return   object    A single instance of this class.
	 */
	static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}


	public function ldapdiroptions() {
	?>
	<div class="wrap">
		<h2>LDAP Directory Shortcode</h2>
        <h3>List by manager (or managers) and job titles:</h3>
        <pre>[listbymgr mgr = "lan_id_of_manager" jobtitle = "title string" ]</pre>
		<ul><strong>Options:</strong> 
        	<li>Separate multiple managers with a comma</li>
        	<li>Optional: Filter by job title - use any word in job title, e.g., "patient" to filter  PCA's. Omit to show all reports.</li>
        	<li>Shows managers by default - use showmgr="false" to hide managers</li>
        	<li>Optional: Show Department name with dname = "name"</li>
            <li>Optional: change Department name header tags with title_tag="name of tag". Defualts to &lt;h4&gt;. Do not include &lt; or &gt; !</li>
        </ul>
        <h3>List by Department number:</h3>
        <pre>[listbydept dept ="dept_number"]</pre>
        <ul>Displays all members of a particular department
       	 	<li>Optional: pagers="no" removes pager links</li>
        </ul>
		<h3>List RN Pagers</h3>
        <pre>[rn_pagers mgr= "lan_id_of_manager" ]</pre>
        <ul><strong>Options:</strong> 
        	<li>Displays all RN pagers by manager(s)</li>
        	<li>Separate multiple managers with a comma</li>
			<li>Optional: cols=true to display output in two columns</li>
        </ul>
	</div>
	<?php
}



} // end class ldipDir






function display ( $arr ) {
	
	$photo = encodePhoto($arr);
	$outrbox = '<div class="outbox">';
	$qphotolink = '<div class="imgfr"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$arr['employeeID'][0].'"><img width="96" height="96" class="qimg" src="' . $photo. '" /></a></div>';															
	$namelink = '<div class="nmlink"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$arr['employeeID'][0].'">'.$arr['displayName'][0].'</a>';

	//$pagerlink = '<br /><a href="http://quarterly.mayo.edu/pager/page.cfm?per_id='.$arr['employeeID'][0].'&location=' . $arr['l'][0] . '&pager='.$arr['pager'][0].'&pentity='.$arr['l'][0].'">'.$arr['pager'][0].'</a>';
	$pagerlink = '<br /><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$arr['employeeID'][0].'&pagernumber='.$arr['pager'][0].'">'.$arr['pager'][0].'</a>';

	$closediv = '</div></div>';
	$ech .= $outrbox . $qphotolink . $namelink. $pagerlink . $closediv;
	return $ech;
}

function encodePhoto($arr) {
	$arr['thumbnailPhoto'][0] = 'data:image/jpg;base64,' . base64_encode ( $arr['thumbnailPhoto'][0] );
	return $arr['thumbnailPhoto'][0];
}



function displayList ( $workr ) {  	
	$outrbox = '<div class="outbox">';
	$qphotolink = '<div class="imgfr"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$workr['perID'].'"><img width="96" height="96" class="qimg" src="'.$workr['photo'].'" /></a></div>';	
	$namelink = '<div class="nmlink"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$workr['perID'].'">'.$workr['fullname'].'</a><br />'. $workr['title'];
	//$pagerlink = '<br /><a href="http://quarterly.mayo.edu/pager/page.cfm?per_id='.$workr['perID'].'&location=' . $workr['loc'] . '&pager='.$workr['pager'].'&pentity='.$workr['loc'].'">'.$workr['pager'].'</a>';
	$pagerlink = '<br /><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$workr['perID'].'&pagernumber='.$workr['pager'].'">'.$workr['pager'].'</a>';
	$closediv = '</div></div>';

	$ech = $outrbox . $qphotolink . $namelink .$pagerlink . $closediv ; 
	return $ech;
}



function showMgr ( $lanid ) {  
	// $c var (count) currently unused 
	$foto = encodePhoto($lanid);
	//$foto = $lanid['thumbnailPhoto'][0] = 'data:image/jpg;base64,' . base64_encode ( $lanid['thumbnailPhoto'][0] );
	$outrbox = '<div class="outbox" style="background: #e6e6e6;">';
	$qphotolink = '<div class="imgfr"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$lanid['employeeID'][0].'"><img width="96" height="96" class="qimg" src="'. $foto .'" /></a></div>';														
	$namelink = '<div class="nmlink"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$lanid['employeeID'][0].'">'.$lanid['displayName'][0].'</a><br />'. $lanid['title'][0];
	// $height = count of boxes mod 5 * height of box + 10px margin

		//$pagerlink = '<br /><a href="http://quarterly.mayo.edu/pager/page.cfm?per_id='.$lanid['employeeID'][0].'&location=' . $lanid['l'][0] . '&pager='.$lanid['pager'][0].'&pentity='.$lanid['l'][0].'">'.$lanid['pager'][0].'</a>';
		$pagerlink = '<br /><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$lanid['employeeID'][0].'&pagernumber='.$lanid['pager'][0].'">'.$lanid['pager'][0].'</a>';

		$closediv = '</div></div>';
	
	$ech = $outrbox . $qphotolink . $namelink  .$pagerlink . $closediv ; 
	return $ech;
	//var_dump($lanid);
}






  /*   connect and bind to ldap server
	*	 returns array array( 'ad' => $ad, 'dn' => $dn, 'usr' => $usr, 'pw' => $pw, 'bind' => $bind);
	*	 ad, dn, usr, pw ->string,  bind -> boolean
	*/
function connect_to_LDAP() {
		$dn = get_site_option ( "ldapServerOU" );
		$usr = get_site_option ( "ldapServerCN" );
		$pw = get_site_option ( "ldapServerPass" );
		$addr = get_site_option ( "ldapServerAddr" );
		$ad = ldap_connect ( $addr )
			or die ( "Connection error." );
		ldap_set_option ( $ad, LDAP_OPT_PROTOCOL_VERSION, 3 );
		ldap_set_option ( $ad, LDAP_OPT_REFERRALS, 0 );
		$bind = ldap_bind ( $ad, $usr, $pw );
		$ids = array( 'ad' => $ad, 'dn' => $dn, 'usr' => $usr, 'pw' => $pw, 'bind' => $bind);
	return $ids;
}

function rn_pagers ( $atts ) {
		extract ( shortcode_atts ( array (
				 'mgr'						=> '', 
				 'pager'					=> true,
				 'title'						=> 'RN',
				 'cols'						=> false
			), $atts ) );
	$w = array();	
	$r = array();
	$works=array();
	// get directReports for mgr, list name and pager for each in table, alpha by last name... derp.
	if ( $cols  ) {
		$colst =  '<div class="two-col col-right-margin">' ;
		$colmd =  '</div><div class="two-col">' ;
		$colend =  '</div><div style="clear: both; margin: 0; padding: 0; border: none; width: 95%;"></div>';
	}
		if ( !empty ( $title ) ) {
		$ttl = explode ( ',',  $title );
			// get rid of any whitespace around strings & convert to uppercase
			foreach ( $ttl as $index => $titl )  {
				$title = trim ( strtoupper ( $titl ) );											
			}
		}
			// more than one manager ...
		if  ( !empty ( $mgr ) ) {
			$sup = explode ( ',',  $mgr );
			$c = count ( $sup );
			foreach ( $sup as $index => $boss )  {
   				$sup[$index] = trim ( $boss );											
					// get rid of any whitespace around strings
			}
		}
	
		$ids = connect_to_LDAP();
				// if bind is true, continue
		if ( $ids['bind'] ) {
		foreach ( $sup as $mgrs ) {
			$SearchFor ="cn=".$mgrs;
			$result = ldap_search ( $ids['ad'],$ids['dn'], $SearchFor );
			$entry = ldap_first_entry ( $ids['ad'], $result );
				if ( $entry != false ) {
					$info = ldap_get_attributes ( $ids['ad'], $entry );	
				}
			

			foreach ( $info['directReports'] as $key => $reports ) { 						
					// get all direct reports for that supervisor...
				$comm  = stripos ( $reports, ',' );  													
					// find first comma   CN=Mxxxxxx,OU=Users,OU=MCR,DC=mfad,DC=mfroot,DC=org
				$eq = stripos ( $reports, '=' );   														
					// find first = 
				$lanid = substr ( $reports, $eq+1, ( ( $comm-1 ) - ( $eq ) ) ); 			
					//get lanid substring between = and comma... 
				$works[$key] = getLDAP ( $lanid, $ids['ad'], $ids['dn'], $ids['usr'], $ids['pw'] );	
					// $works is array of array of workers' info get it all...	
					// remove empty arrays and non RN title...
				if ( ( strpos ($works[$key]['title'], $title ) !== 0 ) or ( $works === NULL ) ) {
					unset ($works[$key]);
				}
				

			} // foreach $info
			if ( count ( $sup > 1  )   ) {
				$w = $w + $works;			// add 
				usort ( $works, 'comparename' );	
			}
		} //foreach $mgrs
	if ( count ( $sup ) > 1 ) {
					$works = array_unique(array_merge ( $w, $works ), SORT_REGULAR );																
						// merge arrays from each supervisor
					usort ( $works, 'comparename' );	
						// and sort...
	//echo count($works);
	if ( $cols   ) {
		$first = array_slice ( $works, 0, ceil ( count ( $works ) /2 ) );
		$ech = $colst;
		$ech .= '<table width="99%"><thead></thead><tbody>';
		foreach ( $first as $key => $nurse ) {
		
		$ech .= '<tr><td><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$nurse['perID'].'">'.$nurse['fullname'].'</a></td><td><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$nurse['perID'].'&pagernumber='.$nurse['pager'].'">'.$workr['pager'].'</a></td></tr>';

	}
	$ech .= '</tbody></table>';
		$ech .= $colmd;
		$ech .= '<table width="99%"><thead></thead><tbody>';
		$second = array_slice ( $works, ceil ( count ( $works ) / 2 ) , count ( $works ) );

		foreach ( $second as $key => $nurse ) {
		$ech .= '<tr><td><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$nurse['perID'].'">'.$nurse['fullname'].'</a></td><td><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$nurse['perID'].'&pagernumber='.$nurse['pager'].'">'.$workr['pager'].'</a></td></tr>';
		
		// http://javaprod.mayo.edu/ADR/MainController?pageid=240&addr_owner=MCR&pagername=127%20or%20(77)4-6576&rd=true
	// $pagerlink = '<br /><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$workr['perID'].'&pagernumber='.$workr['pager'].'">'.$workr['pager'].'</a>';
	}
		$ech .= '</tbody></table>';
		$ech .= $colend;
	} else { // only one column 
		$ech .= '<table width="99%"><thead></thead><tbody>';
		foreach ( $works as $key => $nurse ) {
		$ech .= '<tr><td><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id='.$nurse['perID'].'">'.$nurse['fullname'].'</a></td><td><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid='.$nurse['perID'].'&pagernumber='.$nurse['pager'].'">'.$workr['pager'].'</a></td></tr>';

	}
		$ech .= '</tbody></table>';
	}
	} // end if count $sup
	
	$ech .=  '<script>jQuery("tr:odd").css({"background-color": "aliceblue"});</script>';

		} // if $bind
	ldap_unbind();
	
	return $ech;
	
}
add_shortcode ( 'rn_pagers', 'rn_pagers' );







function listbymgr ( $atts ) {
	extract ( shortcode_atts ( array (
				 'mgr'						=> '', 
				 'jobtitle' 					=> true, 
				 'showmgr' 				=> true, 
				 'title_tag'				=> 'h4',
				 'dname' 					=> '',
				 'add'						=> '',
				 'pager'					=> true,
				 'photo'					=> true

				  
				  ), $atts ) );
	
	/*
		ldap_explode_dn -> get OU for work location (MCJ, MCR, etc.)
		$job_titles = array (
				 'RN' => 'RN 24/7 AD/DIP',
				 'RN' => 'RN 24/7 BSN',
				 'RN' => 'RN DAY BSN',
				 'RN' => 'RN DAY AD/DIP',
				 'RN' => 'RN DAY MSN/DNP',
				 'RN' => 'RN EXTENDED BSN',
				 'RN' => 'RN',
				 
				 'PCA' => 'PATIENT CARE ASSISTANT',
				 'SUM' => 'SUMMER III',
				 'HUC' => 'HEALTH UNIT COORDINATOR',
				 
				 // if rn - get all
				 // if day - get all day
				 // explode $works[$z]['title']
				 
				 if shortcode attr title is in array (exploded $job title...)  
			);			
	*/
	
	if ( !empty ( $jobtitle ) ) {
		$ttl = explode ( ',',  $jobtitle );
			// get rid of any whitespace around strings & convert to uppercase
		foreach ( $ttl as $index => $titl )  {
			$jobtitle = trim ( strtoupper ( $titl ) );											
		}
	}
	// connect, search and unbind
	//  return array to display function 
	
	$ids = connect_to_LDAP();
	// if bind is true, continue
		if ( $ids['bind'] ) {
			$w = array();	
			$r = array();
			$works = array();
			if  ( !empty ( $mgr ) ) {
				$sup = explode ( ',',  $mgr );
				$c = count ( $sup );
					foreach ( $sup as $index => $boss )  {
   						$sup[$index] = trim ( $boss );											
							// get rid of any whitespace around strings
					}
				if ( !empty ( $dname) ) {
						$before_title ='<'.$title_tag.'>';
						$after_title ='</'.$title_tag.'>';
						
				}
				if ( ( $showmgr == 'true' ) && ( !empty ( $dname ) ) ) {
					$mang = ' Manager';
					if ( $c > 1 )  $ess = 's';
					//echo $before_title . $dname . $mang . $ess . $after_title;
				} else {
					$mang = '';
					//echo $before_title . $dname . $after_title;
				}
				$ech = $before_title . $dname . $mang . $ess . $after_title;
				foreach ( $sup as $mgrs ) {
					$SearchFor ="cn=".$mgrs;
					/*
					$attz = array (  'cn', 'department', 'description', 'displayName', 'directReports', 'departmentNumber', 'employeeID', 'givenName' ,'l' ,
							 'mail','manager', 'pager','physicalDeliveryOfficeName' , 'sn','st', 'telephoneNumber' ,'thumbnailPhoto','title'
					);
					*/
					$attz = array ( "cn", "displayName", "directReports", "employeeID", "pager", "l", "thumbnailPhoto", "title"  );
					$result = ldap_search ( $ids['ad'],$ids['dn'], $SearchFor, $attz );
					
					$entry = ldap_first_entry ( $ids['ad'], $result );
					if ( $entry != false ) {
						$info = ldap_get_attributes ( $ids['ad'], $entry );
						// need to show manager here?
						if ( $showmgr == 'true' ) {
							$ech .= showMgr ( $info );
						}
					} //if
					foreach ( $info['directReports'] as $key => $reports ) { 						
						// get all direct reports for that supervisor...
						$comm  = stripos ( $reports, ',' );  													
							// find first comma   CN=Mxxxxxx,OU=Users,OU=MCR,DC=mfad,DC=mfroot,DC=org
						$eq = stripos ( $reports, '=' );   														
							// find first = 
						$lanid = substr ( $reports, $eq+1, ( ( $comm-1 ) - ( $eq ) ) ); 			
							//get lanid substring between = and comma... 
						$works[$key] = getLDAP ( $lanid, $ids['ad'], $ids['dn'], $ids['usr'], $ids['pw'] );	
							// $works is array of array of workers' info get it all...	
					} // foreach $info
					if ( count ( $sup ) > 1 ) {
						$w = $w + $works;			// add 
					}
				} //foreach $atts
				echo '<div style="clear: both;"></div>';   // break after showing managers....
				if ( count ( $sup ) > 1 ) {
					$r = array_unique(array_merge ( $w, $works ), SORT_REGULAR );																
						// merge arrays from each supervisor
					usort ( $r, 'comparename' );	
						// and sort...
				
						$ech .= $before_title. $dname. $after_title;
						foreach ( $r as $k => $worker ) {
							if ( !empty ( $jobtitle ) ) {
								$isin = strpos ( $r[$k]['title'], $jobtitle ); 
									// is jobtitle sting in the ldap title field?
									if ( ( $r[$k]['photo'] != null ) AND ( $isin !== false ) )  {	
										$ech .= displayList ( $worker );
									}
							} else {
								if ( $r[$k]['photo'] != null ) { 
									$ech .= displayList ( $worker );
								}
							}
						}	// foreach	

				} else {	   // only one manager 
					usort ( $works, 'comparename' );
					$ech .= $before_title. $dname. $after_title;
					foreach ( $works as $z => $wk ) {	
						if ( !empty ( $jobtitle ) ) {
							$isin = strpos ( $works[$z]['title'], $jobtitle );	
							// is jobtitle sting in the ldap title field?
							if  ( ( $works[$z]['photo'] != null ) AND ( $isin !== false ) ) {
								$ech .= displayList ( $wk );
							} 
						} else {
							if  ( $works[$z]['photo'] != null )  {
								$ech .= displayList ( $wk );
							}
						} 
					 }	// end  foreach
				}  //if ( count ( $sup ) > 1 ) 
			} // if ! empty $supervisors
		}  // end if $bind 
	ldap_unbind();
	return $ech;
} // end listbymgr

add_shortcode ( 'listbymgr', 'listbymgr' );



  /*
	*	  list by department number
	*
	*
	*/

/*
function listbydept ( $atts, $content = null ) {
	extract ( shortcode_atts ( array ( 'dept' => '' , 'add' => '' ,  'pager'	=> 'true',  ), $atts ) ) ;
		if ( !empty ( $dept ) ) {
			$d = trim ( $dept );  		
		}
	//var_dump($dept);
		$ids=connect_to_LDAP();
		if ( $ids['bind'] ) {
			if ( !empty ( $add ) ) {
				$xtras = cleantitles ( $add );
				// here, allow for more than one
				foreach ( $xtras as $n => $extra ) {
					$extra[$n] = getLDAP ( $add, $ids['ad'], $ids['dn'], $ids['usr'], $ids['pw'] );
				}
				//var_dump($extra);
			}
			$SearchFor ="departmentNumber=" . $d;
			$result = ldap_search ( $ids['ad'], $ids['dn'], $SearchFor );
			ldap_sort ( $ids['ad'], $result, 'sn' );
			$info = array();
			$count = 0;
			for ( $entry=ldap_first_entry ( $ids['ad'], $result ); $entry !=false; $entry=ldap_next_entry ( $ids['ad'], $entry ) ) {
				$info = ldap_get_attributes ( $ids['ad'], $entry );
				$flagged = substr ( $info['description'][0], 0, 8 );
					// flag if disabled...
				if ( in_array ( 'thumbnailPhoto', $info ) && strlen ( $info['thumbnailPhoto'][0] != null ) && $flagged !== 'DISABLED' ) {  

					$ech .= display($info);
				} // if in_array	
			} // for
		} // if bind... 
	ldap_unbind();
	return $ech;
}  // end listbydept...

add_shortcode ( 'listbydept', 'listbydept' );
*/



function listbydept ( $atts, $content = null ) {
	// todo: allow for multiple departments
	global $wpdb, $post;
	$postid = $post->ID;
	extract(shortcode_atts(array('dept' => '', 'add' => '', 'pager' => true), $atts));
	if (!empty ($dept)) {
		// move this into is_array below...
		$d = trim($dept);
	}
	$deptlist = get_transient( 'deptlist_'. $postid );
	if ( $deptlist === false ) {
		//  function multipledepts()
		//	$basequery = "$wpdb->get_results($wpdb->prepare("SELECT * FROM wp_person WHERE";
		//	$endquery = " ORDER BY lastname";
		// 	$addquery = " reporting_unit_code = %d";
		//	$orstr = " OR ";
		// 	if ( is_array($dept) ) {
		//  $ds = explode (',', $d);
		// 	foreach ( $ds as $k=> $dim ) {
		//		// build query for multiple departments
		//		if ( $k > 1 ) {
		//			$str .= $addquery;
		//			$endstr .= $ds[$k];
		//
		//		} elseif { $k < count($ds) ) {
		//			$ste .=
		//		} else {
		//			$str = $addquery;
		//			$endstr = $ds[$k];
		//	} else { // not array, single value
		//		$d = trim($dept);
		//	}
		// unset values
		//
		$query = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_person WHERE reporting_unit_code = %d ORDER BY lastname", $d), ARRAY_A);

		if (!empty($add)) {
			$xtras = cleantitles($add);
			// here, allow for more than one
			foreach ($xtras as $n => $extra) {
				$quer[$n] = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_person WHERE lanid = %s", $extra), ARRAY_A);
				$querz[] = $quer[$n][0];
			}
			$querd = array_merge($query, $querz);
			usort($querd, "comparelastname");
			$deptl = buildoutput($querd);
			set_transient('deptlist_' . $postid, $deptl, WEEK_IN_SECONDS);
			return $deptl;
		} else {
			$deptl = buildoutput($query);
			set_transient('deptlist_' . $postid, $deptl, WEEK_IN_SECONDS);
		}
	}
	return $deptlist;

}  // end listbydept...

add_shortcode ( 'listbydept', 'listbydept' );


function changePost( $post_ID ){
	global $post;
	delete_transient('deptlist_' . $post->ID);
}
function buildoutput ( $query ) {

	foreach ( $query as $k=> $qr ) {
		//var_dump($qr);
		// build the output, and write to transient
		$photo = checkPhoto($qr['perid']);

		$outrbox = '<div class="outbox">';

		$qphotolink = '<div class="imgfr"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id=' . $qr['perid'] . '"><img width="80" height="98" class="qimg" src="' . $photo . '" /></a></div>';

		$namelink = '<div class="nmlink"><a class="q_popup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id=' . $qr['perid'] . '">' . $qr['formatted_name'] . '</a>';

		$pagerlink = '<br /><a class="pgr_popup" href="http://javaprod/ADR/MainController?pageid=240&S=nursing&addr_owner=MCR&perid=' . $qr['perid'] . '&pagernumber=' . $qr['pager'] . '">' . $qr['pager'] . '</a>';

		$closediv = '</div></div>';

		$deptlist .= $outrbox . $qphotolink . $namelink . $pagerlink . $closediv;
	}

	return $deptlist;
}

function checkPhoto($perid) {
	$photolink = 'http://quarterly.mayo.edu/qtphotos/'.$perid.'.jpg';
	// if no photo in quarterly, use placeholder
	// 15757366 no photo
	$props  = @getimagesize($photolink);
	if ( empty($props[0]) )  {
		$photolink = PLUG_PATH . 'default_person.png';
	}
	//$isPhoto = '<a class="qtpopup" href="http://quarterly.mayo.edu/directory/person/person.htm?per_id=' . $perid .'"><img src="' .$photolink .'" /></a>';

	return $photolink;
}

function comparelastname( $a, $z ) {
	return strcmp ( strtolower($a['lastname']), strtolower($z['lastname']) );
}

function comparename( $a, $z ) { 
 	return strcmp ( $a['fullname'], $z['fullname'] );
}

function cmpnames ( $a, $z ) {
	return strcmp ( $a['sn'], $z['sn'] );
}

function cleantitles ($title) {
	$ttl = explode ( ',', $title );
	foreach ( $ttl as $idx => $the_title ) {
		$ttl[$idx] = trim ( $the_title );
	}
	return $ttl;
}

function getLDAP ( $lanid, $ad, $dn, $usr, $pw ) {
	$items = array (  'cn' => 'lanid', 
							 'department' => 'dept', 
							 'description' => 'description',
							 'displayName' => 'fullname',
							 'departmentNumber' => 'dept_num',
							 'employeeID' =>  'perID',
							 'givenName' => 'fname' ,
							 'l' => 'loc' ,
							 'mail' => 'email',
							 'manager' => 'manager',
							 'pager' => 'pager',
							 'physicalDeliveryOfficeName' => 'mail_loc' ,
							 'sn' => 'lname',
							 'st' => 'state',
							 'telephoneNumber' => 'phone' ,
							 'thumbnailPhoto' => 'photo',
							 'title' => 'title'
	);
	
	$res = ldap_search ( $ad,$dn,"cn=".$lanid );
	$ent = ldap_first_entry ( $ad, $res );
	if ( $ent != false ) {
		$inf = ldap_get_attributes ( $ad, $ent );
			// check if removed from quarterly ( done bad things?? )
		if ( substr ( $inf['description'][0], 0, 8 ) == 'DISABLED' ) {
			$inf = ldap_next_attribute ( $ad, $res ) ;
		}
	}
	$item = array();
	foreach ( $items as $key => $needed )   {
		$item[$needed ] = $inf[$key][0] ;
		if ( isset ( $inf ) ) {
			if ( in_array ( 'thumbnailPhoto', $inf )  &&  ( $item['photo'] != '' ) )  {  
				$item['photo'] = 'data:image/jpg;base64,' . base64_encode ( $inf['thumbnailPhoto'][0] );
			} // if in_array
		}
				/* 	photo display: 
							wrap in div to 'crop' left & right white margins...
							for whatever size - native is 96px x 96px
							<div style="float: left; width:20px; height: 20px; overflow: hidden; position: relative; border: 1px solid #999; margin: 4px 5px 0 5px;">  
							<img width="25" height="25" style="position: absolute; left: -3px; top: -1px;" src="data:image/jpg;base64,'.$var['photo'].'" /></div>
				*/
	} // foreach
	return $item; // returns array with keys above
} 