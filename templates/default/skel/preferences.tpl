<!-- $Id: preferences.tpl,v 1.1.1.1.6.1 2004/11/06 15:41:41 powerstat Exp $ -->
<br /><br />
<center>
	<form method="post" name="prefs_form" action="{actionurl}">
	<table width="70%" border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td colspan="2" bgcolor="#c0c0c0" align="center"><font face="{font}"><b>{lang_action}</b></font></td>
		</tr>
		<tr>
			<td align="right"><font face="{font}">{lang_select_font}:</font></td>
			<td><font face="{font}" size="{font_size}"><select name="notes_font">{notes_font}</select></font></td>
		</tr>
		<tr>
			<td align="right"><font face="{font}">{lang_select_size}:</font></td>
			<td><font face="{font}" size="{font_size}"><select name="notes_font_size">{notes_font_size}</select></font></td>
		</tr>
		<tr valign="bottom">
			<td height="50"><font face="{font}">
			<input type="submit" name="submit" value="{lang_edit}" /></font>
			</form></td>
			<td>&nbsp;</td>
		</tr>
	</table>
</center>