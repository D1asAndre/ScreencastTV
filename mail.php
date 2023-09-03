<?php
//get data from read
$name = $_POST['name'];
$email= $_POST['email'];
$message= $_POST['message'];
$to = "stvpowered@gmail.com";
$subject = $_POST['subject'];
$txt ="Nome: ". $name . "\r\n  Email: " . $email . "\r\n Mensagem: \r\n" . $message;
$headers = 'From: stvpowered@gmail.com' . "\r\n" .
    'Reply-To: ' . $email . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
if($email!=NULL){
    mail($to,$subject,$txt,$headers);
}

?>    