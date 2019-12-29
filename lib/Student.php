
<?php 

$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/Database.php');

?>

<?php 
	
	class Student{

		private $db;

		public function __construct(){

			$this->db = new Database();

		}

		public function getStudents() {
			$query = "SELECT * FROM db_student";
			$result = $this->db->select($query);
			return $result;

		}

		public function insertStudent($name, $roll){

			$name = mysqli_real_escape_string($this->db->link, $name);
			$roll = mysqli_real_escape_string($this->db->link, $roll);

			if(empty($name) || empty($roll)){
				$msg = "<div class='alert alert-danger'> <strong>Error </strong> All the fields must be filled</div>";
				return $msg;
			} else {
				$stu_query = "INSERT INTO db_student(name, roll) VALUES('$name', '$roll')";
				$stu_insert = $this->db->insert($stu_query);

				$att_query = "INSERT INTO attendance(roll) VALUES('$roll')";
				$stu_insert = $this->db->insert($att_query);

				if($stu_insert){
					$msg = "<div class='alert alert-success'> <strong>Successfully </strong> inserted the student data!</div>";
					return $msg;
				} else{
					$msg = "<div class='alert alert-danger'> <strong>Error </strong>student data not inserted!</div>";
					return $msg;
				}
			}
		}

		public function insertAttendance($currentDate, $attend = array()){

			$query = "SELECT DISTINCT attend_time FROM attendance";
			$getdata = $this->db->select($query);

			while($getdata && $result = $getdata->fetch_assoc()){

				$db_date = $result['attend_time'];

				if($currentDate == $db_date){
					$msg = "<div class='alert alert-danger'> <strong>Error </strong>Attendance Already taken!</div>";
					return $msg;
				}
			}

			foreach ($attend as $atn_key => $atn_value) {
				if($atn_value == "present")
				{
					$stu_query = "INSERT INTO attendance(roll, attend, attend_time) VALUES('$atn_key', 'present', now())";
					$data_insert = $this->db->insert($stu_query);
				} 
				elseif($atn_value == "absent")
				{
					$stu_query = "INSERT INTO attendance(roll, attend, attend_time) VALUES('$atn_key', 'absent', now())";
					$data_insert = $this->db->insert($stu_query);
				}
			}
			if($data_insert){
					$msg = "<div class='alert alert-success'> <strong>Successfully </strong> inserted the student attendance!</div>";
					return $msg;
				} else{
					$msg = "<div class='alert alert-danger'> <strong>Error </strong>student attendance not inserted!</div>";
					return $msg;
				}

		}

		public function getDateList(){

			$query = "SELECT DISTINCT attend_time FROM attendance";
			$result = $this->db->select($query);
			return $result;
		}

		public function getAlldata($dt){

			$query = "SELECT db_student.name, attendance.* 
			FROM db_student INNER JOIN attendance 
			ON db_student.roll = attendance.roll
			WHERE attend_time = '$dt'";

			$result = $this->db->select($query);
			return $result;
		}

		public function updateAttendance($dt, $attend){

			foreach ($attend as $atn_key => $atn_value) {
				if($atn_value == "present")
				{
					$query = "UPDATE attendance 
					set attend= 'present' 
					WHERE roll = '".$atn_key."' AND attend_time = '".$dt."'";
					$data_update = $this->db->update($query);
				} 
				elseif($atn_value == "absent")
				{
					$query = "UPDATE attendance 
					set attend= 'absent' 
					WHERE roll = '".$atn_key."' AND attend_time = '".$dt."'";
					$data_update = $this->db->update($query);
					
				}
			}
			if($data_update){
					$msg = "<div class='alert alert-success'> <strong>Successfully </strong> updated the student attendance!</div>";
					return $msg;
				} else{
					$msg = "<div class='alert alert-danger'> <strong>Error </strong>student attendance not updated!</div>";
					return $msg;
				}
		}

	}


?>