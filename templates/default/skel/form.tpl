<!-- $Id: form.tpl,v 1.1.1.1.6.1 2004/11/06 15:41:41 powerstat Exp $ -->

<!-- BEGIN form -->

<center>
	<form action="{actionurl}" method="post">
	{hidden_vars}
	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_action}:&nbsp;{name}</b></font></td>
		</tr>
		<tr>
			<td align="center"><font face="{font}">{message}</font></td>
		</tr>
		<tr>
			<td align="center"><font face="{font}"><select name="new_cat"><option value="">{lang_choose}</option>{main_cat_list}</select></font></td>
		</tr>
		<tr>
			<td align="center"><font face="{font}" size="{font_size}"><textarea cols="60" rows="10" name="note">{note}</textarea></font></td>
		</tr>
		<tr>
			<td align="center"><font face="{font}">{lang_access}:</font>&nbsp;&nbsp;{access}</td>
		</tr>
	</table>

<!-- BEGIN add -->

	<table width="40%" border="0" cellspacing="2" cellpadding="2">
		<tr valign="bottom">
			<td height="50" align="center">
				{hidden_vars}
				<font face="{font}"><input type="submit" name="addnote" value="{lang_add}"></font></td>
			<td height="50" align="center">
				<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font></form></td>
			<td height="50" align="center" valign="middle">
				{hidden_vars}
				<font face="{font}"><a href="{done_action}">{lang_done}</a></font></td>
		</tr>
	</table>
</center>

<!-- END add -->

<!-- BEGIN edit -->
	
	<table width="40%" border="0" cellspacing="2" cellpadding="2">
		<tr valign="bottom">
			<td height="50" align="center">
				{hidden_vars}
				<font face="{font}"><input type="submit" name="editnote" value="{lang_edit}"></font>
				</form></td>
			<td height="50" align="center">
				{hidden_vars}
				<font face="{font}">{delete}</font></td>
			<td height="50" align="center" valign="middle">
				{hidden_vars}
				<font face="{font}"><a href="{done_action}">{lang_done}</a></font></td>
		</tr>
	</table>
</center>

<!-- END edit -->

<!-- END form -->
