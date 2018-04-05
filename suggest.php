<?php 

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/Exception.php';

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
        // $mail->isSMTP();
        // $mail->Host = 'localhost';
        // $mail->Port = 80;
        // $mail->CharSet = 'utf-8';
        //It's important not to use the submitter's address as the from address as it's forgery,
        //which will cause your messages to fail SPF checks.
        //Use an address in your own domain as the from address, put the submitter's address in a reply-to
        $mail->setFrom('trent@plussumservices.com', $name);
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