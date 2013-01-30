<?php

$dir = 'uploads';

if ($_POST) {
    
    $img = $_POST['image'];
    if($img) {
        
        $type = '.jpg';
        if(strpos($img, 'jpeg;base64') || strpos($img, 'jpg;base64')) {
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace('data:image/jpg;base64,', '', $img);
        }
        else if(strpos($img, 'png;base64')) {
            $img = str_replace('data:image/png;base64,', '', $img);
            $type = '.png';
        }
        else if(strpos($img, 'gif;base64')) {
            $img = str_replace('data:image/gif;base64,', '', $img);
            $type = '.gif';
        }
        else {
            echo "false";
            return;
        }
        $img = str_replace(' ', '+', $img);
        
        $data = base64_decode($img);
        $file = $dir . '/' . uniqid() . $type;
        $success = file_put_contents($file, $data);
        echo $success ?  dirname($_SERVER['PHP_SELF']) . "/$file" : "false";
    }
}

?>