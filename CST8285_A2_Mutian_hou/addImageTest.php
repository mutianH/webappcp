<?php
// Include courseDAO file
require_once('./dao/courseDAO.php');

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $image_err= "";
    $image_location= "";

    // check the image
    // only limited extension allowed

    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["image"]["name"]);
    echo $_FILES["image"]["size"];
    $extension = end($temp);     // get the extension of the file and check if it is allowed
    if ((($_FILES["image"]["type"] == "image/gif")
    || ($_FILES["image"]["type"] == "image/jpeg")
    || ($_FILES["image"]["type"] == "image/jpg")
    || ($_FILES["image"]["type"] == "image/pjpeg")
    || ($_FILES["image"]["type"] == "image/x-png")
    || ($_FILES["image"]["type"] == "image/png"))
    && ($_FILES["image"]["size"] < 204800)   // max 200K
    && in_array($extension, $allowedExts)){

        if ($_FILES["image"]["error"] > 0)
        {
            echo "error：" . $_FILES["image"]["error"] . "<br>";
        }else
        {
            // if file has no error, print the information ( test purpose)
            echo "file name: " . $_FILES["image"]["name"] . "<br>";
            echo "file type: " . $_FILES["image"]["type"] . "<br>";
            echo "file size: " . ($_FILES["image"]["size"] / 1024) . " kB<br>";
            echo "file temp location: " . $_FILES["image"]["tmp_name"];

            // check if the file name is duplicate/ the file exists in the location
            if (file_exists("images/" . $_FILES["image"]["name"]))
            {
                echo $_FILES["image"]["name"] . " file exists。 ";
            }else{
                move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $_FILES["image"]["name"]);
                echo "file save to: " . "image/" . $_FILES["image"]["name"];
            }
        }
    


    }else{
        $image_err="Please make sure the image is gif/jpeg/jpg/pjep/x-png/png, and max size is 200K.";
        echo $_FILES["image"]["size"]; 
    }










}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add course record to the database.</p>
					
					<!--the following form action, will send the submitted form data to the page itself ($_SERVER["PHP_SELF"]), instead of jumping to a different page.-->
                    <!--enctype="multipart/form-data" is to set MIME for the form, if not use enctype, the default format of MIME is application/x-www-form-urlencoded -->
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="image">Image for this course</label>
                            <input type="file" name="image" id="image"><br>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>

                </div>
            </div>        
        </div>
        <?include 'footer.php';?>
    </div>
</body>
</html>