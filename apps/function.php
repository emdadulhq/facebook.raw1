<?php
//Function made for old data
function old($name){
	if(isset($_POST[$name])){
	echo $_POST[$name];	
	}
}


//Function made for date of birth validation

function ageValidate($dob, $min, $max){
	if($dob >= $min && $dob <= $max){
		return true;
	}else{
		return false;
	}
}

//Email validation
function mailValidate($email){
	//mail part exploding
	$mail_part = explode('@', $email);
	$mail_end = end($mail_part);
	if($mail_end == 'ctbd.com' || $mail_end == 'gmail.com' || $mail_end == 'yahoo.com'){
		return true;
	}else{
		return false;
	}
}


//Cell validation section 
function cellVal($cell){
	if(strlen($cell)==11){
		return true;
	}else{
		return false;
	}
}


//Photo uploading system
function photoUpload($photo, $location, $file_type=['jpg','png','gif','jpeg'], $file_size=1024){

    //photo information
    $photo_name = $photo['name'];
    $photo_tmp_name = $photo['tmp_name'];
    $photo_size = $photo['size']/1024;

    //file extention
    $photo_expld = explode('.',$photo_name);
    $photo_ext = strtolower (end($photo_expld));

    //unique name
    $uniqueName = md5(time(). rand()).'.'.$photo_ext;
    //$uniqueName = date('d-m-y g:i:s').'.'.$photo_ext;

    //file size fix
    if($photo_size > $file_size){
        $Photo_sz = false;
    }else{
        $Photo_sz = true;
    }


    $mess= null;
    if(in_array($photo_ext, $file_type)==false){
        $mess = "<p class=\"alert alert-warning\"> Your Photo Format is not valid!! <button class=\"close\" data-dismiss=\"alert\">&times;</button> </p>";
    }elseif( $Photo_sz == false){
        $mess = "<p class=\"alert alert-warning\"> Your Photo size is too large!! <button class=\"close\" data-dismiss=\"alert\">&times;</button> </p>";
    }else{

        //Upload image
        move_uploaded_file($photo_tmp_name,  $location . $uniqueName);

    }


    //Return value
    return[
    'file_name'     => $uniqueName,
    'mess'          => $mess,
    ];
}



function dbCheck($con, $table, $col, $data){
//Data Check in database
    $query = "SELECT $col FROM $table WHERE  $col ='$data'";

    $value= $con -> query($query);
    $num = $value -> num_rows;

    if ( $num >0 ) {
        return false;
    }else {
        return true;
    }
}

// Validation Message
function msgvld($mess, $type='danger'){
    return '<p class=\'alert alert-'.$type.'\'> '.$mess.' <button class=\'close\' data-dismiss=\'alert\'>&times;</button> </p>';
}

