<!-- $Id: list.tpl,v 1.1.1.1.6.1 2004/11/06 15:41:41 powerstat Exp $ -->

<center>
	<table border="0" cellspacing="2" cellpadding="2">
		<tr>
			<td colspan="5" align="center" bgcolor="#c9c9c9"><font face="{font}"><b>{title_notes}:&nbsp;{name}<b/></font></td>
		</tr>
		<tr>
			<td colspan="5" align=left>
				<table border="0" width="100%">
					<tr>
					{left}
						<td align="center"><font face="{font}">{search_message}</font></td>
					{right}
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><font face="{font}">
    			<form action="{cat_action}" name="form" method="post">
    			{lang_category}&nbsp;&nbsp;<select name="cat_id" onchange="this.form.submit();"><option value="">{lang_all}</option>{category_list}</select>
    			<noscript>&nbsp;<input type="submit" name="submit" value="{lang_submit}"></noscript></form></font>
			</td>
			<td colspan="5" align=right>
				<form method="post" action={actionurl}><font face="{font}">
				{hidden_vars}
				<input type="text" name="search" />&nbsp;<input type="submit" name="submit" value="{lang_search}" />
				</font></form></td>
		</tr>

<!-- BEGIN notes_list -->

		<tr bgcolor="{tr_color}">
			<td><font face="{font}">{new_date}</font></td>
			<td><font face="{font}" size="{font_size}">{first}</font></td>
			<td align="center"><font face="{font}"><a href="{view}">{lang_view}</a></font></td>
			<td align="center"><font face="{font}"><a href="{edit}">{lang_edit}</a></font></td>
			<td align="center"><font face="{font}"><a href="{delete}">{lang_delete}</a></font></td>
		</tr>

<!-- END notes_list -->

		<tr valign="bottom">
			<td colspan="5"><form method="post" action="{addurl}"><font face="{font}">
				{hidden_vars}
				<input type="submit" name="add" value="{lang_add}" /></font></form></td>
		</tr>
	</table>
</center>