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
$query = mysql_query("SELECT * FROM vtu2018 ORDER BY totalmarks DESC");
$resultStyle = $_GET['resultstyle'];
if($resultStyle=="topfull") // Displays all subjects marks of each student.
{
    if($query)
    {
        
        for($i=1;$i<=mysql_num_rows($query);$i++)
        {
            $rows = mysql_fetch_array($query);
            $result = str_replace("Name:","<b>Name:</b>",$rows['result']);
            $result = str_replace("USN","<b>USN</b>",$result);
            echo "<b>Serial Number: </b>" . $i . "<br/>" . $result . "<br/>";
            echo "<hr/>";
        }
    }
    else
    {
        echo "Unable to get data from database".mysql_error();
    }
}
else if($resultStyle=="som") //Displays only total marks and percentage
{
    if($query)
    {
        echo "<table border='border'><tr><th>Serial Number</th><th>Name</th><th>Total Marks</th><th>Percentage</th></tr>";
        for($i=1;$i<=mysql_num_rows($query);$i++)
        {
            $rows = mysql_fetch_array($query);
            $totalMarksAsAString = str_replace(": ","",strip_tags($rows['totalmarks']));
            $percentage = sprintf("%0.2f",($totalMarksAsAString/750)*100);
            $name = $rows['name'];
            echo "<tr><td>$i</td><td>$name</td><td>$totalMarksAsAString</td><td>$percentage</td></tr>";
        }
        echo "</table>";
    }
    else
    {
        echo "Unable to get data from database".mysql_error();
    }
}
else //Displays all subjects marks of each student sorted according to USN
{
    $query = mysql_query("SELECT * FROM vtu2018");
    if($query)
    {
        for($i=1;$i<=mysql_num_rows($query);$i++)
        {
            $rows = mysql_fetch_array($query);
            $result = str_replace("Name:","<b>Name:</b>",$rows['result']);
            $result = str_replace("USN","<b>USN</b>",$result);
            echo "<b>Serial Number: </b>" . $i . "<br/>" . $result . "<br/>";
            echo "<hr/>";
        }
    }
    else
    {
        echo "Unable to get data from database".mysql_error();
    }
}
?>