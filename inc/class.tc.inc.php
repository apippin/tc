<?php
  /**************************************************************************\
  * Application: phpGroupWare - 3rd Counselor                                *
  * Framework: http://www.phpgroupware.org                                   *
  * Author: Alan J. Pippin (apippin@pippins.net)                             *
  * -----------------------------------------------                          *
  *  This program is free software; you can redistribute it and/or modify it *
  *  under the terms of the GNU General Public License as published by the   *
  *  Free Software Foundation; either version 2 of the License, or (at your  *
  *  option) any later version.                                              *
  \**************************************************************************/
      /* $Id: class.tc.inc.php,v 1.1.1.1 2005/07/20 07:40:32 ajp Exp $ */

class tc 
{
	var $db;
	var $db2;
	var $t;
	var $nextmatchs;
	var $grants;
	var $jscal;
	var $cal_options;  
	var $default_ht_num_months;
	var $default_ppi_num_months;
	var $default_ppi_num_years;
	var $default_int_num_quarters;
	var $default_int_num_years;
	var $default_vis_num_years;
	var $default_att_num_quarters;
	var $max_num_districts;
	var $current_year;
	var $current_month;
	var $upload_target_path;
	var $script_path;
	var $max_appointments;
	var $max_presidency_members;

	var $public_functions = array
	(
		'ht_view'    => True,
		'ht_update'  => True,
		'act_list'	  => True,
		'act_view'   => True,
		'act_update' => True,
		'par_view'   => True,
		'ppi_view'   => True,
		'ppi_update' => True,
		'ppi_sched'  => True,
		'int_view'   => True,
		'int_update' => True,
		'int_sched'  => True,
		'vis_sched'  => True,
		'vis_view'   => True,
		'vis_update' => True,
		'att_view'   => True,
		'att_update' => True,
		'dir_view'   => True,
		'org_view'   => True,
		'schedule'   => True,
		'admin'      => True,
		'email'      => True,
		'email_appt' => True,
		'willing_view'   => True,
		'willing_update' => True,
		'send_ical_appt' => True,
		'assign_view'    => True,
		'assign_update'  => True,
		'get_time_selection_form' => True,
	);
 
	function tc()
	{
		if(file_exists("setup/tc_config.local")) {
			include("setup/tc_config.local");
		} else {
			include("setup/tc_config");
		}

		$this->script_path = "$this->application_path"."/bin";
		$this->max_presidency_members = 99;
		$this->max_appointments = 32768;

		$this->db		= $GLOBALS['phpgw']->db;
		$this->db2	= $this->db;
		$this->db3	= $this->db;
		$this->nextmatchs = CreateObject('phpgwapi.nextmatchs');
		$this->t          = $GLOBALS['phpgw']->template;
		$this->account    = $GLOBALS['phpgw_info']['user']['account_id'];
		$this->grants     = $GLOBALS['phpgw']->acl->get_grants('tc');
		$this->grants[$this->account] = PHPGW_ACL_READ + PHPGW_ACL_ADD + PHPGW_ACL_EDIT + PHPGW_ACL_DELETE;

		$this->jscal = CreateObject('tc.jscalendar');   // before phpgw_header() !!!
		$this->cal_options = 'daFormat    : "%Y-%m-%d",
		                      ifFormat    : "%Y-%m-%d",
		                      mondayFirst : false,
		                      weekNumbers : false';

		$GLOBALS['phpgw_info']['flags']['app_header'] = 'The 3rd Counselor';
		$GLOBALS['phpgw']->common->phpgw_header();

		$this->current_day = `date '+%d'`;
		$this->current_day = $this->current_day-0; // Make it numeric
		$this->current_month = `date '+%m'`;
		$this->current_month = $this->current_month-0; // Make it numeric
		$this->current_year = `date '+%Y'`;
		$this->current_year = $this->current_year-0; // Make it numeric

		echo parse_navbar();
		$this->display_app_header();	
	}
  
	function save_sessiondata()
	{
	}

	function display_app_header()
	{
		$this->t->set_file(array('tc_header' => 'header.tpl'));

		if (isset($phpgw_info['user']['preferences']['tc']['tc_font']))
		{
			$font = $phpgw_info['user']['preferences']['tc']['tc_font'];
		}
		else
		{
			$font = 'Arial';
		}

		$this->t->set_var('bg_color',$phpgw_info['theme']['th_bg']);
		$this->t->set_var('font',$font);
		$link_data['menuaction'] = 'tc.tc.ht_view';
		$this->t->set_var('link_hometeaching',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_hometeaching','HomeTeaching');
		$link_data['menuaction'] = 'tc.tc.act_list';
		$this->t->set_var('link_activity',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_activity','Activities');
		$link_data['menuaction'] = 'tc.tc.willing_view';
		$this->t->set_var('link_willing',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_willing','Willingness');
		$link_data['menuaction'] = 'tc.tc.assign_view';
		$this->t->set_var('link_assignment',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_assignment','Assignments');
		$link_data['menuaction'] = 'tc.tc.par_view';
		$this->t->set_var('link_participation',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_participation','Participation');
		$link_data['menuaction'] = 'tc.tc.ppi_view';
		$this->t->set_var('link_ppi',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_ppi','PPIs');
		$link_data['menuaction'] = 'tc.tc.int_view';
		$this->t->set_var('link_int',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_int','Interviews');
		$link_data['menuaction'] = 'tc.tc.vis_view';
		$this->t->set_var('link_visit',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_visit','Visits');
		$link_data['menuaction'] = 'tc.tc.att_view';	
		$this->t->set_var('link_attendance',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_attendance','Attendance');
		$link_data['menuaction'] = 'tc.tc.dir_view';	
		$this->t->set_var('link_dir',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_dir','Directory');
		$link_data['menuaction'] = 'tc.tc.org_view';	
		$this->t->set_var('link_org',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_org','Callings');
		$link_data['menuaction'] = 'tc.tc.admin';	
		$this->t->set_var('link_admin',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_admin','Admin');
		$link_data['menuaction'] = 'tc.tc.schedule';	
		$this->t->set_var('link_schedule',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_schedule','Scheduling');
		$link_data['menuaction'] = 'tc.tc.email';	
		$this->t->set_var('link_email',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_email','Email');

		$this->t->pparse('out','tc_header');
	}

	function ht_view()
	{
		$this->t->set_file(array('ht_view_t' => 'ht_view.tpl'));
		$this->t->set_block('ht_view_t','district_list','list');

		$this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ht_view'));
		$num_months = get_var('num_months',array('GET','POST'));
		if($num_months == '') { $num_months = $this->default_ht_num_months; }
		$this->t->set_var('num_months',$num_months);
		$this->t->set_var('lang_filter','Filter');
		if($num_months == 1) { $this->t->set_var('lang_num_months','Month of History'); }
		else {  $this->t->set_var('lang_num_months','Months of History'); }

		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ht_view'));
		$this->t->set_var('title','Hometeaching'); 

		$sql = "SELECT * FROM tc_district where valid=1 ORDER BY district ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$districts[$i]['district'] = $this->db->f('district');
			$districts[$i]['name'] = $this->db->f('name');
			$districts[$i]['supervisor'] = $this->db->f('supervisor');
			$i++;
		}

		$sql = "SELECT * FROM tc_individual where valid=1 ORDER BY individual ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$i++;
		}
		array_multisort($indiv_name, $individual);

		// Make an array mapping individuals to indiv_names
		for($i=0; $i < count($individual); $i++) {
			$id = $individual[$i];
			$indivs[$id] = $indiv_name[$i];
		}      

		$this->nextmatchs->template_alternate_row_color(&$this->t);
		for($m=$num_months; $m >= 0; $m--) { $total_families[$m]=0; }
		for ($i=0; $i < count($districts); $i++) {
			$this->t->set_var('district_number',$districts[$i]['district']);
			$this->t->set_var('district_name',$districts[$i]['name']);	
			$supervisor = $districts[$i]['supervisor'];

			// Select all the unique companionship numbers for this district
			$sql = "SELECT distinct companionship FROM tc_companionship where valid=1 and district=". $districts[$i]['district'];
			$this->db->query($sql,__LINE__,__FILE__);
			$j=0; $unique_companionships = '';
			while ($this->db->next_record()) {
				$unique_companionships[$j]['companionship'] = $this->db->f('companionship');
				$j++;
			}

			$comp_width=450; $visit_width=25; $table_width=$comp_width + $num_months*$visit_width;
			$table_data=""; $num_companionships = 0;
			for($m=$num_months; $m >= 0; $m--) { 
				$visits[$m]=0; 
				$num_families[$m]=0; 
			}
			for ($j=0; $j < count($unique_companionships); $j++) {
				$companion_table_entry = "";
				// Select all the companions in each companionship
				$sql = "SELECT * FROM tc_companionship where valid=1 and ".
				"companionship=". $unique_companionships[$j]['companionship'];
				$this->db->query($sql,__LINE__,__FILE__);

				while ($this->db->next_record()) {
					// Get this companions information
					if($companion_table_entry != "") { $companion_table_entry .= "<td>&nbsp;/&nbsp;</td>"; }
					$companionship = $this->db->f('companionship');
					$individual = $this->db->f('individual');
					$name = $indivs[$individual];
					$phone = $indiv_phone[$individual];
					$companion_table_entry .= "<td title=\"$phone\"><b>$name</b></td>";
				}
				$table_data.= "<tr bgcolor=#d3dce3><td colspan=20><table><tr>$companion_table_entry</tr></table><hr></td></tr>";

				// Get the names of the families assigned this home teaching companionship
				$sql = "SELECT * from tc_family where valid=1 AND companionship=".$unique_companionships[$j]['companionship'];
				$sql = $sql . " ORDER BY name ASC";
				$this->db->query($sql,__LINE__,__FILE__);
				$k=0;
				while ($this->db->next_record()) {
					$family_name = $this->db->f('name');
					$family_id = $this->db->f('family');
					$this->nextmatchs->template_alternate_row_color(&$this->t);
					$table_data.="<tr bgcolor=". $this->t->get_var('tr_color') ."><td>$family_name Family</td>";
					// Find out how many times Visits were performed by this companionship
					// in the past $num_months for this Family
					$header_row="<th width=$comp_width><font size=-2>Families</th>";
					for($m=$num_months; $m >= 0; $m--) {
						$month = $this->current_month - $m;
						$year = $this->current_year;
						if($month <= 0) { $remainder = $month; $month = 12 + $remainder; $year=$year-1; }
						if($month < 10) { $month = "0"."$month"; }
						$month_start = "$year"."-"."$month"."-"."01";
						$month_end = "$year"."-"."$month"."-"."31";
						$month = "$month"."/"."$year";

						//print "m: $m month: $month year: $year month_start: $month_start month_end: $month_end<br>";
						// Add this to the query to filter on only visits made by this companionship:
						// " AND companionship=" . $unique_companionships[$j]['companionship'].

						// First check to see if the currently assigned companionship has visited them
						$sql = "SELECT * FROM tc_visit WHERE date >= '$month_start' AND date <= '$month_end' ".
							   " AND companionship=".$unique_companionships[$j]['companionship'].
							   " AND family=". $family_id;
						$query_id = $this->db2->query($sql,__LINE__,__FILE__);
						if($this->db2->num_rows($query_id) == 0) {
							// We did not find any visits made by the currently assigned companionship,
							// look for visits made by any other companionship other than 0. (0 == Presidency Visit)
							$sql = "SELECT * FROM tc_visit WHERE date >= '$month_start' AND date <= '$month_end' ".
								   " AND companionship!=0".
								   " AND family=". $family_id;
							$query_id = $this->db2->query($sql,__LINE__,__FILE__);
						}
						$this->db2->query($sql,__LINE__,__FILE__);
						$link_data['menuaction'] = 'tc.tc.ht_update';
						$link_data['date'] = $month_start;
						$link_data['month_start'] = $month_start;
						$link_data['month_end'] = $month_end;
						$link_data['month'] = $month;
						$link_data['district'] = $districts[$i]['district'];
						$link_data['district_name'] = $districts[$i]['name'];
						$link_data['action'] = 'view';
						$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
						$header_row .= "<th width=$visit_width><font size=-2><a href=$link>$month</a></th>";
						if(!$total_visits[$m]) { $total_visits[$m] = 0; }
						if($this->db2->next_record()) {
							if($this->db2->f('visited') == 'y') {
								$visits[$m]++; $total_visits[$m]++;
								$num_families[$m]++; $total_families[$m]++;
								$table_data .= '<td align=center><a href="'.$link.'"><img src="images/checkmark.gif"></a></td>';
							} else if($this->db2->f('visited') == 'n') {
								$num_families[$m]++; $total_families[$m]++;
								$table_data .= '<td align=center><a href="'.$link.'"><img src="images/x.gif"></a></td>';
							} else {
								//$visits[$m]++; $total_visits[$m]++;
								$table_data .= "<td>&nbsp;</td>";
							}
						} else {
							//$visits[$m]++; $total_visits[$m]++;
							$table_data .= "<td>&nbsp;</td>";
						}
					}
					$table_data .= "</tr>"; 
					$k++;
				}
				$table_data .= "<tr><td colspan=20></td></tr>";
			}
			$table_data .= "<tr><td colspan=20><hr></td></tr>";
			$stat_data = "<tr><td><b><font size=-2>Families Hometaught:<br>Hometeaching Percentage:</font></b></td>";

			for($m=$num_months; $m >=0; $m--) {
				if($num_families[$m] > 0) { 
					$percent = ceil(($visits[$m] / $num_families[$m])*100);
				} else {
					$percent = 0;
				}
				$stat_data .= "<td align=center><font size=-2><b>$visits[$m] / $num_families[$m]<br>$percent%</font></b></td>";
			}
			$stat_data .= "</tr>";

			$this->t->set_var('table_width',$table_width);
			$this->t->set_var('header_row',$header_row);
			$this->t->set_var('table_data',$table_data);
			$this->t->set_var('stat_data',$stat_data);
			$this->t->fp('list','district_list',True);
		}

		$totals = "<tr><td><b><font size=-2>Total Families Hometaught:<br>Total Hometeaching Percentage:</font></b></td>";
		for($m=$num_months; $m >=0; $m--) {
			if($total_families[$m] > 0) { 
				$percent = ceil(($total_visits[$m] / $total_families[$m])*100);
			} else {
				$percent = 0;
			}
			$totals .= "<td align=center><font size=-2><b>$total_visits[$m] / $total_families[$m]<br>$percent%</font></b></td>";
		}
		$totals .= "</tr>";

		$this->t->set_var('totals',$totals);

		$this->t->pfp('out','ht_view_t');
		$this->save_sessiondata();
	}
      

	function ht_update()
	{
		$this->t->set_file(array('ht_update_t' => 'ht_update.tpl'));
		$this->t->set_block('ht_update_t','district_list','list');
		$this->t->set_block('ht_update_t','save','savehandle');

		$district = get_var('district',array('GET','POST'));
		$district_name = get_var('district_name',array('GET','POST'));
		$date = get_var('date',array('GET','POST'));
		$month = get_var('month',array('GET','POST'));
		$month_start = get_var('month_start',array('GET','POST'));
		$month_end = get_var('month_end',array('GET','POST'));
		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ht_view'));
		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ht_update&action=save'));
		$this->t->set_var('lang_done','Cancel');
		$this->t->set_var('district_name',$district_name);
		$this->t->set_var('district_number',$district);
		$this->t->set_var('title','Hometeaching Update ' . $month);
		$this->t->set_var('date',$date);

		if($action == 'save') {
			// Get a list of all the companionships in this district
			$sql = "SELECT distinct companionship FROM tc_companionship where valid=1 and district=". $district;
			$this->db->query($sql,__LINE__,__FILE__);
			$j=0; $unique_companionships = '';
			while ($this->db->next_record()) {
				$unique_companionships[$j]['companionship'] = $this->db->f('companionship');
				$j++;
			}
			for ($j=0; $j < count($unique_companionships); $j++) {
				//$comp=$unique_companionships[$j]['companionship'];
				//print "deleting from tc_visit where companionship=$comp and date=$date and district=$district<br>";
				// Delete all the visits that have taken place for all families for this companionsthip for this month
				$this->db->query("DELETE from tc_visit where companionship=" . $unique_companionships[$j]['companionship'] .
				                 " AND " . "date='" . $date . "'",__LINE__,__FILE__);
			}

			// Now, add the visits that are checked for this month
			$new_data = get_var('family_visited',array('POST'));
			foreach ($new_data as $family) {
				foreach ($family as $data) {
					//print "family_visited: $data <br>";
					$data_array = explode("/",$data);
					$family_id = $data_array[0];
					$companionship = $data_array[1];
					$date = $data_array[2];
					$visited = $data_array[3];
					if($visited == "") { $visited = $data_array[4]; }
					//print "family_id: $family_id companionship: $companionship date: $date visited: $visited<br>";
					$this->db->query("INSERT INTO tc_visit (family,companionship,date,notes,visited) " . 
					                 "VALUES (" . $family_id .",". $companionship .",'". $date ."','','". $visited ."')",__LINE__,__FILE__);
				}
			}
			$this->ht_view();
			return false;
		}

		$sql = "SELECT * FROM tc_individual where valid=1 ORDER BY individual ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$i++;
		}
		array_multisort($indiv_name, $individual);

		// Make an array mapping individuals to indiv_names
		for($i=0; $i < count($individual); $i++) {
			$id = $individual[$i];
			$indivs[$id] = $indiv_name[$i];
		}      

		// Select all the unique companionship numbers for this district
		$sql = "SELECT distinct companionship FROM tc_companionship where valid=1 and district=". $district;
		$this->db->query($sql,__LINE__,__FILE__);
		$j=0; $unique_companionships = '';
		while ($this->db->next_record()) {
			$unique_companionships[$j]['companionship'] = $this->db->f('companionship');
			$j++;
		}

		$comp_width=300; $visit_width=25; $table_width=$comp_width + $visit_width;
		$table_data=""; $num_companionships = 0; $num_families = 0; $visits=0;
		for ($j=0; $j < count($unique_companionships); $j++) {
			$companion_table_entry = "";
			// Select all the companions in each companionship
			$sql = "SELECT * FROM tc_companionship where valid=1 and ".
			"companionship=". $unique_companionships[$j]['companionship'];
			$this->db->query($sql,__LINE__,__FILE__);

			while ($this->db->next_record()) {
				// Get this companions information
				if($companion_table_entry != "") { $companion_table_entry .= "<td>&nbsp;/&nbsp;</td>"; }
				$companionship = $this->db->f('companionship');
				$individual = $this->db->f('individual');
				$name = $indivs[$individual];
				$phone = $indiv_phone[$individual];
				$companion_table_entry .= "<td title=\"$phone\"><b>$name</b></td>";
			}
			$table_data.= "<tr bgcolor=#d3dce3><td colspan=20><table><tr>$companion_table_entry</tr></table><hr></td></tr>";

			// Get the names of the families assigned this home teaching companionship
			$sql = "SELECT * from tc_family where valid=1 AND companionship=".$unique_companionships[$j]['companionship'];
			$sql = $sql . " ORDER BY name ASC";
			$this->db->query($sql,__LINE__,__FILE__);
			while ($this->db->next_record()) {
				$family_name = $this->db->f('name');
				$family_id = $this->db->f('family');
				$this->nextmatchs->template_alternate_row_color(&$this->t);
				$table_data.="<tr bgcolor=". $this->t->get_var('tr_color') ."><td>$family_name Family</td>";

				$header_row="<th width=$comp_width><font size=-2>Families</th>";

				// First check to see if the currently assigned companionship has visited them
				$sql = "SELECT * FROM tc_visit WHERE date >= '$month_start' AND date <= '$month_end' ".
				       " AND companionship=".$unique_companionships[$j]['companionship'].
				       " AND family=". $family_id;
				$query_id = $this->db2->query($sql,__LINE__,__FILE__);
				if($this->db2->num_rows($query_id) == 0) {
					// We did not find any visits made by the currently assigned companionship,
					// look for visits made by any other companionship other than 0. (0 == Presidency Visit)
					$sql = "SELECT * FROM tc_visit WHERE date >= '$month_start' AND date <= '$month_end' ".
					       " AND companionship!=0".
					       " AND family=". $family_id;
					$query_id = $this->db2->query($sql,__LINE__,__FILE__);
				}

				$value = $family_id . "/" . $unique_companionships[$j]['companionship'] . "/" . $date;
				$header_row .= "<th width=$visit_width><font size=-2><a href=$link>$month</a></th>";
				if(!$total_visits) { $total_visits = 0; }
				if($this->db2->next_record()) {
					if($this->db2->f('visited') == 'y') {
						$visits++; $total_visits++; $num_families++;
						$table_data .= '<td width=100 align=center>';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/y" checked>Y';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/n">N';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/"> ';
						$table_data .= '</td>';
					} else if($this->db2->f('visited') == 'n') {
						$num_families++;
						$table_data .= '<td width=100 align=center>';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/y">Y';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/n" checked>N';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/">';
						$table_data .= '</td>';
					} else {
						$table_data .= '<td width=100 align=center>';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/y">Y';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/n">N';
						$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/" checked> ';
						$table_data .= '</td>';
					}
				}
				else {
					$value .= "/";
					$table_data .= '<td width=100 align=center>';
					$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/y">Y';
					$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/n">N';
					$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/" checked> ';
					$table_data .= '</td>';	      
				}
			}
			$table_data .= "</tr>"; 
			$table_data .= "<tr><td colspan=20></td></tr>";
		}
		$table_data .= "<tr><td colspan=20><hr></td></tr>";
		$stat_data = "<tr><td><b><font size=-2>Families Hometaught:<br>Hometeaching Percentage:</font></b></td>";

		$percent = ceil(($visits / $num_families)*100);
		$stat_data .= "<td align=center><font size=-2><b>$visits / $num_families<br>$percent%</font></b></td>";
		$stat_data .= "</tr>";

		$this->t->set_var('table_width',$table_width);
		$this->t->set_var('header_row',$header_row);
		$this->t->set_var('table_data',$table_data);
		$this->t->set_var('stat_data',$stat_data);
		$this->t->fp('list','district_list',True);

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('savehandle','');

		$this->t->pfp('out','ht_update_t');
		$this->t->pfp('addhandle','save');

		$this->save_sessiondata();
	}

	function act_list()
	{
		$this->t->set_file(array('act_list_t' => 'act_list.tpl'));
		$this->t->set_block('act_list_t','act_list','list');

		$this->t->set_var('lang_name','Assignment');
		$this->t->set_var('lang_date','Date');
		$this->t->set_var('lang_notes','Description');

		$sql = "SELECT * FROM tc_activity ORDER BY date DESC";
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$activity_list[$i]['activity']  = $this->db->f('activity');
			$activity_list[$i]['assignment'] = $this->db->f('assignment');
			$activity_list[$i]['date']  = $this->db->f('date');
			$activity_list[$i]['notes']  = $this->db->f('notes');

			$sql = "SELECT * FROM tc_assignment WHERE assignment='" . $activity_list[$i]['assignment'] . "'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$activity_list[$i]['name'] = $this->db2->f('name');
				$activity_list[$i]['code'] = $this->db2->f('code');
			}
			$i++;
		}

		for ($i=0; $i < count($activity_list); $i++) {
			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$this->t->set_var('name',$activity_list[$i]['name']);
			$this->t->set_var('date',$activity_list[$i]['date']);
			$activity_notes = $activity_list[$i]['notes'];
			if(strlen($activity_notes) > 40) { $activity_notes = substr($activity_notes,0,40) . "..."; }
			$this->t->set_var('notes',$activity_notes);

			$link_data['menuaction'] = 'tc.tc.act_view';
			$link_data['activity'] = $activity_list[$i]['activity'];
			$link_data['action'] = 'view';
			$this->t->set_var('view',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
			$this->t->set_var('lang_view','View');

			$link_data['menuaction'] = 'tc.tc.act_update';
			$link_data['activity'] = $activity_list[$i]['activity'];
			$link_data['action'] = 'edit';
			$this->t->set_var('edit',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
			$this->t->set_var('lang_edit','Edit');

			$this->t->fp('list','act_list',True);
		}

		$link_data['menuaction'] = 'tc.tc.act_update';
		$link_data['activity'] = '0';
		$link_data['action'] = 'add';
		$this->t->set_var('add','<form method="POST" action="' . $GLOBALS['phpgw']->link('/tc/index.php',$link_data) .
		                  '"><input type="submit" name="Add" value="' . 'Add Activity' .'"></font></form>');

		$this->t->pfp('out','act_list_t');
		$this->save_sessiondata();
	}

	function act_view()
	{
		$this->t->set_file(array('act_view_t' => 'act_view.tpl'));
		$this->t->set_block('act_view_t','part_list','list');

		$sql = "SELECT * FROM tc_activity WHERE activity=" . intval(get_var('activity',array('GET','POST')));
		$this->db->query($sql,__LINE__,__FILE__);
		$this->db->next_record();
		$this->t->set_var('assignment', $this->db->f('assignment'));
		$this->t->set_var('date', $this->db->f('date'));
		$this->t->set_var('notes', $this->db->f('notes'));

		$sql = "SELECT * FROM tc_assignment WHERE assignment='" . $this->db->f('assignment') . "'";
		$this->db2->query($sql,__LINE__,__FILE__);
		if($this->db2->next_record()) {
			$this->t->set_var('name', $this->db2->f('name'));
			$this->t->set_var('code', $this->db2->f('code'));
		}
		$this->t->set_var('lang_name','Assignment');
		$this->t->set_var('lang_date','Date');
		$this->t->set_var('lang_notes','Description');
		$this->t->set_var('lang_done','Done');
		$this->t->set_var('lang_action','View');

		$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
		$this->t->set_var('tr_color',$tr_color);

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.act_list'));

		$link_data['menuaction'] = 'tc.tc.act_update';
		$link_data['activity'] = get_var('activity',array('GET','POST'));
		$link_data['action'] = 'edit';
		$this->t->set_var('edit',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
		$this->t->set_var('lang_edit','Edit');
		$this->t->set_var('cal_date',$this->db->f('date'));

		// Now find out which indivs participated in this activity
		$sql = "SELECT * FROM tc_participation WHERE activity=" . intval(get_var('activity',array('GET','POST')));
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$part_list[$i]['individual']  = $this->db->f('individual');
			$i++;
		}

		for ($i=0; $i < count($part_list); $i++) {
			$sql = "SELECT * FROM tc_individual WHERE individual=" . $part_list[$i]['individual'];
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();
			$names[$i] = $this->db->f('name');
		}
		sort($names);

		for ($i=0; $i < count($names); $i++) {
			//$this->nextmatchs->template_alternate_row_color(&$this->t);
			$this->t->set_var('indiv_name',$names[$i]);
			if(($i+1) % 3 == 0) {
				$this->t->set_var('table_sep',"</td></tr><tr>"); 
			} else { 
				$this->t->set_var('table_sep',"</td>"); 
			}
			if(($i) % 3 == 0) { $this->nextmatchs->template_alternate_row_color(&$this->t); }
			$this->t->fp('list','part_list',True);
		}

		$this->t->pfp('out','act_view_t');
		$this->save_sessiondata();
	}

	function act_update()
	{
		$this->t->set_file(array('form' => 'act_update.tpl'));
		$this->t->set_block('form','individual_list','list');
		$this->t->set_block('form','add','addhandle');
		$this->t->set_block('form','edit','edithandle');
		$this->t->set_var('lang_done','Done');

		$action = get_var('action',array('GET','POST'));
		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.act_list'));
		$activity['activity'] = intval(get_var('activity',array('GET','POST')));

		if($action == 'save') {
			$activity['assignment'] = get_var('assignment',array('POST'));
			$activity['date'] = get_var('date',array('POST'));
			$activity['notes']= get_var('notes',array('POST'));
			$this->db->query("UPDATE tc_activity set " .
			                 "   assignment='" . $activity['assignment'] .
			                 "', date='" . $activity['date'] . "'" .
			                 ", notes='" . $activity['notes'] . "'" .
			                 " WHERE activity=" . $activity['activity'],__LINE__,__FILE__);

			// Delete all the individuals who have particiapted in this activity
			$this->db->query("DELETE from tc_participation where activity=".$activity['activity'],__LINE__,__FILE__);

			// Re-add the individuals who are checked as having participated in this activity
			$indivs = get_var('individual_name',array('POST'));
			if(is_array($indivs)) { // Only do the foreach loop if we have a valid array of indivs to work with
				foreach ($indivs as $indiv) {
					$this->db->query("INSERT INTO tc_participation (individual,activity) " .
					                 "VALUES (" . $individual . ",". $activity['activity'] . ")",__LINE__,__FILE__);
				}
			}

			$this->act_list();
			return false;
		}

		if($action == 'insert') {
			$activity['assignment'] = get_var('assignment',array('POST'));
			$activity['date'] = get_var('date',array('POST'));
			$activity['notes']= get_var('notes',array('POST'));
			$this->db->query("INSERT INTO tc_activity (assignment,date,notes) " .
			                 "VALUES ('" . $activity['assignment'] . "','" .
			                 $activity['date'] . "','" . $activity['notes'] . "')",__LINE__,__FILE__);

			$sql = "SELECT * FROM tc_activity WHERE assignment='".$activity['assignment']."' " .
			       " AND date='".$activity['date']."' AND notes='".$activity['notes']."'";
			$this->db->query($sql,__LINE__,__FILE__);
			if($this->db->next_record()) {
				//print "activity: " . $this->db->f('activity') . "<br>";
				$activity['activity'] = $this->db->f('activity');
			}

			$indivs = get_var('individual_name',array('POST'));
			foreach ($indivs as $indiv)
			{
				$this->db->query("INSERT INTO tc_participation (individual,activity) " .
				                 "VALUES (" . $individual . ",". $activity['activity'] . ")",__LINE__,__FILE__);
			}

			$this->act_list();
			return false;
		}

		if($action == 'add') {
			$activity['activity'] = 0;
			$this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
			$this->t->set_var('assignment','');
			$this->t->set_var('date','');
			$this->t->set_var('notes','');
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Adding New Activity');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.act_update&activity=' .
			                  $activity['activity'] . '&action=' . 'insert'));
		}

		if($action == 'edit') {
			$sql = "SELECT * FROM tc_activity WHERE activity=" . $activity['activity'];
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();
			$this->t->set_var('cal_date',$this->jscal->input('date',$this->db->f('date'),'','','','','',$this->cal_options));
			$this->t->set_var('assignment', $this->db->f('assignment'));
			$assignment = $this->db->f('assignment');
			$this->t->set_var('date', $this->db->f('date'));
			$this->t->set_var('notes', $this->db->f('notes'));
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Editing Activity');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.act_update&activity=' .
			                  $activity['activity'] . '&action=' . 'save'));
		}

		// Create the assignments drop-down list
		$sql = "SELECT * FROM tc_assignment ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i = 0;
		while ($this->db->next_record()) {
			$assignments[$i]['assignment']  = $this->db->f('assignment');
			$assignments[$i]['name'] = $this->db->f('name');
			$assignments[$i]['code'] = $this->db->f('code');
			$i++;
		}

		$assignment_data.= '<select name=assignment>';
		$assignment_data.= '<option value=0></option>';  
		for ($j=0; $j < count($assignments); $j++) {
			$id = $assignments[$j]['assignment'];
			$name = $assignments[$j]['name'];
			if($assignments[$j]['assignment'] == $assignment) { 
				$selected[$id] = 'selected="selected"'; 
			} else { 
				$selected[$id] = ''; 
			}
			$assignment_data.= '<option value='.$id.' '.$selected[$id].'>'.$name.'</option>';
		}
		$assignment_data.='</select>';
		$this->t->set_var('assignment_data',$assignment_data);

		// Create individual selection boxes
		$sql = "SELECT * FROM tc_individual";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			if($this->db->f('valid') == 1 || $action != 'add') {
				$indiv_name[$i] = $this->db->f('name');
				$individual[$i] = $this->db->f('individual');
				$indiv_valid[$i] = $this->db->f('valid');
				$i++;
			}
		}
		array_multisort($indiv_name, $individual, $indiv_valid);

		$j=0;
		for ($i=0; $i < count($individual); $i++) {
			//$this->nextmatchs->template_alternate_row_color(&$this->t);
			$sql = "SELECT * FROM tc_participation where activity=". $activity['activity'] . " AND individual=" . $individual[$i];
			$this->db->query($sql,__LINE__,__FILE__);
			if($this->db->next_record()) { 
				$this->t->set_var('checked','checked'); 
				$checked=1; 
			} else { 
				$this->t->set_var('checked',''); 
				$checked=0; 
			}
			if($checked || $indiv_valid[$i] == 1) {
				$this->t->set_var('individual_name',$indiv_name[$i]);
				$this->t->set_var('individual',$individual[$i]);
				if(($j+1) % 3 == 0) {
					$this->t->set_var('table_sep',"</td></tr><tr>"); 
				} else { 
					$this->t->set_var('table_sep',"</td>"); 
				}
				if(($j) % 3 == 0) { $this->nextmatchs->template_alternate_row_color(&$this->t); }
				$this->t->fp('list','individual_list',True);
				$j++;
			}
		}

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_add','Add Activity');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('edithandle','');
		$this->t->set_var('addhandle','');

		$this->t->pfp('out','form');
		if($action == 'edit') { $this->t->pfp('addhandle','edit'); }
		if($action == 'add') { $this->t->pfp('addhandle','add'); }

		$this->save_sessiondata();
	}

	function assign_view()
	{
		$this->t->set_file(array('assign_view_t' => 'assign_view.tpl'));
		$this->t->set_block('assign_view_t','assign_view','list');

		$this->t->set_var('lang_name','Assignment Name');
		$this->t->set_var('lang_code','Code');

		$sql = "SELECT * FROM tc_assignment ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$assignment_list[$i]['assignment']  = $this->db->f('assignment');
			$assignment_list[$i]['name'] = $this->db->f('name');
			$assignment_list[$i]['code'] = $this->db->f('code');
			$i++;
		}

		for ($i=0; $i < count($assignment_list); $i++) {
			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$this->t->set_var('name',$assignment_list[$i]['name']);
			$this->t->set_var('code',$assignment_list[$i]['code']);

			$link_data['menuaction'] = 'tc.tc.assign_update';
			$link_data['assignment'] = $assignment_list[$i]['assignment'];
			$link_data['action'] = 'edit';
			$this->t->set_var('edit',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
			$this->t->set_var('lang_edit','Edit');

			$link_data['menuaction'] = 'tc.tc.assign_update';
			$link_data['assignment'] = '0';
			$link_data['action'] = 'add';
			$this->t->set_var('add','<form method="POST" action="' . $GLOBALS['phpgw']->link('/tc/index.php',$link_data) .
			                  '"><input type="submit" name="Add" value="' . 'Add Assignment' .'"></font></form>');

			$this->t->fp('list','assign_view',True);
		}

		$this->t->pfp('out','assign_view_t');
		$this->save_sessiondata();
	}

	function assign_update()
	{
		$this->t->set_file(array('form' => 'assign_update.tpl'));
		$this->t->set_block('form','add','addhandle');
		$this->t->set_block('form','edit','edithandle');
		$this->t->set_var('lang_done','Done');

		$action = get_var('action',array('GET','POST'));
		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.assign_view'));
		$assignment['assignment'] = intval(get_var('assignment',array('GET','POST')));

		if($action == 'save') {
			$assignment['name'] = get_var('name',array('POST'));
			$assignment['code'] = get_var('code',array('POST'));
			$this->db->query("UPDATE tc_assignment set " .
			                 "  name='" . $assignment['name'] . "'" .
			                 ", code='" . $assignment['code'] . "'" .
			                 " WHERE assignment=" . $assignment['assignment'],__LINE__,__FILE__);

			$this->assign_view();
			return false;
		}

		if($action == 'insert') {
			$assignment['name'] = get_var('name',array('POST'));
			$assignment['code'] = get_var('code',array('POST'));
			$this->db->query("INSERT INTO tc_assignment (name,code) " .
			                 "VALUES ('" . $assignment['name'] . "','" .
			                 $assignment['code'] . "')",__LINE__,__FILE__);
			$this->assign_view();
			return false;
		}

		if($action == 'add') {
			$assignment['assignment'] = 0;
			$this->t->set_var('name','');
			$this->t->set_var('code','');
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Adding New Assignment');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.assign_update&assignment=' .
			                  $assignment['assignment'] . '&action=' . 'insert'));
		}

		if($action == 'edit')
		{
			$sql = "SELECT * FROM tc_assignment WHERE assignment=" . $assignment['assignment'];
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();
			$this->t->set_var('name', $this->db->f('name'));
			$this->t->set_var('code', $this->db->f('code'));
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Editing Assignment');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.assign_update&assignment=' .
			                  $assignment['assignment'] . '&action=' . 'save'));
		}

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_add','Add Assignment');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('edithandle','');
		$this->t->set_var('addhandle','');

		$this->t->pfp('out','form');
		if($action == 'edit') { $this->t->pfp('addhandle','edit'); }
		if($action == 'add') { $this->t->pfp('addhandle','add'); }

		$this->save_sessiondata();
	}

	function par_view()
	{
		$this->t->set_file(array('par_view_t' => 'par_view.tpl'));
		$this->t->set_block('par_view_t','header_list','list1');
		$this->t->set_block('par_view_t','individual_list','list2');

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual_name[$i] = $this->db->f('name');
			$individual[$i] = $this->db->f('individual');
			$i++;
		}
		array_multisort($individual_name, $individual);

		$sql = "SELECT * FROM tc_activity ORDER BY date DESC";
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$activity_list[$i]['assignment'] = $this->db->f('assignment');
			$activity_list[$i]['date'] = $this->db->f('date');
			$activity_list[$i]['activity']  = $this->db->f('activity');
			$i++;
		}

		$sql = "SELECT * FROM tc_assignment ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while($this->db->next_record()) {
			$assignment_list[$i]['assignment'] = $this->db->f('assignment');
			$assignment_list[$i]['name'] = $this->db->f('name');
			$assignment_list[$i]['code'] = $this->db->f('code');
			$i++;
		}

