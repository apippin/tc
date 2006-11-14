<center>
	<form action="{actionurl}" method="POST">
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>

	<a href="{ppi_link}">{ppi_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{eqpres_ppi_link}">{eqpres_ppi_link_title}</a>
	&nbsp; | &nbsp;
	<a href="{schedule_ppi_link}">{schedule_ppi_link_title}</a>
	<br><br>


	<form action="{actionurl}" method="POST">

<!-- BEGIN elder_list -->
	<table border="0" width="{table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
				<font face="{font}"><b>All Elders with Yearly PPI Not Completed</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
		{table_data}
		<tr>
		<td></td><td></td><td></td><td></td>
		<td height="50" align="right">
			<font face="{font}"><input type="submit" name="save" value="{lang_save}"></font>
			&nbsp; &nbsp;
			<font face="{font}"><input type="reset" name="reset" value="{lang_reset}"></font>
			</form>
		</td>
		</tr>
	</table>
<!-- END elder_list -->

	<table border="0" width="{completed_table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>All Elders with Yearly PPI Completed</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{completed_header_row}</tr>
		{completed}
	</table>
	<br><br>

	<table border="0" width="{totals_table_width}" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9" colspan=20>
			<font face="{font}"><b>Total EQ President Yearly PPIs</b></font>
			</td>
		</tr>
		<tr bgcolor="#c9c9c9"><font face="{font}">{totals_header_row}</tr>
		{totals}
	</table>
	<hr>
</center>

