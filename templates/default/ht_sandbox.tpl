<center>

	<table border="0" width="100%" cellspacing="2" cellpadding="2">
		<tr>
			<td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title}</b></font></td>
		</tr>
	</table>
	<br>

	<form action="{linkurl}" method="POST">
	</form>

	<form action="{actionurl}" method="POST">

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
				<select name="unassignedHT" multiple size="9">
<!-- BEGIN unassigned_ht_list -->
					{unassigned_ht}
<!-- END unassigned_ht_list -->
				</select>
			</td>
			<td>
				<select name="AssignedHT&quot;" multiple size="9">
<!-- BEGIN assigned_ht_list -->
					{assigned_ht}
<!-- END assigned_ht_list -->
				</select>
			</td>
			<td>
				<select name="unassignedFamilies&quot;" multiple size="9">
<!-- BEGIN unassigned_family_list -->
					{unassigned_family}
<!-- END unassigned_family_list -->
				</select>
			</td>
			<td>
				<select name="AssignedFamiles&quot;" multiple size="9">
<!-- BEGIN assigned_family_list -->
					{assigned_family}
<!-- END assigned_family_list -->
				</select>
			</td>
			<td>
				<table>
					<tr>
						<td>
							<button>Clear Selections</button>
						</td>
					</tr>
					<tr>
						<td>
							<button>Add Companionship</button>
						</td>
					</tr>
					<tr>
						<td>
							<button>Delete Companionship</button>
						</td>
					</tr>
					<tr>
						<td>
							<button>Update Companionship</button>
						</td>
					</tr>
					<tr>
						<td>
							<button>Reset to MLS</button>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<br>
	{district_table}
	<br><br>
</center>
