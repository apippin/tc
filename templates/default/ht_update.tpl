<center>
	<form action="{actionurl}" method="POST">
	<input type=hidden name=district value={district_number}>
	<input type=hidden name=date value={date}>

	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

<!-- BEGIN district_list -->
	<br><br>
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>District {district_number}: {district_name}</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
		{stat_data}
	</table>
<!-- END district_list -->

<!-- BEGIN save -->
	<table width="70%" border="0" cellspacing="2" cellpadding="2" align=center>
		<tr valign="bottom">
			<td height="50" align="right">
				<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font></td>
			<td height="50" align="center">
				<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font></form></td>
			<td height="50" align="left">
				<form action="{done_action}" method="POST">
				<font face="{font}"><input type="submit" name="done" value="{lang_done}"></font></form></td>
		</tr>
	</table>
</center>
<!-- END save -->

</center>
