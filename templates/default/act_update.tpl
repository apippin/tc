<!-- BEGIN form -->

{app_header}

<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_action}:&nbsp;{name}</b></font></td>
		</tr>
		<tr>
			<td align="left"><font face="{font}"><b>Assignment:</b>&nbsp;{assignment_data}</td>
		</tr>
		<tr>
			<td align="left"><font face="{font}"><b>Date:</b>&nbsp;{cal_date}</td>
		</tr>
		<tr>
			<td align="left"><font face="{font}"><b>Description:</b></font></td>
		</tr>
		<tr>	
			<td align="left"><font face="{font}" size="{font_size}"><textarea cols="60" rows="10" name="notes" wrap="virtual">{notes}</textarea></font></td>
		</tr>
	</table>

	<table border="0" width="70%" cellspacing="2" cellpadding="2">
                <tr bgcolor="#c9c9c9" align=center><td colspan=3><font face="{font}"><b>Elders Attending</b></font></td></tr>
	        <tr>
<!-- BEGIN elder_list -->
		<td width=25% bgcolor={tr_color}><font face="{font}"><input type="checkbox" name="elder_name[]" value="{elder}" {checked}>{elder_name}</font>{table_sep}
<!-- END elder_list -->
		</tr>
        </table>

<!-- BEGIN add -->
	<table width="70%" border="0" cellspacing="2" cellpadding="2" align=left>
		<tr valign="bottom">
			<td height="50" align="right">
				<font face="{font}"><input type="submit" name="addnote" value="{lang_save}"></font></td>
			<td height="50" align="center">
				<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font></form></td>
			<td height="50" align="center">
				<form action="{done_action}" method="POST">
				<font face="{font}"><input type="submit" name="done" value="{lang_done}"></font></form></td>
		</tr>
	</table>
</center>
<!-- END add -->

<!-- BEGIN edit -->
	<table width="50%" border="0" cellspacing="2" cellpadding="2" align=left>
		<tr valign="bottom" align="left">
			<td height="50" align="right" valign="middle">
				<font face="{font}"><input type="submit" name="editnote" value="{lang_save}"></font>
				</form></td>
			<td height="50" align="center" valign="middle">
				<form action="{done_action}" method="POST">
				<font face="{font}"><input type="submit" name="done" value="{lang_done}"></font></form></td>
		</tr>
	</table>
</center>

<!-- END edit -->

<!-- END form -->
