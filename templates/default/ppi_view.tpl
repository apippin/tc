<center>

	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

	<a href="{ppi_link}">{ppi_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_ppi_link}">{schedule_ppi_link_title}</a>
	<br><br>

	<form action="{filterurl}" method="POST">
	<table width="40%" border="0" cellspacing="2" cellpadding="2" align=center>
	<tr>
		<td align=center width=90%>
		Showing <input type=text size="2" name="num_months" value="{num_months}">
		{lang_num_months}
		</td>
		<td align=center width=10%>	
		<font face="{font}"><input type="submit" name="filter" value="{lang_filter}"></font>
		</td>
	</tr>
	</table>
	</form>

	<form action="{actionurl}" method="POST">

	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>District {district_number}: {district_name}</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
		{stat_data}
	</table>
	<br><br>

</center>
