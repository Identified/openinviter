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
    throw new Exception('Missing credentials or provider');
  }
  $inviter = new OpenInviter();
  $inviter->startPlugin($provider);
  errorcheck($inviter, 'Error initializing plugin');
  $inviter->login($email, $password);
  errorcheck($inviter, 'Login failed');
  $contacts = $inviter->getMyContacts();
  errorcheck($inviter, 'Contacts could not be retrieved');
  if ($contacts === false) {
    throw new Exception('No contacts found');
  }
  echo json_encode(array($contacts, null));
} 
catch (Exception $e) {
  echo json_encode(array(null, $e->getMessage()));
}
?>
