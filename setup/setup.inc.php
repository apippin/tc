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
	$setup_info['eq']['name']      = '3rd';
	$setup_info['eq']['title']     = '3rd Counselor';
	$setup_info['eq']['version']   = '0.2.0';
	$setup_info['eq']['app_order'] = 8;
	$setup_info['eq']['enable']    = 1;
	
	/* some info's for about.php and apps.phpgroupware.org */
	$setup_info['eq']['author']    = 'Alan J. Pippin';
	$setup_info['eq']['license']   = 'GPL';
	$setup_info['eq']['description'] =
		'This app provides tools to help manage a Priesthood Quorum';
	$setup_info['eq']['note'] =
		'This was created for the Miramont Ward.';
	$setup_info['eq']['maintainer'] = 'Alan J. Pippin';
	$setup_info['eq']['maintainer_email'] = 'apippin@pippins.net';
	
	/* The tables this app creates */
	/* This APP requires manual table creation */
	/* Manually import the eq/sql/eq.sql file into mysql */

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
