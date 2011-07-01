<?php
//header('Content-type: application/json');
include('openinviter.php');

function errorcheck($invite, $msg)
{
  if ($invite->getInternalError()) {
    throw new Exception($msg);
  }
}

try {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $provider = $_POST['provider'];

  if (!$email || !$password || !$provider) {
    throw new Exception('Destruction!');
  }
  $inviter = new OpenInviter();
  $inviter->startPlugin($provider);
  errorcheck($inviter, 'a');
  $inviter->login($email, $password);
  errorcheck($inviter, 'b');
  $contacts = $inviter->getMyContacts();
  errorcheck($inviter, 'c');
  if ($contacts === false) {
    throw new Exception('Assmeat!');
  }
  echo json_encode($contacts);
} 
catch (Exception $e) {
  echo "null";
}
?>
