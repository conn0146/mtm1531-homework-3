<?php

error_reporting(-1);
ini_set('display_errors', 'on');

include 'includes/filter-wrapper.php';

$possible_languages = array(
	'English'
	,'French'
	,'Spanish'
);

$errors= array();
$display_thanks = false;

$name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING); 
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$preferredlang = filter_input(INPUT_POST, 'preferredlang', FILTER_SANITIZE_STRING);
$notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
$acceptterms = filter_input(INPUT_POST, 'acceptterms', FILTER_DEFAULT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (empty($name)) {
		$errors['name']=true;
	}
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors['email']=true;
	}
	if(mb_strlen($username) > 25) {
		$errors['username']=true;
	}
	if(empty($password)) {
		$errors['password']=true;
	}
	if (!array_key_exists($preferredlang, $possible_languages)) {
		$errors['preferredlang']=true;
  }
  
  if(empty($acceptterms)) {
	  $errors['acceptterms']=true;
  }
  
  if(empty($errors)) {
	$display_thanks = true;
	
	$email_message = 'Name: ' . $name . "\r\n";
	$email_message .= 'Email: ' . $email . "\r\n";
	$email_message .= 'Preferred Language: ' . $possible_languages[$preferredlang] . "\r\n";
	$email_message .= 'Notes: ' . $notes . "\r\n";
	
	$headers = 'From: Jason <conn0146@algonquinlive.com>' . "\r\n";
	
	mail($email, 'Thanks for registering', $email_message, $headers);
  }
}

?><!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>Registration Form</title>
	<link href="css/general.css" rel="stylesheet">
</head>
<body>
	<?php if ($display_thanks) : ?>
    	<strong>Thank you for registering!</strong>
    <?php else : ?>
    <form method="post" action="index.php">
		<div>
			<label for="name">Name <?php if (isset($errors['name'])) : ?> <strong>is required</strong><?php endif; ?></label>
			<input type ="text" id="name" name="name" value="<?php echo $name; ?>">
		</div>
		<div>
    	<label for="email">E-mail Address <?php if (isset($errors['email'])) : ?> <strong>is required</strong><?php endif; ?></label>
      <input type ="email" id="email" name="email" value="<?php echo $email; ?>">
    </div>
		<div>
    	<label for="username">Username<?php if (isset($errors['username'])) : ?> <strong>maximum length is 25 characters</strong><?php endif; ?></label>
      <input type ="text" id="username" name="username" value="<?php echo $username; ?>">
    </div>
		<div>
			<label for="password">Password<?php if (isset($errors['password'])) : ?> <strong>is required</strong><?php endif; ?></label>
			<input type="password" input id="password" name="password" value="<?php echo $password; ?>">
		</div>
		<fieldset>
     	<legend>Preferred Language</legend>
      <?php if (isset($errors['preferredlang'])) : ?><strong>Select a language</strong><?php endif; ?>
			<?php foreach ($possible_languages as $key => $value) : ?> 
      	<input type="radio" id="<?php echo $key; ?>" name="preferredlang" value="<?php echo $key; ?>" <?php if ($key == $preferredlang) {echo 'checked';} ?>>
        <label for="<?php echo $key; ?>"><?php echo $value; ?></label>
    	<?php endforeach; ?>    
    </fieldset>
		<div>
    	<label for="notes">Notes</label>
      <input type="textarea" id="notes" name="notes" value="<?php echo $notes; ?>">
    </div>
    <div>
    	<input type="checkbox" id="acceptterms" name="acceptterms" <?php if (!empty($acceptterms)) { echo 'checked'; } ?>
        <label for="acceptterms">Accept Terms?<?php if (isset($errors['acceptterms'])) : ?><strong>you haven't accepted the terms</strong><?php endif; ?></label>
    </div>
		
        
    <div>
    	<button type="submit">Submit</button>
    </div>
	</form>
    <?php endif; ?>
</body>
</html>