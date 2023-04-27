<?php
	class Course{		

		private $id;
		private $name;
		private $level;
		private $description;
		private $startdate;
		private $imageLocation;
				
		function __construct($id, $name, $level, $description,$startdate,$imageLocation){
			$this->setId($id);
			$this->setName($name);
			$this->setLevel($level);
			$this->setDescription($description);
			$this->setStartdate($startdate);
			$this->setImageLocation($imageLocation);
			}		
		
		public function getName(){
			return $this->name;
		}
		
		public function setName($name){
			$this->name = $name;
		}
		public function getStartdate(){
			return $this->startdate;
		}
		
		public function setStartdate($startdate){
			$this->startdate = $startdate;
		}
		
		public function getLevel(){
			return $this->level;
		}
		
		public function setLevel($level){
			$this->level = $level;
		}

		public function getDescription(){
			return $this->description;
		}

		public function setDescription($description){
			$this->description = $description;
		}

		public function getImageLocation(){
			return $this->imageLocation;
		}

		public function setImageLocation($imageLocation){
			$this->imageLocation = $imageLocation;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getId(){
			return $this->id;
		}

	}
?>