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
	/* $Id: hook_add_def_pref.inc.php,v 1.1 2001/06/09 02:43:01 skeeter Exp $ */

	global $pref;
	$pref->change('notes','notes_font','Verdana,Arial,Helvetica,sans-serif');
	$pref->change('notes','notes_font_size','3');
?>
