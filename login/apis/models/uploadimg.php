<?php 
//MAIN CONNECTIONS


/**
 * 
 */
class uploadimg
{

	//function for uploading images
	function uploadimage($sound_path,$imagename,$name)
	{
		$image = $_FILES[$imagename]['tmp_name'];
		if (empty($image) ) 
		{
			return "empty";
		}
		else
		{
			$sound_path=$sound_path;
			$File_Name=strtolower($_FILES[$imagename]['name']);
			//$File_Ext=substr($File_Name, strrpos($File_Name, '.')); //get file extension
			$picx=$name;//.$File_Ext;
			//if(!($File_Ext == '.jpg' || $File_Ext == '.jpeg' || $File_Ext == '.png' ))
			//{
			//	return "not supported";
			//}
			$goal_path=$sound_path.basename($picx); 

			/*
			$File_Name2=strtolower($_FILES[$imagename]['name']);
			$File_Ext2=substr($File_Name2, strrpos($File_Name2, '.')); //get file extension
			$picx2=$oldname.$File_Ext2;
			$goal_path2=$sound_path.basename($picx2);
			unlink($goal_path2);
			*/






			
			//unlink($goal_path);

			if(move_uploaded_file($_FILES[$imagename]['tmp_name'], $goal_path))  
			{  
				return "success";
			}
			else
			{
				return "failure: ";
			}
		}
	}
}

?>
