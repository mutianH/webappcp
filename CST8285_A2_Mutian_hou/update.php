<?php
// Include employeeDAO file
require_once('./dao/courseDAO.php');
 
// Define variables and initialize with empty values
$name = $level = $description =$image_location=$startdate="";
$name_err = $level_err = $description_err =$image_err = $date_err="";
$courseDAO = new courseDAO(); 

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate level
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
    
    // description 
    $input_description = trim($_POST["description"]);
    if(empty($input_description)){
        $description_err = "Please enter an description.";     
    } else{
        $description = $input_description;
    }


    $input_startdate = trim($_POST["date"]);
    if(empty($input_startdate)){
        $date_err = "Please enter a date.";     
    } elseif(strtotime($input_startdate) > strtotime('today')){
        $date_err = "Please enter a day before today."; 
    }else{
        $startdate = $input_startdate;
    }

    // echo $_FILES["image"]["size"];
    // echo $image_location;


    //$image_location="testpurpose";

    // image, if no new file upload, $_FILES["image"]["size"] will be zero, and the $image_location will use the old one 
    // if there is new file, the new file information will replace the old information
    
    if($_FILES["image"]["size"]==0){
        $image_location=trim($_POST["oldImage"]);
        //echo "old file location".$image_location;
        //echo "no new file upload.";

    }else{
        $allowedExts = array("gif", "jpeg", "jpg", "png");
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
                && ($_FILES["image"]["size"] < 204800)   // max 200K
                && in_array($extension, $allowedExts)){

                    if ($_FILES["image"]["error"] > 0){
                        $image_err="Please make sure the image is gif/jpeg/jpg/pjep/x-png/png, and max size is 200K.";
                    }
                    if (file_exists("image/" . $_FILES["image"]["name"])){
                            $image_err=$_FILES["image"]["name"] . " file exists. Please upload another image for the course.";
                    }
                $image_location="images/" . $_FILES["image"]["name"];   

                    
        }
    }
}
    
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($level_err) && empty($description_err) && empty($date_err)&& empty($image_err)){
        move_uploaded_file($_FILES["image"]["tmp_name"], "images/" . $_FILES["image"]["name"]);
        //$image_location="images/" . $_FILES["image"]["name"];
        $course = new course($id, $name, $level, $description,$startdate,$image_location);
        $result = $courseDAO->updateCourse($course);        
		header("refresh:2; url=index.php");
		echo '<br><h6 style="text-align:center">' . $result . '</h6>';
        // Close connection
        $courseDAO->getMysqli()->close();
    }

} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        $course = $courseDAO->getcourse($id);
                
        if($course){
            // Retrieve individual field value
            $name = $course->getName();
            $level = $course->getLevel();
            $description = $course->getDescription();
            $startdate = $course->getStartdate();
            $image_location=$course->getImageLocation();
        } else{
            // URL doesn't contain valid id. Redirect to error page
            header("location: error.php");
            exit();
        }
    } else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
    // Close connection
    $courseDAO->getMysqli()->close();
}
?>
 
 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the course record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
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
                            <label>level</label>
                            <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $startdate; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Current Image</label>
                            <img src='<?php echo $image_location;?>' alt='<?php echo $image_location;?>'>
                        </div>

                        <div class="form-group">
                            <label for="image">Image for this course: </label>
                            <input type="file" name="image" id="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image_location; ?>"><br>
                            <span class="invalid-feedback"><?php echo $image_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="hidden" name="oldImage" value="<?php echo $image_location; ?>"/>

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