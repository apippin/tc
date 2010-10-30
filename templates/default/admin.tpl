<!-- BEGIN admin_t -->
	<center>
	<table border="0" width="80%" cellspacing="2" cellpadding="2">
	<tr>
	  <td align="center" bgcolor="#c9c9c9"><font face="{font}"><b>Administrator Tools</b></font></td>
	</tr>
	</table>
	<br>
<!-- END admin_t -->

<!-- BEGIN admin -->
	<center>
        <table border="0" width="80%" cellspacing="2" cellpadding="2">
	<tr>
          <td align="left">
	    <hr>
  	    <b>Update our MLS database snapshot</b>
	    <br><br>
  	    <form enctype="multipart/form-data" action="{upload_action}" method="POST">
	    <input type="hidden" name="MAX_FILE_SIZE" value="500000">
	    Choose the MLS data file to upload (.zip): <input name="uploadedfile" type="file" size=40><br>
            It must contain the following files from MLS: Membership.csv, Hometeaching.csv, Organization.csv
            <br><br>
	    <input type="submit" value="Import Data File"> &nbsp; (Import can take up to 60 seconds)
	    </form>
	  </td>
        </tr>
        </table>
<!-- END admin -->

<!-- BEGIN upload -->
	<center>
        <table border="0" width="80%" cellspacing="2" cellpadding="2">
	<tr>
          <td align="left">
	    <hr>
  	    {uploadstatus}
	    <hr>
	  </td>
	</tr>
	</table>
<!-- END upload -->

<!-- BEGIN leader -->
	<center>
        <table border="0" width="80%" cellspacing="2" cellpadding="2">
	<tr>
          <td align="left">
	    <hr>
  	    <b>Update the Leadership Table</b>
	    <br><br>
	    <form enctype="multipart/form-data" action="{leader_action}" method="POST">
            <table border="0" width="80%" cellspacing="2" cellpadding="2">
            <tr bgcolor="#c9c9c9"><font face="{font}">{header_row}</tr>
            {table_data}
            </table>
            <b>Notes:</b>
            <br>- The District is a drop-down list. Please assign a unique district number per district supervisor.
	    <br>- The President, Counselor, Secretary, and Presidency fields are boolean value (true|false) flags.
            <br>- If a member of the presidency is not a District Leader, make sure to set their "District=0".
            <br>- If a district leader is not a member of the presidency, add them without setting any other flags except the district.
            <br>- You can only add 1 new presidency member at a time (1 per each update request to the table).
	    <br><br>
	    <input type="submit" value="Update Leadership Table">
	    </form>
          </td>
        </tr>
        </table>
        </center>
<!-- END leader -->

<!-- BEGIN cmd -->

<!-- END cmd -->

</center>



