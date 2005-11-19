<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="80%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Elder Participation Table</b></font></td>
		</tr>
	</table>
	<br><br>
	<table border="0" width="{total_width}" cellspacing="2" cellpadding="2">
	<tr bgcolor="#c9c9c9">
		<th width={elder_width}>Elder</th>
<!-- BEGIN header_list -->
		<th width={act_width}><font size=-3>{activity_name}<br>{activity_date}</font></th>
<!-- END header_list -->
		<th width={part_width}><font size=-3>Participated</font></th>
	</tr>

<!-- BEGIN elder_list -->
	<tr bgcolor="{tr_color}"><td>{elder_name}</td>{part_table}</tr>
<!-- END elder_list -->

	</table>
</center>
