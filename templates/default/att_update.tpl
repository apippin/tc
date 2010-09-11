<center>
	<form action="{actionurl}" method="POST">
	<input type=hidden name=year value={year}>
	<input type=hidden name=month value={month}>
	<input type=hidden name=day value={day}>

	<table border="0" width="80%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Attendance Table</b></font></td>
		</tr>
	</table>
	<br>
	<b>Note:</b> All individuals serving in the EQ Presidency, Young Men, Sunday School, or Primary
	<br>have been automarked as attending. Feel free to adjust this accordingly.
	<br>
	<table border="0" width="{total_width}" cellspacing="2" cellpadding="2">
	<tr bgcolor="#c9c9c9">
		<th>&nbsp;</th>
<!-- BEGIN month_list -->
		<th colspan={span}><font size=-3>{month}&nbsp;{year}</font></th>
<!-- END month_list -->
	</tr>
	<tr bgcolor="#c9c9c9">
		<th width={indiv_width}>Individual</th>
<!-- BEGIN header_list -->
		<th width={act_width}><font size=-3><a href="{update_day}">{day}</a></font></th>
<!-- END header_list -->
	</tr>

<!-- BEGIN indiv_list -->
	<tr bgcolor="{tr_color}"><td>{indiv_name}</td>{att_table}</tr>
<!-- END indiv_list -->

	</table>

<!-- BEGIN edit -->
	<table width="50%" border="0" cellspacing="2" cellpadding="2" align=center>
		<tr valign="bottom" align="left">
			<td height="50" align="center" valign="middle">
				<font face="{font}"><input type="submit" name="editnote" value="{lang_save}"></font>
				</form></td>
			<td height="50" align="left" valign="middle">
				<form action="{done_action}" method="POST">
				<font face="{font}"><input type="submit" name="done" value="{lang_done}"></font></form></td>
		</tr>
	</table>
<!-- END edit -->

</center>
