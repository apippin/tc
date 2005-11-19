<!-- $Id: view.tpl,v 1.1.1.1 2001/05/20 07:40:32 seek3r Exp $ -->

<!-- BEGIN view -->

<center>
	<table border="0" width="70%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_action}:&nbsp;{name}</b></font></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><font face="{font}"><b>{lang_time}:</b>&nbsp;{new_date}</font></td>
		</tr>
		<tr>
			<td bgcolor="{tr_color}"><font face="{font}" size="{font_size}">{fnote}</font></td>
		</tr>
		<tr>
			<td height="50" valign="bottom">
				{hidden_vars} 
				<font face="{font}"><a href="{done_action}">{lang_done}</a></font></td>
		</tr>
	</table>
</center>

<!-- END view -->