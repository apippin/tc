
<center>
	<table border="0" width="80%" cellspacing="2" cellpadding="2">
	<tr>
	<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Willingness to Serve Table</b></font></td>
	</tr>
	</table>

	<form action="{filterurl}" method="POST">
	<table width="70%" border="0" cellspacing="2" cellpadding="2" align=center>
	<tr>
		<td align=center>
		{lang_filter_unwilling}
		&nbsp;
		{filter_input}
		<font face="{font}"><input type="submit" name="filter" value="{lang_filter}"></font>
		</td>
	</tr>
	</table>
	</form>
	This table shows what assignments each individual is willing to fulfill, and when they last did so.
	<form action="{actionurl}" method="POST">
	<table border="0" width="{total_width}" cellspacing="2" cellpadding="2">
	<tr bgcolor="#c9c9c9">
		<td width={indiv_width}><b><center>Individual</center></b></td>
<!-- BEGIN header_list -->
		<td width={willing_width}><b><center><font size=-2>{assignment_name}</font></center></b></td>
<!-- END header_list -->
	</tr>

<!-- BEGIN indiv_list -->
	<tr bgcolor="{tr_color}"><td title="{indiv_phone}"><b><font size=-2><a href={editurl}>{indiv_name}</a></b></font></td>{willing_table}</tr>
<!-- END indiv_list -->
	<tr bgcolor="#c9c9c9">
	{stat_table}
	</tr>
	</table>

</center>
