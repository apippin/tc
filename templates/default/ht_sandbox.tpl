<center>

	<form action="{submit_action}" method="POST">
	<table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>
	<br>

 	<table border="0" cellspacing="2" cellpadding="2">
		<tr>
			<th bgcolor="#c9c9c9">Companionship</th>
			<th bgcolor="#c9c9c9">Unassigned HT</th>
			<th bgcolor="#c9c9c9">Assigned HT</th>
			<th bgcolor="#c9c9c9">Unassigned Families</th>
			<th bgcolor="#c9c9c9">Assigned Families</th>
			<th bgcolor="#c9c9c9">Actions</th>
		</tr>
		<tr>
			<td align="center">
				Companionship
				<br>
				<select name="companionship">
<!-- BEGIN comp_list -->
					{companionship_list}
<!-- END comp_list -->
				</select>
				<br><br>
				District
				<br>
				<select name="district">
<!-- BEGIN district_list -->
					{district}
<!-- END district_list -->
				</select>
			</td>
			<td>
				<select name="unassignedHT[]" multiple size="9">
<!-- BEGIN unassigned_ht_list -->
					{unassigned_ht}
<!-- END unassigned_ht_list -->
				</select>
			</td>
			<td>
				<select name="assignedHT[]" multiple size="9">
<!-- BEGIN assigned_ht_list -->
					{assigned_ht}
<!-- END assigned_ht_list -->
				</select>
			</td>
			<td>
				<select name="unassignedFamilies[]" multiple size="9">
<!-- BEGIN unassigned_family_list -->
					{unassigned_family}
<!-- END unassigned_family_list -->
				</select>
			</td>
			<td>
				<select name="assignedFamiles[]" multiple size="9">
<!-- BEGIN assigned_family_list -->
					{assigned_family}
<!-- END assigned_family_list -->
				</select>
			</td>
			<td>
				<table>
					<tr>
						<td>
							<font face="{font}"><input type="reset" name="clear" value="Clear Selections"></font>
						</td>
					</tr>
					<tr>
						<td>
							<font face="{font}"><input type="submit" name="add" value="Add Companionship"></font>
						</td>
					</tr>
					<tr>
						<td>
							<font face="{font}"><input type="submit" name="delete" value="Delete Companionship"></font>
						</td>
					</tr>
					<tr>
						<td>
							<font face="{font}"><input type="submit" name="update" value="Update Companionship"></font>
						</td>
					</tr>
					<tr>
						<td>
							<font face="{font}"><input type="submit" name="reset" value="Reset to MLS"></font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>

	<font face="{font}" color=red><b>{debug_list}</b></font>

	<br>
	{district_table}
	<br><br>
</center>