		$individual_width=300; $part_width=25; $assignment_width=50;
		$total_width=$individual_width+$part_width;
		for ($i=0; $i < count($assignment_list); $i++) {
			$this->t->set_var('assignment_name',$assignment_list[$i]['name']);
			$this->t->set_var('assignment_code',$assignment_list[$i]['code']);
			$this->t->fp('list1','header_list',True);
			$total_width += $assignment_width;
		}

		for ($i=0; $i < count($individual); $i++) {
			$participated=0; $part_table = ''; 
			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$this->t->set_var('individual_name',$individual_name[$i]);
			for ($j=0; $j < count($assignment_list); $j++) {
				$date = "0000-00-00"; $checkmark=0; $num_matches=0;
				for ($k=0; $k < count($activity_list); $k++) {
					if($assignment_list[$j]['assignment'] == $activity_list[$k]['assignment']) {
						$sql = "SELECT * FROM tc_participation where " .
						       " activity=" . $activity_list[$k]['activity'] .
						       " AND individual=" . $individual[$i];
						$this->db->query($sql,__LINE__,__FILE__);
						while($this->db->next_record()) {
							if($activity_list[$k]['date'] > $date) { 
								$date = $activity_list[$k]['date'];
							}
							$checkmark=1;
							$num_matches++;
							$participated++;
						}
					}
				}
				if($checkmark) {
					$part_table .= '<td align=center><img src="images/checkmark.gif">';
					$part_table .= '<font size=-2>'.$num_matches.'</font><br>';
					$part_table .= '<font size=-2>'.$date.'</font></td>';
				} else {
					$part_table .= '<td>&nbsp;</td>';
				}
			}
			if($participated) { 
				$part_table .= '<td align=center><img src="images/checkmark.gif">'.$participated.'</td>'; 
			} else { 
				$part_table .= '<td>&nbsp;</td>'; 
			}
			$this->t->set_var('part_table',$part_table);
			$this->t->fp('list2','individual_list',True);
		}
		$this->t->set_var('total_width',$total_width);
		$this->t->set_var('individual_width',$individual_width);
		$this->t->set_var('part_width',$part_width);
		$this->t->set_var('act_width',$act_width);
		$this->t->pfp('out','par_view_t');
		$this->save_sessiondata(); 
	}

	function willing_view()
	{
		$this->t->set_file(array('willing_view_t' => 'willing_view.tpl'));
		$this->t->set_block('willing_view_t','header_list','list1');
		$this->t->set_block('willing_view_t','individual_list','list2');

		$this->t->set_var('lang_filter','Filter');
		$this->t->set_var('lang_filter_unwilling','Filter out unwilling individuals:');

		$filter_unwilling = get_var('filter_unwilling',array('POST'));
		$this->t->set_var('filter_unwilling',$filter_unwilling);

		if($filter_unwilling == 'y' || $filter_unwilling == '') {
			$filter_input = "<input type=\"radio\" name=\"filter_unwilling\" value=\"y\" checked>Y";
			$filter_input.= "<input type=\"radio\" name=\"filter_unwilling\" value=\"n\">N";
			$filter_input.= "&nbsp;&nbsp;";
		} else {
			$filter_input = "<input type=\"radio\" name=\"filter_unwilling\" value=\"y\">Y";
			$filter_input.= "<input type=\"radio\" name=\"filter_unwilling\" value=\"n\" checked>N";
			$filter_input.= "&nbsp;&nbsp;";
		}
		$this->t->set_var('filter_input',$filter_input);

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$indiv_name[$i] = $this->db->f('name');
			$individual[$i] = $this->db->f('individual');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$i++;
		}
		array_multisort($indiv_name, $individual);

		$sql = "SELECT * FROM tc_assignment ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while($this->db->next_record()) {
			$assignment_list[$i]['assignment'] = $this->db->f('assignment');
			$assignment_list[$i]['name'] = $this->db->f('name');
			$assignment_list[$i]['code'] = $this->db->f('code');
			$i++;
		}

		$sql = "SELECT * FROM tc_activity ORDER BY date DESC";
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$activity_list[$i]['assignment'] = $this->db->f('assignment');
			$activity_list[$i]['date'] = $this->db->f('date');
			$activity_list[$i]['activity']  = $this->db->f('activity');
			$i++;
		}

		$indiv_width=275; $willing_width=40; $assignment_width=50;
		$total_width=$indiv_width+$willing_width;

		for ($i=0; $i < count($assignment_list); $i++) {
			$this->t->set_var('assignment_name',$assignment_list[$i]['name']);
			$this->t->set_var('assignment_code',$assignment_list[$i]['code']);
			$this->t->fp('list1','header_list',True);
			$total_width += $assignment_width;
			$total_willing[$i] = 0;
		}

		for ($i=0; $i < count($individual); $i++) {
			$willing_table = ''; $indiv_willing=0;
			$this->t->set_var('individual_name',$indiv_name[$i]);
			$this->t->set_var('individual_phone',$indiv_phone[$individual[$i]]);
			$this->t->set_var('editurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.willing_update&individual=' .
			                  $individual[$i] . '&action=' . 'edit'));
			for ($j=0; $j < count($assignment_list); $j++) {
				$found_willingness=0; 
				$sql = "SELECT * FROM tc_willingness where " .
				       " assignment=" . $assignment_list[$j]['assignment'] .
				       " AND individual=" . $individual[$i];
				$this->db->query($sql,__LINE__,__FILE__);
				while($this->db->next_record()) {
					$found_willingness=1;
					$date_part="";
					$sql = "SELECT * FROM tc_activity where " .
					       " assignment=". $assignment_list[$j]['assignment'] .
					       " ORDER by date DESC";
					$this->db2->query($sql,__LINE__,__FILE__);
					if($this->db2->next_record()) {
						$activity = $this->db2->f('activity');
						$date = $this->db2->f('date');
						$sql = "SELECT * FROM tc_participation where " .
						       " activity=" . $activity .
						       " AND individual=". $individual[$i];
						$this->db3->query($sql,__LINE__,__FILE__);
						if($this->db3->next_record()) {
							$date_part = $date;
						} 
					}

					if($this->db->f('willing') == 'y') {
						$total_willing[$j]++;
						$indiv_willing=1;
						$willing_table .= '<td align=center><img src="images/checkmark.gif"><br><font size=-2>'.$date_part.'</font></td></td>';
					} else if($this->db->f('willing') == 'n') {
						$willing_table .= '<td align=center><img src="images/x.gif"></td>';
					} else {
						$indiv_willing=1;
						$willing_table .= "<td>&nbsp;</td>";
					}
				}
				if(!$found_willingness) {
					$indiv_willing=1;
					$willing_table .= "<td>&nbsp;</td>";
				}
			}
			if(($indiv_willing == 1) || ($filter_unwilling == 'n')) { 
				$this->t->set_var('willing_table',$willing_table);
				$this->t->fp('list2','individual_list',True);
				$this->nextmatchs->template_alternate_row_color(&$this->t);
			} 
		}

		$stat_table = '<td><b>Total Willing to Serve</b></td>';
		for ($j=0; $j < count($assignment_list); $j++) {
			$stat_table .= "<td align=center><b>".$total_willing[$j]."</b></td>";
		}
		$this->t->set_var('stat_table',$stat_table);

		$this->t->set_var('total_width',$total_width);
		$this->t->set_var('individual_width',$indiv_width);
		$this->t->set_var('willing_width',$willing_width);
		$this->t->pfp('out','willing_view_t');
		$this->save_sessiondata(); 
	}
    
	function willing_update()
	{
		//print "<font color=red>Willingness Update Under Constrcution</font>";
		//$this->willing_view();
		//return false;

		$this->t->set_file(array('willing_update_t' => 'willing_update.tpl'));
		$this->t->set_block('willing_update_t','assignment_list','list');
		$this->t->set_block('willing_update_t','save','savehandle');

		$individual = get_var('individual',array('GET','POST'));
		$this->t->set_var('individual',$individual);
		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.willing_view'));
		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.willing_update&action=save'));
		$this->t->set_var('lang_done','Cancel');
		$this->t->set_var('title','Willingness Update ');

		if($action == 'save') {
			// Delete all the previous willingness entries for this individual
			$this->db->query("DELETE from tc_willingness where individual=" . $individual ,__LINE__,__FILE__);

			// Now, add the assignment willingness that is checked for this individual
			$new_data = get_var('willingness',array('POST'));
			foreach ($new_data as $data) {
				$data_array = explode("/",$data);
				$assignment = $data_array[0];
				$willing = $data_array[1];
				//print "individual: $individual assignment: $assignment willing: $willing<br>";
				$this->db->query("INSERT INTO tc_willingness (individual,assignment,willing) " .
				                 "VALUES (" . $individual .",". $assignment .",'". $willing . "')",__LINE__,__FILE__);
			}      
			$this->willing_view();
			return false;
		}

		$assignment_width=300; $willing_width=25; $table_width=$assignment_width + $willing_width;
		$table_data=""; 

		// Find out the individual's name
		$sql = "SELECT * FROM tc_individual WHERE individual=".$individual." AND valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		if($this->db->next_record()) {
			$indiv_name = $this->db->f('name');
			$this->t->set_var('individual_name',$indiv_name);
		}

		// Select all the assignments
		$sql = "SELECT * FROM tc_assignment ORDER by name ASC";
		$this->db->query($sql,__LINE__,__FILE__);

		while ($this->db->next_record()) {
			$assignment = $this->db->f('assignment');
			$assignment_name = $this->db->f('name');
			$assignment_code = $this->db->f('code');

			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$table_data.="<tr bgcolor=". $this->t->get_var('tr_color') ."><td>$assignment_name</td>";

			$header_row="<th width=$comp_width><font size=-2>Assignments</th><th>Willingness</th>";
			$sql = "SELECT * FROM tc_willingness WHERE individual=".$individual." AND assignment=".$assignment;
			$this->db2->query($sql,__LINE__,__FILE__);
			$value = $assignment;

			if($this->db2->next_record()) {
				if($this->db2->f('willing') == 'y') {
					$table_data .= '<td width=100 align=center>';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/y" checked>Y';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/n">N';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/"> ';
					$table_data .= '</td>';
				} else if($this->db2->f('willing') == 'n') {
					$table_data .= '<td width=100 align=center>';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/y">Y';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/n" checked>N';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/">';
					$table_data .= '</td>';
				} else {
					$table_data .= '<td width=100 align=center>';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/y">Y';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/n">N';
					$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/" checked> ';
					$table_data .= '</td>';
				}
			} else {
				$table_data .= '<td width=100 align=center>';
				$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/y">Y';
				$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/n">N';
				$table_data .= '<input type="radio" name="willingness['.$assignment.']" value="'.$value.'/" checked> ';
				$table_data .= '</td>';
			}

			$table_data .= "\n";
			$table_data .= "</tr>"; 
			$table_data .= "<tr><td colspan=20></td></tr>";
		}

		$table_data .= "<tr><td colspan=20><hr></td></tr>";

		$this->t->set_var('table_width',$table_width);
		$this->t->set_var('header_row',$header_row);
		$this->t->set_var('table_data',$table_data);
		$this->t->fp('list','assignment_list',True);

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('savehandle','');

		$this->t->pfp('out','willing_update_t');
		$this->t->pfp('addhandle','save');

		$this->save_sessiondata();
	}


	function ppi_sched()
	{
		$this->t->set_file(array('ppi_sched_t' => 'ppi_sched.tpl'));
		$this->t->set_block('ppi_sched_t','individual_list','indivlist');
		$this->t->set_block('ppi_sched_t','appt_list','apptlist');
		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('lang_save','Save Appt / Pri / Notes');
		$this->t->set_var('lang_reset','Clear Changes');

		$this->t->set_var('ppi_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_view'));
		$this->t->set_var('ppi_link_title','Yearly PPIs');

		$this->t->set_var('schedule_ppi_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_sched'));
		$this->t->set_var('schedule_ppi_link_title','Schedule Yearly PPIs');

		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_sched&action=save'));
		$this->t->set_var('title','Yearly PPI Scheduler');

		$indiv_width=500; $phone_width=25; $pri_width=10; $notes_width=128; $ppi_date_width=20;
		$table_width=$indiv_width + $phone_width + $pri_width + $notes_width + $ppi_date_width;
		$header_row = "<th width=$indiv_width><font size=-2>individual Name</th>";
		$header_row.= "<th width=$phone_width><font size=-2>Phone</th>";
		$header_row.= "<th width=$pri_width><font size=-2>Priority</th>";
		$header_row.= "<th width=$ppi_date_width><font size=-2>Last PPI</th>";
		$header_row.= "<th width=$notes_width><font size=-2>Scheduling Notes</th>";
		$table_data=""; $completed_data=""; $totals_data="";

		$year = date('Y');

		// Get the President
		$sql = "SELECT * FROM tc_presidency where president=1 and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		if($this->db->next_record()) {
			$president_name = $this->db->f('name');
			$president_name_array = explode(",",$president_name);
			$president_last_name = $president_name_array[0];
			$president_id = $this->db->f('individual');
			$presidency_id = $this->db->f('presidency');
			$interviewer = $this->db->f('individual');
			$district_number = '*';
			$district_name = $president_name;
			$sql = "SELECT * FROM tc_individual where individual='$president_id'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$mls_id = $this->db2->f('mls_id');
			}
			$sql = "SELECT * FROM tc_individual where mls_id='$mls_id'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$president_address = $this->db2->f('address');
			}
		} else {
			print "<hr><font color=red><h3>-E- Unable to locate President in tc_presidency table</h3></font></hr>";
			return;
		}

		if($action == 'save') {
			// Save any changes made to the appointment table
			$new_data = get_var('appt_notes',array('POST'));
			if($new_data != "") {
				foreach ($new_data as $entry) {
					$indiv = $entry['individual'];
					$appointment = $entry['appointment'];
					$location = $entry['location'];
					if($location == "") { $location = "$president_last_name"." home ($president_address)"; }
					if($indiv == 0) { $location = ""; }

					//Only perform a database update if we have made a change to this appointment
					$sql = "SELECT * FROM tc_appointment where appointment='$appointment' and individual='$indiv' and location='$location'";
					$this->db->query($sql,__LINE__,__FILE__);
					if(!$this->db->next_record()) {
						// Perform database save actions here
						$this->db->query("UPDATE tc_appointment set " .
						                 " individual='" . $indiv . "'" .
						                 ",location='" . $location . "'" .
						                 " WHERE appointment=" . $appointment,__LINE__,__FILE__);
						// Email the appointment
						$this->email_appt($appointment);
					}
				}
			}

			// Save any changes made to the ppi notes table
			$new_data = get_var('ppi_notes',array('POST'));
			foreach ($new_data as $entry) {
				$ppi_notes = $entry['notes'];
				$individual = $entry['individual'];
				$ppi_pri = $entry['pri'];

				// Perform database save actions here
				$this->db->query("UPDATE tc_individual set " .
				                 " ppi_notes='" . $ppi_notes . "'" .
				                 ",ppi_pri='" . $ppi_pri . "'" .
				                 " WHERE individual=" . $individual,__LINE__,__FILE__);

			}

			$take_me_to_url = $GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_sched');
			//Header('Location: ' . $take_me_to_url);
		}

		// create the individual id -> individual name mapping
		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where valid=1 and steward='Elder' ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		$individual = NULL;
		$indiv_name = NULL;
		while ($this->db->next_record()) {
			$indiv_name[$i] = $this->db->f('name');
			$individual[$i] = $this->db->f('individual');
			$i++;
		}
		array_multisort($indiv_name, $individual);

		// APPOINTMENT TABLE
		$date_width=250; $time_width=100; $indiv_width=200; $location_width=100;
		$appt_table_width=$date_width + $time_width + $indiv_width + $location_width;
		$appt_header_row = "<th width=$date_width><font size=-2>Date</th>";
		$appt_header_row.= "<th width=$time_width><font size=-2>Time</th>";      
		$appt_header_row.= "<th width=$indiv_width><font size=-2>indiv</th>";
		$appt_header_row.= "<th width=$location_width><font size=-2>Location</th>";
		$appt_table_data = ""; 

		$total_indivs=0; $indivs_with_yearly_ppi=0;

		// Display a scheduling table for the President
		$table_data=""; $appt_table_data="";
		$table_title = "District ".$district_number.": ".$district_name.": All indivs with Yearly PPI Not Completed";
		$appt_table_title = "District ".$district_number.": ".$district_name.": Yearly PPI Appointment Slots";
		$this->t->set_var('table_title',$table_title);
		$this->t->set_var('appt_table_title',$appt_table_title);

		// query the database for all the appointments
		$sql = "SELECT * FROM tc_appointment where presidency=".$presidency_id." and date>=CURDATE() ORDER BY date ASC, time ASC";
		$this->db->query($sql,__LINE__,__FILE__);

		while ($this->db->next_record()) {
			$appointment = $this->db->f('appointment');
			$indiv = $this->db->f('individual');
			$location = $this->db->f('location');
			if(($location == "") && ($indiv > 0)) { $location = "$president_last_name"." home ($president_address)"; }

			$date = $this->db->f('date');
			$date_array = explode("-",$date);
			$year = $date_array[0]; $month = $date_array[1]; $day = $date_array[2];
			$day_string = date("l d-M-Y", mktime(0,0,0,$month,$day,$year));

			$time = $this->db->f('time');
			$time_array = explode(":",$time);
			$time_string = date("g:i a", mktime($time_array[0], $time_array[1], $time_array[2]));

			$appt_table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
			$appt_table_data.= "<td align=center>$day_string</td>";
			$appt_table_data.= "<td align=center>$time_string</td>";

			$appt_table_data.= '<td align=center><select name=appt_notes['.$appointment.'][individual]>';
			$appt_table_data.= '<option value=0></option>';
			for ($i=0; $i < count($individual); $i++) {
				$id = $individual[$i];
				$name = $indiv_name[$i];
				if($individual[$i] == $indiv) { 
					$selected[$id] = 'selected="selected"'; 
				} else { 
					$selected[$id] = ''; 
				}
				$appt_table_data.= '<option value='.$id.' '.$selected[$id].'>'.$name.'</option>';
			}
			$appt_table_data.='</select></td>';

			$appt_table_data.= '<td align=center><input type=text size="35" maxlength="120" ';
			$appt_table_data.= 'name="appt_notes['.$appointment.'][location]" value="'.$location.'">';

			$appt_table_data.= '<input type=hidden name="appt_notes['.$appointment.'][appointment]" value="'.$appointment.'">';

			$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
			$this->t->set_var('tr_color',$tr_color);
		}

		$this->t->set_var('appt_table_data',$appt_table_data);
		$this->t->set_var('appt_header_row',$appt_header_row);
		$this->t->set_var('appt_table_width',$appt_table_width);

		// PPI SCHEDULING TABLE
		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where valid=1 and steward='Elder' ORDER BY ppi_pri ASC, name ASC";
		$this->db->query($sql,__LINE__,__FILE__);

		$i=0; 
		$individual = NULL;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$indiv_ppi_pri[$individual[$i]] = $this->db->f('ppi_pri');
			$indiv_ppi_notes[$individual[$i]] = $this->db->f('ppi_notes');
			$i++;
			$total_indivs++;
		}

		$max = count($individual);

		for($i=0; $i < $max; $i++) {
			$id = $individual[$i];
			$name = $indiv_name[$i];
			$phone = $indiv_phone[$id];
			$ppi_pri = $indiv_ppi_pri[$id];
			$ppi_notes = $indiv_ppi_notes[$id];

			// If this individual has had a yearly PPI this year, don't show him on the schedule list
			$year_start = $year - 1 . "-12-31"; $year_end = $year + 1 . "-01-01";
			$sql = "SELECT * FROM tc_interview WHERE date > '$year_start' AND date < '$year_end' ".
			       "AND individual=" . $id . " AND interview_type='ppi'";
			$this->db2->query($sql,__LINE__,__FILE__);

			if(!$this->db2->next_record()) {
				$sql = "SELECT * FROM tc_interview WHERE individual=" . $id . " AND interview_type='ppi' ORDER BY date DESC";
				$this->db->query($sql,__LINE__,__FILE__);
				if($this->db->next_record()) { 
					$date = $this->db->f('date'); 
				} else { 
					$date = ""; 
				}
				$link_data['menuaction'] = 'tc.tc.ppi_update';
				$link_data['individual'] = $id;
				$link_data['name'] = $name;
				$link_data['interview'] = '';
				$link_data['interview_type'] = 1;
				$link_data['action'] = 'add';
				$link_data['interviewer'] = $interviewer;
				$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
				$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
				$this->t->set_var('tr_color',$tr_color);
				$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td title=\"$phone\"><a href=$link>$name</a></td>";
				$table_data.= "<td align=center>$phone</td>";
				//$table_data.= "<td align=center>$ppi_pri</td>";
				$table_data.= "<td align=center>";
				$table_data.= '<select name=ppi_notes['.$i.'][pri]>';
				foreach(range(0,6) as $num) {
					if($num == 0) { $num = 1; } else {$num = $num*5; }
					if($ppi_pri == $num) { 
						$selected[$num] = 'selected="selected"'; 
					} else { 
						$selected[$num] = ''; 
					}
					$table_data.= '<option value='.$num.' '.$selected[$num].'>'.$num.'</option>';
				}
				$table_data.= '</select></td>';
				$table_data.= "<td align=center>$date</td>";
				$table_data.= '<td><input type=text size="50" maxlength="128" name="ppi_notes['.$i.'][notes]" value="'.$ppi_notes.'">';
				$table_data.= '<input type=hidden name="ppi_notes['.$i.'][individual]" value="'.$id.'">';
				$table_data.= '<input type=hidden name="ppi_notes['.$i.'][indiv_name]" value="'.$name.'">';
				$table_data.= '</td>';
				$table_data.= '</tr>';
			} else {
				$link_data['menuaction'] = 'tc.tc.ppi_update';
				$link_data['interviewer'] = $this->db2->f('interviewer');
				$link_data['individual'] = $this->db2->f('individual');
				$link_data['name'] = $name;
				$link_data['interview'] = $this->db2->f('interview');
				$link_data['interview_type'] = $this->db2->f('interview_type');
				$link_data['action'] = 'view';
				$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);    
				$indivs_with_yearly_ppi++;
				$date = $this->db2->f('date');
				$ppi_notes = $this->db2->f('notes');
				if(strlen($ppi_notes) > 40) { $ppi_notes = substr($ppi_notes,0,40) . "..."; }
				$tr_color2 = $this->nextmatchs->alternate_row_color($tr_color2);
				$this->t->set_var('tr_color2',$tr_color2);
				$completed_data.= "<tr bgcolor=". $this->t->get_var('tr_color2') ."><td title=\"$phone\"><a href=$link>$name</a></td>";
				$completed_data.= "<td align=center>$phone</td>";
				$completed_data.= "<td align=center><a href=".$link.">$date</a></td>";
				$completed_data.= "<td align=left>$ppi_notes</td>";
				$completed_data.= '</tr>';
			}
		} // End for individuals Loop

		$name_width=175; $phone_width=100; $date_width=100; $notes_width=300;
		$completed_table_width=$name_width + $phone_width + $date_width + $notes_width;
		$completed_header_row = "<th width=$name_width><font size=-2>Individual Name</th>";
		$completed_header_row.= "<th width=$phone_width><font size=-2>Phone</th>";      
		$completed_header_row.= "<th width=$date_width><font size=-2>Date</th>";
		$completed_header_row.= "<th width=$notes_width><font size=-2>PPI Notes</th>";

		$this->t->set_var('table_width',$table_width);
		$this->t->set_var('header_row',$header_row);
		$this->t->set_var('table_data',$table_data);
		$this->t->set_var('completed_header_row',$completed_header_row);
		$this->t->set_var('completed_table_width',$completed_table_width);
		$this->t->set_var('completed',$completed_data);
		$this->t->fp('indivlist','individual_list',True); 

		$indivs_width=300; $totals_width=100;
		$totals_table_width=$indivs_width + $totals_width;
		$totals_header_row = "<th width=$indivs_width><font size=-2>Individuals</th>";
		$totals_header_row.= "<th width=$totals_width><font size=-2>$year</th>";
		$totals_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		$totals_data.= "<td align=left><font size=-2><b>Total Individuals with yearly PPIs completed:</b></font></td>";
		$totals_data.= "<td align=center><font size=-2><b>$indivs_with_yearly_ppi / $total_indivs</b></font></td>";
		$percent = ceil(($indivs_with_yearly_ppi / $total_indivs)*100);
		$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
		$this->t->set_var('tr_color',$tr_color);
		$totals_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		$totals_data.= "<td align=left><font size=-2><b>Percentage:</b></font></td>";
		$totals_data.= "<td align=center><font size=-2><b>$percent%</b></font></td>";
		$totals_data.= "</tr>";

		$this->t->set_var('totals',$totals_data);
		$this->t->set_var('totals_header_row',$totals_header_row);
		$this->t->set_var('totals_table_width',$totals_table_width);

		$this->t->pfp('out','ppi_sched_t');
		$this->save_sessiondata(); 

	}
  
	function int_sched()
	{
		$this->t->set_file(array('int_sched_t' => 'int_sched.tpl'));
		$this->t->set_block('int_sched_t','individual_list','indivlist');
		$this->t->set_block('int_sched_t','appt_list','apptlist');
		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('lang_save','Save Appt / Pri / Notes');
		$this->t->set_var('lang_reset','Clear Changes');

		$this->t->set_var('int_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_view'));
		$this->t->set_var('int_link_title','Hometeaching Interviews');

		$this->t->set_var('schedule_int_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_sched'));
		$this->t->set_var('schedule_int_link_title','Schedule Hometeaching Interviews');

		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_sched&action=save'));
		$this->t->set_var('title','Hometeaching Interviews Scheduler');

		$indiv_width=500; $phone_width=25; $pri_width=10; $notes_width=128; $int_date_width=20;
		$table_width=$indiv_width + $phone_width + $pri_width + $notes_width + $int_date_width;
		$header_row = "<th width=$indiv_width><font size=-2>individual Name</th>";
		$header_row.= "<th width=$phone_width><font size=-2>Phone</th>";
		$header_row.= "<th width=$pri_width><font size=-2>Priority</th>";
		$header_row.= "<th width=$int_date_width><font size=-2>Last Interview</th>";
		$header_row.= "<th width=$notes_width><font size=-2>Scheduling Notes</th>";
		$table_data=""; $completed_data=""; $totals_data="";

		$year = date('Y');
		$month = date('m');
		$nextyear = $year + 1;
		if($month >= 1 && $month <= 3) { $quarter_start=$year."-01-01"; $quarter_end=$year."-04-01"; }
		if($month >= 4 && $month <= 6) { $quarter_start=$year."-04-01"; $quarter_end=$year."-07-01"; }
		if($month >= 7 && $month <= 9) { $quarter_start=$year."-07-01"; $quarter_end=$year."-10-01"; }
		if($month >= 10 && $month <= 12) { $quarter_start=$year."-10-01"; $quarter_end=$nextyear."-01-01"; }
		//print "year: $year month: $month quarter_start: $quarter_start quarter_end: $quarter_end<br>";

		// create the individual id -> individual name mapping
		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1 ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		$individual_data = NULL;
		$indiv_name_data = NULL;
		while ($this->db->next_record()) {
			$indiv_name_data[$i] = $this->db->f('name');
			$individual_data[$i] = $this->db->f('individual');
			$individ2name[$individual_data[$i]] = $indiv_name_data[$i];
			$i++;
		}
		// add any YM that are home teachers
		$sql = "SELECT * FROM tc_companionship where valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		while ($this->db->next_record()) {
			$tmp_individual = $this->db->f('individual');
			$sql = "Select * FROM tc_individual where individual='$tmp_individual' and steward='' and valid=1";
			$this->db2->query($sql,__LINE__,__FILE__);
			while ($this->db2->next_record()) {
				$indiv_name_data[$i] = $this->db2->f('name');
				$individual_data[$i] = $this->db2->f('individual');
				$individ2name[$individual_data[$i]] = $indiv_name_data[$i];
				$i++;
			}
		}
		array_multisort($indiv_name_data, $individual_data);

		if($action == 'save') {
			// Save any changes made to the appointment table
			$new_data = get_var('appt_notes',array('POST'));
			if($new_data != "") {
				foreach ($new_data as $entry) {
					$indiv = $entry['individual'];
					$appointment = $entry['appointment'];
					$location = $entry['location'];
					if($location == "") {
						$supervisor = $entry['supervisor'];
						$supervisor_array = explode(",", $individ2name[$supervisor]);
						$supervisor_last_name = $supervisor_array[0];
						$sql = "SELECT * FROM tc_individual where individual='$supervisor'";
						$this->db2->query($sql,__LINE__,__FILE__);
						if($this->db2->next_record()) {
							$mls_id = $this->db2->f('mls_id');
						}
						$sql = "SELECT * FROM tc_individual where mls_id='$mls_id'";
						$this->db2->query($sql,__LINE__,__FILE__);
						if($this->db2->next_record()) {
							$supervisor_address = $this->db2->f('address');
						}
						$location = "$supervisor_last_name"." home ($supervisor_address)";
					}
					if($indiv == 0) { $location = ""; }

					//print "indiv: $indiv appointment: $appointment <br>";
					//Only perform a database update if we have made a change to this appointment
					$sql = "SELECT * FROM tc_appointment where appointment='$appointment' and individual='$indiv' and location='$location'";
					$this->db->query($sql,__LINE__,__FILE__);
					if(!$this->db->next_record()) {
						// Perform database save actions here
						$this->db->query("UPDATE tc_appointment set " .
						                 " individual='" . $indiv . "'" .
						                 ",location='" . $location . "'" .
						                 " WHERE appointment=" . $appointment,__LINE__,__FILE__);
						// Email the appointment
						$this->email_appt($appointment);
					}
				}
			}

			// Save any changes made to the int notes table
			$new_data = get_var('hti_notes',array('POST'));
			foreach ($new_data as $entry) {
				$hti_notes = $entry['notes'];
				$individual = $entry['individual'];
				$indiv_name = $entry['indiv_name'];
				$hti_pri = $entry['pri'];
				//print "hti_notes: $hti_notes indiv_name: $indiv_name <Br>";
				// Perform database save actions here
				$this->db->query("UPDATE tc_individual set " .
				                 " hti_notes='" . $hti_notes . "'" .
				                 ",hti_pri='" . $hti_pri . "'" .
				                 " WHERE individual=" . $individual,__LINE__,__FILE__);
			}

			$take_me_to_url = $GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_sched');
			//Header('Location: ' . $take_me_to_url);
		}

		// Get the Districts
		$sql = "SELECT * FROM tc_district where valid=1 ORDER BY district ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$district = $this->db->f('district');
			$districts[$i]['district'] = $this->db->f('district');
			$districts[$i]['name'] = $this->db->f('name');
			$districts[$i]['supervisor'] = $this->db->f('supervisor');
			$sql = "SELECT * FROM tc_presidency where district=$district and valid=1";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$districts[$i]['presidency'] = $this->db2->f('presidency');
			}
			$i++;
		}

		// APPOINTMENT TABLE
		$district = 1;
		$date_width=250; $time_width=100; $indiv_width=200; $location_width=100;
		$appt_table_width=$date_width + $time_width + $indiv_width + $location_width;
		$appt_header_row = "<th width=$date_width><font size=-2>Date</th>";
		$appt_header_row.= "<th width=$time_width><font size=-2>Time</th>";      
		$appt_header_row.= "<th width=$indiv_width><font size=-2>Individual</th>";
		$appt_header_row.= "<th width=$location_width><font size=-2>Location</th>";
		$appt_table_data = ""; 

		$total_comps=0; $comps_with_quarterly_int=0;

		// Display a scheduling table for each district
		for ($d=0; $d < count($districts); $d++) {
			$table_data=""; $appt_table_data="";
			$this->t->set_var('district_number',$districts[$d]['district']);
			$this->t->set_var('district_name',$districts[$d]['name']);	
			$supervisor = $districts[$d]['supervisor'];
			$supervisor_array = explode(",", $supervisor);
			$supervisor_last_name = $supervisor_array[0];
			$sql = "SELECT * FROM tc_individual where individual='$supervisor'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$mls_id = $this->db2->f('mls_id');
			}
			$sql = "SELECT * FROM tc_individual where mls_id='$mls_id'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$supervisor_address = $this->db2->f('address');
			}
			$location = "$supervisor_last_name"." home ($supervisor_address)";
			$table_title = "District ".$districts[$d]['district'].": ".$districts[$d]['name'].": All Individuals with Interviews Not Completed";
			$appt_table_title = "District ".$districts[$d]['district'].": ".$districts[$d]['name'].": Interview Appointment Slots";
			$this->t->set_var('table_title',$table_title);
			$this->t->set_var('appt_table_title',$appt_table_title);

			// query the database for all the appointments
			$sql = "SELECT * FROM tc_appointment where presidency=".$districts[$d]['presidency']." and date>=CURDATE() ORDER BY date ASC, time ASC";
			$this->db->query($sql,__LINE__,__FILE__);

			while ($this->db->next_record()) {
				$appointment = $this->db->f('appointment');
				$indiv = $this->db->f('individual');
				$location = $this->db->f('location');
				if(($location == "") && ($indiv > 0)) { $location = "$supervisor_last_name"." home ($supervisor_address)"; }

				$date = $this->db->f('date');
				$date_array = explode("-",$date);
				$year = $date_array[0]; $month = $date_array[1]; $day = $date_array[2];
				$day_string = date("l d-M-Y", mktime(0,0,0,$month,$day,$year));

				$time = $this->db->f('time');
				$time_array = explode(":",$time);
				$time_string = date("g:i a", mktime($time_array[0], $time_array[1], $time_array[2]));

				$appt_table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
				$appt_table_data.= "<td align=center>$day_string</td>";
				$appt_table_data.= "<td align=center>$time_string</td>";

				$appt_table_data.= '<td align=center><select name=appt_notes['.$appointment.'][individual]>';
				$appt_table_data.= '<option value=0></option>';
				for ($i=0; $i < count($individual_data); $i++) {
					$id = $individual_data[$i];
					$name = $indiv_name_data[$i];
					if($individual_data[$i] == $indiv) { 
						$selected[$id] = 'selected="selected"'; 
					} else { 
						$selected[$id] = ''; 
					}
					$appt_table_data.= '<option value='.$id.' '.$selected[$id].'>'.$name.'</option>';
				}
				$appt_table_data.='</select></td>';

				$appt_table_data.= '<td align=center><input type=text size="35" maxlength="120" ';
				$appt_table_data.= 'name="appt_notes['.$appointment.'][location]" value="'.$location.'">';

				$appt_table_data.= '<input type=hidden name="appt_notes['.$appointment.'][appointment]" value="'.$appointment.'">';
				$appt_table_data.= '<input type=hidden name="appt_notes['.$appointment.'][supervisor]" value="'.$supervisor.'">';

				$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
				$this->t->set_var('tr_color',$tr_color);
			}

			$this->t->set_var('appt_table_data',$appt_table_data);
			$this->t->set_var('appt_header_row',$appt_header_row);
			$this->t->set_var('appt_table_width',$appt_table_width);

			// INTERVIEW SCHEDULING TABLE

			// Select all the unique companionship numbers for this district
			$sql = "SELECT distinct companionship FROM tc_companionship where valid=1 and district=". $districts[$d]['district'];
			$this->db->query($sql,__LINE__,__FILE__);
			$j=0; $unique_companionships = '';
			while ($this->db->next_record())
			{
				$unique_companionships[$j]['companionship'] = $this->db->f('companionship');
				$j++;
			}

			$i=0;
			for ($j=0; $j < count($unique_companionships); $j++) {
				// Select all the companions from each companionship
				$sql = "SELECT * FROM tc_companionship where valid=1 and companionship=". $unique_companionships[$j]['companionship'];
				$this->db->query($sql,__LINE__,__FILE__);
				$k=0; $int_completed=0;
				$comp = $unique_companionships[$j]['companionship'];
				$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
				$this->t->set_var('tr_color',$tr_color);
				$total_comps++;
				while ($this->db->next_record()) {
					// Get this companions information
					$individual = $this->db->f('individual');

					$sql = "SELECT * FROM tc_individual where individual='$individual'";
					$this->db2->query($sql,__LINE__,__FILE__);	
					if($this->db2->next_record()) {
						$individual = $this->db2->f('individual');
						$indiv_name = $this->db2->f('name');
						$indiv_phone[$individual] = $this->db2->f('phone');
						$indiv_hti_pri[$individual] = $this->db2->f('hti_pri');
						$indiv_hti_notes[$individual] = $this->db2->f('hti_notes');
					}
					$id = $individual;
					$name = $indiv_name;
					$phone = $indiv_phone[$id];
					$hti_pri = $indiv_hti_pri[$id];
					$hti_notes = $indiv_hti_notes[$id];

					// If the companionship has already had its quarterly interview,
					// Skip the other companion in the companionship.
					if($int_completed == 1) {
						$completed_data.= "<tr bgcolor=". $this->t->get_var('tr_color2') ."><td title=\"$phone\"><a href=$link>$name</a></td>";
						$completed_data.= "<td align=center>$phone</td>";
						$completed_data.= "<td align=center><a href=".$link.">$date</a></td>";
						$completed_data.= "<td align=left>$hti_notes</td>";
						$completed_data.= '</tr>';
						$tr_color2 = $this->nextmatchs->alternate_row_color($tr_color2);
						$this->t->set_var('tr_color2',$tr_color2);
						$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
						$this->t->set_var('tr_color',$tr_color);
						continue;
					}

					// If this companionship has had a hometeaching interview this quarter, don't show them on the schedule list
					$sql = "SELECT * FROM tc_interview WHERE date >= '$quarter_start' AND date < '$quarter_end' AND individual='$id'";
					$this->db2->query($sql,__LINE__,__FILE__);

					if(!$this->db2->next_record()) {
						$sql = "SELECT * FROM tc_interview WHERE individual='$id' ORDER BY date DESC";
						$this->db3->query($sql,__LINE__,__FILE__);
						if($this->db3->next_record()) { 
							$date = $this->db3->f('date'); 
						} else { 
							$date = ""; 
						}
						$link_data['menuaction'] = 'tc.tc.int_update';
						$link_data['individual'] = $id;
						$link_data['name'] = $name;
						$link_data['interview'] = '';
						$link_data['action'] = 'add';
						$link_data['interviewer'] = $districts[$d]['supervisor'];
						$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
						$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td title=\"$phone\"><a href=$link>$name</a></td>";
						$table_data.= "<td align=center>$phone</td>";
						$table_data.= "<td align=center>";
						$table_data.= '<select name=hti_notes['.$i.'][pri]>';
						foreach(range(0,6) as $num) {
							if($num == 0) { $num = 1; } else {$num = $num*5; }
							if($hti_pri == $num) { 
								$selected[$num] = 'selected="selected"'; 
							} else { 
								$selected[$num] = ''; 
							}
							$table_data.= '<option value='.$num.' '.$selected[$num].'>'.$num.'</option>';
						}
						$table_data.= '</select></td>';
						$table_data.= "<td align=center>$date</td>";
						$table_data.= '<td><input type=text size="50" maxlength="128" name="hti_notes['.$i.'][notes]" value="'.$hti_notes.'">';
						$table_data.= '<input type=hidden name="hti_notes['.$i.'][individual]" value="'.$id.'">';
						$table_data.= '<input type=hidden name="hti_notes['.$i.'][indiv_name]" value="'.$name.'">';
						$table_data.= '</td>';
						$table_data.= '</tr>'."\n";
						$i++;
					} else {
						$link_data['menuaction'] = 'tc.tc.int_update';
						$link_data['interviewer'] = $this->db2->f('interviewer');
						$link_data['individual'] = $this->db2->f('individual');
						$link_data['name'] = $name;
						$link_data['interview'] = $this->db2->f('interview');
						$link_data['action'] = 'view';
						$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);    
						$comps_with_quarterly_int++;
						$int_completed=1;
						$date = $this->db2->f('date');
						$hti_notes = $this->db2->f('notes');
						if(strlen($hti_notes) > 40) { $hti_notes = substr($hti_notes,0,40) . "..."; }
						$completed_data.= "<tr bgcolor=". $this->t->get_var('tr_color2') ."><td title=\"$phone\"><a href=$link>$name</a></td>";
						$completed_data.= "<td align=center>$phone</td>";
						$completed_data.= "<td align=center><a href=".$link.">$date</a></td>";
						$completed_data.= "<td align=left>$hti_notes</td>";
						$completed_data.= '</tr>';
					}
				}
			}

			$name_width=175; $phone_width=100; $date_width=100; $notes_width=300;
			$completed_table_width=$name_width + $phone_width + $date_width + $notes_width;
			$completed_header_row = "<th width=$name_width><font size=-2>Individual Name</th>";
			$completed_header_row.= "<th width=$phone_width><font size=-2>Phone</th>";      
			$completed_header_row.= "<th width=$date_width><font size=-2>Date</th>";
			$completed_header_row.= "<th width=$notes_width><font size=-2>Interview Notes</th>";

			$this->t->set_var('table_width',$table_width);
			$this->t->set_var('header_row',$header_row);
			$this->t->set_var('table_data',$table_data);
			$this->t->set_var('completed_header_row',$completed_header_row);
			$this->t->set_var('completed_table_width',$completed_table_width);
			$this->t->set_var('completed',$completed_data);
			$this->t->fp('indivlist','individual_list',True);

		} // End for each district loop


		$indivs_width=300; $totals_width=100;
		$totals_table_width=$indivs_width + $totals_width;
		$totals_header_row = "<th width=$indivs_width><font size=-2>Individuals</th>";
		$totals_header_row.= "<th width=$totals_width><font size=-2>$year</th>";
		$totals_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		$totals_data.= "<td align=left><font size=-2><b>Total Companionships with interviews completed:</b></font></td>";
		$totals_data.= "<td align=center><font size=-2><b>$comps_with_quarterly_int / $total_comps</b></font></td>";
		$percent = ceil(($comps_with_quarterly_int / $total_comps)*100);
		$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
		$this->t->set_var('tr_color',$tr_color);
		$totals_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		$totals_data.= "<td align=left><font size=-2><b>Percentage:</b></font></td>";
		$totals_data.= "<td align=center><font size=-2><b>$percent%</b></font></td>";
		$totals_data.= "</tr>";

		$this->t->set_var('totals',$totals_data);
		$this->t->set_var('totals_header_row',$totals_header_row);
		$this->t->set_var('totals_table_width',$totals_table_width);

		$this->t->pfp('out','int_sched_t');
		$this->save_sessiondata(); 

	}
  
	function vis_sched()
	{
		$this->t->set_file(array('vis_sched_t' => 'vis_sched.tpl'));
		$this->t->set_block('vis_sched_t','family_list','familylist');
		$this->t->set_block('vis_sched_t','appt_list','apptlist');
		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('lang_save','Save Appt / Pri / Notes');
		$this->t->set_var('lang_reset','Clear Changes');

		$this->t->set_var('vis_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_view'));
		$this->t->set_var('vis_link_title','View Yearly Visits');

		$this->t->set_var('schedule_vis_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_sched'));
		$this->t->set_var('schedule_vis_link_title','Schedule Yearly Visits');

		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_sched&action=save'));
		$this->t->set_var('title','Presidency Yearly Visit Scheduler');

		$family_width=500; $phone_width=40; $pri_width=10; $notes_width=128; $visit_date_width=20;
		$table_width=$family_width + $phone_width + $pri_width + $notes_width + $visit_date_width;
		$header_row = "<th width=$family_width><font size=-2>Family Name</th>";
		$header_row.= "<th width=$phone_width><font size=-2>Phone</th>";
		$header_row.= "<th width=$pri_width><font size=-2>Priority</th>";
		$header_row.= "<th width=$visit_date_width><font size=-2>Last Visit</th>";
		$header_row.= "<th width=$notes_width><font size=-2>Scheduling Notes</th>";
		$table_data=""; $completed_data=""; $totals_data="";

		$year = date('Y');

		// create the family id -> family name mapping
		$sql = "SELECT * FROM tc_family where valid=1 and individual != 0 and companionship != 0 ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		$family_id = NULL;
		while ($this->db->next_record()) {
			$family_id[$i] = $this->db->f('family');
			$family_name[$i] = $this->db->f('name');
			$familyid2name[$family_id[$i]] = $family_name[$i];
			$sql = "SELECT * FROM tc_individual where family='$family_id[$i]'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$familyid2address[$family_id[$i]] = $this->db2->f('address');
			}
			$i++;
		}
		array_multisort($family_name, $family_id);

		if($action == 'save') {
			// Save any changes made to the appointment table
			$new_data = get_var('appt_notes',array('POST'));
			if($new_data != "") {
				foreach ($new_data as $entry) {
					$family = $entry['family'];
					$appointment = $entry['appointment'];
					$location = $entry['location'];
					if($location == "") {
						$family_name_array = explode(",", $familyid2name[$family]);
						$family_last_name = $family_name_array[0];
						$family_address = $familyid2address[$family];
						$location = "$family_last_name"." home ($family_address)";
					}
					if($family == 0) { $location = ""; }

					//Only perform a database update if we have made a change to this appointment
					$sql = "SELECT * FROM tc_appointment where appointment='$appointment' and family='$family' and location='$location'";
					$this->db->query($sql,__LINE__,__FILE__);
					if(!$this->db->next_record()) {
						// Perform database save actions here
						$this->db->query("UPDATE tc_appointment set " .
						                 " family='" . $family . "'" .
						                 ",location='" . $location . "'" .
						                 " WHERE appointment=" . $appointment,__LINE__,__FILE__);

						// Email the appointment
						$this->email_appt($appointment);
					}
				}
			}

			// Save any changes made to the visit notes table
			$new_data = get_var('vis_notes',array('POST'));
			foreach ($new_data as $entry) {
				$visit_notes = $entry['notes'];
				$family = $entry['family_id'];
				$visit_pri = $entry['pri'];

				// Perform database save actions here
				$this->db->query("UPDATE tc_family set " .
				                 " visit_notes='" . $visit_notes . "'" .
				                 ",visit_pri='" . $visit_pri . "'" .
				                 " WHERE family=" . $family,__LINE__,__FILE__);
			}

			$take_me_to_url = $GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_sched');
			//Header('Location: ' . $take_me_to_url);
		}

		// APPOINTMENT TABLE
		$date_width=250; $time_width=100; $family_width=250; $location_width=100;
		$appt_table_width=$date_width + $time_width + $family_width + $location_width;
		$appt_header_row = "<th width=$date_width><font size=-2>Date</th>";
		$appt_header_row.= "<th width=$time_width><font size=-2>Time</th>";      
		$appt_header_row.= "<th width=$family_width><font size=-2>Family</th>";
		$appt_header_row.= "<th width=$location_width><font size=-2>Location</th>";
		$appt_table_data = ""; 

		// Find out what the President ID is
		$sql = "SELECT * FROM tc_presidency where president=1 and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		if($this->db->next_record()) {
			$presidency_name = $this->db->f('name');
			$presidency_id = $this->db->f('presidency');
		} else {
			print "<hr><font color=red><h3>-E- Unable to locate Presidency in tc_presidency table</h3></font></hr>";
			return;
		}

		// query the database for all the appointments
		$sql = "SELECT * FROM tc_appointment where presidency=$presidency_id and date>=CURDATE() ORDER BY date ASC, time ASC";
		$this->db->query($sql,__LINE__,__FILE__);

		while ($this->db->next_record()) {
			$appointment = $this->db->f('appointment');
			$family = $this->db->f('family');
			$location = $this->db->f('location');
			$family_name_array = explode(",", $familyid2name[$family]);
			$family_last_name = $family_name_array[0];
			$family_address = $familyid2address[$family];
			if(($location == "") && ($family > 0)) { $location = "$family_last_name"." home ($family_address)"; }

			$date = $this->db->f('date');
			$date_array = explode("-",$date);
			$year = $date_array[0]; $month = $date_array[1]; $day = $date_array[2];
			$day_string = date("l d-M-Y", mktime(0,0,0,$month,$day,$year));

			$time = $this->db->f('time');
			$time_array = explode(":",$time);
			$time_string = date("g:i a", mktime($time_array[0], $time_array[1], $time_array[2]));

			$appt_table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
			$appt_table_data.= "<td align=center>$day_string</td>";
			$appt_table_data.= "<td align=center>$time_string</td>";

			$appt_table_data.= '<td align=center><select name=appt_notes['.$appointment.'][family]>';
			$appt_table_data.= '<option value=0></option>';
			for ($i=0; $i < count($family_id); $i++) {
				$id = $family_id[$i];
				$name = $family_name[$i];
				if($family_id[$i] == $family) { 
					$selected[$id] = 'selected="selected"'; 
				} else { 
					$selected[$id] = ''; 
				}
				$appt_table_data.= '<option value='.$id.' '.$selected[$id].'>'.$name.' Family</option>';
			}
			$appt_table_data.='</select></td>';

			$appt_table_data.= '<td align=center><input type=text size="35" maxlength="120" ';
			$appt_table_data.= 'name="appt_notes['.$appointment.'][location]" value="'.$location.'">';

			$appt_table_data.= '<input type=hidden name="appt_notes['.$appointment.'][appointment]" value="'.$appointment.'">';

			$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
			$this->t->set_var('tr_color',$tr_color);
		}

		$this->t->set_var('appt_table_data',$appt_table_data);
		$this->t->set_var('appt_header_row',$appt_header_row);
		$this->t->set_var('appt_table_width',$appt_table_width);


		// VISIT SCHEDULING TABLE
		$sql = "SELECT * FROM tc_family where valid=1 and individual != 0  and companionship != 0 ORDER BY visit_pri ASC, name ASC";
		$this->db->query($sql,__LINE__,__FILE__);

		$total_families=0; $families_with_yearly_visit=0;

		$i=0; 
		$family_id = NULL;
		$family_name = NULL;
		$family_phone = NULL;
		$family_visit_pri = NULL;
		$family_visit_notes = NULL;
		while ($this->db->next_record()) {
			$family_id[$i] = $this->db->f('family');
			$family_name[$i] = $this->db->f('name');
			$family_phone[$family_id[$i]] = $family_id[$i] . " ERROR";
			$family_visit_pri[$family_id[$i]] = $this->db->f('visit_pri');
			$family_visit_notes[$family_id[$i]] = $this->db->f('visit_notes');
			$i++;
			$total_families++;
		}

		$sql = "SELECT * FROM tc_individual where valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		while ($this->db->next_record()) {
			$family = $this->db->f('family');
			$phone = $this->db->f('phone');
			$family_phone[$family] = $phone;
		}

		$max = count($family_id);

		for($i=0; $i < $max; $i++) {
			$id = $family_id[$i];
			$name = $family_name[$i];
			$phone = $family_phone[$id];
			$vis_pri = $family_visit_pri[$id];
			$vis_notes = $family_visit_notes[$id];

			// If this family has had a yearly visit this year, don't show them on the schedule list
			$year_start = $year - 1 . "-12-31"; $year_end = $year + 1 . "-01-01";
			$sql = "SELECT * FROM tc_visit WHERE date > '$year_start' AND date < '$year_end' ".
			       "AND family=" . $id . " AND companionship=0";
			$this->db2->query($sql,__LINE__,__FILE__);

			if(!$this->db2->next_record()) {
				$sql = "SELECT * FROM tc_visit WHERE family=" . $id . " AND companionship=0 ORDER BY date DESC";
				$this->db->query($sql,__LINE__,__FILE__);
				if($this->db->next_record()) { 
					$date = $this->db->f('date'); 
				} else { 
					$date = ""; 
				}
				$link_data['menuaction'] = 'tc.tc.vis_update';
				$link_data['visit'] = '';
				$link_data['family'] = $id;
				$link_data['name'] = $name;
				$link_data['action'] = 'add';
				$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
				$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td title=\"$phone\"><a href=$link>$name Family</a></td>";
				$table_data.= "<td align=center>$phone</td>";
				$table_data.= "<td align=center>";
				$table_data.= '<select name=vis_notes['.$i.'][pri]>';
				foreach(range(0,6) as $num) {
					if($num == 0) { $num = 1; } else {$num = $num*5; }
					if($vis_pri == $num) { 
						$selected[$num] = 'selected="selected"'; 
					} else { 
						$selected[$num] = ''; 
					}
					$table_data.= '<option value='.$num.' '.$selected[$num].'>'.$num.'</option>';
				}
				$table_data.= '</select></td>';
				$table_data.= "<td align=center>$date</td>";
				$table_data.= '<td><input type=text size="50" maxlength="128" name="vis_notes['.$i.'][notes]" value="'.$vis_notes.'">';
				$table_data.= '<input type=hidden name="vis_notes['.$i.'][family_id]" value="'.$id.'">';
				$table_data.= '<input type=hidden name="vis_notes['.$i.'][family_name]" value="'.$name.'">';
				$table_data.= '</td>';
				$table_data.= '</tr>';
				$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
				$this->t->set_var('tr_color',$tr_color);
			} else {
				$link_data['menuaction'] = 'tc.tc.vis_update';
				$link_data['visit'] = $this->db2->f('visit');
				$link_data['family'] = $this->db2->f('family');
				$link_data['name'] = $name;
				$link_data['date'] = $this->db2->f('date');
				$link_data['action'] = 'view';
				$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);    
				$families_with_yearly_visit++;
				$date = $this->db2->f('date');
				$vis_notes = $this->db2->f('notes');
				if(strlen($vis_notes) > 40) { $vis_notes = stripslashes(substr($vis_notes,0,40) . "..."); }
				$completed_data.= "<tr bgcolor=". $this->t->get_var('tr_color2') ."><td title=\"$phone\"><a href=$link>$name Family</a></td>";
				$completed_data.= "<td align=center>$phone</td>";
				$completed_data.= "<td align=center><a href=".$link.">$date</a></td>";
				$completed_data.= "<td align=left>$vis_notes</td>";
				$completed_data.= '</tr>';
				$tr_color2 = $this->nextmatchs->alternate_row_color($tr_color2);
				$this->t->set_var('tr_color2',$tr_color2);
			}
		}

		$name_width=190; $phone_width=100; $date_width=100; $notes_width=300;
		$completed_table_width=$name_width + $phone_width + $date_width + $notes_width;
		$completed_header_row = "<th width=$name_width><font size=-2>Family Name</th>";
		$completed_header_row.= "<th width=$phone_width><font size=-2>Phone</th>";      
		$completed_header_row.= "<th width=$date_width><font size=-2>Date</th>";
		$completed_header_row.= "<th width=$notes_width><font size=-2>Visit Notes</th>";

		$family_width=300; $totals_width=100;
		$totals_table_width=$family_width + $totals_width;
		$totals_header_row = "<th width=$family_width><font size=-2>Families</th>";
		$totals_header_row.= "<th width=$totals_width><font size=-2>$year</th>";
		$totals_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		$totals_data.= "<td align=left><font size=-2><b>Total Families with yearly Visits completed:</b></font></td>";
		$totals_data.= "<td align=center><font size=-2><b>$families_with_yearly_visit / $total_families</b></font></td>";
		$percent = ceil(($families_with_yearly_visit / $total_families)*100);
		$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
		$this->t->set_var('tr_color',$tr_color);
		$totals_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		$totals_data.= "<td align=left><font size=-2><b>Percentage:</b></font></td>";
		$totals_data.= "<td align=center><font size=-2><b>$percent%</b></font></td>";
		$totals_data.= "</tr>";

		$this->t->set_var('table_width',$table_width);
		$this->t->set_var('header_row',$header_row);
		$this->t->set_var('table_data',$table_data);
		$this->t->set_var('totals_header_row',$totals_header_row);
		$this->t->set_var('totals_table_width',$totals_table_width);
		$this->t->set_var('completed_header_row',$completed_header_row);
		$this->t->set_var('completed_table_width',$completed_table_width);
		$this->t->set_var('completed',$completed_data);
		$this->t->set_var('totals',$totals_data);
		$this->t->fp('familylist','family_list',True);
		$this->t->fp('apptlist','appt_list',True);

		$this->t->pfp('out','vis_sched_t');
		$this->save_sessiondata(); 
	}
  
	function ppi_view()
	{
		$this->t->set_file(array('ppi_view_t' => 'ppi_view.tpl'));
		$this->t->set_block('ppi_view_t','district_list','list');

		$this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_view'));
		$num_months = get_var('num_months',array('GET','POST'));
		if($num_months == '') { $num_months = $this->default_ppi_num_months; }
		$this->t->set_var('num_months',$num_months);
		if($num_months == 1) { 
			$this->t->set_var('lang_num_months','Month of History'); 
		} else {  
			$this->t->set_var('lang_num_months','Months of History'); 
		}
		$this->t->set_var('lang_filter','Filter');
		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_view'));

		$this->t->set_var('ppi_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_view'));
		$this->t->set_var('ppi_link_title','Yearly PPIs'); 

		$this->t->set_var('schedule_ppi_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_sched'));
		$this->t->set_var('schedule_ppi_link_title','Schedule Yearly PPIs');

		$this->t->set_var('title','Yearly PPIs');
		$num_months = get_var('num_months',array('GET','POST'));
		if($num_months == '') { $num_months = $this->default_ppi_num_years; }
		$this->t->set_var('num_months',$num_months);
		if($num_months == 1) { 
			$this->t->set_var('lang_num_months','Year of History'); 
		} else { 
			$this->t->set_var('lang_num_months','Years of History');
		}

		$sql = "SELECT * FROM tc_presidency where president=1 and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		if($this->db->next_record()) {
			$president_name = $this->db->f('name');
			$interviewer = $this->db->f('individual');
			$interview_type = 'ppi';
		} else {
			print "<hr><font color=red><h3>-E- Unable to locate President in tc_presidency table</h3></font></hr>";
			return;
		}
		$this->t->set_var('district_number','*');
		$this->t->set_var('district_name',$president_name);

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1 ORDER BY individual ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$indiv_ppi_pri[$individual[$i]] = $this->db->f('ppi_pri');
			$indiv_ppi_notes[$individual[$i]] = $this->db->f('ppi_notes');
			$i++;
		}
		$total_indivs=$i;
		array_multisort($indiv_name, $individual);
		//var_dump($indiv_name); print "<br><br>"; var_dump($individual);

		$header_row="<th width=$comp_width><font size=-2>Individual Name</th>";

		$indiv_width=400; $ppi_width=75; $table_width=$indiv_width + $num_months*$ppi_width;
		$table_data="";
		for($m=$num_months; $m >= 0; $m--) {
			$year = date('Y') - $m;
			$header_row .= "<th width=150><font size=-2>$year</th>"; 
			$ppis[$m] = 0;
		}

		for ($j=0; $j < count($individual); $j++) {
			$id = $individual[$j];
			$name = $indiv_name[$j];
			$phone = $indiv_phone[$id];

			$link_data['menuaction'] = 'tc.tc.ppi_update';
			$link_data['interviewer'] = $interviewer;
			$link_data['indiv'] = $id;
			$link_data['name'] = $name;
			$link_data['interview'] = '';
			$link_data['interview_type'] = $interview_type;
			$link_data['action'] = 'add';
			$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td title=\"$phone\"><a href=$link>$name</a></td>";

			// Find out how many times PPIs were performed in the past $num_months for this individual
			for($m=$num_months; $m >= 0; $m--) {
				$year = date('Y') - $m;
				$year_start = $year - 1 . "-12-31"; $year_end = $year + 1 . "-01-01";
				$sql = "SELECT * FROM tc_interview WHERE date > '$year_start' AND date < '$year_end' ".
				       "AND individual=" . $id . " AND interview_type='ppi'";
				$this->db2->query($sql,__LINE__,__FILE__);

				if(!$total_ppis[$m]) { $total_ppis[$m] = 0; }
				if($this->db2->next_record()) {
					$ppis[$m]++; $total_ppis[$m]++; $ppi_recorded[$companionship][$m]=1;
					$link_data['menuaction'] = 'tc.tc.ppi_update';
					$link_data['companionship'] = $companionship;
					$link_data['interviewer'] = $this->db2->f('interviewer');
					$link_data['indiv'] = $id;
					$link_data['name'] = $name;
					$link_data['interview'] = $this->db2->f('interview');
					$link_data['interview_type'] = $interview_type;
					$link_data['action'] = 'view';
					$date = $this->db2->f('date');
					$date_array = explode("-",$date);
					$month = $date_array[1];
					$day   = $date_array[2];
					$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
					$table_data .= '<td align=center><a href='.$link.'><img src="images/checkmark.gif">&nbsp;'.$month.'-'.$day.'</a></td>';
				} else { 
					$table_data .= "<td>&nbsp;</td>"; 
				}
			}
			$table_data .= "</tr>\n"; 
		}
		$table_data .= "<tr><td colspan=20><hr></td></tr>";

		$stat_data = "<tr><td><b><font size=-2>$total_indivs Individuals<br>PPI Totals:</font></b></td>";
		for($m=$num_months; $m >=0; $m--) {
			$percent = ceil(($ppis[$m] / $total_indivs)*100);
			$stat_data .= "<td align=center><font size=-2><b>$ppis[$m]<br>$percent%</font></b></td>";
		}
		$stat_data .= "</tr>";

		$this->t->set_var('table_width',$table_width);
		$this->t->set_var('header_row',$header_row);
		$this->t->set_var('table_data',$table_data);
		$this->t->set_var('stat_data',$stat_data);
		$this->t->pfp('out','ppi_view_t');
		$this->save_sessiondata(); 
	}

	function ppi_update()
	{
		$this->t->set_file(array('form' => 'ppi_update.tpl'));
		$this->t->set_block('form','interviewer_list','int_list');
		$this->t->set_block('form','add','addhandle');
		$this->t->set_block('form','edit','edithandle');

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_view'));
		$this->t->set_var('readonly','');
		$this->t->set_var('disabled','');

		$action = get_var('action',array('GET','POST'));
		$companionship = get_var('companionship',array('GET','POST'));
		$interviewer = get_var('interviewer',array('GET','POST'));      
		$name = get_var('name',array('GET','POST'));
		$interview = get_var('interview',array('GET','POST'));
		$indiv = get_var('indiv',array('GET','POST'));
		$date = get_var('date',array('GET','POST'));
		$notes = get_var('notes',array('GET','POST'));
		$interview_type = get_var('interview_type',array('GET','POST'));

		$sql = "SELECT * FROM tc_presidency where valid=1 and (president=1 or counselor=1 or secretary=1)";
		$this->db2->query($sql,__LINE__,__FILE__);
		while ($this->db2->next_record()) {
			$indiv = $this->db2->f('individual');
			$interviewer_name = $this->db2->f('name');
			if($indiv == $interviewer) {
				$this->t->set_var('interviewer',$interviewer . ' selected');
			} else {
				$this->t->set_var('interviewer',$interviewer);
			}
			$this->t->set_var('interviewer_name',$interviewer_name);
			$this->t->set_var('interview_type_checked','');
			$this->t->fp('int_list','interviewer_list',True);
		}

		if($action == 'save') {
			$notes = get_var('notes',array('POST'));
			$this->db->query("UPDATE tc_interview set " .
			                 "   interview='" . $interview . "'" .
			                 ", interviewer='" . $interviewer . "'" .
			                 ", individual='" . $indiv . "'" .
			                 ", date='" . $date . "'" .
			                 ", notes='" . $notes . "'" .
			                 ", interview_type='" . $interview_type . "'" .
			                 " WHERE interview=" . $interview,__LINE__,__FILE__);
			$this->ppi_view();
			return false;
		}

		if($action == 'insert') {
			$notes = get_var('notes',array('POST'));
			$this->db->query("INSERT INTO tc_interview (interviewer,individual,date,notes,interview_type) " .
			                 "VALUES ('" . $interviewer . "','" . $indiv . "','" .
			                 $date . "','" . $notes . "','" . $interview_type  ."')",__LINE__,__FILE__);
			$this->ppi_view();
			return false;
		}

		if($action == 'add') {
			$this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
			$this->t->set_var('interview', '');
			$this->t->set_var('interviewer', $interviewer);
			$this->t->set_var('name',$name);
			$this->t->set_var('indiv',$indiv);
			$this->t->set_var('date','');
			$this->t->set_var('notes','');
			$this->t->set_var('interview_type',$interview_type);
			$this->t->set_var('interview_type_checked','checked');
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Adding New PPI');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_update&interview=' .
			                  $interview . '&action=' . 'insert'));
		}

		if($action == 'edit' || $action == 'view') {
			$sql = "SELECT * FROM tc_interview WHERE interview=".$interview;
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();
			$this->t->set_var('interview',$interview);
			$this->t->set_var('name',$name);
			$this->t->set_var('interviewer', $this->db->f('interviewer'));
			$this->t->set_var('indiv',$this->db->f('indiv'));
			$this->t->set_var('date',$this->db->f('date'));
			$this->t->set_var('notes',$this->db->f('notes'));
			$this->t->set_var('interview_type',$this->db->f('interview_type'));
			if($this->db->f('interview_type') == 1) { $this->t->set_var('interview_type_checked','checked'); }
		}

		if($action == 'edit') {
			$this->t->set_var('cal_date',$this->jscal->input('date',$date,'','','','','',$this->cal_options));
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Editing PPI');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_update&interview='. 
			                  $interview . '&action=' . 'save'));
		}

		if($action == 'view') {
			$date = $this->db->f('date');
			$this->t->set_var('cal_date','<input type=text size="10" maxlength="10" name="date" value="'.$date.'" readonly>');
			$this->t->set_var('readonly','READONLY');
			$this->t->set_var('disabled','DISABLED');
			$this->t->set_var('lang_done','Done');
			$this->t->set_var('lang_action','Viewing PPI');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_update&interview=' .
			                  $interview . '&action=' . 'edit'));
		}

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_add','Add PPI');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('edithandle','');
		$this->t->set_var('addhandle','');

		$this->t->pfp('out','form');

		if($action == 'view') { $this->t->set_var('lang_save','Edit PPI'); }
		if($action == 'edit' || $action == 'view') { $this->t->pfp('addhandle','edit'); }
		if($action == 'add') { $this->t->pfp('addhandle','add'); }

		$this->save_sessiondata(); 
	}

	function int_view()
	{
		$this->t->set_file(array('int_view_t' => 'int_view.tpl'));
		$this->t->set_block('int_view_t','district_list','list');

		$this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_view'));
		$num_quarters = get_var('num_quarters',array('GET','POST'));
		if($num_quarters == '') { $num_quarters = $this->default_int_num_quarters; }
		$this->t->set_var('num_quarters',$num_quarters);
		if($num_quarters == 1) { 
			$this->t->set_var('lang_num_quarters','Quarter of History'); 
		} else {
			$this->t->set_var('lang_num_quarters','Quarters of History'); 
		}
		$this->t->set_var('lang_filter','Filter');

		$this->t->set_var('int_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_view'));
		$this->t->set_var('int_link_title','Hometeaching Interviews'); 

		$this->t->set_var('schedule_int_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_sched'));
		$this->t->set_var('schedule_int_link_title','Schedule Hometeaching Interviews');

		$this->t->set_var('title','Hometeaching Interviews'); 

		$num_months = $num_quarters * 3 - 1;
		$current_month = $this->current_month;
		if($current_month >= 1 && $current_month <= 3) { $current_month=3; }
		else if($current_month >= 4 && $current_month <= 6) { $current_month=6; }
		else if($current_month >= 7 && $current_month <= 9) { $current_month=9; }
		else if($current_month >= 10 && $current_month <= 12) { $current_month=12; }

		$sql = "SELECT * FROM tc_district where valid=1 ORDER BY district ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$districts[$i]['district'] = $this->db->f('district');
			$districts[$i]['name'] = $this->db->f('name');
			$districts[$i]['supervisor'] = $this->db->f('supervisor');
			$i++;
		}

		$sql = "SELECT * FROM tc_individual where valid=1 ORDER BY individual ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$i++;
		}
		array_multisort($indiv_name, $individual);
		for($i=0; $i < count($individual); $i++) {
			$id = $individual[$i];
			$indivs[$id] = $indiv_name[$i];
		}      

		$total_companionships = 0;
		$this->nextmatchs->template_alternate_row_color(&$this->t);
		for ($i=0; $i < count($districts); $i++) {
			$this->t->set_var('district_number',$districts[$i]['district']);
			$this->t->set_var('district_name',$districts[$i]['name']);	
			$supervisor = $districts[$i]['supervisor'];

			// Select all the unique companionship numbers for this district
			$sql = "SELECT distinct companionship FROM tc_companionship where valid=1 and district=". $districts[$i]['district'];
			$this->db->query($sql,__LINE__,__FILE__);
			$j=0; $unique_companionships = '';
			while ($this->db->next_record()) {
				$unique_companionships[$j]['companionship'] = $this->db->f('companionship');
				$j++;
			}

			$comp_width=250; $int_width=75; $table_width=$comp_width + $num_months*$int_width;
			$table_data=""; $num_companionships = $j; $num_indivs = 0;
			for($m=$num_months; $m >= 0; $m--) { $ints[$m] = 0; }
			for ($j=0; $j < count($unique_companionships); $j++) {
				// Select all the companions in each companionship
				$sql = "SELECT * FROM tc_companionship where valid=1 and ".
				       "companionship=". $unique_companionships[$j]['companionship'];
				$this->db->query($sql,__LINE__,__FILE__);
				$k=0;
				$comp = $unique_companionships[$j]['companionship'];
				for($m=$num_months; $m >= 0; $m--) { $int_recorded[$comp][$m] = 0; }
				while ($this->db->next_record()) {
					// Get this companions information
					$num_indivs++;
					$companionship = $this->db->f('companionship');
					$individual = $this->db->f('individual');
					$name = $indivs[$individual];
					$phone = $indiv_phone[$individual];
					$link_data['menuaction'] = 'tc.tc.int_update';
					$link_data['companionship'] = $companionship;
					$link_data['interviewer'] = $supervisor;
					$link_data['indiv'] = $individual;
					$link_data['name'] = $name;
					$link_data['interview'] = '';
					$link_data['action'] = 'add';
					$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
					$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td title=\"$phone\"><a href=$link>$name</a></td>";

					// Find out how many times Interviews were performed in the past $num_months for this individual
					$header_row="<th width=$comp_width><font size=-2>Companionship</th>";
					for($m=$num_months; $m >= 0; $m--) {
						$month = $current_month - $m;
						$year = $this->current_year;
						if($month <= 0) { 
							$remainder = $month; 
							$month = 12 + $remainder; 
							$year=$year-1; 
						}
						if($month < 10) { $month = "0"."$month"; }
						$month_start = "$year"."-"."$month"."-"."01";
						$month_end = "$year"."-"."$month"."-"."31";
						$month = "$month"."/"."$year";
						$sql = "SELECT * FROM tc_interview WHERE date >= '$month_start' AND date <= '$month_end' ".
						       "AND individual=" . $individual;
						$this->db2->query($sql,__LINE__,__FILE__);
						$header_row .= "<th width=$int_width><font size=-2>$month</th>";

						if(!$total_ints[$m]) { $total_ints[$m] = 0; }
						if($this->db2->next_record()) {
							if(!$int_recorded[$companionship][$m]) {
								$ints[$m]++; 
								$total_ints[$m]++; 
								$int_recorded[$companionship][$m]=1;
							}
							$link_data['menuaction'] = 'tc.tc.int_update';
							$link_data['companionship'] = $companionship;
							$link_data['interviewer'] = $this->db2->f('interviewer');
							$link_data['indiv'] = $individual;
							$link_data['name'] = $name;
							$link_data['interview'] = $this->db2->f('interview');
							$link_data['action'] = 'view';
							$date = $this->db2->f('date');
							$date_array = explode("-",$date);
							$month = $date_array[1];
							$day   = $date_array[2];
							$link = $GLOBALS['phpgw']->link('/tc/index.php',$link_data);
							$table_data .= '<td align=center><a href='.$link.'><img src="images/checkmark.gif">&nbsp;'.$month.'-'.$day.'</a></td>';
						}
						else { 
							$table_data .= "<td>&nbsp;</td>"; 
						}
					}
					$table_data .= "</tr>"; 
					$k++;
				}
				$table_data .= "<tr><td colspan=20><hr></td></tr>";
			}
			$total_companionships += $num_companionships;
			$stat_data = "<tr><td><b><font size=-2>$num_companionships Companionships<br>Interview Totals:</font></b></td>";

			// Print the hometeaching interview stats
			for($m=$num_months; $m >=0; $m--) {
				$month = $current_month - $m;
				if($month < 0) { $month = 12 + $month; } // Handle going backwards over a year boundary
				$month_begins = $month % $this->monthly_hometeaching_interview_stats;
				//print "$month % $this->monthly_hometeaching_interview_stats = $month_begins <br>";
				if($this->monthly_hometeaching_interview_stats == 1) { $month_begins = 1; }
				if(($month_begins) == 1) { 
					$total = $ints[$m]; 
				} else { 
					$total += $ints[$m]; 
				}
				$percent = ceil(($total / $num_companionships)*100);
				$stat_data .= "<td align=center><font size=-2><b>$total<br>$percent%</font></b></td>";
			}
			$stat_data .= "</tr>";

			$this->t->set_var('table_width',$table_width);
			$this->t->set_var('header_row',$header_row);
			$this->t->set_var('table_data',$table_data);
			$this->t->set_var('stat_data',$stat_data);
			$this->t->fp('list','district_list',True);
		}

		// Display the totals
		$total = 0;
		$totals = "<tr><td><b><font size=-2>$total_companionships Total Comps<br>Interview Totals:</font></b></td>";
		for($m=$num_months; $m >=0; $m--) {
			$month = $current_month - $m;
			if($month < 0) { $month = 12 + $month; } // Handle going backwards over a year boundary
			$month_begins = $month % $this->monthly_hometeaching_interview_stats;
			if($this->monthly_hometeaching_interview_stats == 1) { $month_begins = 1; }
			if(($month_begins) == 1) { 
				$total = $total_ints[$m]; 
			} else { 
				$total += $total_ints[$m]; 
			}
			$percent = ceil(($total / $total_companionships)*100);
			$totals .= "<td align=center><font size=-2><b>$total<br>$percent%</font></b></td>";
		}
		$totals .= "</tr>";

		$this->t->set_var('totals',$totals);
		$this->t->pfp('out','int_view_t');
		$this->save_sessiondata(); 
	}

	function int_update()
	{
		$this->t->set_file(array('form' => 'int_update.tpl'));
		$this->t->set_block('form','interviewer_list','int_list');
		$this->t->set_block('form','add','addhandle');
		$this->t->set_block('form','edit','edithandle');

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_view'));
		$this->t->set_var('readonly','');
		$this->t->set_var('disabled','');
		$this->t->set_var('interview_type_checked','');

		$action = get_var('action',array('GET','POST'));
		$companionship = get_var('companionship',array('GET','POST'));
		$interviewer = get_var('interviewer',array('GET','POST'));      
		$name = get_var('name',array('GET','POST'));
		$interview = get_var('interview',array('GET','POST'));
		$indiv = get_var('indiv',array('GET','POST'));
		$date = get_var('date',array('GET','POST'));
		$notes = get_var('notes',array('GET','POST'));
		$interview_type = get_var('interview_type',array('GET','POST'));

		$sql = "SELECT * FROM tc_presidency where valid=1 and (president=1 or counselor=1 or secretary=1 or district!=0)";
		$this->db2->query($sql,__LINE__,__FILE__);
		while ($this->db2->next_record()) {
			$indiv = $this->db2->f('individual');
			$interviewer_name = $this->db2->f('name');
			if($indiv == $interviewer) {
				$this->t->set_var('interviewer',$interviewer . ' selected');
			} else {
				$this->t->set_var('interviewer',$interviewer);
			}
			$this->t->set_var('interviewer_name',$interviewer_name);
			$this->t->fp('int_list','interviewer_list',True);
		}

		if($action == 'save') {
			$notes = get_var('notes',array('POST'));
			$this->db->query("UPDATE tc_interview set " .
			                 "   interview='" . $interview . "'" .
			                 ", interviewer='" . $interviewer . "'" .
			                 ", individual='" . $indiv . "'" .
			                 ", date='" . $date . "'" .
			                 ", notes='" . $notes . "'" .
			                 ", interview_type='" . $interview_type . "'" .
			                 " WHERE interview=" . $interview,__LINE__,__FILE__);
			$this->int_view();
			return false;
		}

		if($action == 'insert') {
			$notes = get_var('notes',array('POST'));
			$this->db->query("INSERT INTO tc_interview (interviewer,individual,date,notes,interview_type) " .
			                 "VALUES ('" . $interviewer . "','" . $indiv . "','" .
			                 $date . "','" . $notes ."','" . $interview_type . "')",__LINE__,__FILE__);
			$this->int_view();
			return false;
		}

		if($action == 'add') {
			$this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
			$this->t->set_var('interview', '');
			$this->t->set_var('interviewer', $interviewer);
			$this->t->set_var('name',$name);
			$this->t->set_var('indiv',$indiv);
			$this->t->set_var('date','');
			$this->t->set_var('notes','');
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Adding New Interview');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_update&interview=' .
			                  $interview . '&action=' . 'insert'));
		}

		if($action == 'edit' || $action == 'view') {
			$sql = "SELECT * FROM tc_interview WHERE interview=".$interview;
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();
			$this->t->set_var('interview',$interview);
			$this->t->set_var('name',$name);
			$this->t->set_var('interviewer', $this->db->f('interviewer'));
			$this->t->set_var('indiv',$this->db->f('individual'));
			$this->t->set_var('date',$this->db->f('date'));
			$this->t->set_var('notes',$this->db->f('notes'));
			if($this->db->f('interview_type') == 1) { $this->t->set_var('interview_type_checked','checked'); }
		}

		if($action == 'edit') {
			$this->t->set_var('cal_date',$this->jscal->input('date',$date,'','','','','',$this->cal_options));
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Editing Interview');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_update&interview=' .
			                  $interview . '&action=' . 'save'));
		}

		if($action == 'view') {
			$date = $this->db->f('date');
			$this->t->set_var('cal_date','<input type=text size="10" maxlength="10" name="date" value="'.$date.'" readonly>');
			$this->t->set_var('readonly','READONLY');
			$this->t->set_var('disabled','DISABLED');
			$this->t->set_var('lang_done','Done');
			$this->t->set_var('lang_action','Viewing Interview');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_update&interview=' .
			                  $interview . '&action=' . 'edit'));
		}

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_add','Add Interview');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('edithandle','');
		$this->t->set_var('addhandle','');

		$this->t->pfp('out','form');

		if($action == 'view') { $this->t->set_var('lang_save','Edit Interview'); }
		if($action == 'edit' || $action == 'view') { $this->t->pfp('addhandle','edit'); }
		if($action == 'add') { $this->t->pfp('addhandle','add'); }

		$this->save_sessiondata(); 
	}

	function vis_view()
	{
		$this->t->set_file(array('vis_view_t' => 'vis_view.tpl'));
		$this->t->set_block('vis_view_t','visit_list','list1');
		$this->t->set_block('vis_view_t','family_list','list2');

		$this->t->set_var('lang_name','Family Name');
		$this->t->set_var('lang_date','Date');

		$this->t->set_var('vis_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_view'));
		$this->t->set_var('vis_link_title','View Yearly Visits');

		$this->t->set_var('schedule_vis_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_sched'));
		$this->t->set_var('schedule_vis_link_title','Schedule Yearly Visits');

		$this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_view'));
		$num_years = get_var('num_years',array('GET','POST'));
		if($num_years == '') { $num_years = $this->default_vis_num_years; }
		$this->t->set_var('num_years',$num_years);
		if($num_years == 1) { 
			$this->t->set_var('lang_num_years','Year of History'); 
		} else {  
			$this->t->set_var('lang_num_years','Years of History'); 
		}
		$this->t->set_var('lang_filter','Filter');

		$year = date('Y') - $num_years + 1;
		$year_start = $year - 1 . "-12-31"; $year_end = $year + 1 . "-01-01";

		$sql = "SELECT * FROM tc_visit WHERE companionship=0 and date > '$year_start' ORDER BY date DESC";
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$visit_list[$i]['visit'] = $this->db->f('visit');
			$visit_list[$i]['family'] = $this->db->f('family');
			$visit_list[$i]['date']  = $this->db->f('date');
			$i++;
		}

		for ($i=0; $i < count($visit_list); $i++) {
			$this->nextmatchs->template_alternate_row_color(&$this->t);

			$sql = "SELECT * FROM tc_family WHERE family=".$visit_list[$i]['family'];
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();

			$this->t->set_var('family',$visit_list[$i]['family']);
			$this->t->set_var('family_name',$this->db->f('name'));
			$this->t->set_var('date',$visit_list[$i]['date']);

			$link_data['menuaction'] = 'tc.tc.vis_update';
			$link_data['visit'] = $visit_list[$i]['visit'];
			$link_data['name'] = $this->db->f('name');
			$link_data['date'] = $visit_list[$i]['date'];
			$link_data['action'] = 'view';
			$this->t->set_var('view',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
			$this->t->set_var('lang_view','View');

			$link_data['menuaction'] = 'tc.tc.vis_update';
			$link_data['visit'] = $visit_list[$i]['visit'];
			$link_data['name'] = $this->db->f('name');
			$link_data['date'] = $visit_list[$i]['date'];
			$link_data['action'] = 'edit';
			$this->t->set_var('edit',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
			$this->t->set_var('lang_edit','Edit');

			$this->t->fp('list1','visit_list',True);
		}

		// List the families that are available to record a visit against
		$sql = "SELECT * FROM tc_family WHERE companionship != 0 and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		$total_records = $this->db->num_rows();

		$i = 0;
		while ($this->db->next_record()) {
			$family_names[$i] = $this->db->f('name');
			$family_ids[$i] = $this->db->f('family');
			$i++;
		} array_multisort($family_names, $family_ids);

		for ($i=0; $i < count($family_names); $i++) {
			$link_data['menuaction'] = 'tc.tc.vis_update';
			$link_data['visit'] = '';
			$link_data['family'] = $family_ids[$i];
			$link_data['action'] = 'add';
			$link_data['name'] = $family_names[$i];
			$this->t->set_var('add',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));

			$this->t->set_var('name',$family_names[$i]);
			if(($i+1) % 3 == 0) { 
				$this->t->set_var('table_sep',"</td></tr><tr>"); 
			} else { 
				$this->t->set_var('table_sep',"</td>"); 
			}
			if(($i) % 3 == 0) { $this->nextmatchs->template_alternate_row_color(&$this->t); }

			$this->t->fp('list2','family_list',True);
		}   

		$this->t->pfp('out','vis_view_t');
		$this->save_sessiondata(); 
	}

	function vis_update()
	{
		$this->t->set_file(array('form' => 'vis_update.tpl'));
		$this->t->set_block('form','add','addhandle');
		$this->t->set_block('form','edit','edithandle');

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_view'));
		$this->t->set_var('readonly','');
		$this->t->set_var('disabled','');

		$action = get_var('action',array('GET','POST'));
		$visit = get_var('visit',array('GET','POST'));
		$family = get_var('family',array('GET','POST'));
		$name = get_var('name',array('GET','POST'));
		$date = get_var('date',array('GET','POST'));
		$notes = get_var('notes',array('GET','POST'));
		$companionship = 0;

		if($action == 'save') {
			$notes = get_var('notes',array('POST'));
			$this->db->query("UPDATE tc_visit set " .
			                 "  date='" . $date . "'" .
			                 ", notes='" . $notes . "'" .
			                 " WHERE visit=" . $visit,__LINE__,__FILE__);
			$this->vis_view();
			return false;
		}

		if($action == 'insert') {
			$notes = get_var('notes',array('POST'));
			$this->db->query("INSERT INTO tc_visit (family,companionship,date,notes) " .
			                 "VALUES ('" . $family . "','" . $companionship . "','" .
			                 $date . "','" . $notes . "')",__LINE__,__FILE__);
			$this->vis_view();
			return false;
		}

		if($action == 'add') {
			$this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
			$this->t->set_var('family', $family);
			$this->t->set_var('visit', '');
			$this->t->set_var('name', $name);
			$this->t->set_var('date','');
			$this->t->set_var('notes','');
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Adding New Visit');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_update&family=' .
			                  $family . '&action=' . 'insert'));
		}

		if($action == 'edit' || $action == 'view') {
			$sql = "SELECT * FROM tc_visit WHERE visit=".$visit;
			$this->db->query($sql,__LINE__,__FILE__);
			$this->db->next_record();
			$this->t->set_var('visit',$visit);
			$this->t->set_var('name',$name);
			$this->t->set_var('family', $family);
			$this->t->set_var('date',$this->db->f('date'));
			$this->t->set_var('notes',$this->db->f('notes'));
		}

		if($action == 'edit') {
			$this->t->set_var('cal_date',$this->jscal->input('date',$date,'','','','','',$this->cal_options));
			$this->t->set_var('lang_done','Cancel');
			$this->t->set_var('lang_action','Editing Visit');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_update&visit=' .
			                  $visit . '&action=' . 'save'));
		}

		if($action == 'view') {
			$date = $this->db->f('date');
			$this->t->set_var('cal_date','<input type=text size="10" maxlength="10" name="date" value="'.$date.'" readonly>');
			$this->t->set_var('readonly','READONLY');
			$this->t->set_var('disabled','DISABLED');
			$this->t->set_var('lang_done','Done');
			$this->t->set_var('lang_action','Viewing Visit');
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_update&visit=' .
			                  $visit . '&action=' . 'edit'));
		}

		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_add','Add Visit');
		$this->t->set_var('lang_save','Save Changes');
		$this->t->set_var('edithandle','');
		$this->t->set_var('addhandle','');

		$this->t->pfp('out','form');

		if($action == 'view') { $this->t->set_var('lang_save','Edit Visit'); }
		if($action == 'edit' || $action == 'view') { $this->t->pfp('addhandle','edit'); }
		if($action == 'add') { $this->t->pfp('addhandle','add'); }

		$this->save_sessiondata(); 
	}

	function att_view()
	{
		$monthnum['Jan']=1; $monthnum['Feb']=2; $monthnum['Mar']=3; $monthnum['Apr']=4;
		$monthnum['May']=5; $monthnum['Jun']=6; $monthnum['Jul']=7; $monthnum['Aug']=8;
		$monthnum['Sep']=9; $monthnum['Oct']=10; $monthnum['Nov']=11; $monthnum['Dec']=12;

		$this->t->set_file(array('att_view_t' => 'att_view.tpl'));
		$this->t->set_block('att_view_t','act_list','list');

		$this->t->set_block('att_view_t','month_list','list1');
		$this->t->set_block('att_view_t','header_list','list2');
		$this->t->set_block('att_view_t','individual_list','list3');

		$this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.att_view'));
		$num_quarters = get_var('num_quarters',array('GET','POST'));
		if($num_quarters == '') { $num_quarters = $this->default_att_num_quarters; }
		$this->t->set_var('num_quarters',$num_quarters);
		$this->t->set_var('lang_filter','Filter');
		if($num_quarters == 1) { 
			$this->t->set_var('lang_num_quarters','Quarter of History'); 
		} else { 
			$this->t->set_var('lang_num_quarters','Quarters of History'); 
		}

		$num_months = $num_quarters * 3;
		$current_month = $this->current_month;
		if($current_month >= 1 && $current_month <= 3) { $current_month=3; }
		else if($current_month >= 4 && $current_month <= 6) { $current_month=6; }
		else if($current_month >= 7 && $current_month <= 9) { $current_month=9; }
		else if($current_month >= 10 && $current_month <= 12) { $current_month=12; }

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual_name[$i] = $this->db->f('name');
			$individual[$i] = $this->db->f('individual');
			$i++;
		}
		array_multisort($individual_name, $individual);

		// Create a list of sunday dates for a window of 3 months back and current month
		$i=0; 
		$last_time = 0; 
		$found_sunday = 0;
		$sunday_list[0]['date'] = date("Y-m-d", mktime(0, 0, 0, ($current_month-$num_months)+1, 1, date("y")));
		$last_date = explode("-",$sunday_list[0]['date']);
		$last_time = mktime(0, 0, 0, $last_date[1], $last_date[2], $last_date[0]);
		$time_limit = mktime(0, 0, 0, $current_month, 31, date("y"));
		while($last_time < $time_limit) {
			$day = date("w",$last_time);
			if(date("w",$last_time) == 0) {
				$sunday_list[$i]['date'] = date("Y-m-d", $last_time);
				$last_date = explode("-",$sunday_list[$i]['date']);
				$last_time = mktime(0, 0, 0, $last_date[1], $last_date[2], $last_date[0]);
				$sunday_list[$i]['day'] = $last_date[2];
				$sunday_list[$i]['month'] = date("M",$last_time);
				$sunday_list[$i]['year'] = $last_date[0];
				$found_sunday = 1;
				$last_date = $sunday_list[$i]['date'];
			}
			$last_time += 90000;
			if($found_sunday) { $i++; $found_sunday=0; }
		}

		$total_individuals = count($individual);
		$old_month=$sunday_list[0]['month']; $span=0;
		for ($i=0; $i < count($sunday_list); $i++) {
			$date = $sunday_list[$i]['date'];
			$this->t->set_var('date',$sunday_list[$i]['date']);
			$this->t->set_var('day',$sunday_list[$i]['day']);
			if(($old_month != $sunday_list[$i]['month']) || $i == count($sunday_list)-1) {
				if($i == count($sunday_list)-1) { $span++; }
				$cur_month = $sunday_list[$i]['month'];
				$old_month = $sunday_list[$i]['month'];	  
				$link_data['menuaction'] = 'tc.tc.att_update';
				$link_data['month'] = $sunday_list[$i-1]['month'];
				$link_data['year'] = $sunday_list[$i-1]['year'];
				$link_data['action'] = 'update_month';
				$cur_month = $sunday_list[$i-1]['month'];
				$cur_year = $sunday_list[$i-1]['year'];
				$header_row .= "<th><font size=-3>$cur_month&nbsp;$cur_year</font></th>";
				$this->t->set_var('update_month',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
				$this->t->set_var('month',$sunday_list[$i-1]['month']);
				$this->t->set_var('year',$sunday_list[$i-1]['year']);
				$this->t->set_var('span',$span); $span=0;
				$this->t->fp('list1','month_list',True);
			}
			$span++;
		}
		$this->t->set_var('total_individuals',$total_individuals);
		$this->t->set_var('header_row',$header_row);

		$individual_width=200; $att_width=25; $total_width=$individual_width; 
		for ($i=0; $i < count($sunday_list); $i++) {
			$link_data['menuaction'] = 'tc.tc.att_update';
			$link_data['month'] = $sunday_list[$i]['month'];
			$link_data['year'] = $sunday_list[$i]['year'];
			$link_data['day'] = $sunday_list[$i]['day'];
			$link_data['date'] = $sunday_list[$i]['date'];
			$link_data['action'] = 'update_day';
			$this->t->set_var('update_day',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
			$this->t->set_var('date',$sunday_list[$i]['date']);
			$this->t->set_var('day',$sunday_list[$i]['day']);
			$this->t->set_var('month',$sunday_list[$i]['month']);
			$this->t->set_var('year',$sunday_list[$i]['year']);
			$this->t->fp('list2','header_list',True);
			$total_width += $att_width;
			$attendance[$monthnum[$sunday_list[$i]['month']]]=0;
		}

		for ($i=0; $i < count($individual); $i++) {
			$att_table = "";
			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$this->t->set_var('individual_name',$individual_name[$i]);
			#print "checking for individual: " . $individual[$i] . "<br>";
			for ($j=0; $j < count($sunday_list); $j++) {
				#print "checking for date: " .  $sunday_list[$j]['date'] . "<br>";
				#print "SELECT * FROM tc_attendance WHERE date='"
				#  . $sunday_list[$j]['date'] . "' AND individual=" . $individual[$i] . "<br>";
				$sql = "SELECT * FROM tc_attendance WHERE date='" .
				       $sunday_list[$j]['date'] . "' AND individual=" . $individual[$i];
				$this->db->query($sql,__LINE__,__FILE__);
				if($this->db->next_record()) {
					$cur_month = $sunday_list[$j]['month'];
				if($attended[$i][$cur_month] != 1) {
					$attended[$i][$cur_month]=1;
					$attendance[$monthnum[$cur_month]]++;
				} 
					$att_table .= '<td align=center><img src="images/checkmark.gif"></td>';
				} else {
					$att_table .= '<td>&nbsp;</td>';
				}
			}
			$this->t->set_var('att_table',$att_table);
			$this->t->fp('list3','individual_list',True);
		}
		$this->t->set_var('total_width',$total_width);
		$this->t->set_var('individual_width',$individual_width);
		$this->t->set_var('att_width',$att_width);

		# Now calculate attendance for these months
		$attendance_str = "";
		$nonattendance_str = "";
		$aveattendance_str = "";
		$avenonattendance_str = "";
		$num_months=0;
		$ave_total_attended=0;
		ksort($attendance);
		foreach($attendance as $att => $value) {
			$total_attended = $attendance[$att];
			$ave_total_attended += $attendance[$att]; $num_months++;
			$percent = ceil(($total_attended / $total_individuals)*100);
			$attendance_str.="<td align=center><font size=-2><b>$total_attended ($percent%)</b></font></td>";
			$total_nonattended = $total_individuals - $total_attended;
			$percent = ceil(($total_nonattended / $total_individuals)*100);
			$nonattendance_str.="<td align=center><font size=-2><b>$total_nonattended ($percent%)</b></font></td>";

			$total_attended = ceil(($ave_total_attended / $num_months));
			$percent = ceil(($total_attended / $total_individuals)*100);
			$aveattendance_str .= "<td align=center><font size=-2><b>$total_attended ($percent%)</b></font></td>";
			$total_attended = $total_individuals - ceil(($ave_total_attended / $num_months));
			$percent = ceil(($total_attended / $total_individuals)*100);
			$avenonattendance_str .= "<td align=center><font size=-2><b>$total_attended ($percent%)</b></font></td>";
		}

		$this->t->set_var('attendance',$attendance_str);
		$this->t->set_var('aveattendance',$aveattendance_str);
		$this->t->set_var('nonattendance',$nonattendance_str);
		$this->t->set_var('avenonattendance',$avenonattendance_str);

		$this->t->pfp('out','att_view_t');
		$this->save_sessiondata(); 
	}

	function att_update()
	{
		$monthnum['Jan']=1; $monthnum['Feb']=2; $monthnum['Mar']=3; $monthnum['Apr']=4;
		$monthnum['May']=5; $monthnum['Jun']=6; $monthnum['Jul']=7; $monthnum['Aug']=8;
		$monthnum['Sep']=9; $monthnum['Oct']=10; $monthnum['Nov']=11; $monthnum['Dec']=12;

		$this->t->set_file(array('form' => 'att_update.tpl'));
		$this->t->set_block('form','edit','edithandle');

		$this->t->set_block('form','month_list','list1');
		$this->t->set_block('form','header_list','list2');
		$this->t->set_block('form','individual_list','list3');

		$this->t->set_var('done_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.att_view'));

		$action = get_var('action',array('GET','POST'));
		$month = get_var('month',array('GET','POST'));
		$year = get_var('year',array('GET','POST'));
		$day = get_var('day',array('GET','POST'));
		$date = get_var('date',array('GET','POST'));

		if($action == 'save_month' || $action == 'save_day') {
			$new_data = get_var('individuals_attended',array('POST'));
			$month = $monthnum[$month]; if($month < 10) { $month = "0" . $month; }

			if($action == 'save_month') {
				$this->db->query("DELETE from tc_attendance where date LIKE '".$year."-".$month."-%'",__LINE__,__FILE__);
			}

			if($action == 'save_day') {
				$this->db->query("DELETE from tc_attendance where date LIKE '".$year."-".$month."-".$day."'",__LINE__,__FILE__);
			}   

			foreach ($new_data as $data) {
				$data_array = explode("-",$data);
				$indiv = $data_array[0];
				$date  = "$data_array[1]-$data_array[2]-$data_array[3]";	      
				$this->db->query("INSERT INTO tc_attendance (individual,date) " .
				                 "VALUES (" . $indiv . ",'". $date . "')",__LINE__,__FILE__);
			}

			$this->att_view();
			return false;
		}

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$indiv_name[$i] = $this->db->f('name');
			$individual[$i] = $this->db->f('individual');
			$indiv_attending[$individual[$i]] = $this->db->f('attending');
			$i++;
		}
		array_multisort($indiv_name, $individual);

		if($action == 'update_month') {
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.att_update&action=save_month'));
			$i=0; 
			$last_time = 0; 
			$found_sunday = 0;
			$sunday_list[0]['date'] = date("Y-m-d", mktime(0, 0, 0, $monthnum[$month], 1, $year));
			$last_date = explode("-",$sunday_list[0]['date']);
			$last_time = mktime(0, 0, 0, $last_date[1], $last_date[2], $last_date[0]);
			$time_limit = mktime(0, 0, 0, $monthnum[$month], 31, $year);
			while($last_time <= $time_limit) {
				$day = date("w",$last_time);
					if(date("w",$last_time) == 0) { 
					$sunday_list[$i]['date'] = date("Y-m-d", $last_time); 
					$last_date = explode("-",$sunday_list[$i]['date']);
					$last_time = mktime(0, 0, 0, $last_date[1], $last_date[2], $last_date[0]);
					$sunday_list[$i]['day'] = $last_date[2];
					$sunday_list[$i]['month'] = date("M",$last_time);
					$sunday_list[$i]['year'] = $last_date[0];
					$found_sunday = 1; 
				}
				$last_time += 90000;
				if($found_sunday) { $i++; $found_sunday=0; }
			}

			$this->t->set_var('span', $i);
			$this->t->set_var('month',$sunday_list[$i-1]['month']);
			$this->t->set_var('year',$sunday_list[$i-1]['year']);
			$this->t->fp('list1','month_list',True);
			$indiv_width=200; $att_width=25; $total_width=$indiv_width;
			for ($i=0; $i < count($sunday_list); $i++) {
				$link_data['menuaction'] = 'tc.tc.att_update';
				$link_data['month'] = $sunday_list[$i]['month'];
				$link_data['year'] = $sunday_list[$i]['year'];
				$link_data['day'] = $sunday_list[$i]['day'];
				$link_data['date'] = $sunday_list[$i]['date'];
				$link_data['action'] = 'update_day';
				$this->t->set_var('update_day',$GLOBALS['phpgw']->link('/tc/index.php',$link_data));
				$this->t->set_var('date',$sunday_list[$i]['date']);
				$this->t->set_var('day',$sunday_list[$i]['day']);
				$this->t->set_var('month',$sunday_list[$i]['month']);
				$this->t->set_var('year',$sunday_list[$i]['year']);
				$this->t->fp('list2','header_list',True);
				$total_width += $att_width;
			}     
		}

		if($action == 'update_day') {
			$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.att_update&action=save_day'));
			$sunday_list[0]['date'] = date("Y-m-d", mktime(0, 0, 0, $monthnum[$month], $day, $year));
			$this->t->set_var('month',$month);
			$this->t->set_var('year',$year);
			$this->t->fp('list1','month_list',True);
			$this->t->set_var('date',$date);
			$this->t->set_var('day',$day);
			$this->t->set_var('month',$month);
			$this->t->set_var('year',$year);
			$this->t->fp('list2','header_list',True);
		}           

		for ($i=0; $i < count($individual); $i++) {
			$att_table = "";
			$this->nextmatchs->template_alternate_row_color(&$this->t);
			$this->t->set_var('individual_name',$indiv_name[$i]);
			for ($j=0; $j < count($sunday_list); $j++) {
				$sql = "SELECT * FROM tc_attendance WHERE date='" .
				       $sunday_list[$j]['date'] . "' AND individual=" . $individual[$i];
				$this->db->query($sql,__LINE__,__FILE__);
				$value = $individual[$i] . "-" . $sunday_list[$j]['date'];
				if($this->db->next_record()) {
					$att_table .= '<td align=center><input type="checkbox" name="individuals_attended[]" value="'.$value.'" checked></td>';
				} else if($indiv_attending[$individual[$i]] == 1) {
					$att_table .= '<td align=center><input type="checkbox" name="individuals_attended[]" value="'.$value.'" checked></td>';
				} else {
					$att_table .= '<td align=center><input type="checkbox" name="individuals_attended[]" value="'.$value.'"></td>';
				}
			}
			$this->t->set_var('att_table',$att_table);
			$this->t->fp('list3','individual_list',True);
		} 

		$this->t->set_var('lang_done', 'Cancel');
		$this->t->set_var('lang_reset','Clear Form');
		$this->t->set_var('lang_save','Save Changes');

		$this->t->pfp('out','form');
		$this->t->pfp('addhandle','edit');

		$this->save_sessiondata();       
	}

	function dir_view()
	{
		$this->t->set_file(array('dir_view_t' => 'dir_view.tpl'));
		$this->t->set_block('dir_view_t','dir_list','list');

		$sql = "SELECT * FROM tc_individual where valid=1 and hh_position='Head of Household' ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$parent[$i]['id'] = $this->db->f('individual');
			$parent[$i]['name'] = $this->db->f('name');
			$parent[$i]['phone'] = $this->db->f('phone');
			$parent[$i]['address'] = $this->db->f('address');
			$i++;
		}   

		for ($i=0; $i < count($parent); $i++) {
			$name = $parent[$i]['name'];
			$phone = $parent[$i]['phone'];
			$address = $parent[$i]['address'];
			$this->t->set_var('name', $name);
			$this->t->set_var('address', $address);
			$this->t->set_var('phone', $phone);
			$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
			$this->t->set_var('tr_color',$tr_color);
			$this->t->fp('list','dir_list',True);
			//print "$phone $name $address<br>";
		}
		$this->t->pfp('out','dir_view_t');
		$this->save_sessiondata();   
	}
  
	function org_view()
	{
		$this->t->set_file(array('org_view_t' => 'org_view.tpl'));
		$this->t->set_block('org_view_t','calling_list','list1');
		$this->t->set_block('org_view_t','org_list','list2');

		# Display a list ordered alphabetically
		$sql = "SELECT * FROM tc_calling ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$calling[$i]['id'] = $this->db->f('individual');
			$calling[$i]['name'] = $this->db->f('name');
			$calling[$i]['position'] = $this->db->f('position');
			$calling[$i]['sustained'] = $this->db->f('sustained');
			$calling[$i]['organization'] = $this->db->f('organization');
			$i++;
		}   
		for ($i=0; $i < count($calling); $i++) {
			$name = $calling[$i]['name'];
			$position = $calling[$i]['position'];
			$sustained = $calling[$i]['sustained'];
			$organization = $calling[$i]['organization'];
			$this->t->set_var('name', $name);
			$this->t->set_var('position', $position);
			$this->t->set_var('sustained', $sustained);
			$this->t->set_var('organization', $organization);
			$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
			$this->t->set_var('tr_color',$tr_color);
			$this->t->fp('list1','calling_list',True);
		}

		# Display a list ordered by organization
		$sql = "SELECT * FROM tc_calling ORDER BY sequence ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$calling[$i]['id'] = $this->db->f('individual');
			$calling[$i]['name'] = $this->db->f('name');
			$calling[$i]['position'] = $this->db->f('position');
			$calling[$i]['sustained'] = $this->db->f('sustained');
			$calling[$i]['organization'] = $this->db->f('organization');
			$i++;
		}   
		for ($i=0; $i < count($calling); $i++) {
			$name = $calling[$i]['name'];
			$position = $calling[$i]['position'];
			$sustained = $calling[$i]['sustained'];
			$organization = $calling[$i]['organization'];
			$this->t->set_var('name', $name);
			$this->t->set_var('position', $position);
			$this->t->set_var('sustained', $sustained);
			$this->t->set_var('organization', $organization);
			$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
			$this->t->set_var('tr_color',$tr_color);
			$this->t->fp('list2','org_list',True);
		}

		$this->t->pfp('out','org_view_t');
		$this->save_sessiondata();   
	}
  
	function schedule()
	{
		$this->t->set_file(array('sched_t' => 'schedule.tpl'));
		$this->t->set_block('sched_t','presidency_list','list');

		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.schedule&action=save'));
		$this->t->set_var('title','Scheduling Tool');

		$this->t->set_var('lang_save','Save Schedule');
		$this->t->set_var('lang_reset','Cancel');

		$this->t->set_var('schedule_vis_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.vis_sched'));
		$this->t->set_var('schedule_vis_link_title','Schedule Yearly Visits');

		$this->t->set_var('schedule_int_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.int_sched'));
		$this->t->set_var('schedule_int_link_title','Schedule Hometeaching Interviews');

		$this->t->set_var('schedule_ppi_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.ppi_sched'));
		$this->t->set_var('schedule_ppi_link_title','Schedule Yearly PPIs');

		$date_width=150; $time_width=220; $indiv_width=170; $family_width=180; $location_width=100;
		$table_width=$date_width + $time_width + $indiv_width + $family_width + $location_width;
		$header_row = "<th width=$date_width><font size=-2>Date</th>";
		$header_row.= "<th width=$time_width><font size=-2>Time</th>";      
		$header_row.= "<th width=$indiv_width><font size=-2>individual</th>";
		$header_row.= "<th width=$family_width><font size=-2>Family</th>";
		$header_row.= "<th width=$location_width><font size=-2>Location</th>";
		$table_data = "";

		$sql = "SELECT * FROM tc_presidency where valid=1 GROUP BY individual ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$presidency_data[$i]['id'] = $this->db->f('presidency');
			$presidency_data[$i]['name'] = $this->db->f('name');
			$presidency_data[$i]['indiv'] = $this->db->f('individual');
			$presidency2name[$presidency_data[$i]['id']] = $presidency_data[$i]['name'];
			$presidency2indiv[$presidency_data[$i]['id']] = $presidency_data[$i]['individual'];
			$i++;
		}

		$sql = "SELECT * FROM tc_family where valid=1 and individual != 0 ORDER BY name ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$family_id[$i] = $this->db->f('family');
			$family_name[$i] = $this->db->f('name');
			$familyid2name[$family_id[$i]] = $family_name[$i];
			$sql = "SELECT * FROM tc_individual where family='$family_id[$i]' and hh_position='Head of Household'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$familyid2address[$family_id[$i]] = $this->db2->f('address');
			}
			$i++;
		}
		array_multisort($family_name, $family_id);

		if($action == 'save') {
			$new_data = get_var('sched',array('POST'));
			foreach ($new_data as $presidency_array) {
				foreach ($presidency_array as $entry) {
					$presidency = $entry['presidency'];
					$appointment = $entry['appointment'];
					$location = $entry['location'];
					$date = $entry['date'];
					$hour = $entry['hour'];
					$minute = $entry['minute'];
					$pm = $entry['pm'];
					$indiv = $entry['individual'];
					$family = $entry['family'];
					$location = $entry['location'];
					if($pm) { $hour = $hour + 12; }
					$time = $hour.':'.$minute.':'.'00';
					$uid = 0;

					// Update our location
					if($location == "") {
						if($family > 0) {
							$family_name_array = explode(",", $familyid2name[$family]);
							$family_last_name = $family_name_array[0];
							$family_address = $familyid2address[$family];
							$location = "$family_last_name"." home ($family_address)";
						} else if($indiv > 0) {
							$supervisor_name_array = explode(",",$presidency2name[$presidency]);
							$supervisor_last_name = $supervisor_name_array[0];
							$sql = "SELECT * FROM tc_individual where individual='$presidency2indiv[$presidency]'";
							$this->db2->query($sql,__LINE__,__FILE__);
							if($this->db2->next_record()) {
								$mls_id = $this->db2->f('mls_id');
							}
							$sql = "SELECT * FROM tc_individual where mls_id='$mls_id'";
							$this->db2->query($sql,__LINE__,__FILE__);
							if($this->db2->next_record()) {
								$supervisor_address = $this->db2->f('address');
							}
							$location = "$supervisor_last_name"." home ($supervisor_address)";
						}
					}

					// Zero out the family or individual if date = NULL
					if($date == "") {
						$indiv = 0;
						$family = 0;
						$location = "";
					}

					if(($indiv == 0) && ($family == 0)) { $location = ""; }

					// Update an existing appointment
					if($appointment < $this->max_appointments)
					{
						//Only perform a database update if we have made a change to this appointment
						$sql = "SELECT * FROM tc_appointment where " .
						       "appointment='$appointment'" .
						       " and presidency='$presidency'" .
						       " and individual='$indiv'" .
						       " and family='$family'" .
						       " and date='$date'" .
						       " and time='$time'" .
						       " and location='$location'";
						$this->db->query($sql,__LINE__,__FILE__);
						if(!$this->db->next_record()) {
							$old_date = $this->db->f('date');
							$old_time = $this->db->f('time');
							$this->db2->query("UPDATE tc_appointment set" .
							                  " family=" . $family . 
							                  " ,individual=" . $indiv . 
							                  " ,date='" . $date . "'" .
							                  " ,time='" . $time . "'" .
							                  " ,location='" . $location . "'" .
							                  " ,presidency='" . $presidency . "'" .
							                  " WHERE appointment=" . $appointment,__LINE__,__FILE__);

							// Email the appointment
							$this->email_appt($appointment);
						}
					}

					// Add a new appointment
					else if(($appointment >= $this->max_appointments) && ($date != "") && ($time != ""))
					{
						//print "adding entry: appt=$appointment date: $date time: $time individual: $indiv family: $family<br>";
						$this->db2->query("INSERT INTO tc_appointment (appointment,presidency,family,individual,date,time,location,uid) " .
						                  "VALUES (NULL,'" . $presidency . "','" . $family . "','" . $indiv . "','" .
						                  $date . "','" . $time  . "','" . $location . "','" . $uid ."')",__LINE__,__FILE__);

						// Now reselect this entry from the database to see if we need
						// to send an appointment out for it.
						$sql = "SELECT * FROM tc_appointment where " .
						       "individual='$indiv'" .
						       " and family='$family'" .
						       " and presidency='$presidency'" .
						       " and date='$date'" .
						       " and time='$time'" .
						       " and uid='$uid'" .
						       " and location='$location'";
						$this->db3->query($sql,__LINE__,__FILE__);
						if($this->db3->next_record()) {
							// Email the appointment if warranted
							if(($date != "") && ($time != "") && (($indiv > 0) || $family > 0)) { 
								$this->email_appt($this->db3->f('appointment'));
							}
						}
					}
				}
			}

			$take_me_to_url = $GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.schedule');
			//Header('Location: ' . $take_me_to_url);
		}

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1 ORDER BY individual ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv_phone[$individual[$i]] = $this->db->f('phone');
			$i++;
		}
		array_multisort($indiv_name, $individual);

		for ($i=0; $i < count($presidency_data); $i++) {
			$presidency = $presidency_data[$i]['id'];
			$interviewer = $presidency_data[$i]['individual'];
			$name = $presidency_data[$i]['name'];
			$this->t->set_var('presidency_name',$name);
			$table_data="";

			// query the database for all the appointments
			$sql = "SELECT * FROM tc_appointment where presidency=$presidency and date>=CURDATE() ORDER BY date ASC, time ASC";
			$this->db->query($sql,__LINE__,__FILE__);

			// Prefill any existing appointment slots
			while ($this->db->next_record()) {
				$appointment = $this->db->f('appointment');
				$indiv = $this->db->f('individual');
				$family = $this->db->f('family');
				$location = $this->db->f('location');

				if($location == "") {
					if($family > 0) {
						$family_name_array = explode(",", $familyid2name[$family]);
						$family_last_name = $family_name_array[0];
						$family_address = $familyid2address[$family];
						$location = "$family_last_name"." home ($family_address)";
					} else if($indiv > 0) {
						$supervisor_name_array = explode(",",$presidency2name[$presidency]);
						$supervisor_last_name = $supervisor_name_array[0];
						$sql = "SELECT * FROM tc_individual where individual='$presidency2indiv[$presidency]'";
						$this->db2->query($sql,__LINE__,__FILE__);
						if($this->db2->next_record()) {
							$mls_id = $this->db2->f('mls_id');
						}
						$sql = "SELECT * FROM tc_individual where mls_id='$mls_id'";
						$this->db2->query($sql,__LINE__,__FILE__);
						if($this->db2->next_record()) {
							$supervisor_address = $this->db2->f('address');
						}
						$location = "$supervisor_last_name"." home ($supervisor_address)";
					}
				}

				$date = $this->db->f('date');
				$date_array = explode("-",$date);
				$year = $date_array[0]; $month = $date_array[1]; $day = $date_array[2];
				$day_string = date("l d-M-Y", mktime(0,0,0,$month,$day,$year));

				$time = $this->db->f('time');
				$time_array = explode(":",$time);
				$hour = $time_array[0];
				$minute = $time_array[1];
				$pm = 0;
				if($hour > 12) { $pm=1; $hour = $hour - 12; }
				$time_string = date("g:i a", mktime($time_array[0], $time_array[1], $time_array[2]));

				$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";

				// Date selection
				$table_data.= '<td align=left>';
				$table_data.= $this->jscal->input('sched['.$presidency.']['.$appointment.'][date]',$date,'','','','','',$this->cal_options);
				$table_data.= '</td>';

				// Hour & Minutes selection
				$table_data.= "<td align=center>";
				$table_data .= $this->get_time_selection_form($hour, $minute, $pm, $presidency, $appointment);
				$table_data.= "</td>";

				// individual drop down list (for PPIs)
				$table_data.= '<td align=center><select name=sched['.$presidency.']['.$appointment.'][indiv] STYLE="font-size : 8pt">';
				$table_data.= '<option value=0></option>';  
				for ($j=0; $j < count($individual); $j++) {
					$id = $individual[$j];
					$name = $indiv_name[$j];
					if($individual[$j] == $indiv) {
						$selected[$id] = 'selected="selected"'; 
					} else {
						$selected[$id] = ''; 
					}
					$table_data.= '<option value='.$id.' '.$selected[$id].'>'.$name.'</option>';
				}
				$table_data.='</select></td>';

				// Family drop down list (for Visits)
				$table_data.= '<td align=center><select name=sched['.$presidency.']['.$appointment.'][family] STYLE="font-size : 8pt">';
				$table_data.= '<option value=0></option>';  	    
				for ($j=0; $j < count($individual); $j++) {
					$id = $family_id[$j];
					$name = $family_name[$j];
					if($family_id[$j] == $family) { 
						$selected[$id] = 'selected="selected"'; 
					} else { 
						$selected[$id] = ''; 
					}
					$table_data.= '<option value='.$id.' '.$selected[$id].'>'.$name.' Family</option>';
				}
				$table_data.='</select></td>';

				// Location text box
				$table_data.= '<td align=center><input type=text size="25" maxlength="120" ';
				$table_data.= 'name="sched['.$presidency.']['.$appointment.'][location]" value="'.$location.'" STYLE="font-size : 8pt">';

				$table_data.= '<input type=hidden name="sched['.$presidency.']['.$appointment.'][appointment]" value="'.$appointment.'">';
				$table_data.= '<input type=hidden name="sched['.$presidency.']['.$appointment.'][presidency]" value="'.$presidency.'">';

				$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
				$this->t->set_var('tr_color',$tr_color);
			}

			// Create blank appointment slot
			for ($b=0; $b < 4; $b++) {
				$appointment = $this->max_appointments + $b;
				$table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') .">";

				// Date selection
				$table_data.= '<td align=left>';
				$table_data.= $this->jscal->input('sched['.$presidency.']['.$appointment.'][date]','','','','','','',$this->cal_options);
				$table_data.= '</td>';

				// Time selection
				$table_data.= "<td align=center>";
				$table_data .= $this->get_time_selection_form(0, 0, 0, $presidency, $appointment);
				$table_data.= "</td>";

				// individual drop down list
				$table_data.= '<td align=center><select name=sched['.$presidency.']['.$appointment.'][indiv] STYLE="font-size : 8pt">';
				$table_data.= '<option value=0></option>';  
				for ($j=0; $j < count($individual); $j++) {
					$id = $individual[$j];
					$name = $indiv_name[$j];
					$table_data.= '<option value='.$id.'>'.$name.'</option>';
				}
				$table_data.='</select></td>';

				// Family drop down list
				$table_data.= '<td align=center><select name=sched['.$presidency.']['.$appointment.'][family] STYLE="font-size : 8pt">';
				$table_data.= '<option value=0></option>';  	    
				for ($j=0; $j < count($individual); $j++) {
					$id = $family_id[$j];
					$name = $family_name[$j];
					$table_data.= '<option value='.$id.'>'.$name.' Family</option>';
				}
				$table_data.='</select></td>';

				// Location text box
				$table_data.= '<td align=center><input type=text size="25" maxlength="120" ';
				$table_data.= 'name="sched['.$presidency.']['.$appointment.'][location]" value="" STYLE="font-size : 8pt">';

				$table_data.= '<input type=hidden name="sched['.$presidency.']['.$appointment.'][appointment]" value="'.$appointment.'">';
				$table_data.= '<input type=hidden name="sched['.$presidency.']['.$appointment.'][presidency]" value="'.$presidency.'">';

				$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
				$this->t->set_var('tr_color',$tr_color);
			}

			$this->t->set_var('table_data',$table_data);
			$this->t->set_var('header_row',$header_row);
			$this->t->set_var('table_width',$table_width);
			$this->t->fp('list','presidency_list',True);
		}

		$this->t->pfp('out','sched_t');
		$this->save_sessiondata();   
	}

	function email()
	{
		$this->t->set_file(array('email_t' => 'email.tpl'));
		$this->t->set_block('email_t','individual_list','list');

		$action = get_var('action',array('GET','POST'));

		$this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.email'));
		$this->t->set_var('title','Email Tool');

		$this->t->set_var('lang_email','Send Email');
		$this->t->set_var('lang_reset','Cancel');

		$this->t->set_var('email_member_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.email&action=member'));
		$this->t->set_var('email_member_link_title','Email Quorum Member');

		$this->t->set_var('email_quorum_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.email&action=quorum'));
		$this->t->set_var('email_quorum_link_title','Email Quorum');

		$this->t->set_var('email_reminder_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.email&action=reminder'));
		$this->t->set_var('email_reminder_link_title','Email Reminders');

		$this->t->set_var('email_edit_link',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.email&action=edit'));
		$this->t->set_var('email_edit_link_title','Edit Email Addresses');

		$table_width=600;
		$this->t->set_var('table_width',$table_width);

		$this->t->pfp('out','email_t');
		$this->save_sessiondata();   
	}

	function admin()
	{
		$this->t->set_file(array('admin_t' => 'admin.tpl'));
		$this->t->set_block('admin_t','upload','uploadhandle');
		$this->t->set_block('admin_t','admin','adminhandle');
		$this->t->set_block('admin_t','cmd','cmdhandle');
		$this->t->set_block('admin_t','presidency','presidencyhandle');

		$this->t->set_var('upload_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.admin&action=upload'));
		$this->t->set_var('presidency_action',$GLOBALS['phpgw']->link('/tc/index.php','menuaction=tc.tc.admin&action=presidency'));

		$action = get_var('action',array('GET','POST'));

		$this->t->pfp('out','admin_t');

		// TODO:  changed this so it picks the quorum dynamically
		$sql = "SELECT * FROM tc_individual where steward='Elder' and valid=1 ORDER BY individual ASC";
		$this->db->query($sql,__LINE__,__FILE__);
		$i=0;
		while ($this->db->next_record()) {
			$individual[$i] = $this->db->f('individual');
			$indiv_name[$i] = $this->db->f('name');
			$indiv2name[$individual[$i]] = $indiv_name[$i];
			$i++;
		}
		array_multisort($indiv_name, $individual);

		if($action == 'upload') {
			$target_path = $this->upload_target_path . '/' . basename( $_FILES['uploadedfile']['name']);

			if(($_FILES['uploadedfile']['type'] == "application/zip") ||
			   ($_FILES['uploadedfile']['type'] == "application/x-zip-compressed") ||
			   ($_FILES['uploadedfile']['type'] == "application/x-zip") ||
			   ($_FILES['uploadedfile']['type'] == "application/octet-stream")) {

				if(!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					$uploadstatus = "<b><font color=red> -E- Unable to move the uploaded file to ";
					$uploadstatus.= "the target path (check the path and permissions) of: $target_path</font></b>";
					$uploadstatus = "<b>The following file was uploaded successfully: </b><br><br>";
					$uploadstatus.= "Tmp Filename : " . $_FILES['uploadedfile']['tmp_name'] . "<br>";
					$uploadstatus.= "Filename     : " . $_FILES['uploadedfile']['name'] . "<br>";
					$uploadstatus.= "Type         : " . $_FILES['uploadedfile']['type'] . "<br>";
					$uploadstatus.= "Size         : " . $_FILES['uploadedfile']['size'] . "<br>";
					$uploadstatus.= "Error        : " . $_FILES['uploadedfile']['error'] . "<br>";	 
					$this->t->set_var('uploadstatus',$uploadstatus);
					$this->t->pfp('uploadhandle','upload',True);
					return 0;
				}

				$uploadstatus = "<b>The following file was uploaded successfully: </b><br><br>";
				$uploadstatus.= "Filename : " . $_FILES['uploadedfile']['name'] . "<br>";
				$uploadstatus.= "Type     : " . $_FILES['uploadedfile']['type'] . "<br>";
				$uploadstatus.= "Size     : " . $_FILES['uploadedfile']['size'] . "<br>";	 
				$this->t->set_var('uploadstatus',$uploadstatus);
				$this->t->pfp('uploadhandle','upload');
				$this->t->set_var('uploadhandle','');
				print "<table border=1 width=80%><tr><td>\n<pre>";

				# make a directory for this data to be stored in
				$date="data_" . date("Y_m_d");
				$data_dir = $this->upload_target_path . '/' . $date;
				print "-> Making the data directory: $date<br>\n";
				exec('mkdir -p ' . $data_dir . ' 2>&1', $result, $return_code);
				if($return_code != 0) {
					print implode('\n',$result) . "<br>";
					print "<b><font color=red>";
					print "-E- Unable to create the data directory. Aborting import.";
					print "</font></b>";
					return 0;
				}

				# move the file uploaded into this directory
				print "-> Moving the uploaded file into the data dir<br>\n";
				exec('mv ' . $target_path . ' ' . $data_dir . '/' . ' 2>&1', $result, $return_code);
				if($return_code != 0) {
					print implode('\n',$result) . "<br>";
					print "<b><font color=red>";
					print "-E- Unable to move the uploaded file into the data dir. Aborting import.";
					print "</font></b>";
					return 0;
				}

				# unzip the data into this directory
				print "-> Unzipping the data<br>\n";
				exec($this->unzip_path .' -u '. $data_dir . '/*.zip -d ' . $data_dir . ' 2>&1', $result, $return_code);
				if($return_code != 0) {
					print implode('\n',$result) . "<br>";
					print "<b><font color=red>";
					print "-E- Unable to unzip the uploaded file into the data dir: $data_dir. Aborting import.";
					print "</font></b>";
					return 0;
				}
				exec('mv ' . $data_dir . '/*/* '. $data_dir . ' 2>&1', $result, $return_code);

				# update the data_latest link to point to this new directory
				print "-> Updating the latest data dir link<br>\n";
				$data_latest = $this->upload_target_path . '/data_latest';
				exec('rm ' . $data_latest. '; ln -s ' . $data_dir .' '. $data_latest .' 2>&1', $result, $return_code);
				if($return_code != 0) {
					print implode('\n',$result) . "<br>";
					print "<b><font color=red>";
					print "-E- Unable to update the data latest link. Aborting import.";
					print "</font></b>";
					return 0;
				}

				# run the import perl script to encorporate it into the DB
				ob_start('ob_logstdout', 2);
				print "-> Importing the data into the database<br>\n";
				ob_flush(); flush(); sleep(1);
				$import_log = $this->upload_target_path . '/import.log';
				$data_log = $this->upload_target_path . '/data.log';
				$import_cmd = $this->script_path . '/import_ward_data ' . $data_latest . ' 2>&1 | tee ' . $import_log;
				$parse_cmd = $this->script_path . '/parse_ward_data -v ' . $data_latest . ' > ' . $data_log . '2>&1';
				#print "import_cmd: $import_cmd<br>";
				#print "parse_cmd: $parse_cmd<br>";
				ob_start('ob_logstdout', 2);
				passthru($import_cmd);
				passthru($parse_cmd);
				ob_flush(); flush(); sleep(1);

				# fix the permissions of the data dir
				exec('chmod -R o-rwx ' . $data_dir, $result, $return_code);

				$this->t->pfp('cmdhandle','cmd');
				print "</pre></td></tr></table>";
			} else if(($_FILES['uploadedfile']['type'] != "application/zip") &&
			          ($_FILES['uploadedfile']['type'] != "application/x-zip-compressed") &&
			          ($_FILES['uploadedfile']['type'] != "application/x-zip") &&
			          ($_FILES['uploadedfile']['type'] != "application/octet-stream")) {
				$uploadstatus = "<b><font color=red>The file format must be a .zip file, please try again! </font></b>";
				$uploadstatus.= "<br><br><b>Detected file format: " . $_FILES['uploadedfile']['type'] . "</b>";
				$this->t->set_var('uploadstatus',$uploadstatus);
				$this->t->pfp('uploadhandle','upload',True);
			} else {
				$uploadstatus = "<b><font color=red> There was an error (" . $_FILES['uploadedfile']['error'];
				$uploadstatus.= ") uploading the file, please try again! </font></b>";
				$this->t->set_var('uploadstatus',$uploadstatus);
				$this->t->pfp('uploadhandle','upload',True);
			}
		} else if($action == "presidency") {
			$new_data = get_var('eqpres',array('POST'));
			foreach ($new_data as $entry) {
				$id = $entry['id'];
				$email = $entry['email'];
				$indiv = $entry['indiv'];
				$name = $entry['name'];
				$district = $entry['district'];
				$president = $entry['president'];
				$counselor = $entry['counselor'];
				$secretary = $entry['secretary'];
				// look up the individual name for the ID
				$name = $indiv2name[$indiv]; 
				//print "id=$id indiv=$indiv name=$name email=$email district=$district president=$president ";
				//print "counselor=$counselor secretary=$secretary<br>";

				if(($indiv > 0) || ($name != "")) {
					if($id < $this->max_presidency_members) {
						//print "Updating Existing Entry<br>";
						$this->db2->query("UPDATE tc_presidency set" .
						                  " individual=" . $indiv . 
						                  " ,district=" . $district . 
						                  " ,name='" . $name . "'" .
						                  " ,email='" . $email . "'" .
						                  " ,president='" . $president . "'" .
						                  " ,counselor='" . $counselor . "'" .
						                  " ,secretary='" . $secretary . "'" .
						                  " WHERE presidency=" . $id,__LINE__,__FILE__);
					} else {
						//print "Adding New Entry<br>";
						$this->db2->query("INSERT INTO tc_presidency (presidency,individual,district,name," .
						                  "email,president,counselor,secretary,valid) " .
						                  "VALUES (NULL,'" . $indiv . "','" . $district . "','" .
						                  $name . "','" . $email . "','" . $president  . "','" .
						                  $counselor . "','" . $secretary . "','1'" .
						                  ")",__LINE__,__FILE__);
					}
				} else {
					//print "Ignoring Blank Entry<br>";
				}
			}

			// Now update the tc_district table appropriately

			// Delete all the previous district entries from the table
			$this->db->query("DELETE from tc_district where valid=1",__LINE__,__FILE__);
			$this->db->query("DELETE from tc_district where valid=0",__LINE__,__FILE__);

			// Always add a "District 0" assigned to the High Priests Group
			$district = 0;
			$name = "High Priests";
			$indiv = 0;
			$valid = 0;
			$this->db2->query("INSERT INTO tc_district (district,name,supervisor,valid) " .
			                  "VALUES ('" . $district . "','" . $name . "','" .
			                  $indiv . "','" . $valid . "'" .
			                  ")",__LINE__,__FILE__);

			// Requery the tc_presidency table
			$sql = "SELECT * FROM tc_presidency where valid=1";
			$this->db->query($sql,__LINE__,__FILE__);
			while ($this->db->next_record()) {
				// Extract the data for each presidency record
				$id = $this->db->f('presidency');
				$indiv = $this->db->f('individual');
				$name = $this->db->f('name');
				$district = $this->db->f('district');
				$name = $this->db->f('name');
				$valid = 1;

				// If we have a valid district, add it to the district table
				if($district > 0) {
					$this->db2->query("INSERT INTO tc_district (district,name,supervisor,valid) " .
					                  "VALUES ('" . $district . "','" . $name . "','" .
					                  $indiv . "','" . $valid . "'" .
					                  ")",__LINE__,__FILE__);
				}
			}

			$this->t->set_var('adminhandle','');
			$this->t->pfp('adminhandle','admin'); 
		}
		else
		{
			$this->t->set_var('adminhandle','');
			$this->t->pfp('adminhandle','admin'); 
		}

		// Now save off the data needed for a Presidency Table Update

		$sql = "SELECT * FROM tc_presidency where valid=1";
		$this->db->query($sql,__LINE__,__FILE__);
		$table_data = "";
		$header_row = "<th>Individual</th><th>Email</th><th>District</th><th>President</th><th>Counselor</th><th>Secretary</th>";
		while ($this->db->next_record()) {
			// Extract the data for each presidency record
			$id = $this->db->f('presidency');
			$indiv = $this->db->f('individual');
			$district = $this->db->f('district');
			$name = $this->db->f('name');
			$email = $this->db->f('email');
			$president = $this->db->f('president');
			$counselor = $this->db->f('counselor');
			$secretary = $this->db->f('secretary');

			// Create the forms needed in the table
			$table_data .= "<tr bgcolor=". $this->t->get_var('tr_color') .">";

			// Presidency ID
			$table_data .= '<input type=hidden name="eqpres['.$id.'][id]" value="'.$id.'">';

			// individual
			if($eqpresidency == 0) {
				$table_data.= '<td align=center><select name="eqpres['.$id.'][indiv]">';
				$table_data.= '<option value=0></option>';  
				for ($j=0; $j < count($individual); $j++) {
					$tmp_id = $individual[$j];
					$name = $indiv_name[$j];
					if($individual[$j] == $indiv) { 
						$indivname = $name; 
						$selected = 'selected="selected"'; 
					} else { 
						$selected = ''; 
					}
					$table_data.= '<option value='.$tmp_id.' '.$selected.'>'.$name.'</option>';
				}
				$table_data.='</select></td>';
				$table_data.='<input type=hidden name="eqpres['.$id.'][name]" value="'.$indivname.'">';
			} else {
				$table_data.= '<td align=left><input type=text size="20" name="eqpresname" value="Presidency"></td>';
				$table_data.= '<input type=hidden name="eqpres['.$id.'][name]" value="Presidency">';
			}

			// Email Address
			$table_data .= '<td><input type="text" size="50" name="eqpres['.$id.'][email]" value="'.$email.'"></td>';

			// District
			$table_data.= '<td align=center><select name="eqpres['.$id.'][district]">';
			$table_data.= '<option value=0></option>';
			for ($j=0; $j <= $this->max_num_districts; $j++) {
				if($district == $j) { 
					$selected = 'selected="selected"'; 
				} else { 
					$selected = ''; 
				}
				$table_data.= '<option value='.$j.' '.$selected.'>'.$j.'</option>';
			}
			$table_data.='</select></td>';

			// President
			$table_data.= '<td align=center><select name="eqpres['.$id.'][president]">';
			if($president == 1) { $table_data .= '<option value=0>0</option><option value=1 selected="selected">1</option>'; }
			else { $table_data .= '<option value=0 selected="selected">0</option><option value=1>1</option>'; }
			$table_data.='</select></td>';

			// Counselor
			$table_data.= '<td align=center><select name="eqpres['.$id.'][counselor]">';
			if($counselor == 1) { $table_data .= '<option value=0>0</option><option value=1 selected="selected">1</option>'; }
			else { $table_data .= '<option value=0 selected="selected">0</option><option value=1>1</option>'; }
			$table_data.='</select></td>';

			// Secretary
			$table_data.= '<td align=center><select name="eqpres['.$id.'][secretary]">';
			if($secretary == 1) { $table_data .= '<option value=0>0</option><option value=1 selected="selected">1</option>'; }
			else { $table_data .= '<option value=0 selected="selected">0</option><option value=1>1</option>'; }
			$table_data.='</select></td>';

			// End of ROW
			$table_data .= "</tr>\n";
			$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
			$this->t->set_var('tr_color',$tr_color);
		}

		// Now create 1 blank row to always have a line available to add a new individual with
		$id = $this->max_presidency_members;
		$table_data .= "<tr bgcolor=". $this->t->get_var('tr_color') .">";
		// Presidency ID
		$table_data .= '<input type=hidden name="eqpres['.$id.'][id]" value="'.$id.'">';
		// individual
		$table_data.= '<td align=center><select name="eqpres['.$id.'][indiv]">';
		$table_data.= '<option value=0></option>';  
		for ($j=0; $j < count($individual); $j++) {
			$tmp_id = $individual[$j];
			$name = $indiv_name[$j];
			$table_data.= '<option value='.$tmp_id.'>'.$name.'</option>';
		}
		$table_data.='</select></td>';
		$table_data.='<input type=hidden name="eqpres['.$id.'][name]" value="">';
		// Email Address
		$table_data.='<td><input type="text" size="50" name="eqpres['.$id.'][email]" value=""></td>';
		// District
		$table_data.= '<td align=center><select name="eqpres['.$id.'][district]">';
		$table_data.= '<option value=0></option>';
		for ($j=0; $j <= $this->max_num_districts; $j++) {
			if($j == 0) { 
				$selected = 'selected="selected"'; 
			} else { 
				$selected = ''; 
			}
			$table_data.= '<option value='.$j.' '.$selected.'>'.$j.'</option>';
		}
		$table_data.='</select></td>';
		// President
		$table_data.= '<td align=center><select name="eqpres['.$id.'][president]">';
		$table_data.= '<option value=0>0</option><option value=1>1</option>';
		$table_data.='</select></td>';
		// Counselor
		$table_data.= '<td align=center><select name="eqpres['.$id.'][counselor]">';
		$table_data.= '<option value=0>0</option><option value=1>1</option>';
		$table_data.='</select></td>';
		// Secretary
		$table_data.= '<td align=center><select name="eqpres['.$id.'][secretary]">';
		$table_data.= '<option value=0>0</option><option value=1>1</option>';
		$table_data.='</select></td>';
		// End of ROW
		$table_data .= "</tr>\n";
		$tr_color = $this->nextmatchs->alternate_row_color($tr_color);
		$this->t->set_var('tr_color',$tr_color);

		$this->t->set_var('header_row',$header_row);
		$this->t->set_var('table_data',$table_data);
		$this->t->pfp('presidencyhandle','presidency',True);

		$this->save_sessiondata();   
	}

	function email_appt($appointment)
	{
		//print "Emailing notification of appointment: $appointment <br>";

		$sql = "SELECT * FROM tc_appointment where appointment='$appointment'";
		$this->db->query($sql,__LINE__,__FILE__);

		while ($this->db->next_record()) {
			$appointment = $this->db->f('appointment');
			$presidency = $this->db->f('presidency');
			$location = $this->db->f('location');
			$interviewer = "";
			$email = "";
			$indiv = $this->db->f('individual');
			$indiv_name = "";
			$family = $this->db->f('family');
			$family_name = "";
			$appt_name = "";
			$phone = "";
			$uid = $this->db->f('uid');

			// Extract the year, month, day, hours, minutes, seconds from the appointment time
			$appt_date = $this->db->f('date');
			$date_array = explode("-",$appt_date);
			$year = $date_array[0]; $month = $date_array[1]; $day = $date_array[2];
			$appt_time = $this->db->f('time');
			$time_array = explode(":",$appt_time);
			$hour = $time_array[0]; $minute = $time_array[1]; $seconds = $time_array[2];

			// Format the appointment time into an iCal UTC equivalent
			$dtstamp = gmdate("Ymd"."\T"."His"."\Z");
			$dtstart = gmdate("Ymd"."\T"."His"."\Z", mktime($hour,$minute,$seconds,$month,$day,$year));
			$dtstartstr = date("l, F d, o g:i A", mktime($hour,$minute,$seconds,$month,$day,$year));

			$sql = "SELECT * FROM tc_presidency where presidency='$presidency'";
			$this->db2->query($sql,__LINE__,__FILE__);
			if($this->db2->next_record()) {
				$email = $this->db2->f('email');
				$interviewer = $this->db2->f('name');
			}

			// Set the email address of the interviewer
			$from = $email;

			if($indiv > 0) {
				$sql = "SELECT * FROM tc_individual where individual='$indiv'";
				$this->db2->query($sql,__LINE__,__FILE__);
				if($this->db2->next_record()) {
					$indiv_name = $this->db2->f('name');
					$phone = $this->db2->f('phone');
					$appt_name = $indiv_name . " Interview";
					$duration = $this->default_ppi_appt_duration * 60;
				}
			}

			if($family > 0) {
				$sql = "SELECT * FROM tc_family where family='$family'";
				$this->db2->query($sql,__LINE__,__FILE__);
				if($this->db2->next_record()) {
					$family_name = $this->db2->f('name');
					$phone = $this->db2->f('phone');
					$individual = $this->db2->f('individual');
					$appt_name = $family_name . " Family Visit";
					$sql = "SELECT * FROM tc_individual where individual='$individual'";
					$this->db3->query($sql,__LINE__,__FILE__);
					if($this->db3->next_record()) {
						$phone = $this->db3->f('phone');
					}
					$duration = $this->default_visit_appt_duration * 60;
				}
			}

			$dtend = gmdate("Ymd"."\T"."His"."\Z", mktime($hour,$minute,$seconds+$duration,$month,$day,$year));
			$dtendstr = date("g:i A", mktime($hour,$minute,$seconds+$duration,$month,$day,$year));
			$date = $dtstartstr . "-" . $dtendstr;
			$description = "$appt_name : $phone";

			if(($uid == 0) && ($appt_name != "")) {
				// Create a new calendar item for this appointment, since this must be the first time we
				// are sending it out.
				print "Sent new appointment to " . $interviewer . " at " . $email . " for " . $appt_name . "<br>";
				$uid = rand() . rand(); // Generate a random identifier for this appointment
				$subject = "Created: $appt_name";

				$this->db->query("UPDATE tc_appointment set" .
				                 " uid=" . $uid . 
				                 " WHERE appointment=" . $appointment,__LINE__,__FILE__);

				$action = "PUBLISH";
				$this->send_ical_appt($action, $email, $from, $subject, $dtstamp, $dtstart,
				                      $dtend, $date, $location, $appt_name, $description, $uid);
			} else if(($uid != 0) && ($appt_name == "")) {
				// Remove the calendar item for this appointment since it has already been sent
				// and there is no name we have changed it to.
				print "Sent deleted appointment to " . $interviewer . " at " . $email . " for " . $appt_date . " " . $appt_time . "<br>";
				$subject = "Canceled: $appt_date $appt_time";

				$this->db->query("UPDATE tc_appointment set" .
				                 " uid=0" . 
				                 " WHERE appointment=" . $appointment,__LINE__,__FILE__);

				$action = "CANCEL";
				$this->send_ical_appt($action, $email, $from, $subject, $dtstamp, $dtstart,
				                      $dtend, $date, $location, $subject, $subject, $uid);
			} else if($uid != 0) {
				// Update the existing appointment since we have changed it
				print "Sent updated appointment to " . $interviewer . " at " . $email . " for " . $appt_name . "<br>";

				$subject = "Canceled: $appt_date $appt_time";
				$action = "CANCEL";
				$this->send_ical_appt($action, $email, $from, $subject, $dtstamp, $dtstart,
				$dtend, $date, $location, $subject, $subject, $uid);

				$uid = rand() . rand(); // Generate a random identifier for this appointment
				$this->db->query("UPDATE tc_appointment set" .
				                 " uid=" . $uid .
				                 " WHERE appointment=" . $appointment,__LINE__,__FILE__);

				$subject = "Updated: $appt_name";
				$action = "PUBLISH";
				$this->send_ical_appt($action, $email, $from, $subject, $dtstamp, $dtstart,
				                      $dtend, $date, $location, $appt_name, $description, $uid);
			}
		}
		return true;
	}

	function send_ical_appt($action, $to, $from, $subject, $dtstamp, $dtstart, $dtend, $date, $location, $summary, $description, $uid)
	{
		// Initialize our local variables
		$boundary = "=MIME_APPOINTMENT_BOUNDARY";
		$message = "";
		$headers = "";

		// Form the headers for the email message
		$headers.="X-Mailer: PHP/" . phpversion() . "\n";
		$headers.="Mime-Version: 1.0\n";
		$headers.="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
		$headers.="Content-Disposition: inline\n";
		$headers.="Reply-To: $from\n";
		$headers.="From: $from\n";

		// Print the plaintext version of the appointment
		$message.="--$boundary\n";
		$message.="Content-Type: text/plain; charset=us-ascii\n";
		$message.="Content-Disposition: inline\n";
		$message.="\n";
		$message.="What: $description\n";
		$message.="When: $date\n";
		$message.="Where: $location\n";
		$message.="\n";

		// Print the .ics attachment version of the appointment
		$message.="--$boundary\n";
		$message.="Content-Type: text/calendar; charset=us-ascii\n";
		$message.="Content-Disposition: attachment; filename=\"appointment.ics\"\n";
		$message.="\n";
		$message.="BEGIN:VCALENDAR" . "\n";
		$message.="VERSION:2.0" . "\n";
		$message.="PRODID:-//Microsoft Corporation//Outlook 11.0 MIMEDIR//EN" . "\n";
		$message.="METHOD:$action" . "\n";
		$message.="BEGIN:VEVENT" . "\n";
		$message.="ORGANIZER:MAILTO:$from". "\n";
		$message.="DTSTAMP:$dtstamp" . "\n";
		$message.="DTSTART:$dtstart" . "\n";
		$message.="DTEND:$dtend" . "\n";
		$message.="SUMMARY:$summary" . "\n";
		$message.="DESCRIPTION:$description" . "\n";
		$message.="LOCATION:$location" . "\n";
		$message.="UID:$uid" ."\n";
		$message.="TRANSP:OPAQUE" . "\n";
		$message.="SEQUENCE:0" . "\n";
		$message.="CLASS:PUBLIC" . "\n";
		$message.="END:VEVENT" . "\n";
		$message.="END:VCALENDAR" . "\n";

		// Complete the message
		$message.="--$boundary\n";

		// Send the message
		mail($to, $subject, $message, $headers);
	}

	function get_time_selection_form($hour, $minute, $pm, $presidency, $appointment)
	{
		$form_data = "";
		$blank = 0;

		if($hour == 0) { $blank = 1; }

		if($this->time_drop_down_lists == 1) {
			// Create drop down lists to get the time
			$form_data.= '<select name=sched['.$presidency.']['.$appointment.'][hour]>';
			if($blank == 1) { $form_data.= '<option value=""></option>'; }
			foreach(range(1,12) as $num) {
				if($hour == $num) { 
					$selected = 'selected="selected"'; 
				} else { 
					$selected = ''; 
				}
				$form_data.= '<option value='.$num.' '.$selected.'>'.$num.'</option>';
			}
			$form_data.= '</select>';
			$form_data.= '&nbsp;:&nbsp;';
			$form_data.= '<select name=sched['.$presidency.']['.$appointment.'][minute]>';
			if($blank == 1) { $form_data.= '<option value=""></option>'; }
			$num = 0;
			while($num < 60) {
				if($num < 10) { $num = "0" . "$num"; }
				if($minute == $num) { 
					$selected = 'selected="selected"'; 
				} else { 
					$selected = ''; 
				}
				if($blank == 1) { $selected = ""; }
				$form_data.= '<option value='.$num.' '.$selected.'>'.$num.'</option>';
				$num = $num + $this->time_drop_down_list_inc;
			}
			$form_data.= '</select>';
		} else {
			// Use free form text fields to get the time
			if($blank == 1) { 
				$hour = ""; 
				$minute = ""; 
				$ampm = ""; 
			}
			$form_data.= '<input type=text size=2 name=sched['.$presidency.']['.$appointment.'][hour] value='.$hour.'>';
			$form_data.= ':';
			$form_data.= '<input type=text size=2 name=sched['.$presidency.']['.$appointment.'][minute] value='.$minute.'>';
			$form_data.= '&nbsp;';
		}
		// Always use a drop-down select form for am/pm
		$form_data.= '<select name=sched['.$presidency.']['.$appointment.'][pm]>';
		if($blank == 0) {
			if($pm == 0) { 
				$form_data.= '<option value=0 selected>am</option>'; 
				$form_data.= '<option value=1>pm</option>'; 
			}
			if($pm == 1) { 
				$form_data.= '<option value=0>am</option>'; 
				$form_data.= '<option value=1 selected>pm</option>'; 
			}
		} else {
			$form_data.= '<option value=""></option>';
			$form_data.= '<option value=0>am</option>';
			$form_data.= '<option value=1>pm</option>';
		}
		$form_data.= '</select>';

		return $form_data;
	}
}

?>
