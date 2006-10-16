<?php
  /**************************************************************************\
  * phpGroupWare - eq                                                        *
  * http://www.phpgroupware.org                                              *
  * -----------------------------------------------                          *
  *  This program is free software; you can redistribute it and/or modify it *
  *  under the terms of the GNU General Public License as published by the   *
  *  Free Software Foundation; either version 2 of the License, or (at your  *
  *  option) any later version.                                              *
  \**************************************************************************/
	/* $Id: class.eq.inc.php,v 1.1.1.1 2001/05/20 07:40:32 seek3r Exp $ */

class eq
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
  var $default_att_num_months;
  var $current_year;
  var $current_month;
  var $upload_target_path;
  var $script_path;
  
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
     'vis_view'   => True,
     'vis_update' => True,
     'att_view'   => True,
     'att_update' => True,
     'dir_view'   => True,
     'org_view'   => True,
     'admin'      => True
     );
 
  function eq()
    {
      $this->default_ht_num_months = 3;
      $this->default_ppi_num_months = 3;
      $this->default_ppi_num_years = 0;
      $this->default_att_num_months = 3;
      $this->upload_target_path = "/home/users/eqpres/eq_data/";
      $this->script_path = "/usr/share/phpgroupware/eq/";
      
      $this->db		= $GLOBALS['phpgw']->db;
      $this->db2	= $this->db;
      $this->nextmatchs = CreateObject('phpgwapi.nextmatchs');
      $this->t          = $GLOBALS['phpgw']->template;
      $this->account    = $GLOBALS['phpgw_info']['user']['account_id'];
      $this->grants     = $GLOBALS['phpgw']->acl->get_grants('eq');
      $this->grants[$this->account] = PHPGW_ACL_READ + PHPGW_ACL_ADD + PHPGW_ACL_EDIT + PHPGW_ACL_DELETE;
       
      $this->jscal = CreateObject('phpgwapi.jscalendar');   // before phpgw_header() !!!
      $this->cal_options = 'daFormat    : "%Y-%m-%d",
                                ifFormat    : "%Y-%m-%d",
                                mondayFirst : false,
                                weekNumbers : false';
       
      $GLOBALS['phpgw_info']['flags']['app_header'] = 'Elders Quorum Tools';
      $GLOBALS['phpgw']->common->phpgw_header();
      
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
      $this->t->set_file(array('eq_header' => 'header.tpl'));
      
      if (isset($phpgw_info['user']['preferences']['eq']['eq_font']))
	{
	  $font = $phpgw_info['user']['preferences']['eq']['eq_font'];
	}
      else
	{
	  $font = 'Arial';
	}
      
      $this->t->set_var('bg_color',$phpgw_info['theme']['th_bg']);
      $this->t->set_var('font',$font);
      $link_data['menuaction'] = 'eq.eq.ht_view';
      $this->t->set_var('link_hometeaching',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_hometeaching','HomeTeaching');
      $link_data['menuaction'] = 'eq.eq.act_list';
      $this->t->set_var('link_activity',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_activity','Activities');
      $link_data['menuaction'] = 'eq.eq.par_view';
      $this->t->set_var('link_participation',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_participation','Participation');
      $link_data['menuaction'] = 'eq.eq.ppi_view';
      $this->t->set_var('link_ppi',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_ppi','PPIs');
      $link_data['menuaction'] = 'eq.eq.vis_view';
      $this->t->set_var('link_visit',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_visit','Visits');
      $link_data['menuaction'] = 'eq.eq.att_view';	
      $this->t->set_var('link_attendance',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_attendance','Attendance');
      $link_data['menuaction'] = 'eq.eq.dir_view';	
      $this->t->set_var('link_dir',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_dir','Directory');
      $link_data['menuaction'] = 'eq.eq.org_view';	
      $this->t->set_var('link_org',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_org','Callings');
      $link_data['menuaction'] = 'eq.eq.admin';	
      $this->t->set_var('link_admin',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_admin','Admin');
		
      $this->t->pparse('out','eq_header');
    }

  function ht_view()
    {
      $this->t->set_file(array('ht_view_t' => 'ht_view.tpl'));
      $this->t->set_block('ht_view_t','district_list','list');
   
      $this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ht_view'));
      $num_months = get_var('num_months',array('GET','POST'));
      if($num_months == '') { $num_months = $this->default_ht_num_months; }
      $this->t->set_var('num_months',$num_months);
      $this->t->set_var('lang_filter','Filter');
      if($num_months == 1) { $this->t->set_var('lang_num_months','Month of History'); }
      else {  $this->t->set_var('lang_num_months','Months of History'); }
      
      $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ht_view'));
      $this->t->set_var('title','Hometeaching'); 
      
      $sql = "SELECT * FROM eq_district where valid=1 ORDER BY district ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $districts[$i]['district'] = $this->db->f('district');
	  $districts[$i]['name'] = $this->db->f('name');
	  $districts[$i]['supervisor'] = $this->db->f('supervisor');
	  $i++;
	}

      $sql = "SELECT * FROM eq_elder where valid=1 ORDER BY elder ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $elder_id[$i] = $this->db->f('elder');
	  $elder_name[$i] = $this->db->f('name');
	  $elder_phone[$elder_id[$i]] = $this->db->f('phone');
	  $i++;
	}
      array_multisort($elder_name, $elder_id);

      // Make an array mapping elder_ids to elder_names
      for($i=0; $i < count($elder_id); $i++) {
          $id = $elder_id[$i];
          $elders[$id] = $elder_name[$i];
      }      

      $sql = "SELECT * FROM eq_aaronic where valid=1 ORDER BY aaronic ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      while ($this->db->next_record())
	{
	  $aaronic_id = $this->db->f('aaronic');
	  $aaronic[$aaronic_id]['name'] = $this->db->f('name');
	  $aaronic[$aaronic_id]['phone'] = $this->db->f('phone');
	}
      
      $total_families = 0;
      $this->nextmatchs->template_alternate_row_color(&$this->t);
      for ($i=0; $i < count($districts); $i++) {
	$this->t->set_var('district_number',$districts[$i]['district']);
	$this->t->set_var('district_name',$districts[$i]['name']);	
	$supervisor = $districts[$i]['supervisor'];
	$unique_companionships='';
		
	// Select all the unique companionship numbers for this district
	$sql = "SELECT distinct companionship FROM eq_companionship where valid=1 and district=". $districts[$i]['district'];
	$this->db->query($sql,__LINE__,__FILE__);
	$j=0;
	while ($this->db->next_record())
	  {
	    $unique_companionships[$j]['companionship'] = $this->db->f('companionship');
	    $j++;
	  }

	$comp_width=450; $visit_width=25; $table_width=$comp_width + $num_months*$visit_width;
	$table_data=""; $num_companionships = 0; $num_families = 0;
	for($m=$num_months; $m >= 0; $m--) { $visits[$m] = 0; }
	for ($j=0; $j < count($unique_companionships); $j++) {
	  $companion_table_entry = "";
	  // Select all the companions in each companionship
	  $sql = "SELECT * FROM eq_companionship where valid=1 and ".
	         "companionship=". $unique_companionships[$j]['companionship'];
	  $this->db->query($sql,__LINE__,__FILE__);

	  while ($this->db->next_record())
	    {
	      // Get this companions information
	      if($companion_table_entry != "") { $companion_table_entry .= "<td>&nbsp;/&nbsp;</td>"; }
	      $companionship = $this->db->f('companionship');
	      $elder_id = $this->db->f('elder');
	      $aaronic_id = $this->db->f('aaronic');
	      if($elder_id) {
		$name = $elders[$elder_id];
		$phone = $elder_phone[$elder_id];
	      }
	      else if($aaronic_id) {
		$name = $aaronic[$aaronic_id]['name'];
		$phone = $aaronic[$aaronic_id]['phone'];		
	      }
	      $companion_table_entry .= "<td title=\"$phone\"><b>$name</b></td>";
	    }
	  $table_data.= "<tr bgcolor=#d3dce3><td colspan=20><table><tr>$companion_table_entry</tr></table><hr></td></tr>";
	  
	  // Get the names of the families assigned this home teaching companionship
	  $sql = "SELECT * from eq_family where valid=1 AND companionship=".$unique_companionships[$j]['companionship'];
	  $sql = $sql . " ORDER BY name ASC";
	  $this->db->query($sql,__LINE__,__FILE__);
	  $k=0;
	  while ($this->db->next_record())
	    {
	      $num_families++; $total_families++;
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
		$sql = "SELECT * FROM eq_visit WHERE date >= '$month_start' AND date <= '$month_end' ".
		       " AND companionship!=0".
 		       " AND family=". $family_id;
		$this->db2->query($sql,__LINE__,__FILE__);
		$link_data['menuaction'] = 'eq.eq.ht_update';
		$link_data['date'] = $month_start;
		$link_data['month_start'] = $month_start;
		$link_data['month_end'] = $month_end;
		$link_data['month'] = $month;
		$link_data['district'] = $districts[$i]['district'];
		$link_data['district_name'] = $districts[$i]['name'];
		$link_data['action'] = 'view';
		$link = $GLOBALS['phpgw']->link('/eq/index.php',$link_data);
		$header_row .= "<th width=$visit_width><font size=-2><a href=$link>$month</a></th>";
		if(!$total_visits[$m]) { $total_visits[$m] = 0; }
		if($this->db2->next_record()) {
		  if($this->db2->f('visited') == 'y') {
		    $visits[$m]++; $total_visits[$m]++;		  
		    $table_data .= '<td align=center><a href="'.$link.'"><img src="checkmark.gif"></a></td>';
		  }
		  else if($this->db2->f('visited') == 'n') {
		    $table_data .= '<td align=center><a href="'.$link.'"><img src="x.gif"></a></td>';
		  }
		  else {
		    $visits[$m]++; $total_visits[$m]++;
		    $table_data .= "<td>&nbsp;</td>";
		  }
		}
		else {
		  $visits[$m]++; $total_visits[$m]++;
		  $table_data .= "<td>&nbsp;</td>";
		}
	      }
	      $table_data .= "</tr>"; 
	      $k++;
	    }
	  $table_data .= "<tr><td colspan=20></td></tr>";
	}
	$table_data .= "<tr><td colspan=20><hr></td></tr>";
	$stat_data = "<tr><td><b><font size=-2>$num_families Families<br>Visit Totals:</font></b></td>";

	for($m=$num_months; $m >=0; $m--) {
	  $percent = ceil(($visits[$m] / $num_families)*100);
	  $stat_data .= "<td align=center><font size=-2><b>$visits[$m]<br>$percent%</font></b></td>";
	}
	$stat_data .= "</tr>";

	$this->t->set_var('table_width',$table_width);
	$this->t->set_var('header_row',$header_row);
	$this->t->set_var('table_data',$table_data);
	$this->t->set_var('stat_data',$stat_data);
	$this->t->fp('list','district_list',True);
      }

      $totals = "<tr><td><b><font size=-2>$total_families Total Families<br>Visit Totals:</font></b></td>";
      for($m=$num_months; $m >=0; $m--) {
	$percent = ceil(($total_visits[$m] / $total_families)*100);
	$totals .= "<td align=center><font size=-2><b>$total_visits[$m]<br>$percent%</font></b></td>";
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
      
      $this->t->set_var('done_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ht_view'));
      $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ht_update&action=save'));
      $this->t->set_var('lang_done','Cancel');
      $this->t->set_var('district_name',$district_name);
      $this->t->set_var('district_number',$district);
      $this->t->set_var('title','Hometeaching Update ' . $month);
      $this->t->set_var('date',$date);
      
      if($action == 'save')
	{
	  // Get a list of all the companionships in this district
	  $sql = "SELECT distinct companionship FROM eq_companionship where valid=1 and district=". $district;
	  $this->db->query($sql,__LINE__,__FILE__);
	  $j=0;
	  while ($this->db->next_record())
	    {
	      $unique_companionships[$j]['companionship'] = $this->db->f('companionship');
	      $j++;
	    }
	  for ($j=0; $j < count($unique_companionships); $j++)
	    {
	      // FIXME: We won't be able to go back and edit history on families that have been
	      // reassigned to a different companionship. The following delete command will not delete
	      // the history of visits under an older companionship, only the ones for the existing
	      // companionship. This will lead to duplicate visits being entered for an older
	      // month for the same family, making it impossible to change the past history once
	      // a family is reassigned. However, you will be able to view the history just fine.

	      //$comp=$unique_companionships[$j]['companionship'];
	      //print "deleting from eq_visit where companionship=$comp and date=$date and district=$district<br>";
	      // Delete all the visits that have taken place for all families for this month
	      $this->db->query("DELETE from eq_visit where companionship=" . $unique_companionships[$j]['companionship'] .
			       " AND " . "date='" . $date . "'",__LINE__,__FILE__);
	    }

	  // Now, add the visits that are checked for this month
	  $new_data = get_var('family_visited',array('POST'));
	  foreach ($new_data as $family)
	   {
	     foreach ($family as $data)
	       {
		 //print "family_visited: $data <br>";
		 $data_array = explode("/",$data);
		 $family_id = $data_array[0];
		 $companionship = $data_array[1];
		 $date = $data_array[2];
		 $visited = $data_array[3];
		 if($visited == "") { $visited = $data_array[4]; }
		 //print "family_id: $family_id companionship: $companionship date: $date visited: $visited<br>";
		 $this->db->query("INSERT INTO eq_visit (family,companionship,date,notes,visited) "
		 		  . "VALUES (" . $family_id .",". $companionship .",'". $date ."','','". $visited ."')",__LINE__,__FILE__);
	       }
	   }
	  $this->ht_view();
	  return false;
	}
      
      $sql = "SELECT * FROM eq_elder where valid=1 ORDER BY elder ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $elder_id[$i] = $this->db->f('elder');
	  $elder_name[$i] = $this->db->f('name');
	  $elder_phone[$elder_id[$i]] = $this->db->f('phone');
	  $i++;
	}
      array_multisort($elder_name, $elder_id);

      // Make an array mapping elder_ids to elder_names
      for($i=0; $i < count($elder_id); $i++) {
          $id = $elder_id[$i];
          $elders[$id] = $elder_name[$i];
      }      

      $sql = "SELECT * FROM eq_aaronic where valid=1 ORDER BY aaronic ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      while ($this->db->next_record())
	{
	  $aaronic_id = $this->db->f('aaronic');
	  $aaronic[$aaronic_id]['name'] = $this->db->f('name');
	  $aaronic[$aaronic_id]['phone'] = $this->db->f('phone');
	}
      
      // Select all the unique companionship numbers for this district
      $sql = "SELECT distinct companionship FROM eq_companionship where valid=1 and district=". $district;
      $this->db->query($sql,__LINE__,__FILE__);
      $j=0; $unique_companionships='';
      while ($this->db->next_record())
	{
	  $unique_companionships[$j]['companionship'] = $this->db->f('companionship');
	  $j++;
	}
      
      $comp_width=300; $visit_width=25; $table_width=$comp_width + $visit_width;
      $table_data=""; $num_companionships = 0; $num_families = 0; $visits=0;
      for ($j=0; $j < count($unique_companionships); $j++) {
	$companion_table_entry = "";
	// Select all the companions in each companionship
	$sql = "SELECT * FROM eq_companionship where valid=1 and ".
	       "companionship=". $unique_companionships[$j]['companionship'];
	$this->db->query($sql,__LINE__,__FILE__);
	
	while ($this->db->next_record())
	  {
	    // Get this companions information
	    if($companion_table_entry != "") { $companion_table_entry .= "<td>&nbsp;/&nbsp;</td>"; }
	    $companionship = $this->db->f('companionship');
	    $elder_id = $this->db->f('elder');
	    $aaronic_id = $this->db->f('aaronic');
	    if($elder_id) {
	      $name = $elders[$elder_id];
	      $phone = $elder_phone[$elder_id];
	    }
	    else if($aaronic_id) {
	      $name = $aaronic[$aaronic_id]['name'];
	      $phone = $aaronic[$aaronic_id]['phone'];
	    }
	    $companion_table_entry .= "<td title=\"$phone\"><b>$name</b></td>";
	  }
	$table_data.= "<tr bgcolor=#d3dce3><td colspan=20><table><tr>$companion_table_entry</tr></table><hr></td></tr>";
	
	// Get the names of the families assigned this home teaching companionship
	$sql = "SELECT * from eq_family where valid=1 AND companionship=".$unique_companionships[$j]['companionship'];
	$sql = $sql . " ORDER BY name ASC";
	$this->db->query($sql,__LINE__,__FILE__);
	while ($this->db->next_record())
	  {
	    $num_families++; $total_families++;
	    $family_name = $this->db->f('name');
	    $family_id = $this->db->f('family');
	    $this->nextmatchs->template_alternate_row_color(&$this->t);
	    $table_data.="<tr bgcolor=". $this->t->get_var('tr_color') ."><td>$family_name Family</td>";
	    
	    $header_row="<th width=$comp_width><font size=-2>Families</th>";
	    $sql = "SELECT * FROM eq_visit WHERE date >= '$month_start' AND date <= '$month_end' ".
	           " AND companionship!=0".
	           " AND family=". $family_id;
	    $this->db2->query($sql,__LINE__,__FILE__);
	    $value = $family_id . "/" . $unique_companionships[$j]['companionship'] . "/" . $date;
	    $header_row .= "<th width=$visit_width><font size=-2><a href=$link>$month</a></th>";
	    if(!$total_visits) { $total_visits = 0; }
	    if($this->db2->next_record()) {
	      if($this->db2->f('visited') == 'y') {
		$visits++; $total_visits++;
		$table_data .= '<td width=100 align=center>';
		$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/y" checked>Y';
		$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/n">N';
		$table_data .= '<input type="radio" name="family_visited['.$family_id.'][]" value="'.$value.'/"> ';
		$table_data .= '</td>';
	      } else if($this->db2->f('visited') == 'n') {
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
      $stat_data = "<tr><td><b><font size=-2>$num_families Families<br>Visit Totals:</font></b></td>";
      
      $percent = ceil(($visits / $num_families)*100);
      $stat_data .= "<td align=center><font size=-2><b>$visits<br>$percent%</font></b></td>";
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
      
      $this->t->set_var('lang_name','Activity Name');
      $this->t->set_var('lang_date','Date');
      
      $sql = "SELECT * FROM eq_activity ORDER BY date DESC";
      $this->db->query($sql,__LINE__,__FILE__);
      $total_records = $this->db->num_rows();

      $i = 0;
      while ($this->db->next_record())
	{
	  $activity_list[$i]['name'] = $this->db->f('name');
	  $activity_list[$i]['date']  = $this->db->f('date');
	  $activity_list[$i]['activity']  = $this->db->f('activity');
	  $i++;
	}
            
      for ($i=0; $i < count($activity_list); $i++)
	{	  
	  $this->nextmatchs->template_alternate_row_color(&$this->t);
	  $this->t->set_var('name',$activity_list[$i]['name']);
	  $this->t->set_var('date',$activity_list[$i]['date']);
	  
	  $link_data['menuaction'] = 'eq.eq.act_view';
	  $link_data['activity'] = $activity_list[$i]['activity'];
	  $link_data['action'] = 'view';
	  $this->t->set_var('view',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
	  $this->t->set_var('lang_view','View');

	  $link_data['menuaction'] = 'eq.eq.act_update';
	  $link_data['activity'] = $activity_list[$i]['activity'];
	  $link_data['action'] = 'edit';
	  $this->t->set_var('edit',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
	  $this->t->set_var('lang_edit','Edit');

	  $link_data['menuaction'] = 'eq.eq.act_update';
	  $link_data['activity'] = '0';
	  $link_data['action'] = 'add';
	  $this->t->set_var('add','<form method="POST" action="' . $GLOBALS['phpgw']->link('/eq/index.php',$link_data)
                           . '"><input type="submit" name="Add" value="' . 'Add Activity' .'"></font></form>');

	  $this->t->fp('list','act_list',True);
	}

      $this->t->pfp('out','act_list_t');
      $this->save_sessiondata();
    }

  function act_view()
    {
      $this->t->set_file(array('act_view_t' => 'act_view.tpl'));
      $this->t->set_block('act_view_t','part_list','list');
      
      $sql = "SELECT * FROM eq_activity WHERE activity=" . intval(get_var('activity',array('GET','POST')));
      $this->db->query($sql,__LINE__,__FILE__);
      $this->db->next_record();
      $this->t->set_var('name', $this->db->f('name'));
      $this->t->set_var('date', $this->db->f('date'));
      $this->t->set_var('notes', $this->db->f('notes'));
            
      $this->t->set_var('lang_name','Activity Name');
      $this->t->set_var('lang_date','Date');
      $this->t->set_var('lang_notes','Notes');
      $this->t->set_var('lang_done','Done');
      $this->t->set_var('lang_action','View');

      $tr_color = $this->nextmatchs->alternate_row_color($tr_color);
      $this->t->set_var('tr_color',$tr_color);
            
      $this->t->set_var('done_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.act_list'));

      $link_data['menuaction'] = 'eq.eq.act_update';
      $link_data['activity'] = get_var('activity',array('GET','POST'));
      $link_data['action'] = 'edit';
      $this->t->set_var('edit',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
      $this->t->set_var('lang_edit','Edit');
      $this->t->set_var('cal_date',$this->db->f('date'));

      // Now find out which elders participated in this activity
      $sql = "SELECT * FROM eq_participation WHERE activity=" . intval(get_var('activity',array('GET','POST')));
      $this->db->query($sql,__LINE__,__FILE__);
      $total_records = $this->db->num_rows();

      $i = 0;
      while ($this->db->next_record())
	{
	  $part_list[$i]['elder']  = $this->db->f('elder');
	  $i++;
	}
      
      for ($i=0; $i < count($part_list); $i++)
	{
	  $sql = "SELECT * FROM eq_elder WHERE elder=" . $part_list[$i]['elder'];
	  $this->db->query($sql,__LINE__,__FILE__);
	  $this->db->next_record();
	  $names[$i] = $this->db->f('name');
	} sort($names);
      
      for ($i=0; $i < count($names); $i++)
	{
          //$this->nextmatchs->template_alternate_row_color(&$this->t);
	  $this->t->set_var('elder_name',$names[$i]);
	  if(($i+1) % 3 == 0) { $this->t->set_var('table_sep',"</td></tr><tr>"); }
	  else { $this->t->set_var('table_sep',"</td>"); }
	  if(($i) % 3 == 0) { $this->nextmatchs->template_alternate_row_color(&$this->t); }
	  $this->t->fp('list','part_list',True);
	}
      
      $this->t->pfp('out','act_view_t');
      $this->save_sessiondata();
    }

  function act_update()
    {
      $this->t->set_file(array('form' => 'act_update.tpl'));
      $this->t->set_block('form','elder_list','list');
      $this->t->set_block('form','add','addhandle');
      $this->t->set_block('form','edit','edithandle');
      $this->t->set_var('lang_done','Done');

      $action = get_var('action',array('GET','POST'));
      $this->t->set_var('done_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.act_list'));
      $activity['activity'] = intval(get_var('activity',array('GET','POST')));

      if($action == 'save')
	{
	  $activity['name'] = $this->db->db_addslashes(get_var('name',array('POST')));
	  $activity['date'] = $this->db->db_addslashes(get_var('date',array('POST')));
	  $activity['notes']= $this->db->db_addslashes(get_var('notes',array('POST')));
	  $this->db->query("UPDATE eq_activity set " .
			   "   name='" . $activity['name'] .
			   "', date='" . $activity['date'] . "'" .
			   ", notes='" . $activity['notes'] . "'" .
			   " WHERE activity=" . $activity['activity'],__LINE__,__FILE__);

	  // Delete all the elders who have particiapted in this activity
	  $this->db->query("DELETE from eq_participation where activity=".$activity['activity'],__LINE__,__FILE__);
	  
	  // Re-add the elders who are checked as having participated in this activity
	  $elders = get_var('elder_name',array('POST'));
	  foreach ($elders as $elder)
	    {
	      $this->db->query("INSERT INTO eq_participation (elder,activity) "
			       . "VALUES (" . $elder . ",". $activity['activity'] . ")",__LINE__,__FILE__);
	    }

	  $this->act_list();
	  return false;
	}

      if($action == 'insert')
	{
	  $activity['name'] = $this->db->db_addslashes(get_var('name',array('POST')));
	  $activity['date'] = $this->db->db_addslashes(get_var('date',array('POST')));
	  $activity['notes']= $this->db->db_addslashes(get_var('notes',array('POST')));
	  $this->db->query("INSERT INTO eq_activity (name,date,notes) "
			   . "VALUES ('" . $activity['name'] . "','"
			   . $activity['date'] . "','" . $activity['notes'] . "')",__LINE__,__FILE__);
	  $this->act_list();
	  return false;
	}
      
      if($action == 'add')
	{
	  $activity['activity'] = 0;
	  $this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
	  $this->t->set_var('name','');
	  $this->t->set_var('date','');
	  $this->t->set_var('notes','');
	  $this->t->set_var('lang_done','Cancel');
	  $this->t->set_var('lang_action','Adding New Activity');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.act_update&activity='
								. $activity['activity'] . '&action=' . 'insert'));
	}

      if($action == 'edit')
	{
	  $sql = "SELECT * FROM eq_activity WHERE activity=" . $activity['activity'];
	  $this->db->query($sql,__LINE__,__FILE__);
	  $this->db->next_record();
	  $this->t->set_var('cal_date',$this->jscal->input('date',$this->db->f('date'),'','','','','',$this->cal_options));
	  $this->t->set_var('name', $this->db->f('name'));
	  $this->t->set_var('date', $this->db->f('date'));
	  $this->t->set_var('notes', $this->db->f('notes'));
	  $this->t->set_var('lang_done','Cancel');
	  $this->t->set_var('lang_action','Editing Activity');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.act_update&activity='
								. $activity['activity'] . '&action=' . 'save'));

	}

      $sql = "SELECT * FROM eq_elder";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  if($this->db->f('valid') == 1 || $action != 'add') {
	    $elder_name[$i] = $this->db->f('name');
	    $elder_id[$i] = $this->db->f('elder');
	    $elder_valid[$i] = $this->db->f('valid');
	    $i++;
	  }
	}
      array_multisort($elder_name, $elder_id, $elder_valid);

      $j=0;
      for ($i=0; $i < count($elder_id); $i++)
	{
	  //$this->nextmatchs->template_alternate_row_color(&$this->t);
	  $sql = "SELECT * FROM eq_participation where activity=". $activity['activity'] . " AND elder=" . $elder_id[$i];
	  $this->db->query($sql,__LINE__,__FILE__);
	  if($this->db->next_record()) { $this->t->set_var('checked','checked'); $checked=1; }
	  else { $this->t->set_var('checked',''); $checked=0; }
	  if($checked || $elder_valid[$i] == 1) {
	    $this->t->set_var('elder_name',$elder_name[$i]);
	    $this->t->set_var('elder',$elder_id[$i]);
	    if(($j+1) % 3 == 0) { $this->t->set_var('table_sep',"</td></tr><tr>"); }
	    else { $this->t->set_var('table_sep',"</td>"); }
	    if(($j) % 3 == 0) { $this->nextmatchs->template_alternate_row_color(&$this->t); }
	    $this->t->fp('list','elder_list',True);
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

  function par_view()
    {
      $this->t->set_file(array('par_view_t' => 'par_view.tpl'));
      $this->t->set_block('par_view_t','header_list','list1');
      $this->t->set_block('par_view_t','elder_list','list2');

      $sql = "SELECT * FROM eq_elder where valid=1";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $elder_name[$i] = $this->db->f('name');
	  $elder_id[$i] = $this->db->f('elder');
	  $i++;
	}
      array_multisort($elder_name, $elder_id);

      $sql = "SELECT * FROM eq_activity ORDER BY date DESC";
      $this->db->query($sql,__LINE__,__FILE__);
      $total_records = $this->db->num_rows();

      $i = 0;
      while ($this->db->next_record())
	{
	  $activity_list[$i]['name'] = $this->db->f('name');
	  $activity_list[$i]['date'] = $this->db->f('date');
	  $activity_list[$i]['activity']  = $this->db->f('activity');
	  if($activity_list[$i]['date'] == '0000-00-00') { $activity_list[$i]['date']=""; }
	  $i++;
	}

      $elder_width=250; $part_width=25; $act_width=50;
      $total_width=$elder_width+$part_width;
      for ($i=0; $i < count($activity_list); $i++) {
	$this->t->set_var('activity_name',$activity_list[$i]['name']);
	$this->t->set_var('activity_date',$activity_list[$i]['date']);
	$this->t->fp('list1','header_list',True);
	$total_width += $act_width;
      }

      for ($i=0; $i < count($elder_id); $i++) {
	$participated=0; $part_table = ''; 
	$this->nextmatchs->template_alternate_row_color(&$this->t);
	$this->t->set_var('elder_name',$elder_name[$i]);
	for ($j=0; $j < count($activity_list); $j++) {
	  $sql = "SELECT * FROM eq_participation where activity="
	     . $activity_list[$j]['activity'] . " AND elder=" . $elder_id[$i];
	  $this->db->query($sql,__LINE__,__FILE__);
	  if($this->db->next_record()) {
	    $part_table .= '<td align=center><img src="checkmark.gif"></td>';
	    $participated++;
	  } else {
	    $part_table .= '<td>&nbsp;</td>';
	  }
	}
	if($participated) { $part_table .= '<td align=center><img src="checkmark.gif">'.$participated.'</td>'; }
	else { $part_table .= '<td>&nbsp;</td>'; }
	$this->t->set_var('part_table',$part_table);
	$this->t->fp('list2','elder_list',True);
      }
      $this->t->set_var('total_width',$total_width);
      $this->t->set_var('elder_width',$elder_width);
      $this->t->set_var('part_width',$part_width);
      $this->t->set_var('act_width',$act_width);
      $this->t->pfp('out','par_view_t');
      $this->save_sessiondata(); 
    }

  function ppi_view()
    {
      $this->t->set_file(array('ppi_view_t' => 'ppi_view.tpl'));
      $this->t->set_block('ppi_view_t','district_list','list');

      $this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_view'));
      $num_months = get_var('num_months',array('GET','POST'));
      if($num_months == '') { $num_months = $this->default_ppi_num_months; }
      $this->t->set_var('num_months',$num_months);
      if($num_months == 1) { $this->t->set_var('lang_num_months','Month of History'); }
      else {  $this->t->set_var('lang_num_months','Months of History'); }
      $this->t->set_var('lang_filter','Filter');
      
      $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_view'));
      $this->t->set_var('eqpres_ppi_link',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_view&eqpresppi=1'));
      $eqpresppi = get_var('eqpresppi',array('GET','POST'));
      
      if($eqpresppi == 1) {
	$num_months = get_var('num_months',array('GET','POST'));
	if($num_months == '') { $num_months = $this->default_ppi_num_years; }
	$this->t->set_var('num_months',$num_months);
        $this->t->set_var('ppi_link_title','PPIs');
        $this->t->set_var('title','EQ President Yearly PPIs');
	if($num_months == 1) { $this->t->set_var('lang_num_months','Year of History'); }
	else { $this->t->set_var('lang_num_months','Years of History'); }
	$this->t->set_var('eqpres_ppi_link',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_view'));
      }
      else { 
        $this->t->set_var('ppi_link_title','EQ President Yearly PPIs'); 
        $this->t->set_var('title','PPIs'); 
      }

      $sql = "SELECT * FROM eq_district where valid=1 ORDER BY district ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  if($eqpresppi == 1 && $this->db->f('district') == 1) {
	    $districts[$i]['district'] = $this->db->f('district');
	    $districts[$i]['name'] = $this->db->f('name');
	    $districts[$i]['supervisor'] = $this->db->f('supervisor');
          } else if($eqpresppi == 0) {
            $districts[$i]['district'] = $this->db->f('district');
	    $districts[$i]['name'] = $this->db->f('name');
            $districts[$i]['supervisor'] = $this->db->f('supervisor');
            $i++;
	  }
	}

      $sql = "SELECT * FROM eq_elder where valid=1 ORDER BY elder ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $elder_id[$i] = $this->db->f('elder');
	  $elder_name[$i] = $this->db->f('name');
	  $elder_phone[$elder_id[$i]] = $this->db->f('phone');
	  $i++;
	}
      array_multisort($elder_name, $elder_id);
      for($i=0; $i < count($elder_id); $i++) {
          $id = $elder_id[$i];
          $elders[$id] = $elder_name[$i];
      }      

      $sql = "SELECT * FROM eq_aaronic where valid=1 ORDER BY aaronic ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      while ($this->db->next_record())
	{
	  $aaronic_id = $this->db->f('aaronic');
	  $aaronic[$aaronic_id]['name'] = $this->db->f('name');
	  $aaronic[$aaronic_id]['phone'] = $this->db->f('phone');
	}
      
      $total_companionships = 0;
      $this->nextmatchs->template_alternate_row_color(&$this->t);
      for ($i=0; $i < count($districts); $i++) {
	if($eqpresppi == 1) { 
	  $this->t->set_var('district_number','*');
	  $this->t->set_var('district_name','EQ President');
	} else {
	  $this->t->set_var('district_number',$districts[$i]['district']);
	  $this->t->set_var('district_name',$districts[$i]['name']);	
	}
	$supervisor = $districts[$i]['supervisor'];
	$unique_companionships='';
		
	// Select all the unique companionship numbers for this district
	if($eqpresppi == 1) { 
	  $sql = "SELECT distinct companionship FROM eq_companionship where valid=1";
	} 
	else {
	  $sql = "SELECT distinct companionship FROM eq_companionship where valid=1 and district=". $districts[$i]['district'];
	}
	$this->db->query($sql,__LINE__,__FILE__);
	$j=0;
	while ($this->db->next_record())
	  {
	    $unique_companionships[$j]['companionship'] = $this->db->f('companionship');
	    $j++;
	  }
	
	$comp_width=400; $ppi_width=25; $table_width=$comp_width + $num_months*$ppi_width;
	$table_data=""; $num_companionships = $j; $num_elders = 0;
	for($m=$num_months; $m >= 0; $m--) { $ppis[$m] = 0; }
	for ($j=0; $j < count($unique_companionships); $j++) {
	  // Select all the companions in each companionship
	  if($eqpresppi) {
 	    $sql = "SELECT * FROM eq_companionship where valid=1 and aaronic=0 and ".
	           "companionship=". $unique_companionships[$j]['companionship'];  
	  }
          else {
	    $sql = "SELECT * FROM eq_companionship where valid=1 and ".
	           "companionship=". $unique_companionships[$j]['companionship'];
          }
	  $this->db->query($sql,__LINE__,__FILE__);
	  $k=0;
	  $comp = $unique_companionships[$j]['companionship'];
	  for($m=$num_months; $m >= 0; $m--) { $ppi_recorded[$comp][$m] = 0; }
	  while ($this->db->next_record())
	    {
	      // Get this companions information
	      $num_elders++;
	      $companionship = $this->db->f('companionship');
	      $elder_id = $this->db->f('elder');
	      $aaronic_id = $this->db->f('aaronic');
	      if($elder_id) {
		$name = $elders[$elder_id];
		$phone = $elder_phone[$elder_id];
	      }
	      else if($aaronic_id) {
		$name = $aaronic[$aaronic_id]['name'];
		$phone = $aaronic[$aaronic_id]['phone'];
	      }
	      $link_data['menuaction'] = 'eq.eq.ppi_update';
	      $link_data['companionship'] = $companionship;
	      $link_data['interviewer'] = $supervisor;
	      $link_data['elder'] = $elder_id;
	      $link_data['aaronic'] = $aaronic_id;
	      $link_data['name'] = $name;
	      $link_data['ppi'] = '';
	      $link_data['eqpresppi'] = $eqpresppi;
	      $link_data['action'] = 'add';
	      $link = $GLOBALS['phpgw']->link('/eq/index.php',$link_data);
	      $table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td title=\"$phone\"><a href=$link>$name</a></td>";

	      // Find out how many times PPIs were performed in the past $num_months for this Elder
	      $header_row="<th width=$comp_width><font size=-2>Companionship</th>";
	      for($m=$num_months; $m >= 0; $m--) {
	        if($eqpresppi == 1) {
		  $year = date('Y') - $m;
		  $year_start = $year - 1 . "-12-31"; $year_end = $year + 1 . "-01-01";
		  $sql = "SELECT * FROM eq_ppi WHERE date > '$year_start' AND date < '$year_end' ".
		         "AND elder=" . $elder_id . " AND aaronic=" . $aaronic_id . " AND eqpresppi=1";
	          $this->db2->query($sql,__LINE__,__FILE__);
		  $header_row .= "<th width=150><font size=-2>$year</th>"; 
	        }
	        else {
		  $month = $this->current_month - $m;
		  $year = $this->current_year;
		  if($month <= 0) { $remainder = $month; $month = 12 + $remainder; $year=$year-1; }
		  if($month < 10) { $month = "0"."$month"; }
		  $month_start = "$year"."-"."$month"."-"."01";
		  $month_end = "$year"."-"."$month"."-"."31";
		  $month = "$month"."/"."$year";
		  $sql = "SELECT * FROM eq_ppi WHERE date >= '$month_start' AND date <= '$month_end' ".
		         "AND elder=" . $elder_id . " AND aaronic=" . $aaronic_id . " AND eqpresppi=0";
		  $this->db2->query($sql,__LINE__,__FILE__);
		  $header_row .= "<th width=$ppi_width><font size=-2>$month</th>";
		}
		if(!$total_ppis[$m]) { $total_ppis[$m] = 0; }
		if($this->db2->next_record()) {
		  if(!$ppi_recorded[$companionship][$m]) {
		    $ppis[$m]++; $total_ppis[$m]++; $ppi_recorded[$companionship][$m]=1;
		  }
		  $link_data['menuaction'] = 'eq.eq.ppi_update';
		  $link_data['companionship'] = $companionship;
		  $link_data['interviewer'] = $this->db2->f('interviewer');
		  $link_data['elder'] = $elder_id;
		  $link_data['aaronic'] = $aaronic_id;
		  $link_data['name'] = $name;
		  $link_data['ppi'] = $this->db2->f('ppi');
		  $link_data['eqpresppi'] = $eqpresppi;
		  $link_data['action'] = 'view';
		  $date = $this->db2->f('date');
		  $date_array = explode("-",$date);
		  $month = $date_array[1];
		  $day   = $date_array[2];
		  $link = $GLOBALS['phpgw']->link('/eq/index.php',$link_data);
		  $table_data .= '<td align=center><a href='.$link.'><img src="checkmark.gif"><br>'.$month.'-'.$day.'</a></td>';
		}
		else { $table_data .= "<td>&nbsp;</td>"; }
	      }
	      $table_data .= "</tr>"; 
	      $k++;
	    }
	  $table_data .= "<tr><td colspan=20><hr></td></tr>";
	}
	// Now add Elders not assigned to any companionship to the table if we are in eqpresppi mode
	if($eqpresppi == 1) {
	  $table_data .= "<tr bgcolor=\"#c9c9c9\"><hr><td colspan=20><b>Unassigned Potential Home Teachers</b><hr></td></tr>";
	  foreach($elders as $elder_id => $value) {
	    $sql = "SELECT * FROM eq_companionship where valid=1 and elder=".$elder_id;
	    $this->db->query($sql,__LINE__,__FILE__);
	    if(!$this->db->next_record()) {
	      // We found an Elder not in a companionship, add them to the table
	      $num_elders++;
	      $companionship=0;
	      $name = $elders[$elder_id];
	      $link_data['menuaction'] = 'eq.eq.ppi_update';
	      $link_data['companionship'] = $companionship;
	      $link_data['interviewer'] = $supervisor;
	      $link_data['elder'] = $elder_id;
	      $link_data['name'] = $name;
	      $link_data['ppi'] = '';
	      $link_data['eqpresppi'] = $eqpresppi;
	      $link_data['action'] = 'add';
	      $link = $GLOBALS['phpgw']->link('/eq/index.php',$link_data);
	      $table_data.= "<tr bgcolor=". $this->t->get_var('tr_color') ."><td><a href=$link>$name</a></td>";
	      for($m=$num_months; $m >= 0; $m--) {
		$year = date('Y') - $m;
		$year_start = $year - 1 . "-12-31"; $year_end = $year + 1 . "-01-01";
		$sql = "SELECT * FROM eq_ppi WHERE date > '$year_start' AND date < '$year_end' ".
	               "AND elder=" . $elder_id . " AND eqpresppi=1";
	        $this->db2->query($sql,__LINE__,__FILE__);
		if(!$total_ppis[$m]) { $total_ppis[$m] = 0; }
		if($this->db2->next_record()) {
		  $ppis[$m]++; $total_ppis[$m]++;
		  $link_data['menuaction'] = 'eq.eq.ppi_update';
		  $link_data['companionship'] = $companionship;
		  $link_data['interviewer'] = $supervisor;
		  $link_data['elder'] = $elder_id;
		  $link_data['name'] = $name;
		  $link_data['ppi'] = $this->db2->f('ppi');
		  $link_data['eqpresppi'] = $eqpresppi;
		  $link_data['action'] = 'view';
		  $date = $this->db2->f('date');
		  $link = $GLOBALS['phpgw']->link('/eq/index.php',$link_data);
		  $table_data .= '<td align=center><a href='.$link.'><img src="checkmark.gif"><br>'.$date.'</a></td>';
		}
		else { $table_data .= "<td>&nbsp;</td>"; }
	      }
	      $table_data .= "</tr>"; 
	    }
	  }
	}
	$total_companionships += $num_companionships;
	if($eqpresppi == 1) {
	  $stat_data = "<tr><td><b><font size=-2>$num_elders Elders<br>PPI Totals:</font></b></td>";
	} else {
	  $stat_data = "<tr><td><b><font size=-2>$num_companionships Companionships<br>PPI Totals:</font></b></td>";
        }
	for($m=$num_months; $m >=0; $m--) {
	  if($eqpresppi == 1) { $percent = ceil(($ppis[$m] / $num_elders)*100); }
	  else { $percent = ceil(($ppis[$m] / $num_companionships)*100); }
	  $stat_data .= "<td align=center><font size=-2><b>$ppis[$m]<br>$percent%</font></b></td>";
	}
	$stat_data .= "</tr>";

	$this->t->set_var('table_width',$table_width);
	$this->t->set_var('header_row',$header_row);
	$this->t->set_var('table_data',$table_data);
	$this->t->set_var('stat_data',$stat_data);
	$this->t->fp('list','district_list',True);
      }

      $totals = "<tr><td><b><font size=-2>$total_companionships Total Comps<br>PPI Totals:</font></b></td>";
      for($m=$num_months; $m >=0; $m--) {
	$percent = ceil(($total_ppis[$m] / $total_companionships)*100);
	$totals .= "<td align=center><font size=-2><b>$total_ppis[$m]<br>$percent%</font></b></td>";
      }
      $totals .= "</tr>";
      
      $this->t->set_var('totals',$totals);
      $this->t->pfp('out','ppi_view_t');
      $this->save_sessiondata(); 
    }

  function ppi_update()
    {
      $this->t->set_file(array('form' => 'ppi_update.tpl'));
      $this->t->set_block('form','interviewer_list','int_list');
      $this->t->set_block('form','add','addhandle');
      $this->t->set_block('form','edit','edithandle');
      
      $this->t->set_var('done_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_view'));
      $this->t->set_var('readonly','');
      $this->t->set_var('disabled','');
      
      $action = get_var('action',array('GET','POST'));
      $companionship = get_var('companionship',array('GET','POST'));
      $interviewer = get_var('interviewer',array('GET','POST'));      
      $name = get_var('name',array('GET','POST'));
      $ppi = get_var('ppi',array('GET','POST'));
      $elder = get_var('elder',array('GET','POST'));
      $aaronic = get_var('aaronic',array('GET','POST'));
      $date = get_var('date',array('GET','POST'));
      $notes = get_var('notes',array('GET','POST'));
      $eqpresppi = get_var('eqpresppi',array('GET','POST'));
      
      $sql = "SELECT * FROM eq_district where valid=1 ORDER BY district ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      while ($this->db->next_record())
	{
	  $supervisor = $this->db->f('supervisor');
	  $sql = "SELECT * FROM eq_elder WHERE elder=" . $supervisor;
	  $this->db2->query($sql,__LINE__,__FILE__);
	  $this->db2->next_record();
	  $interviewer_name = $this->db2->f('name');
	  
	  if($supervisor == $interviewer) { 
	    $this->t->set_var('interviewer',$supervisor . ' selected');
	  } else {
	    $this->t->set_var('interviewer',$interviewer);
	  }
	  $this->t->set_var('interviewer_name',$interviewer_name);
	  $this->t->fp('int_list','interviewer_list',True);
	}
      
      if($action == 'save')
	{
	  $notes = $this->db->db_addslashes(get_var('notes',array('POST')));
	  $this->db->query("UPDATE eq_ppi set " .
			   "   ppi='" . $ppi . "'" .
		    ", interviewer='" . $interviewer . "'" .
			  ", elder='" . $elder . "'" .
			", aaronic='" . $aaronic . "'" .
			   ", date='" . $date . "'" .
			  ", notes='" . $notes . "'" .
	              ", eqpresppi='" . $eqpresppi . "'" .
			   " WHERE ppi=" . $ppi,__LINE__,__FILE__);
	  $this->ppi_view();
	  return false;
	}

      if($action == 'insert')
	{
	  $notes = $this->db->db_addslashes(get_var('notes',array('POST')));
	  $this->db->query("INSERT INTO eq_ppi (interviewer,elder,aaronic,date,notes,eqpresppi) "
			   . "VALUES ('" . $interviewer . "','" . $elder . "','" . $aaronic . "','"
			   . $date . "','" . $notes . "','" . $eqpresppi  ."')",__LINE__,__FILE__);
	  $this->ppi_view();
	  return false;
	}
      
      if($action == 'add')
	{
	  $this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
	  $this->t->set_var('ppi', '');
	  $this->t->set_var('interviewer', $interviewer);
	  $this->t->set_var('name',$name);
	  $this->t->set_var('elder',$elder);
	  $this->t->set_var('aaronic',$aaronic);
	  $this->t->set_var('date','');
	  $this->t->set_var('notes','');
	  $this->t->set_var('eqpresppi',$eqpresppi);
	  $this->t->set_var('lang_done','Cancel');
	  $this->t->set_var('lang_action','Adding New PPI');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_update&ppi='
								. $ppi . '&action=' . 'insert'));
	}

      if($action == 'edit' || $action == 'view')
	{
	  $sql = "SELECT * FROM eq_ppi WHERE ppi=".$ppi;
	  $this->db->query($sql,__LINE__,__FILE__);
	  $this->db->next_record();
	  $this->t->set_var('ppi',$ppi);
	  $this->t->set_var('name',$name);
	  $this->t->set_var('interviewer', $this->db->f('interviewer'));
	  $this->t->set_var('elder',$this->db->f('elder'));
	  $this->t->set_var('aaronic',$this->db->f('aaronic'));
	  $this->t->set_var('date',$this->db->f('date'));
	  $this->t->set_var('notes',$this->db->f('notes'));
	  $this->t->set_var('eqpresppi',$this->db->f('eqpresppi'));
	}
      
      if($action == 'edit')
	{
	  $this->t->set_var('cal_date',$this->jscal->input('date',$date,'','','','','',$this->cal_options));
	  $this->t->set_var('lang_done','Cancel');
	  $this->t->set_var('lang_action','Editing PPI');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_update&ppi='
								. $ppi . '&action=' . 'save'));
	}

      if($action == 'view')
	{
	  $date = $this->db->f('date');
	  $this->t->set_var('cal_date','<input type=text size="10" maxlength="10" name="date" value="'.$date.'" readonly>');
	  $this->t->set_var('readonly','READONLY');
	  $this->t->set_var('disabled','DISABLED');
	  $this->t->set_var('lang_done','Done');
	  $this->t->set_var('lang_action','Viewing PPI');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.ppi_update&ppi='
								. $ppi . '&action=' . 'edit'));
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

  function vis_view()
    {
      $this->t->set_file(array('vis_view_t' => 'vis_view.tpl'));
      $this->t->set_block('vis_view_t','visit_list','list1');
      $this->t->set_block('vis_view_t','family_list','list2');

      $this->t->set_var('lang_name','Family Name');
      $this->t->set_var('lang_date','Date');
      
      $sql = "SELECT * FROM eq_visit WHERE companionship=0 ORDER BY date DESC";
      $this->db->query($sql,__LINE__,__FILE__);
      $total_records = $this->db->num_rows();

      $i = 0;
      while ($this->db->next_record())
	{
	  $visit_list[$i]['visit'] = $this->db->f('visit');
	  $visit_list[$i]['family'] = $this->db->f('family');
	  $visit_list[$i]['date']  = $this->db->f('date');
	  $i++;
	}
            
      for ($i=0; $i < count($visit_list); $i++)
	{	  
	  $this->nextmatchs->template_alternate_row_color(&$this->t);

	  $sql = "SELECT * FROM eq_family WHERE family=".$visit_list[$i]['family'];
	  $this->db->query($sql,__LINE__,__FILE__);
	  $this->db->next_record();
	  	  
	  $this->t->set_var('family',$visit_list[$i]['family']);
	  $this->t->set_var('family_name',$this->db->f('name'));
	  $this->t->set_var('date',$visit_list[$i]['date']);
	  
	  $link_data['menuaction'] = 'eq.eq.vis_update';
	  $link_data['visit'] = $visit_list[$i]['visit'];
	  $link_data['name'] = $this->db->f('name');
	  $link_data['date'] = $visit_list[$i]['date'];
	  $link_data['action'] = 'view';
	  $this->t->set_var('view',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
	  $this->t->set_var('lang_view','View');

	  $link_data['menuaction'] = 'eq.eq.vis_update';
	  $link_data['visit'] = $visit_list[$i]['visit'];
	  $link_data['name'] = $this->db->f('name');
	  $link_data['date'] = $visit_list[$i]['date'];
	  $link_data['action'] = 'edit';
	  $this->t->set_var('edit',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
	  $this->t->set_var('lang_edit','Edit');

	  $this->t->fp('list1','visit_list',True);
	}

      // List the families that are available to record a visit against
      $sql = "SELECT * FROM eq_family WHERE valid=1";
      $this->db->query($sql,__LINE__,__FILE__);
      $total_records = $this->db->num_rows();

      $i = 0;
      while ($this->db->next_record())
	{
	  $family_names[$i] = $this->db->f('name');
	  $family_ids[$i] = $this->db->f('family');
	  $i++;
	} array_multisort($family_names, $family_ids);
      
      for ($i=0; $i < count($family_names); $i++)
	{
	  $link_data['menuaction'] = 'eq.eq.vis_update';
	  $link_data['visit'] = '';
	  $link_data['family'] = $family_ids[$i];
	  $link_data['action'] = 'add';
	  $link_data['name'] = $family_names[$i];
	  $this->t->set_var('add',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));

	  $this->t->set_var('name',$family_names[$i]);
	  if(($i+1) % 3 == 0) { $this->t->set_var('table_sep',"</td></tr><tr>"); }
	  else { $this->t->set_var('table_sep',"</td>"); }
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
      
      $this->t->set_var('done_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.vis_view'));
      $this->t->set_var('readonly','');
      $this->t->set_var('disabled','');
      
      $action = get_var('action',array('GET','POST'));
      $visit = get_var('visit',array('GET','POST'));
      $family = get_var('family',array('GET','POST'));
      $name = get_var('name',array('GET','POST'));
      $date = get_var('date',array('GET','POST'));
      $notes = get_var('notes',array('GET','POST'));
      $companionship = 0;
      
      if($action == 'save')
	{
	  $notes = $this->db->db_addslashes(get_var('notes',array('POST')));
	  $this->db->query("UPDATE eq_visit set " .
			   "  date='" . $date . "'" .
			  ", notes='" . $notes . "'" .
			   " WHERE visit=" . $visit,__LINE__,__FILE__);
	  $this->vis_view();
	  return false;
	}

      if($action == 'insert')
	{
	  $notes = $this->db->db_addslashes(get_var('notes',array('POST')));
	  $this->db->query("INSERT INTO eq_visit (family,companionship,date,notes) "
			   . "VALUES ('" . $family . "','" . $companionship . "','"
			   . $date . "','" . $notes . "')",__LINE__,__FILE__);
	  $this->vis_view();
	  return false;
	}
      
      if($action == 'add')
	{
	  $this->t->set_var('cal_date',$this->jscal->input('date','','','','','','',$this->cal_options));
	  $this->t->set_var('family', $family);
	  $this->t->set_var('visit', '');
	  $this->t->set_var('name', $name);
	  $this->t->set_var('date','');
	  $this->t->set_var('notes','');
	  $this->t->set_var('lang_done','Cancel');
	  $this->t->set_var('lang_action','Adding New Visit');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.vis_update&family='
								. $family . '&action=' . 'insert'));
	}

      if($action == 'edit' || $action == 'view')
	{
	  $sql = "SELECT * FROM eq_visit WHERE visit=".$visit;
	  $this->db->query($sql,__LINE__,__FILE__);
	  $this->db->next_record();
	  $this->t->set_var('visit',$visit);
	  $this->t->set_var('name',$name);
	  $this->t->set_var('family', $family);
	  $this->t->set_var('date',$this->db->f('date'));
	  $this->t->set_var('notes',$this->db->f('notes'));
	}
      
      if($action == 'edit')
	{
	  $this->t->set_var('cal_date',$this->jscal->input('date',$date,'','','','','',$this->cal_options));
	  $this->t->set_var('lang_done','Cancel');
	  $this->t->set_var('lang_action','Editing Visit');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.vis_update&visit='
								. $visit . '&action=' . 'save'));
	}

      if($action == 'view')
	{
	  $date = $this->db->f('date');
	  $this->t->set_var('cal_date','<input type=text size="10" maxlength="10" name="date" value="'.$date.'" readonly>');
	  $this->t->set_var('readonly','READONLY');
	  $this->t->set_var('disabled','DISABLED');
	  $this->t->set_var('lang_done','Done');
	  $this->t->set_var('lang_action','Viewing Visit');
	  $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.vis_update&visit='
								. $visit . '&action=' . 'edit'));
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
      $this->t->set_file(array('att_view_t' => 'att_view.tpl'));
      $this->t->set_block('att_view_t','act_list','list');

      $this->t->set_block('att_view_t','month_list','list1');
      $this->t->set_block('att_view_t','header_list','list2');
      $this->t->set_block('att_view_t','elder_list','list3');
      
      $this->t->set_var('linkurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.att_view'));
      $num_months = get_var('num_months',array('GET','POST'));
      if($num_months == '') { $num_months = $this->default_att_num_months; }
      $this->t->set_var('num_months',$num_months);
      $this->t->set_var('lang_filter','Filter');
      if($num_months == 1) { $this->t->set_var('lang_num_months','Month of History'); }
      else {  $this->t->set_var('lang_num_months','Months of History'); }
         
      $sql = "SELECT * FROM eq_elder where valid=1";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $elder_name[$i] = $this->db->f('name');
	  $elder_id[$i] = $this->db->f('elder');
	  $i++;
	}
      array_multisort($elder_name, $elder_id);

      
      // Create a list of sunday dates for a window of 3 months back and current month
      $i=0; 
      $last_time = 0; 
      $found_sunday = 0;
      $sunday_list[0]['date'] = date("Y-m-d", mktime(0, 0, 0, date("m")-$num_months, 1, date("y")));
      $last_date = explode("-",$sunday_list[0]['date']);
      $last_time = mktime(0, 0, 0, $last_date[1], $last_date[2], $last_date[0]);
      $time_limit = mktime(0, 0, 0, date("m"), date("t"), date("y"));
      while($last_time < $time_limit)
      {
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

      $total_elders = count($elder_id);
      $old_month=$sunday_list[0]['month']; $span=0;
      for ($i=0; $i < count($sunday_list); $i++) {
        $date = $sunday_list[$i]['date'];
        $this->t->set_var('date',$sunday_list[$i]['date']);
	$this->t->set_var('day',$sunday_list[$i]['day']);
	if(($old_month != $sunday_list[$i]['month']) || $i == count($sunday_list)-1) {
	  if($i == count($sunday_list)-1) { $span++; }
	  $cur_month = $sunday_list[$i]['month'];
	  $old_month = $sunday_list[$i]['month'];	  
	  $link_data['menuaction'] = 'eq.eq.att_update';
	  $link_data['month'] = $sunday_list[$i-1]['month'];
	  $link_data['year'] = $sunday_list[$i-1]['year'];
	  $link_data['action'] = 'update_month';
	  $cur_month = $sunday_list[$i-1]['month'];
	  $cur_year = $sunday_list[$i-1]['year'];
	  $header_row .= "<th><font size=-3>$cur_month&nbsp;$cur_year</font></th>";
	  $this->t->set_var('update_month',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
	  $this->t->set_var('month',$sunday_list[$i-1]['month']);
	  $this->t->set_var('year',$sunday_list[$i-1]['year']);
	  $this->t->set_var('span',$span); $span=0;
	  $this->t->fp('list1','month_list',True);
	} $span++;
      }
      $this->t->set_var('total_elders',$total_elders);
      $this->t->set_var('header_row',$header_row);
      
      $elder_width=200; $att_width=25; $total_width=$elder_width; 
      for ($i=0; $i < count($sunday_list); $i++) {
      	$link_data['menuaction'] = 'eq.eq.att_update';
	$link_data['month'] = $sunday_list[$i]['month'];
	$link_data['year'] = $sunday_list[$i]['year'];
	$link_data['day'] = $sunday_list[$i]['day'];
	$link_data['date'] = $sunday_list[$i]['date'];
	$link_data['action'] = 'update_day';
	$this->t->set_var('update_day',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
        $this->t->set_var('date',$sunday_list[$i]['date']);
	$this->t->set_var('day',$sunday_list[$i]['day']);
        $this->t->set_var('month',$sunday_list[$i]['month']);
	$this->t->set_var('year',$sunday_list[$i]['year']);
	$this->t->fp('list2','header_list',True);
	$total_width += $att_width;
      }

      for ($i=0; $i < count($elder_id); $i++) {
        $att_table = "";
	$this->nextmatchs->template_alternate_row_color(&$this->t);
	$this->t->set_var('elder_name',$elder_name[$i]);
	#print "checking for elder: " . $elder_id[$i] . "<br>";
	for ($j=0; $j < count($sunday_list); $j++) {
	  #print "checking for date: " .  $sunday_list[$j]['date'] . "<br>";
	  #print "SELECT * FROM eq_attendance WHERE date='"
	  #  . $sunday_list[$j]['date'] . "' AND elder=" . $elder_id[$i] . "<br>";
	  $sql = "SELECT * FROM eq_attendance WHERE date='"
	     . $sunday_list[$j]['date'] . "' AND elder=" . $elder_id[$i];
	  $this->db->query($sql,__LINE__,__FILE__);
	  if($this->db->next_record()) {
	    $cur_month = $sunday_list[$j]['month'];
	    if($attended[$i][$cur_month] != 1) { 
	      $attended[$i][$cur_month]=1;
	      $attendance[$cur_month]++;
	    }
	    $att_table .= '<td align=center><img src="checkmark.gif"></td>';
	  } else {
	    $att_table .= '<td>&nbsp;</td>';
	  }
	}
	$this->t->set_var('att_table',$att_table);
	$this->t->fp('list3','elder_list',True);
      }
      $this->t->set_var('total_width',$total_width);
      $this->t->set_var('elder_width',$elder_width);
      $this->t->set_var('att_width',$att_width);

      # Now calculate attendance for these months
      $attendance_str = "";
      $nonattendance_str = "";
      $aveattendance_str = "";
      $avenonattendance_str = "";
      $num_months=0;
      $ave_total_attended=0;
      foreach($attendance as $att => $value) {
	$total_attended = $attendance[$att];
	$ave_total_attended += $attendance[$att]; $num_months++;
	$percent = ceil(($total_attended / $total_elders)*100);
	$attendance_str.="<td align=center><font size=-2><b>$total_attended ($percent%)</b></font></td>";
	$total_nonattended = $total_elders - $total_attended;
	$percent = ceil(($total_nonattended / $total_elders)*100);
	$nonattendance_str.="<td align=center><font size=-2><b>$total_nonattended ($percent%)</b></font></td>";
	
	$total_attended = ceil(($ave_total_attended / $num_months));
	$percent = ceil(($total_attended / $total_elders)*100);
	$aveattendance_str .= "<td align=center><font size=-2><b>$total_attended ($percent%)</b></font></td>";
	$total_attended = $total_elders - ceil(($ave_total_attended / $num_months));
	$percent = ceil(($total_attended / $total_elders)*100);
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
      $this->t->set_block('form','elder_list','list3');

      $this->t->set_var('done_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.att_view'));

      $action = get_var('action',array('GET','POST'));
      $month = get_var('month',array('GET','POST'));
      $year = get_var('year',array('GET','POST'));
      $day = get_var('day',array('GET','POST'));
      $date = get_var('date',array('GET','POST'));

      if($action == 'save_month' || $action == 'save_day')
	{
	   $new_data = get_var('elders_attended',array('POST'));
	   $month = $monthnum[$month]; if($month < 10) { $month = "0" . $month; }

	   if($action == 'save_month') {	
             $this->db->query("DELETE from eq_attendance where date LIKE '".$year."-".$month."-%'",__LINE__,__FILE__);
	   }

	   if($action == 'save_day') {	      
             $this->db->query("DELETE from eq_attendance where date LIKE '".$year."-".$month."-".$day."'",__LINE__,__FILE__);
	   }   

	   foreach ($new_data as $data)
	   {
	      $data_array = explode("-",$data);
	      $elder = $data_array[0];
	      $date  = "$data_array[1]-$data_array[2]-$data_array[3]";	      
	      $this->db->query("INSERT INTO eq_attendance (elder,date) "
	      		       . "VALUES (" . $elder . ",'". $date . "')",__LINE__,__FILE__);
	   }
	
	 $this->att_view();
	 return false;    
	}

      $sql = "SELECT * FROM eq_elder where valid=1";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
	{
	  $elder_name[$i] = $this->db->f('name');
	  $elder_id[$i] = $this->db->f('elder');
	  $i++;
	}
      array_multisort($elder_name, $elder_id);
      
      if($action == 'update_month')
      {
        $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.att_update&action=save_month'));
        $i=0; 
	$last_time = 0; 
	$found_sunday = 0;
	$sunday_list[0]['date'] = date("Y-m-d", mktime(0, 0, 0, $monthnum[$month], 1, $year));
	$last_date = explode("-",$sunday_list[0]['date']);
	$last_time = mktime(0, 0, 0, $last_date[1], $last_date[2], $last_date[0]);
	$time_limit = mktime(0, 0, 0, $monthnum[$month], 31, $year);
	while($last_time <= $time_limit)
	{
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
	$elder_width=200; $att_width=25; $total_width=$elder_width;
	for ($i=0; $i < count($sunday_list); $i++) {
	  $link_data['menuaction'] = 'eq.eq.att_update';
	  $link_data['month'] = $sunday_list[$i]['month'];
	  $link_data['year'] = $sunday_list[$i]['year'];
	  $link_data['day'] = $sunday_list[$i]['day'];
	  $link_data['date'] = $sunday_list[$i]['date'];
	  $link_data['action'] = 'update_day';
	  $this->t->set_var('update_day',$GLOBALS['phpgw']->link('/eq/index.php',$link_data));
	  $this->t->set_var('date',$sunday_list[$i]['date']);
	  $this->t->set_var('day',$sunday_list[$i]['day']);
	  $this->t->set_var('month',$sunday_list[$i]['month']);
	  $this->t->set_var('year',$sunday_list[$i]['year']);
	  $this->t->fp('list2','header_list',True);
	  $total_width += $att_width;
	}     
      }

      if($action == 'update_day')
      {
        $this->t->set_var('actionurl',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.att_update&action=save_day'));
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
            
      for ($i=0; $i < count($elder_id); $i++) {
        $att_table = "";
	$this->nextmatchs->template_alternate_row_color(&$this->t);
	$this->t->set_var('elder_name',$elder_name[$i]);
	for ($j=0; $j < count($sunday_list); $j++) {
	  $sql = "SELECT * FROM eq_attendance WHERE date='"
	     . $sunday_list[$j]['date'] . "' AND elder=" . $elder_id[$i];
	  $this->db->query($sql,__LINE__,__FILE__);
	  $value = $elder_id[$i] . "-" . $sunday_list[$j]['date'];
	  if($this->db->next_record()) {
	    $att_table .= '<td align=center><input type="checkbox" name="elders_attended[]" value="'.$value.'" checked></td>';
	  } else {
	    $att_table .= '<td align=center><input type="checkbox" name="elders_attended[]" value="'.$value.'"></td>';
	  }
	}
	$this->t->set_var('att_table',$att_table);
	$this->t->fp('list3','elder_list',True);
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
      
      $sql = "SELECT * FROM eq_parent where valid=1 ORDER BY name ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
      	{
	  $parent[$i]['id'] = $this->db->f('parent');
	  $parent[$i]['name'] = $this->db->f('name');
	  $parent[$i]['phone'] = $this->db->f('phone');
	  $parent[$i]['address'] = $this->db->f('address');
	  $i++;
	}   
      
      for ($i=0; $i < count($parent); $i++) 
      {
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
      $sql = "SELECT * FROM eq_calling ORDER BY name ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
      	{
	  $calling[$i]['id'] = $this->db->f('indiv_id');
	  $calling[$i]['name'] = $this->db->f('name');
	  $calling[$i]['position'] = $this->db->f('position');
	  $calling[$i]['sustained'] = $this->db->f('sustained');
	  $calling[$i]['organization'] = $this->db->f('organization');
	  $i++;
	}   
      for ($i=0; $i < count($calling); $i++) 
      {
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
      $sql = "SELECT * FROM eq_calling ORDER BY sequence ASC";
      $this->db->query($sql,__LINE__,__FILE__);
      $i=0;
      while ($this->db->next_record())
      	{
	  $calling[$i]['id'] = $this->db->f('indiv_id');
	  $calling[$i]['name'] = $this->db->f('name');
	  $calling[$i]['position'] = $this->db->f('position');
	  $calling[$i]['sustained'] = $this->db->f('sustained');
	  $calling[$i]['organization'] = $this->db->f('organization');
	  $i++;
	}   
      for ($i=0; $i < count($calling); $i++) 
      {
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

  function admin()
    {
      $this->t->set_file(array('admin_t' => 'admin.tpl'));
      $this->t->set_block('admin_t','upload','uploadhandle');
      $this->t->set_block('admin_t','admin','adminhandle');
      $this->t->set_block('admin_t','cmd','cmdhandle');
      
      $this->t->set_var('upload_action',$GLOBALS['phpgw']->link('/eq/index.php','menuaction=eq.eq.admin&action=upload'));
      
      $action = get_var('action',array('GET','POST'));

      $this->t->pfp('out','admin_t');
      
      if($action == 'upload')
	{	 
	  $target_path = $this->upload_target_path . basename( $_FILES['uploadedfile']['name']);
	  
	  if(($_FILES['uploadedfile']['type'] == "application/zip") &&
	     (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))) {
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
	    $data_dir = $this->upload_target_path . $date;
	    print "-> Making the data directory: $date<br>\n";
	    exec('mkdir ' . $data_dir . ' 2>&1', $result, $return_code);
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
	    $data_file = $data_dir . '';
	    exec('unzip ' . $data_dir . '/*.zip -d ' . $data_dir . ' 2>&1', $result, $return_code);
	    if($return_code != 0) {
	      print implode('\n',$result) . "<br>";
	      print "<b><font color=red>";
	      print "-E- Unable to unzip the uploaded file into the data dir. Aborting import.";
	      print "</font></b>";
	      return 0;
	    }
	    exec('mv ' . $data_dir . '/*/* '. $data_dir . ' 2>&1', $result, $return_code);

	    # update the data_latest link to point to this new directory
	    print "-> Updating the latest data dir link<br>\n";
	    $data_latest = $this->upload_target_path . 'data_latest';
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
	    print "-> Importing the data into the EQ database<br>\n";
	    ob_flush(); flush(); sleep(1);
	    $import_log = $this->upload_target_path . '/import.log';
	    $data_log = $this->upload_target_path . '/data.log';
	    $import_cmd = $this->script_path . 'import_ward_data ' . $data_latest . ' | tee ' . $import_log;
	    $parse_cmd = $this->script_path . 'parse_ward_data -v ' . $data_latest . ' > ' . $data_log;
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
	    
	  } else if($_FILES['uploadedfile']['type'] != "application/zip") {
	    $uploadstatus = "<b><font color=red>The file format must be a .zip file, please try again! </font></b>";
	    $this->t->set_var('uploadstatus',$uploadstatus);
	    
	  } else {
	    $uploadstatus = "<b><font color=red> There was an error (" . $_FILES['uploadedfile']['error'];
	    $uploadstatus.= ") uploading the file, please try again! </font></b>";
	    $this->t->set_var('uploadstatus',$uploadstatus);
	  }
	}
      else
	{
	  $this->t->set_var('adminhandle','');
	  $this->t->pfp('adminhandle','admin'); 
	}
      
      $this->save_sessiondata();   
    }
  
}

?>
