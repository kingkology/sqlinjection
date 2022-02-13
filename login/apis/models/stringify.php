<?php 

	/**
	 * 
	 */
	class mysqli_real_escape_string 
	{
		
		function __construct($connection_string,$string_to_stringify)
		{
			if (function_exists('mb_ereg_replace'))
			{
			    function mb_escape(string $string_to_stringify)
			    {
			        return mb_ereg_replace('[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]', '\\\0', $string_to_stringify);
			    }
			} else {
			    function mb_escape(string $string_to_stringify)
			    {
			        return preg_replace('~[\x00\x0A\x0D\x1A\x22\x25\x27\x5C\x5F]~u', '\\\$0', $string_to_stringify);
			    }
			}
		}
	}


	
	


?>