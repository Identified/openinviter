<?php
include('openinviter.php');

$email = $_POST['email'];
$password = $_POST['password'];
$provider = $_POST['provider'];


function errorcheck($invite)
{
  if ($inviter->getInternalError()) {
    throw new Exception('Death!');
  }
}

try {
  if (!$email || !$password || !$provider) throw new Exception('Destruction!');
  $inviter = new OpenInviter();
  $inviter->startPlugin($provider);
  errorcheck($inviter);
  $inviter->login($email, $password);
  errorcheck($inviter);
  $contacts = $inviter->getMyContacts();
  errorcheck($inviter);
  if ($contacts === false) {
    throw new Exception('Assmeat!');
  }
  echo $contacts;
} 
catch (Exception $e) {
  echo "null";
}
?>
