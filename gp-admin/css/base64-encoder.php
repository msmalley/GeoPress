<?php
 
if ($_FILES && $_FILES['uploadfile']) {
    header( "Content-type: text/plain; charset=UTF-8" );
    $fp = fopen( $_FILES['uploadfile']['tmp_name'], 'rb' );
    if ($_POST['base64']) {
        while (!feof($fp)) echo(base64_encode(fread($fp, 45))."\n");
    } else {
        $buf = '';
        while (!feof($fp)) {
            $buf .= rawurlencode(fread($fp, 120));
            while (strlen($buf)>=60) {
                echo(substr($buf,0,60)."\n");
                $buf = substr($buf,60);
            }
        }
        if ($buf) echo($buf);
    }
    fclose($fp);
    die();
}
 
?>
<html>
<head>
<title>Base64 File Encoder</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
<p><label>File to convert: <input type="file" name="uploadfile" /></label>
<p><label>Check here to use base64 encoding: <input type="checkbox" name="base64" checked="checked" /></p>
<p><input type="submit" name="submit" value="Submit" /></p>
</form>
</body>
</html>