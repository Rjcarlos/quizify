<!--
  File Name: searchRecordCarlos.php
  Purpose: showcase staff records
  Programmer: Roan Carlos 5/6/23
-->
<link rel="stylesheet" type="text/css" href="styleCarlos.css">

<?php
 
include_once 'connect.php';

$servername = "localhost";
$username = "root";
$password = "Carlos.6693?";
$db = "quizify_db";

//include into file to connect to servers and display search form html
include ('searchRecordCarlos.html');

$StaffName = ($_POST['StaffName']);
$JobName = ($_POST['JobName']);

//prevent incorrect staff name and job name when searching
if($StaffName > 1 && $JobName > 1){
    echo "Choose only ONE way of searching for record. Please try again.";
    die;
}
else if($StaffName > 1){//when user searches via name, record with corresponding name will be showcased
    //regular expression; preg_match formatter
    $StaffNamePattern = "/^[a-zA-Z ]+$/";
    if(!(preg_match($StaffNamePattern, $StaffName))){
        echo "<p>Fill in the staff name correctly</p>";
        die;
    }
    $sql = "SELECT * FROM staffCarlos where StaffName like '%$StaffName%'";
}
else if($JobName > 1){
    $sql = "SELECT * FROM staffCarlos where JobName like '%$JobName%'";
}
else { //when all fields are blank and submit button clicked, all the records will be displayed
    $sql = "SELECT * FROM staffCarlos";
}

$result = $conn->query($sql);
$header = array("ID", "Name", "Department Code", "Job Name", "Years of Service", "Current Salary");
    echo "<table><tr>";
if ($result->num_rows <= 0) { //stops the program when the search doesnt match any records
    echo "0 results";
    die;
}

for($i = 0; $i < count($header); $i++){//displays all header titles
    echo "<th>$header[$i]</th>";
}
    echo "</tr>";

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        $records = array($row["StaffID"], $row["StaffName"], $row["DepartCode"],$row["JobName"],$row["YearsOfService"],$row["CurrentSalary"]);
        for($i = 0; $i < count($header); $i++){
            echo "<td>$records[$i]</td>";
        }
        echo "</tr>";

    }
} 

$conn->close();
?>
?>