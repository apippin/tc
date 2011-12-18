<center>
<script type="text/javascript" src="{jquery_url}"></script>
<script type="text/javascript" src="{jquery_tablesorter_url}"></script>
<script type="text/javascript">                                         
$(document).ready(function() 
    { 
	  $("#callings").tablesorter( {
	  							  cssHeader: "headerSort",
								  cssAsc: "headerSortUp",
								  cssDesc: "headerSortDown"
								  } );
    } 
); 
</script>   

	<table border="0" width="80%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Ward Callings</b></font></td>
		</tr>
	</table>

	<table id="callings" class="tablesorter" border="0" width=80% cellspacing="2" cellpadding="2">
		<thead>
		<tr>
			<th colspan="1" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Name</b></font></th>
			<th colspan="1" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Calling</b></font></th>
			<th colspan="1" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Organization</b></font></th>
			<th colspan="1" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Date Sustained</b></font></th>
		</tr>
		</thead>
		<tbody>
<!-- BEGIN calling_list -->
		<tr bgcolor="{tr_color}">
			<td align="left" width=25%><font face="{font}">{name}</font></td>
			<td align="left" width=25%><font face="{font}">{position}</font></td>
			<td align="left" width=25%><font face="{font}">{organization}</font></td>
			<td align="left" width=25%><font face="{font}">{sustained}</font></td>
		</tr>
<!-- END calling_list -->
		</tbody>

	</table>
</center>
