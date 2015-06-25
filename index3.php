<?php
    require('vendor/autoload.php');
    // this will simply read AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY from env vars
    $s3 = Aws\S3\S3Client::factory();
    $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
    $thankyou = "";

    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);

    $conn = new mysqli($server, $username, $password, $db);

    // Check connection
    if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
    }
    // echo "Connected successfully";
    if($_POST['submit']){

        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $num_hours = $_POST['num_hours'];
        $availability = $_POST['availability'];

        $sql = "INSERT INTO signups (id, name, email, phone, num_hours,availability,CV_url,timeCreated)
        VALUES (NULL, '$name', '$email','$phone','$num_hours','$availability','$CV_url',NULL)";

        if ($conn->query($sql)) {
            // echo "New record created successfully<br>";

            // The message
            $message = "Name: '$name'"."\r\n"."Email: '$email'"."\r\n"."CV: '$CV_url'"."\r\n"."Hours: '$num_hours'"."\r\n"."availability: '$availability'"."\r\n"."Phone: '$phone'"."\r\n";

            // In case any of our lines are larger than 70 characters, we should use wordwrap()
            $message = wordwrap($message, 70, "\r\n");

            // Send
            mail('hello@yakhub.co.uk', 'My Subject', $message);
        }
        else{
        //echo "What happened";
        }
    }
    else {
        $thankyou = "";
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] && isset($_FILES['userfile']) && $_FILES['userfile']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['userfile']['tmp_name'])) {
        // FIXME: add more validation, e.g. using ext/fileinfo
        try {
            // FIXME: do not use 'name' for upload (that's the original filename from the user's computer)
            $upload = $s3->upload($bucket, $_FILES['userfile']['name'], fopen($_FILES['userfile']['tmp_name'], 'rb'), 'public-read');
            $upload_url = $upload->get('ObjectURL');
            
            $thankyou = "Yay :) ".$upload_url;

            $sql = "INSERT INTO signups (id, name, email, phone, num_hours,availability,CV_url,timeCreated)
            VALUES (NULL, '$name', '$email','$phone','$num_hours','$availability','$CV_url',NULL)";

            if ($conn->query($sql)) {
                // echo "New record created successfully<br>";

                // The message
                $message = "Name: '$name'"."\r\n"."Email: '$email'"."\r\n"."CV: '$CV_url'"."\r\n"."Hours: '$num_hours'"."\r\n"."availability: '$availability'"."\r\n"."Phone: '$phone'"."\r\n";

                // In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message = wordwrap($message, 70, "\r\n");

                // Send
                mail('hello@yakhub.co.uk', 'My Subject', $message);
            }
            else{
            echo $conn->errr;
            }

        } catch(Exception $e) { 
            $thankyou = "Error :(";
        } 
    } 

    $conn->close();
?>
<html>
    <head><meta charset="UTF-8"></head>
    <body>
        <h1>S3 upload example</h1>

        <?php echo $thankyou;?>

        <h2>Upload a file</h2>
        <form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <input name="userfile" type="file"><input type="submit" value="Upload">
        </form>
    </body>
</html>
