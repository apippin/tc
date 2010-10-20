<center>
	<table border="0" width="50%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

	<a href="{ppi_link}">{ppi_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_ppi_link}">{schedule_ppi_link_title}</a>
	<br><br>

<hr>
<!-- BEGIN appt_list -->
	<form action="{actionurl}" method="POST">
		<input type=hidden name=presidency_location value={presidency_location}>
        <table border="0" width="50%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>{appt_table_title}</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{appt_header_row}</tr>
		{appt_table_data}
		<tr>
		<td></td><td></td><td></td><td></td>
		</tr>
        </table>
	<table border="0" width="50%" cellspacing="2" cellpadding="2">
		<tr>
                <td height="50" align="right">
			<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font>
			&nbsp; &nbsp;
			<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
		</td>
		</tr>
	</table>
	</form>
<!-- END appt_list -->
<hr>
<!-- BEGIN individual_list -->
        <form action="{actionurl}" method="POST">
	<table border="0" table="50%" cellspacing="2" cellpadding="2">
              	<tr>
                	<td colspan=5 height="50" align="right">
                        	<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font>
                        	&nbsp; &nbsp;
                        	<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
                	</td>
		</tr>
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>{not_completed_table_title}</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
		<tr>
		<tr><td colspan=5 align=center><i>Note: The highest priority is 1, the lowest priority is 30</i></td></tr>
		<td></td><td></td><td></td><td></td>
		<td height="50" align="right">
			<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font>
			&nbsp; &nbsp;
			<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
		</td>
		</tr>
	</table>
        </form>
<!-- END individual_list -->
	<hr>
	<table border="0" width="50%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>{completed_table_title}</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{completed_header_row}</tr>
		{completed}
	</table>
	<br><br>

	<table border="0" width="50%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>Total {ppi_frequency_label} PPIs</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{totals_header_row}</tr>
		{totals}
	</table>
	<hr>
</center>


