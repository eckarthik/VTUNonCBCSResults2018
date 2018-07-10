## VTU Results
This code will collect results of students from the VTU website and performs various analysis like Top students,Subject wise highest marks, Subject wise pass percentage.

## Usage

This code fetches results and stores in a database. So you have to create a database first. 

Create a table with name **vtu2018** (If you use any other name then make sure you change it in the code also) containing four columns - name,usn,result,totalmarks. 

Once the table is created, open **vtures.php** file in any editor and change the USN to your college USN and run the **vtures.php** file. It will automatically fetch all the results and stores it in the database. 

After all the results have been fetched and stored in the database, run **stats.php** file to get the results analysis. 
 
Run **vturesultstoplist.php** to get complete results of each student.