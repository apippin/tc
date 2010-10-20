<center>
	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

	<a href="{vis_link}">{vis_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_vis_link}">{schedule_vis_link_title}</a>
	<br><br>



<!-- BEGIN appt_list -->
	<form action="{actionurl}" method="POST">
	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>Yearly Visits Appointment Slots</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{appt_header_row}</tr>
		{appt_table_data}
		<tr>
			<td></td><td></td><td></td><td></td>
		</tr>
		<tr>
			<td height="50" colspan=4 align="right">
				<font face="{font}"><input type="submit" name="save" value="{lang_save_appt}"></font>
				&nbsp; &nbsp;
				<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
				</form>
			</td>
		</tr>
	</table>
<!-- END appt_list -->

<!-- BEGIN family_list -->
	<form action="{actionurl}" method="POST">
	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>All Families with Yearly Visit Not Completed</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
		<tr>
		<tr><td colspan=5 align=center><i>Note: The highest priority is 1, the lowest priority is 30</i></td></tr>
		<tr>
		<td height="50" colspan=5 align="right">
			<font face="{font}"><input type="submit" name="save" value="{lang_save_pri_notes}"></font>
			&nbsp; &nbsp;
			<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
			</form>
		</td>
		</tr>
	</table>
<!-- END family_list -->


	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>All Families with Yearly Visit Completed</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{completed_header_row}</tr>
		{completed}
	</table>
	<br><br>

	<table border="0" width="60%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>Total Presidency Yearly Visits</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{totals_header_row}</tr>
		{totals}
	</table>
	<hr>
</center>









