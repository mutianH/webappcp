<?php require_once('./dao/courseDAO.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Index Courses information</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        table tr td:last-child{
            width: 120px;
        }
        img{
            height: 50%;
            width:50px;
        }   
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="course">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Courses Details</h2>
                        <a href="add.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New course</a>
                    </div>
                    <?php
                        $courseDAO = new courseDAO();
                        $courses = $courseDAO->getCourses();
                        
                        if($courses){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Level</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Startdate</th>";
                                        echo "<th>Image</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                foreach($courses as $course){
                                    echo "<tr>";
                                        echo "<td>" . $course->getId(). "</td>";
                                        echo "<td>" . $course->getName() . "</td>";
                                        echo "<td>" . $course->getLevel() . "</td>";
                                        echo "<td>" . $course->getDescription() . "</td>";
                                        echo "<td>" . $course->getStartdate() . "</td>";
                                       // echo "<td><img src='images/java.jpg' alt='".$course->getImageLocation()."'></td>";
                                        echo "<td><img src='".$course->getImageLocation() ."' alt='".$course->getImageLocation()."'></td>";
                                       
                                        echo "<td>";
                                            echo '<a href="view.php?id='. $course->getId() .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update.php?id='. $course->getId() .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id='. $course->getId() .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            //$result->free();
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                   
                    // Close connection
                    $courseDAO->getMysqli()->close();
                    include 'footer.php';
                    ?>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>