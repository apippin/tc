<?php
	/**************************************************************************\
	* phpGroupWare                                                             *
	* http://www.phpgroupware.org                                              *
	* --------------------------------------------                             *
	*  This program is free software; you can redistribute it and/or modify it *
	*  under the terms of the GNU General Public License as published by the   *
	*  Free Software Foundation; either version 2 of the License, or (at your  *
	*  option) any later version.                                              *
	\**************************************************************************/

	/* $Id: setup.inc.php,v 1.9.4.2 2004/03/07 02:14:23 skwashd Exp $ */

	/* Basic information about this app */
	$setup_info['eq']['name']      = 'eq';
	$setup_info['eq']['title']     = 'Elders Quorum Tools';
	$setup_info['eq']['version']   = '0.0.1.001';
	$setup_info['eq']['app_order'] = 8;
	$setup_info['eq']['enable']    = 1;
	
	/* some info's for about.php and apps.phpgroupware.org */
	$setup_info['eq']['author']    = 'Alan J. Pippin';
	$setup_info['eq']['license']   = 'GPL';
	$setup_info['eq']['description'] =
		'This app provides tools to help manage an Elders Quorum';
	$setup_info['eq']['note'] =
		'This was created for the Miramont Ward.';
	$setup_info['eq']['maintainer'] = 'Alan J. Pippin';
	$setup_info['eq']['maintainer_email'] = 'apippin@pippins.net';
	
	/* The tables this app creates */
	$setup_info['eq']['tables'][] = 'eq_aaronic';
	$setup_info['eq']['tables'][] = 'eq_activity';
	$setup_info['eq']['tables'][] = 'eq_appointment';
	$setup_info['eq']['tables'][] = 'eq_assignment';
	$setup_info['eq']['tables'][] = 'eq_attendance';
	$setup_info['eq']['tables'][] = 'eq_calling';
	$setup_info['eq']['tables'][] = 'eq_child';
	$setup_info['eq']['tables'][] = 'eq_companionship';
	$setup_info['eq']['tables'][] = 'eq_district';
	$setup_info['eq']['tables'][] = 'eq_elder';
	$setup_info['eq']['tables'][] = 'eq_family';
	$setup_info['eq']['tables'][] = 'eq_interview';
	$setup_info['eq']['tables'][] = 'eq_parent';
	$setup_info['eq']['tables'][] = 'eq_participation';
	$setup_info['eq']['tables'][] = 'eq_ppi';
	$setup_info['eq']['tables'][] = 'eq_presidency';
	$setup_info['eq']['tables'][] = 'eq_visit';
	$setup_info['eq']['tables'][] = 'eq_willingness';

	/* The hooks this app includes, needed for hooks registration */
	$setup_info['eq']['hooks'] = Array(
		'preferences',
		'manual',
		'add_def_prefs'
	);

	/* Dependacies for this app to work */
	$setup_info['eq']['depends'][] = array(
			 'appname' => 'phpgwapi',
			 'versions' => Array('0.9.10', '0.9.11' , '0.9.12', '0.9.13', '0.9.14', '0.9.16')
		);
?>
