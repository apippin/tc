<?php
  /**************************************************************************\
  * phpGroupWare - tc                                                        *
  * http://www.phpgroupware.org                                              *
  * -----------------------------------------------                          *
  *  This program is free software; you can redistribute it and/or modify it *
  *  under the terms of the GNU General Public License as published by the   *
  *  Free Software Foundation; either version 2 of the License, or (at your  *
  *  option) any later version.                                              *
  \**************************************************************************/

	/* $Id: hook_admin.inc.php,v 1.3.4.1 2003/03/27 18:10:15 ralfbecker Exp $ */
{
// Only Modify the $file and $title variables.....
	$file = Array(
//		'Administrate'	=> $GLOBALS['phpgw']->link('/tc/admin.php'),
	);
//Do not modify below this line
	display_section($appname,$file);
}
?>
