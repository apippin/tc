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
	$setup_info['tc']['name']      = 'tc';
	$setup_info['tc']['title']     = '3rd Counselor';
	$setup_info['tc']['version']   = '1.0.0';
	$setup_info['tc']['app_order'] = 8;
	$setup_info['tc']['enable']    = 1;
	
	/* some info's for about.php and apps.phpgroupware.org */
	$setup_info['tc']['author']    = 'Alan J. Pippin';
	$setup_info['tc']['license']   = 'GPL';
	$setup_info['tc']['description'] =
		'Module for managing the administrative tasks of a Mormon Elders Quorum or High Priest Group';
	$setup_info['tc']['note'] =
		'Originally developed by Alan Pippin; Recent contributions and maintenance done by Owen Leonard';
	$setup_info['tc']['maintainer'] = 'Alan J. Pippin';
	$setup_info['tc']['maintainer_email'] = 'apippin@pippins.net';
	
	/* The tables this app creates */
	/* This APP requires manual table creation */
	/* Manually import the tc/sql/tc.sql file into mysql */

	/* The hooks this app includes, needed for hooks registration */
	$setup_info['tc']['hooks'] = Array(
		'preferences',
		'manual',
		'add_def_prefs'
	);

	/* Dependacies for this app to work */
	$setup_info['tc']['depends'][] = array(
		 'appname' => 'phpgwapi',
		 'versions' => Array('0.9.10', '0.9.11' , '0.9.12', '0.9.13', '0.9.14', '0.9.16')
	);
?>
