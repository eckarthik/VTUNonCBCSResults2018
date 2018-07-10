<html>
<head><title>result stats</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>
<?php
/**
 * @author Karthik E C
 */
error_reporting(0);
$host = "localhost";
$username = "root";
$password = ""; 
mysql_connect($host,$username,$password) or die("Cannot connect to MySQL: ".mysql_error());
mysql_select_db("vtures");
function getSubjectHighestStudents($subject)
{
    $query = mysql_query("SELECT * FROM vtu2018");
    $temp = 0;
    $arrayStudents = array();
    $counter=0;
    for($i=0;$i<mysql_num_rows($query);$i++)
    {
        $resultData = mysql_result($query,$i,'result');
        $name = mysql_result($query,$i,'name');
        $usn = mysql_result($query,$i,'usn');
        $regex = "<tr><td><div class=\"divTableCell\">$subject<\/div><\/td><td><div class=\"divTableCell\" style=\"text-align: left;width: 400px;\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><\/tr>";
        preg_match("/$regex/i",$resultData,$matches);
        $totalMarks = $matches[4];
        $result = $matches[5];
        if($totalMarks>=$temp)
        {
            $temp = $totalMarks;
            $pos = $i;
            $topStudentResult = $result;
            //echo "Test.$i ".$temp."<br/><br/>";
            $topStudentName = mysql_result($query,$pos,'name');
            $topStudentUSN = mysql_result($query,$pos,'usn');
            $topStudentMarks = $temp;
            $arrayStudents[$counter] = array("name"=>$topStudentName,"usn"=>$topStudentUSN,"totalmarks"=>$topStudentMarks,"result"=>$topStudentResult);
            //echo "Name: ".$topStudentName." USN: ".$topStudentUSN. " TotalMarks: ". $topStudentMarks. " Result: ". $topStudentResult. "<br/>";
            $counter++;
        }                                                                          
    }
    echo "<table class='table table-striped'><thead class='thead-dark'><tr><th scope=\"col\">Name</th><th scope=\"col\">USN</th><th scope=\"col\">TotalMarks(External+Internal)</th><th scope=\"col\">Result</th></tr></thead><tbody>";
    if($subject=="10CS85" || $subject=="10CS86") //Shows more results for 10CS85 and 10CS86 subjects
    {
        for($i=count($arrayStudents)-1;$i>=0;$i--)
        {
            echo "<tr><td>".$arrayStudents[$i]['name']."</td><td>".$arrayStudents[$i]['usn']."</td><td>".$arrayStudents[$i]['totalmarks']."</td><td>".$arrayStudents[$i]['result']."</td></tr>";
        }
    }
    else
    {
        echo "<tr><td>".$arrayStudents[$counter-1]['name']."</td><td>".$arrayStudents[$counter-1]['usn']."</td><td>".$arrayStudents[$counter-1]['totalmarks']."</td><td>".$arrayStudents[$counter-1]['result']."</td></tr>";
        //echo "<tr><td>".$arrayStudents[$counter-2]['name']."</td><td>".$arrayStudents[$counter-2]['usn']."</td><td>".$arrayStudents[$counter-2]['totalmarks']."</td><td>".$arrayStudents[$counter-2]['result']."</td></tr>";
        //echo "Name: ".$arrayStudents[$i]['name']. " USN: ". $arrayStudents[$i]['usn'] . " TotalMarks(External+Internal): ". $arrayStudents[$i]['totalmarks']. " Result: ". $arrayStudents[$i]['result']. "<br/>"; 
    }
    echo "</tbody></table>";
    //echo " <br/> Counter: $counter";
             
}
function getSubjectStats($subject) //Gives stats for individual subjects
{
    $query = mysql_query("SELECT * FROM vtu2018");
    $temp = 0;
    $arrayStudents = array();
    $counter=0;
    for($i=0;$i<mysql_num_rows($query);$i++)
    {
        $resultData = mysql_result($query,$i,'result');
        $name = mysql_result($query,$i,'name');
        $usn = mysql_result($query,$i,'usn');
        $regex = "<tr><td><div class=\"divTableCell\">$subject<\/div><\/td><td><div class=\"divTableCell\" style=\"text-align: left;width: 400px;\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><td><div class=\"divTableCell\">(.*?)<\/div><\/td><\/tr>";
        preg_match("/$regex/i",$resultData,$matches);
        $totalMarks = $matches[4];
        $result = $matches[5];
        if($result=='P')
        {
            $counter++;
        }                                                                          
    }
    $per = sprintf("%0.2f",($counter/81)*100);
    echo $per."%";
}
function getTop81Students() //Displays results of students sorted on the basis of total marks
{
    $query = mysql_query("SELECT * FROM vtu2018 ORDER BY totalmarks DESC");
    echo "<table class='table table-striped'><thead class='thead-dark'><tr><th>Position</th><th>Name</th><th>Total Marks</th><th>Percentage</th></tr></thead><tbody>";
    $tempTotalMarks = 750; 
    $position = 1;
    $backcounter=0;
    for($i=1;$i<=mysql_num_rows($query);$i++)
    {
        $rows = mysql_fetch_array($query);
        $totalMarksAsAString = str_replace(": ","",strip_tags($rows['totalmarks']));
        if($rows['totalmarks']==$tempTotalMarks)
        {
            $backcounter++;
            $position = $i-$backcounter;
        }
        if($rows['totalmarks']<$tempTotalMarks)
        {
            $backcounter=0;
            $position = $i;
        }
        $percentage = sprintf("%0.2f",($totalMarksAsAString/750)*100);
        $name = $rows['name'];
        echo "<tr><td>$position</td><td>$name</td><td>$totalMarksAsAString</td><td>$percentage</td></tr>";
        $tempTotalMarks = $rows['totalmarks'];
    }
    echo "</tbody></table>";
}
?>
<div style="background:LavenerBlush" class="jumbotron">
    <h1>Top Students</h1>      
    <?php getTop81Students(); ?>
  </div>
  
