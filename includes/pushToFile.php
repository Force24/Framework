<?php
  	date_default_timezone_set('Europe/London');
  	$dateNow = date('d/m/Y h:i:s a');
  	$data = [];
  	$fields = ["firstName", "lastName", "email", "companyName", "number"]; // insert correct field names
  	foreach ($fields as $field) {
      	if (isset($_POST[$field])) {
          	$field = $_POST[$field];
          	$data[] = is_array($field) ? implode(', ', $field) : trim($field);
      	} else {
          	$data[] = '';
      	}
  	}  

	$handle = fopen((__dir__)."/csvName.csv", "a+"); // replace with correct csv name
	fputcsv($handle, $data);
	fclose($handle);

	$to = ""; // this is your Email address
	// $bcc = "";
	$from = $_POST['email']; // this is the sender's Email address
	$name = $_POST['name'];
	$subject = "Submission by: " . $_POST['firstName'] . " " . $_POST['lastName'];
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1" . "\r\n";
	$message = '<html><body>';
	$message .= '<table width="180" style="width:180px;table-layout:fixed;border:0px" cellpadding="0" cellspacing="0" border="0" align="center">';
	$message .= '<tr><td align="center" style="">';

	//Client Logo
	$message .= '<img src="https://s3.eu-west-2.amazonaws.com/force24-assets/EmailTemplates/AccountTemplates/db41dc8a/a91b937e/images/1512638765-d0885b6e.jpg?v=1561044139751" width="180" alt="Image" style="display:block;width:180px;border:0px" border="0">';
	//End Client Logo

	$message .= '</td></tr></table>';
	$message .= '<table width="600" style="width:600px;table-layout:fixed;border:0px" cellpadding="0" cellspacing="0" border="0" align="center">';
	$message .= '<tr><td align="center" style="text-align:left; font-family: Arial, Helvetica, sans-serif; color: #000000; padding:20px 0">';
	$message .= '<div style="text-align: center;"><span style="line-height: 145%; font-size: 16px; font-weight:bold">' . $dateNow . '</span></div>';
	$message .= "<div style='text-align: center;'><span style='line-height: 145%; font-size: 16px;'>" . $_POST['firstName'] . " " . $_POST['lastName'] . "</span><strong><span style='line-height: 145%; font-size: 16px;'>&#160;</span></strong><span style='line-height: 145%; font-size: 16px;'>has submitted your form</span></div>";
	$message .= '<div style="text-align: center;">&#160;</div>';

	//Link to page with form
	$message .= '<div style="text-align: center;"><table style="margin:0 auto;border:0px" border="0" cellspacing="0" cellpadding="0" align="Center"><tbody><tr><td align="center" style=";"><table style="margin:0 auto;border:0px" border="0" cellspacing="0" cellpadding="0" align="Center"><tbody><tr><td style="" align="center"><a style="text-decoration: none; text-align: center; font-weight: Regular; font-size: 16px; font-family: Arial, "Helvetica Neue", Helvetica, sans-serif; color: #000000;line-height: 145%;" href="http://force24.co.uk">Visit the page</a></td></tr></tbody></table></td></tr></tbody></table></div>';
	//End Link to page with form

	$message .= '</td></tr></table>';
	$message .= '<table width="600" style="width:600px;background-color:#e5f6f9;border:0px" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="#e5f6f9">';
	$message .= '<tr><td align="center" style=""><table width="520" style="width:520px;border:0px" cellpadding="0" cellspacing="0" border="0" align="center">';
	$message .= '<tr><td align="center" valign="top" style="width: auto; padding: 25px 0px;">';
	$message .= '<table width="520" style="width:520px;table-layout:fixed;border:0px" cellpadding="0" cellspacing="0" border="0" align="center">';
	$message .= '<tr><td align="center" style="text-align:left; font-family: Arial, Helvetica, sans-serif; color: #000000;">';
	$message .= '<div style="text-align: center; line-height:145%; font-size:16px;">';
	$message .= '<span style="font-family: arial, helvetica, sans-serif; font-size: 16px;">';

	//Fields
	$message .= "<strong>First Name:</strong> " . $_POST['firstName'] . "<br>";
	$message .= "<strong>Last Name:</strong> " . $_POST['lastName'] . "<br>";
	$message .= "<strong>Email Address:</strong> " . $_POST['email'] . "<br>";
	$message .= "<strong>Company Name:</strong> " . $_POST['companyName'] . "<br>";
	$message .= "<strong>Telephone Number:</strong> " . $_POST['number'] . "<br>";
	//End Fields

	$message .= "</span></div></td></tr></table></td></tr></table></td></tr></table>";
	$message .= "</body></html>";
	mail($to,$subject,$message,$headers);
	echo 'done';
  
?>