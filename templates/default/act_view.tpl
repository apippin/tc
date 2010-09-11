
<!-- BEGIN view -->

{app_header}

<center>
        <table border="0" width="70%" cellspacing="2" cellpadding="2">
                <tr>
                        <td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_action}:&nbsp;{name}</b></font></td>
                </tr>
                <tr>
                        <td>&nbsp;</td>
                </tr>
                <tr>
                        <td><b>{lang_name}:</b>&nbsp;{name}</font></td>
                </tr>
                <tr>
                        <td><b>{lang_date}:</b>&nbsp;{cal_date}</font></td>
                </tr>
	        <tr>
  			<td><b>{lang_notes}:</b></font></td>
		</tr>
                <tr>
                        <td bgcolor="{tr_color}"><font face="{font}" size="{font_size}">{notes}</font></td>
                </tr>
	</table>
	
	<table border="0" width="70%" cellspacing="2" cellpadding="2">
                <tr bgcolor="#c9c9c9" align=center><td colspan=3><font face="{font}"><b>Individuals Attending</b></font></td></tr>
	        <tr>
<!-- BEGIN part_list -->
		<td width=25% bgcolor={tr_color}><font face="{font}">{indiv_name}</font>{table_sep}
<!-- END part_list -->
		</tr>
        </table>

	<table border="0" width="70%" cellspacing="2" cellpadding="2">
                <tr>
                        <td height="50" valign="bottom" width=20%>
                                <form action="{done_action}" method="POST">
                                <font face="{font}"><input type="submit" name="done" value="{lang_done}"></font>
                                </form>
                        </td>
			<td height="50" valign="bottom">
			<a href={edit}>Edit</a>
			</td>
                </tr>
	</table>
</center>

<!-- END view -->


