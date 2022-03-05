<?php

$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);	    

if(empty($POST['stripeToken'])){
 header('Location: /');   
    die();
}


require_once($_SERVER['DOCUMENT_ROOT'].'/stripe/stripe-php-7.77.0/init.php');
/*
echo'<pre>';
var_dump($_POST);
echo'</pre>';
die();
*/


\Stripe\Stripe::setApiKey('sk_test_...');


$token = $POST['stripeToken'];
$email = "example@example.com";
 $amount = "2000";
$description = "something descriptive";


try {
    
  
$customer = \Stripe\Customer::create(array(
    "email"=>$email,
    "source"=>$token
    ));



} catch(\Stripe\Exception\CardException $e) {
  // Since it's a decline, \Stripe\Exception\CardException will be caught
 // echo 'Status is:' . $e->getHttpStatus() . '\n';
 // echo 'Type is:' . $e->getError()->type . '\n';
 // echo 'Code is:' . $e->getError()->code . '\n';
  // param is '' in this case
 // echo 'Param is:' . $e->getError()->param . '\n';
 $error= 'Message is:' . $e->getError()->message . '\n';
} catch (\Stripe\Exception\RateLimitException $e) {
   $error= 'Too many requests made to the API too quickly';
} catch (\Stripe\Exception\InvalidRequestException $e) {
   $error= 'Invalid parameters were supplied to Stripe';//'s api
} catch (\Stripe\Exception\AuthenticationException $e) {
  $error= 'Authentication Error';// with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Exception\ApiConnectionException $e) {
  $error= 'Network communication with Stripe failed';
} catch (\Stripe\Exception\ApiErrorException $e) {
$error='A Stripe error occurred';  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
 $error='An error occurred'; // Something else happened, completely unrelated to Stripe
}

if($error==''){
try {

//add 2 zeros...
$charge = $customer = \Stripe\Charge::create(array(
    "amount"=>str_replace(['.',','], '', $amount),// . '00',
    "currency"=>'usd',
    "description"=>html_entity_decode($description),
    "customer"=>$customer->id
    ));
} catch(\Stripe\Exception\CardException $e) {
  // Since it's a decline, \Stripe\Exception\CardException will be caught
 // echo 'Status is:' . $e->getHttpStatus() . '\n';
 // echo 'Type is:' . $e->getError()->type . '\n';
 // echo 'Code is:' . $e->getError()->code . '\n';
  // param is '' in this case
 // echo 'Param is:' . $e->getError()->param . '\n';
 $error= 'Message is:' . $e->getError()->message . '\n';
} catch (\Stripe\Exception\RateLimitException $e) {
   $error= 'Too many requests made to the API too quickly';
} catch (\Stripe\Exception\InvalidRequestException $e) {
   $error= 'Invalid parameters were supplied to Stripe';//'s api
} catch (\Stripe\Exception\AuthenticationException $e) {
  $error= 'Authentication Error';// with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Exception\ApiConnectionException $e) {
  $error= 'Network communication with Stripe failed';
} catch (\Stripe\Exception\ApiErrorException $e) {
$error='A Stripe error occurred';  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
 $error='An error occurred'; // Something else happened, completely unrelated to Stripe
}
}
echo ($error!=''?$error:'');