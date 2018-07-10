<?php
error_reporting(0);
/**
 * @author Karthik E C
 */
require('simple_html_dom.php');
function attack_filter($string)
{
if (get_magic_quotes_gpc()) $string = stripslashes($string);
return mysql_real_escape_string($string);
}
$host = "localhost";
$username = "root";
$password = "";
mysql_connect($host,$username,$password) or die("Cannot connect to MySQL: ".mysql_error());
mysql_select_db("vtures");
for($i=1;$i<=120;$i++) //USN 1NC14CS001 to 1NC14CS120... Change the loop initialization and termination condition to match your class student strength.
{
    //Building the USN
    if($i>9 && $i<=99)
    {
        $usn = "1NC14CS0$i";
    }
    else if($i>=100)
    {
        $usn= "1NC14CS$i";
    }
    else
    {
        //USNs between 001 to 009
        $usn = "1NC14CS00$i";
    }
    //$usn = "1NC13CS118";
$request = array(
'http' => array(
    'method' => 'POST',
    'content' => http_build_query(array(
        'lns' => $usn
    )),
)
);

$context = stream_context_create($request);
$html = file_get_html('http://results.vtu.ac.in/vitaviresultnoncbcs18/resultpage.php', false, $context);
//print_r($html);
if(preg_match("/University Seat Number is not available or Invalid./i",$html))
{
    continue;
}
else
{
    preg_match("/<td style=\"padding-left:15px\"><b>:<\/b>(.*?)<\/td>/",$html,$regdata); //Search for student name from the HTML response data
    $name = $regdata[1];
    preg_match("/<td style=\"padding-left:15px;text-transform:uppercase\"><b> :<\/b>(.*?)<\/td>/",$html,$regdata);
    $USNnum = strtoupper($regdata[1]);
    $result = "";
    $result .= "USN: $USNnum <br/> Name: $name <br/>"; 
    $result .= "<table border='border'>";
    foreach($html->find('div.divTableRow') as $e)
    {
        $result .= "<tr>";
        if(strpos($e->plaintext,"->")!=false)
            continue;
        foreach($e->find('div.divTableCell') as $tdata)
            $result .= "<td>$tdata</td>";
        $result .= "</tr>";
    }
    $result .= "</table>";
    preg_match_all("/<td style=\"padding-left:10px\"><b>(.*?)<\/b><\/td>/",$html,$regdata); //Search for totalmarks and result class from the HTML response data
    $totalmarks = $regdata[0][0]; 
    $resultclass = $regdata[0][1];
    $result .= "<br/>Total Marks $totalmarks <br/> Result: $resultclass"; 
    echo $result;
    $resultforDB = attack_filter($result);
    $query = mysql_query("INSERT into vtu2018 (name,usn,result,totalmarks) VALUES('$name','$USNnum','$resultforDB','$totalmarks')"); //Add the result data into the database
    if($query)
    {
        echo " <br/> $USNnum result added to database successfully <br/>";
    }
    else
    {
        echo "Error occured while adding result of $USNnum to database".mysql_error();
    }
}
}
?>