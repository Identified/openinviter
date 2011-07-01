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

  if (!$email || !$password) {
    throw new Exception('Missing credentials');
  }
  $inviter = new OpenInviter();
  $inviter->getPlugins();
  $provider = $inviter->getPluginByDomain($email);
  if (!$provider) {
    throw new Exception('Invalid domain');
  }

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