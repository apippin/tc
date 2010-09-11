<?php
	/**************************************************************************\
	* phpGroupWare - Notes                                                     *
	* http://www.phpgroupware.org                                              *
	* Written by Bettina Gille [ceb@phpgroupware.org]                          *
	* -----------------------------------------------                          *
	*  This program is free software; you can redistribute it and/or modify it *
	*  under the terms of the GNU General Public License as published by the   *
	*  Free Software Foundation; either version 2 of the License, or (at your  *
	*  option) any later version.                                              *
	\**************************************************************************/
	/* $Id: index.php,v 1.48.4.1 2003/11/04 15:06:35 ceb Exp $ */

	$GLOBALS['phpgw_info']['flags'] = array
	(
		'currentapp' => '3rd',
		'noheader'   => True,
		'nonavbar'   => True 
	);
	include('../header.inc.php');

	if (@isset($_GET['menuaction']))
	{
		list($app,$class,$method) = explode('.',$_GET['menuaction']);
		if (! $app || ! $class || ! $method)
		{
			$invalid_data = True;
		}
	}
	else
	{
		$app = '3rd';
		$class = 'eq';
		$invalid_data = True;
	}

	$GLOBALS['obj'] = CreateObject(sprintf('%s.%s',$app,$class));
	$GLOBALS[$class] = $GLOBALS['obj'];
	if ((is_array($GLOBALS[$class]->public_functions) && $GLOBALS[$class]->public_functions[$method]) && ! $invalid_data)
	{
		execmethod($_GET['menuaction']);
		unset($app);
		unset($obj);
		unset($class);
		unset($method);
		unset($invalid_data);
		unset($api_requested);
	}
	else
	{
		if (! $app || ! $class || ! $method)
		{
			$GLOBALS['phpgw']->log->message(array(
				'text' => 'W-BadmenuactionVariable, menuaction missing or corrupt: %1',
				'p1'   => $menuaction,
				'line' => __LINE__,
				'file' => __FILE__
			));
		}

		if (! is_array($obj->public_functions) || ! $obj->public_functions[$method] && $method)
		{
			$GLOBALS['phpgw']->log->message(array(
				'text' => 'W-BadmenuactionVariable, attempted to access private method: %1',
				'p1'   => $method,
				'line' => __LINE__,
				'file' => __FILE__
			));
		}
		//$GLOBALS['phpgw']->log->commit();
		
		//$GLOBALS['phpgw']->redirect_link('/eq/index.php?menuaction=eq.eq.ht_view');
	} 

//$obj = CreateObject('eq.eq');
//$obj->ht_view();
//$GLOBALS['phpgw']->common->phpgw_footer();

?>
