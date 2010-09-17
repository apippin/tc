<center>
	<form action="{actionurl}" method="POST">
	<input type=hidden name=individual value={individual}>

	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

<!-- BEGIN assignment_list -->
	<br><br>
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
 		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>Individual: {individual_name}</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
	</table>
<!-- END assignment_list -->

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
