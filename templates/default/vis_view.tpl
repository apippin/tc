<hr>

<center>

	<a href="{vis_link}">{vis_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_vis_link}">{schedule_vis_link_title}</a>
	<br><br>

	<h3>Presidency Visits</h3>
        <table border="0" cellspacing="2" cellpadding="2">
                <tr>
			<td colspan="1" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_name}</b></font></td>
                        <td colspan="1" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{lang_date}</b></font></td>
                </tr>

<!-- BEGIN visit_list -->

                <tr bgcolor="{tr_color}">
                        <td width=60%><font face="{font}" size="{font_size}">
                        <a href="{view}">{family_name} Family</a></font></td>
                        <td><font face="{font}">{date}</font></td>
                        <td align="center"><font face="{font}"><a href="{view}">{lang_view}</a></font></td>
                        <td align="center"><font face="{font}"><a href="{edit}">{lang_edit}</a></font></td>
                </tr>

<!-- END visit_list -->

        </table>
	
	<br><br>

	<table border="0" width="70%" cellspacing="2" cellpadding="2">
                <tr bgcolor="#c9c9c9" align=center><td colspan=3><font face="{font}">
		<b>Ward Families Available to Visit</b></font></td></tr>
	        <tr>
<!-- BEGIN family_list -->
		<td width=25% bgcolor={tr_color}><font face="{font}"><a href="{add}">{name} Family</a></font>{table_sep}
<!-- END family_list -->
		</tr>
        </table>
</center>

