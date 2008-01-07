<!-- BEGIN form -->

{app_header}

<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_action}:&nbsp;{name}</b></font></td>
		</tr>
		<tr>
			<td align="left"><font face="{font}"><b>Name:</b>&nbsp;<input type=text size="60" name="name" value="{name}"</input></td>
		</tr>
		<tr>
			<td align="left"><font face="{font}"><b>Code:</b>&nbsp;<input type=text size="12" name="code" value="{code}"</input>&nbsp;This is a 2 or 3 letter abbreviation that uniquely identifies this assignment.</td>
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
