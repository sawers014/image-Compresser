<?php  
$name = $_POST['myfile'];


echo $name;

if (isset($_FILES['image']['name']))
{
    $saveto = "$name.$ext";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = TRUE;
    switch($_FILES['image']['type'])
    {
        case "images/gif": $src = imagecreatefromgif($saveto); break;
        case "images/jpeg": // Both regular and progressive jpegs
        case "images/pjpeg": $src = imagecreatefromjpeg($saveto); break;
        case "images/png": $src = imagecreatefrompng($saveto); break;
        default: $typeok = FALSE; break;
    }
    if ($typeok)
    {
        list($w, $h) = getimagesize($saveto);
        $max = 100;
        $tw = $w;
        $th = $h;
        if ($w > $h && $max < $w)
        {
            $th = $max / $w * $h;
            $tw = $max;
        }
        elseif ($h > $w && $max < $h)
        {
            $tw = $max / $h * $w;
            $th = $max;
        }
        elseif ($max < $w)
        {
            $tw = $th = $max;
        }

        $tmp = imagecreatetruecolor($tw, $th);      
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imageconvolution($tmp, array( // Sharpen image
            array(−1, −1, −1),
            array(−1, 16, −1),
            array(−1, −1, −1)      
        ), 8, 0);
        imagejpeg($tmp, $saveto);
        imagedestroy($tmp);
        imagedestroy($src);
    }
}
?>
