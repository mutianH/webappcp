<?php
require_once('abstractDAO.php');
require_once('./model/course.php');

class courseDAO extends abstractDAO {
        
    function __construct() {
        try{
            parent::__construct();
        } catch(mysqli_sql_exception $e){
            throw $e;
        }
    }  
    
    public function getCourse($courseId){
        $query = 'SELECT * FROM courses WHERE id = ?';
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param('i', $courseId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $temp = $result->fetch_assoc();
            $course = new course($temp['id'],$temp['name'], $temp['level'], $temp['description'],$temp['startdate'],$temp['imageLocation']);
            $result->free();
            return $course;
        }
        $result->free();
        return false;
    }


    public function getCourses(){
        //The query method returns a mysqli_result object
        $result = $this->mysqli->query('SELECT * FROM courses');
        $courses = Array();
        
        if($result->num_rows >= 1){
            while($row = $result->fetch_assoc()){
                //Create a new course object, and add it to the array.
                $course = new course($row['id'], $row['name'], $row['level'], $row['description'],$row['startdate'],$row['imageLocation']);
                $courses[] = $course;
            }
            $result->free();
            return $courses;
        }
        $result->free();
        return false;
    }   
    
    public function addCourse($course){
        
        if(!$this->mysqli->connect_errno){
			$query = 'INSERT INTO courses (name, level, description,startdate,imageLocation) VALUES (?,?,?,?,?)';
			$stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $name = $course->getName();
			        $level = $course->getLevel();
			        $description = $course->getDescription();
                    $startdate = $course->getStartdate();
                    $imageLocation=$course->getImageLocation();
                  
			        $stmt->bind_param('sisss', 
				        $name,
				        $level,
				        $description,
                        $startdate,
                        $imageLocation
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $course->getName() . ' added successfully!';
                    } 
			}else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   
    public function updateCourse($course){
        
        if(!$this->mysqli->connect_errno){
            // should check if db could find the id
            $query = "UPDATE courses SET name=?, level=?, description=?,startdate=?,imageLocation=? WHERE id=?";
            $stmt = $this->mysqli->prepare($query);
            if($stmt){
                    $id = $course->getId();
                    $name = $course->getName();
			        $level = $course->getlevel();
			        $description = $course->getdescription();
                    $startdate = $course->getStartdate();
                    $imageLocation=$course->getImageLocation();
                  
			        $stmt->bind_param('sisssi',
				        $name, 
				        $level,
				        $description,
                        $startdate,
                        $imageLocation,
                        $id
			        );    
                    //Execute the statement
                    $stmt->execute();         
                    
                    if($stmt->error){
                        return $stmt->error;
                    } else {
                        return $course->getId().$course->getName() .$course->getImageLocation(). ' updated successfully!';
                    } 
			}
             else {
                $error = $this->mysqli->errno . ' ' . $this->mysqli->error;
                echo $error; 
                return $error;
            }
       
        }else {
            return 'Could not connect to Database.';
        }
    }   

    public function deleteCourse($courseId){
        if(!$this->mysqli->connect_errno){
            $query = 'DELETE FROM courses WHERE id = ?';
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param('i', $courseId);
            $stmt->execute();
            if($stmt->error){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
?>