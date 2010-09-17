<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

	<a href="{int_link}">{int_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_int_link}">{schedule_int_link_title}</a>
	<br><br>


<!-- BEGIN individual_list -->
	<form action="{actionurl}" method="POST">
        <hr>
        <table border="0" width="{appt_table_width}" cellspacing="2" cellpadding="2">
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
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
                <td height="50" align="right">
			<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font>
			&nbsp; &nbsp;
			<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
			</form>
		</td>
		</tr>
	</table>

	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>{table_title}</b></font>
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
			</form>
		</td>
		</tr>
	</table>
<!-- END individual_list -->
	<hr>
	<table border="0" width="{completed_table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>All Individuals with Interviews Completed</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{completed_header_row}</tr>
		{completed}
	</table>
	<br><br>

	<table border="0" width="{totals_table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>Total Hometeaching Interviews</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{totals_header_row}</tr>
		{totals}
	</table>
	<hr>
</center>












