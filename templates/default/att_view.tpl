<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="80%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Attendance Table</b></font></td>
		</tr>
	</table>
	<br>

	<form action="{filterurl}" method="POST">
	<table width="70%" border="0" cellspacing="2" cellpadding="2" align=center>
	<tr>
		<td align=center>
		Showing <input type=text size="2" name="num_quarters" value="{num_quarters}">
		{lang_num_quarters}
		</td>
		<td align=center>	
		<font face="{font}"><input type="submit" name="filter" value="{lang_filter}"></font>
		</td>
	</tr>
	</table>
	</form>

	<table border="0" width="{total_width}" cellspacing="2" cellpadding="2">
	<tr bgcolor="#c9c9c9">
		<th>&nbsp;</th>
<!-- BEGIN month_list -->
		<th colspan={span}><font size=-3>
		<a href="{update_month}">{month}&nbsp;{year}</a></font></th>
<!-- END month_list -->
	</tr>
	<tr bgcolor="#c9c9c9">
		<th width={individual_width}>Individual</th>
<!-- BEGIN header_list -->
		<th width={act_width}><font size=-3><a href="{update_day}">{day}</a></font></th>
<!-- END header_list -->
	</tr>

<!-- BEGIN individual_list -->
	<tr bgcolor="{tr_color}"><td>{individual_name}</td>{att_table}</tr>
<!-- END individual_list -->

	</table>
	<br>

	<table border="0" width="{total_width}" cellspacing="2" cellpadding="2">
	<tr bgcolor="#c9c9c9"><th width={individual_width}><font size=-2>{total_individuals} Total Individuals</font></th>{header_row}</tr>
	<tr>
		<td><b><font size=-2>Individuals Attending:</b></font></td>
		{attendance}
	</tr>
	<tr>
		<td><b><font size=-2>Individuals <font color=red>Not</font> Attending:</b></font></td>
		{nonattendance}
	</tr>
	<tr><td colspan={span}><hr></td></tr>
	<tr>
		<td><b><font size=-2>Average Individuals Attending:</b></font></td>
		{aveattendance}
	</tr>
	<tr>
		<td><b><font size=-2>Average Individuals <font color=red>Not</font> Attending:</b></font></td>	
		{avenonattendance}
	</tr>
	</table>
</center>

