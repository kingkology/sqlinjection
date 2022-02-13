<?php 
	
	function password_strength($password)
	{
	  $strength = 'none';

	  $uppercase = preg_match('@[A-Z]@', $password);
	  $lowercase = preg_match('@[a-z]@', $password);
	  $number    = preg_match('@[0-9]@', $password);
	  $special_character = preg_match('@[^\w]@', $password);


	  if( $uppercase && $lowercase && $number && $special_character && strlen($password) >= 9) {
	    $strength = 'extra';
	  }
	  else if ( ($uppercase && $lowercase && $number && strlen($password) >= 9) ) {
	    $strength = 'strong';
	  }
	  else if ( ($lowercase && $number && strlen($password) >= 9) || ($uppercase && $number && strlen($password) >= 9) ) {
	    $strength = 'moderate';
	  }
	  else
	  {
	    $strength = 'weak';
	  }

	  
	  return $strength;   
	}


?>