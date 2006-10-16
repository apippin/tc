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
            <br>
	    <input type="submit" value="Import Data File"> &nbsp; (Import can take up to 30 seconds)
	    </form>
            <hr>
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

<!-- BEGIN cmd -->

<!-- END cmd -->

</center>



