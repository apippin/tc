<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

	<a href="{schedule_vis_link}">{schedule_vis_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_ppi_link}">{schedule_ppi_link_title}</a>
	<br><br>

<!-- BEGIN district_list -->
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>District {district_number} :&nbsp;{district_name} : Appointment Slots</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
		<tr>
		<td></td><td></td><td></td>
		<td height="50" align="right">
			<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font>
			<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
		</td>
		</tr>
	</table>
	<br><br>

<!-- END district_list -->	

	</form>

</center>








