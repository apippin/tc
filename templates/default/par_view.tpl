<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="80%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Participation Table</b></font></td>
		</tr>
	</table>
	<br>
	This table shows how many times each individual has participated in each assignment and when they last did so.
        <br>
	<table border="0" width="{total_width}" cellspacing="2" cellpadding="2">
	<tr bgcolor="#c9c9c9">
		<td width={individual_width}><b><center>Individual</center></b></td>
<!-- BEGIN header_list -->
		<td width={act_width}><b><center><font size=-2>{assignment_name}</font></center></b></td>
<!-- END header_list -->
		<td width={part_width}><b><center><font size=-2>Participated</font></center></b></td>
	</tr>

<!-- BEGIN individual_list -->
	<tr bgcolor="{tr_color}"><td><b><font size=-2>{individual_name}</b></font></td>{part_table}</tr>
<!-- END individual_list -->

	</table>
</center>
