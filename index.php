<?php 
    include 'inc/header.php';
    include 'lib/Student.php'; 

?>

<script type="text/javascript">
    $(document).ready(function(){
        $("form").submit(function(){
            var roll = true;
            $(':radio').each(function(){
                name = $(this).attr('name');
                if(roll && !$(':radio[name="'+ name +'"]:checked').length){
                    $('.alert').hide();
                   $('#radiocheck').show();
                   roll = false; 
                }
            });
            return roll;
        });
    });
</script>

<?php
    
    $stu = new Student();
    $currentDate= date("Y-m-d");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $attend = $_POST['attend'];
        

        $insertAttend = $stu->insertAttendance($currentDate, $attend);
    }

?>

<?php 
    if(isset($insertAttend)){
        echo $insertAttend;
    }
?>

    <div class='alert alert-danger' style="display:none" id="radiocheck"> <strong>Error </strong> student roll missing! </div>

        <div class="panel panel-default">

            <div class="panel-heading">
                 <a class="btn btn-success" href="add.php">Add Student</a>
                <a class="btn btn-info pull-right" href="date_view.php"> View All</a>
                
            </div>

            <div class="panel-body">

                <div class="well text-center">
                    <strong>Date: <?php echo $currentDate; ?>
                </div>

                <form action="" method="Post">
                    <table class="table table-striped">
                        <tr>
                            <th>Serial</th>
                            <th>Student</th>
                            <th>Student Roll</th>
                            <th>Attendance</th>
                        </tr>
<?php 
        $get_student = $stu->getStudents();
        if($get_student){
            $i = 0;
            while($value = $get_student->fetch_assoc()){
                $i++
        
?>
                        <tr>
                            <td> <?php echo $i ?> </td>
                            <td> <?php echo $value['name']; ?></td>
                            <td> <?php echo $value['roll']; ?></td>
                            <td> <input type="radio" name="attend[<?php echo $value['roll']; ?>]" value="present">P
                                 <input type="radio" name="attend[<?php echo $value['roll']; ?>]" value="absent">A
                            </td>
                        </tr>
<?php 
    }
}
?>
                    </table>

                    <input type="submit" name="submit" class="btn btn-primary" value="Submit">

                </form>
            </div>

        </div>

<?php include 'inc/footer.php' ?>