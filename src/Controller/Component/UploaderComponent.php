<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class UploaderComponent extends Component {    
    	
  function generateUniqueFilename($fileName, $path='') {
    $path = empty($path) ? WWW_ROOT.'/archivos' : WWW_ROOT.$path;
    $no = floor((time()-1284342319)/30);
	
	$fileNametemp = substr(trim( preg_replace('/[^0-9a-zA-Z\.\_\-]/i','',strtolower($fileName)) ),0); // 2011-05-27
	$newFileName = substr_replace($fileNametemp,"_$no",strrpos($fileNametemp,"."),0);
    //$newFileName = substr_replace($fileName,"_$no",strrpos($fileName,"."),0); // 2011-05-27
    
	//while (file_exists($path."/".$newFileName)) {
    //  $no++;
    //  $newFileName = substr_replace($fileName, "_$no", strrpos($fileName, "."),0);
    //}
    return $newFileName;
  }

  function handleFileUpload($fileData, $fileName, $validateFile, $path='') {

    $path = empty($path) ? WWW_ROOT.'/archivos' : WWW_ROOT.$path;
    $error = false;
    //Get file type
    $typeArr = explode('/', $fileData['type']);
 
    //If size is provided for validation check with that size. Else compare the size with INI file
    if (($validateFile['size'] && $fileData['size'] > $validateFile['size']) || $fileData['error'] == UPLOAD_ERR_INI_SIZE) {
        $error = 'el tamaño del archivo es demasiado grande (' . $fileData['size'] . " bytes). El máximo establecido es de " . $validateFile['size'] . " bytes";
    } 
    /*
    // 15/05/09 - comentado por Dante caceres para que permita copiar archivos de FTP hacia webroot.
    elseif ($validateFile['type'] && (strpos($validateFile['type'], strtolower($typeArr[1])) === false)) {
        //File type is not the one we are going to accept. Error!!
        $error = 'el tipo de archivo '.$typeArr['1'].' no está soportado.';
    } */
    else {
        //Data looks OK at this stage. Let's proceed.
        if ($fileData['error'] == UPLOAD_ERR_OK) {
            //Oops!! File size is zero. Error!
            if ($fileData['size'] == 0) {
                $error = 'el tamaño del archivo es cero.';
            } else {
                if (is_uploaded_file($fileData['tmp_name'])) {
                    //Finally we can upload file now. Let's do it and return without errors if success in moving.
                    if (!move_uploaded_file($fileData['tmp_name'], $path."/".$fileName)) {
                        $error = true;
                    }
                } else {
                    if (!copy($fileData['tmp_name'], $path."/".$fileName)) {
                        $error = true;
                    }
                    else {
                    	@unlink($fileData['tmp_name']);
                    }
                }
            }
        }
    }
    return $error;
  }
  
	function handleFileUploadv2($fileData, $fileName, $validateFile, $path='') {

	    $path = empty($path) ? WWW_ROOT.'/archivos' : WWW_ROOT.$path;
	    $error = false;
	     
	    $typeArr = explode('/', $fileData['type']);
	
	    //If size is provided for validation check with that size. Else compare the size with INI file
	    if (($validateFile['size'] && $fileData['size'] > $validateFile['size']) || $fileData['error'] == UPLOAD_ERR_INI_SIZE) {
	        $error = 'el tamaño del archivo es demasiado grande (' . $fileData['size'] . " bytes). El máximo establecido es de " . $validateFile['size'] . " bytes";
	    } 
	 
	    // 15/05/09 - comentado por Dante caceres para que permita copiar archivos de FTP hacia webroot.
	    elseif ( $typeArr[1] != 'jpeg') {
	        //File type is not the one we are going to accept. Error!!
	        $error = 'el tipo de archivo '.$typeArr['1'].' no está soportado.';
	    }
	    else {
	        //Data looks OK at this stage. Let's proceed.
	        if ($fileData['error'] == UPLOAD_ERR_OK) {
	            //Oops!! File size is zero. Error!
	            if ($fileData['size'] == 0) {
	                $error = 'el tamaño del archivo es cero.';
	            } else {
	                if (is_uploaded_file($fileData['tmp_name'])) {
	                    //Finally we can upload file now. Let's do it and return without errors if success in moving.
	                    if (!move_uploaded_file($fileData['tmp_name'], $path."/".$fileName)) {
	                        $error = true;
	                    }
	                } else {
	                    if (!copy($fileData['tmp_name'], $path."/".$fileName)) {
	                        $error = true;
	                    }
	                    else {
	                    	@unlink($fileData['tmp_name']);
	                    }
	                }
	            }
	        }
	    }
	    return $error;
  }
  
  function handleFileUploadMultiple($fileData, $fileName, $validateFile, $path='') {

    $path = empty($path) ? WWW_ROOT.'/archivos' : WWW_ROOT.$path;
    $error = false;
    //Get file type
    $typeArr = explode('/', $fileData['type']);
 
    //If size is provided for validation check with that size. Else compare the size with INI file
    if (($validateFile['size'] && $fileData['size'] > $validateFile['size']) || $fileData['error'] == UPLOAD_ERR_INI_SIZE) {
        $error = 'el tamaño del archivo es demasiado grande (' . $fileData['size'] . " bytes). El máximo establecido es de " . $validateFile['size'] . " bytes";
    }     
    elseif ($validateFile['type'] && (strpos($validateFile['type'], strtolower($typeArr[1])) === false)) {
        //File type is not the one we are going to accept. Error!!
        $error = 'el tipo de archivo '.$typeArr['1'].' no está soportado.';
    }
    else {
        //Data looks OK at this stage. Let's proceed.
        if ($fileData['error'] == UPLOAD_ERR_OK) {
            //Oops!! File size is zero. Error!
            if ($fileData['size'] == 0) {
                $error = 'el tamaño del archivo es cero.';
            } else {
                if (is_uploaded_file($fileData['tmp_name'])) {
                    //Finally we can upload file now. Let's do it and return without errors if success in moving.
                    if (!move_uploaded_file($fileData['tmp_name'], $path."/".$fileName)) {
                        $error = true;
                    }
                } else {
                    if (!copy($fileData['tmp_name'], $path."/".$fileName)) {
                        $error = true;
                    }
                    else {
                    	@unlink($fileData['tmp_name']);
                    }
                }
            }
        }
    }
    return $error;
  }


  function deleteMovedFile($fileName) {
    if (!$fileName || !is_file($fileName)) {
      return true;
    }
    if(unlink($fileName)) {
      return true;
    }

    return false;
  }

}
?>
