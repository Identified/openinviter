<?php
//header('Content-type: application/json');
include('openinviter.php');

function errorcheck($invite, $msg)
{
  if ($invite->getInternalError()) {
    throw new Exception($msg);
  }
}

function get_contacts($u, $p)
{
  $inviter = new OpenInviter();
  $inviter->getPlugins();
  $provider = $inviter->getPluginByDomain($u);
  if (!$provider) {
    throw new Exception('Invalid domain');
  }
  
  $inviter->startPlugin($provider);
  errorcheck($inviter, 'Error initializing plugin');
  $login_succeeded = $inviter->login($u, $p);
  errorcheck($inviter, 'Login failed');
  if (!$login_succeeded) {
    throw new Exception('Login failed!');
  }
  $contacts = $inviter->getMyContacts();
  errorcheck($inviter, 'Contacts could not be retrieved');
  if ($contacts === false) {
    throw new Exception('No contacts found');
  }
  return $contacts;
}

try {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (!$email || !$password) {
    throw new Exception('Missing credentials');
  }

  $contacts = get_contacts($email, $password);
  echo json_encode(array($contacts, null));
} 
catch (Exception $e) {
  echo json_encode(array(null, $e->getMessage()));
}
?>