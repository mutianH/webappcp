<?php
// Include courseDAO file
require_once('./dao/courseDAO.php');
$courseDAO = new courseDAO(); 

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Get URL parameter
    $id =  trim($_GET["id"]);
    $course = $courseDAO->getCourse($id);
            
    if($course){
        // Retrieve individual field value
        $name = $course->getName();
        $level = $course->getLevel();
        $description = $course->getDescription();
        $startdate = $course->getStartdate();
        $image_location = $course->getImageLocation();
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
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
                    <h1 class="mt-5 mb-3">View Course</h1>
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $name; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <p><b><?php echo $level; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p><b><?php echo $description; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Startdate</label>
                        <p><b><?php echo $startdate; ?></b></p>
                    </div>
                    <div class="form-group">
                            <label>Image</label>
                            <img src='<?php echo $image_location;?>' alt='<?php echo $image_location;?>'>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
        <?php include 'footer.php';?>
    </div>
</body>
</html>