<div style="background:Ivory" class="jumbotron">
    <h1>Subject Wise Analysis - Highest Marks</h1>
    <div class="alert alert-info" role="alert" style="text-align:center">
  <h4 class="alert-heading">SOFTWARE ARCHITECTURES - 10IS81</h4>
</div>      
    <?php getSubjectHighestStudents("10IS81"); ?>
    <br />
    <div class="alert alert-info" role="alert" style="text-align:center">
  <h4 class="alert-heading">SYSTEM MODELLING & SIMULATION - 10CS82</h4>
</div>      
    <?php getSubjectHighestStudents("10CS82"); ?>
    <br />
    <div class="alert alert-info" role="alert" style="text-align:center">
  <h4 class="alert-heading">INFORMATION & NETWORK SECURITY - 10CS835</h4>
</div>      
    <?php getSubjectHighestStudents("10CS835"); ?>
    <br />
    <div class="alert alert-info" role="alert" style="text-align:center">
  <h4 class="alert-heading">AD-HOC NETWORKS - 10CS841</h4>
</div>      
    <?php getSubjectHighestStudents("10CS841"); ?>
    <br />
    <div class="alert alert-info" role="alert" style="text-align:center">
  <h4 class="alert-heading">PROJECT WORK - 10CS85</h4>
</div>      
    <?php getSubjectHighestStudents("10CS85"); ?>
    <br />
    <div class="alert alert-info" role="alert" style="text-align:center">
  <h4 class="alert-heading">SEMINAR - 10CS86</h4>
</div>      
    <?php getSubjectHighestStudents("10CS86"); ?>
</div>  




<div style="background:Ivory" class="jumbotron">
    <h1>Subject Wise Pass Percentage</h1>      
    <div class="alert alert-info" role="alert">
  <h4 class="alert-heading">SOFTWARE ARCHITECTURES - 10IS81 - <?php getSubjectStats("10IS81"); ?> <br />
  
                            SYSTEM MODELLING & SIMULATION - 10CS82 - <?php getSubjectStats("10CS82"); ?> <br />
                            
                            INFORMATION & NETWORK SECURITY - 10CS834 - <?php getSubjectStats("10CS835"); ?> <br />
                            
                            AD-HOC NETWORKS - 10CS841 - <?php getSubjectStats("10CS841"); ?> <br />
                            
                            PROJECT WORK - 10CS85 - <?php getSubjectStats("10CS85"); ?> <br />
                            
                            SEMINAR - 10CS86 - <?php getSubjectStats("10CS86"); ?></h4>
</div> 
  </div>
<h3 style="width: 100%;height:50px;background:black;color:white;padding:5px;text-align:center">Karthik E C &#9786;</h3>
</body>


</html>