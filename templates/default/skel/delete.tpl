<!-- $Id: delete.tpl,v 1.1.1.1.6.1 2004/11/06 15:41:41 powerstat Exp $ -->

<br /><br /><br />

<center>
	<table border="0" width="50%" cellpadding="2" cellspacing="2">
		<form method="post" action="{action_url}">
		<tr>
			<td align="center"><font face="{font}">{deleteheader}</font></td>
		</tr>
		<tr>
			<td>
				<table border="0" width="30%" align="center">
					<tr>
						<td align="center">
							{hidden_vars}
							<input type="submit" name="confirm" value="{lang_yes}"></form></td>
						<td align="center"><a href="{nolink}">{lang_no}</a></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</center>