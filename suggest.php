<?php 

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$name = trim(filter_input(INPUT_POST,"name",FILTER_SANITIZE_STRING));
	$email = trim(filter_input(INPUT_POST,"email",FILTER_SANITIZE_EMAIL));
	$details = trim(filter_input(INPUT_POST,"details",FILTER_SANITIZE_SPECIAL_CHARS));

	if ($name == "" || $email == "" || $details == "") {
		echo "please fill in the required fields: Name, Email and Details";
		exit;
	}
	if ($_POST["address"] != "") {
		echo "Bad form input";
		exit;
	}
	if (!PHPMailer::validateAddress($email)) {
		echo "Invalid Email Address";
		exit;
	} 

	$emailBody = "";
	$emailBody .= "Name " . $name . "\n";
	$emailBody .= "Email " . $email  . "\n";
	$emailBody .= "Details " . $details . "\n";

	//To Do: Send Email
	$mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 2;
	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "trentmcdole94@gmail.com";
	//Password to use for SMTP authentication
	$mail->Password = "aatvpavyqcmadsru";

        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('trentmcdole94@gmail.com', $name);
        $mail->addReplyTo($email, $name);
        $mail->addAddress('trentmcdole94@gmail.com', 'Trent McDole');
        $mail->Subject = 'Library Suggestion from' . $name;
        $mail->Body = $emailBody;
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            exit;
        }
            header("location:suggest.php?status=thanks");
	
}

$pageTitle = 'Suggest a Media Item';
$section = "suggest";
include 'inc/header.php'; 

?>

<div class="section page">
	<div class="wrapper">
			<h1>Suggest a Media Item</h1>

		<?php if (isset($_GET["status"]) && $_GET["status"] == "thanks") { ?>

			<p>Thanks for the email I'll check out your suggestion shortly!</p>

		<?php } else { ?>

			<p>If you think there is something I&rsquo;m missing let me know! Complete the form to send me an email.</p>
			<form action="suggest.php" method="post">
				<table>
					<tr>
						<th><label for="name">Name:</label></th>
						<td><input type="text" name="name" id="name" /></td>
					</tr>
					<tr>
						<th><label for="email">Email:</label></th>
						<td><input type="text" name="email" id="email" /></td>
					</tr>
					<tr>
						<th><label for="details">Suggest Item Details:</label></th>
						<td><textarea name="details" id="details"></textarea></td>
					</tr>
					<tr style="display:none">
						<th><label for="address">Address:</label></th>
						<td><input type="text" name="address" id="address" />
							<p>Please leave this field blank</p></td>
					</tr>
				</table>
				<input type="submit" value="Send" />
			</form>

		<?php } ?>

	</div>
</div>







<?php include 'inc/footer.php'; ?>