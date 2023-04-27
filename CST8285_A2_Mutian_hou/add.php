<?php
// Include courseDAO file
require_once('./dao/courseDAO.php');

 
// Define variables and initialize with empty values
$name = $level = $description =$image_location="";
$name_err = $level_err = $description_err =$image_err= $date_err="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate level: not empty, 1 number only
    $input_level = trim($_POST["level"]);
    if(empty($input_level)){
        $level_err = "Please enter level.";     
    } elseif(strlen($input_level)!=1){
        $level_err = "Please enter number between 1 ~ 4."; 
    }elseif(!is_numeric($input_level)){
        $level_err = "Please enter a number for level."; 
    }else{
        $level = $input_level;
    }

    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter an description.";     
    } else{
        $description = $input_description;
    }
    
    //$input_startdate = trim($_POST["date"]);
    $input_startdate = date('Y-m-d',strtotime($_POST["date"]));
    //echo $input_startdate;
    if(empty($input_startdate)){
        $date_err = "Please enter a date.";     
    } elseif(strtotime($input_startdate) > strtotime('today')){
        $date_err = "Please enter a day before today."; 
    }else{
        $startdate = $input_startdate;
        
       // echo "type of the startdate:".gettype($startdate);
    }

    // check the image
    // only limited extension allowed

    $allowedExts = array("gif", "jpeg", "jpg", "png");
    //spilt string
    $temp = explode(".", $_FILES["image"]["name"]);
    //echo $_FILES["image"]["size"];    
    $extension = end($temp);     // get the extension of the file and check if it is allowed
    if ($_FILES["image"]["size"]>0){
        if ((($_FILES["image"]["type"] == "image/gif")
            || ($_FILES["image"]["type"] == "image/jpeg")
            || ($_FILES["image"]["type"] == "image/jpg")
            || ($_FILES["image"]["type"] == "image/pjpeg")
            || ($_FILES["image"]["type"] == "image/x-png")
            || ($_FILES["image"]["type"] == "image/png"))
            && ($_FILES["image"]["size"] < 204800)   // max 
            && in_array($extension, $allowedExts)){

                if ($_FILES["image"]["error"] > 0){
                    //echo "error：" . $_FILES["image"]["error"] . "<br>";
                    $image_err="Please make sure the image is gif/jpeg/jpg/pjep/x-png/png, and max size is 200K.";
                }else{
                    // if file has no error, print the information ( test purpose)

                    // echo "file name: " . $_FILES["image"]["name"] . "<br>";
                    // echo "file type: " . $_FILES["image"]["type"] . "<br>";
                    // echo "file size: " . ($_FILES["image"]["size"] / 1024) . " kB<br>";
                    // echo "file temp location: " . $_FILES["image"]["tmp_name"];

                    // check if the file name is duplicate/ the file exists in the location
                    if (file_exists("images/" . $_FILES["image"]["name"])){
                        //echo $_FILES["image"]["name"] . " file exists.  ";
                        //$image_location="images/" . $_FILES["image"]["name"];
                        $image_err=$_FILES["image"]["name"] . " file exists. Please upload another image for the course.";
                    }
                }
        }else{
            $image_err="Please make sure the image is gif/jpeg/jpg/pjep/x-png/png, and max size is 200K.";
        }
    }else{
        $image_err="Please upload an image.";
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($level_err) && empty($description_err)&& empty($date_err)&& empty($image_err)){
        //move_uploaded_file(string $from, string $to): bool
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $_FILES["image"]["name"]);
        //echo "file save to: " . "images/" . $_FILES["image"]["name"];
        //echo "before:".$image_location;
        $image_location="images/" . $_FILES["image"]["name"];
        //echo "after:".$image_location;
        $courseDAO = new courseDAO();    
        $course = new course(0, $name, $level, $description,$startdate,$image_location);
        $addResult = $courseDAO->addCourse($course);        
        header( "refresh:2; url=index.php" ); 
		echo '<br><h6 style="text-align:center">' . $addResult . '</h6>';   
        // Close connection
        $courseDAO->getMysqli()->close();
        }

}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        table tr td:last-child{
            width: 120px;
        }
        img{
            height: 50%;
            width:50px;
        }   
    </style>
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
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>level</label>
                            <input type="text" name="level" class="form-control <?php echo (!empty($level_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $level; ?>">
                            <span class="invalid-feedback"><?php echo $level_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>description</label>
                            <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                            <span class="invalid-feedback"><?php echo $description_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Startdate</label>
                            <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startdate; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>

                        <div class="form-group">
                            <label for="image">Image for this course</label>
                            <input type="file" name="image" id="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
                            <span class="invalid-feedback"><?php echo $image_err;?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>

                </div>
            </div>        
        </div>
        <?php include 'footer.php';?>
    </div>
</body>
</html>