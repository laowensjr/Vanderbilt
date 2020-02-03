<?php 
require "PAT.php";
$authCreds =  new PAT();

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="bootstrap/bootstrap431/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="bootstrap/bootstrap431/js/bootstrap.min.js"></script>
    <title>The Most Starred Public PHP Projects</title>
  </head>
  <body>
  <h1>The Most Starred Public PHP Projects on GitHub</h1>
    <div class="container">
  <div class="row">
    <div class="col-md-12">
      <p>Click through to View the Details of each one.</p>
      <br /> <br />
      </div>
      </div>
       <div class="row">
     <div class="col-md-3">This List is Updated Regularly from Github's API. See Update Dates Below</div><div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-3"></div>
     </div>
     <div class="row">
     <div class="col-md-12">
     
<?php

// Initilize a cURL session to the Github API. Get the Most starred Repositories and sort them Descending. By default Github allows 30 records. There is an option to display more by using the per page parameter. 
  
//$url = "https://api.github.com/search/repositories?q=stars:>1&sort=stars&order=desc";
$url = "https://api.github.com/search/repositories?q=language:php";
$ch = curl_init();

//Set options to be used in the cURL request.

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, "laowensjr");
curl_setopt($ch, CURLOPT_USERPWD, $authCreds->setUsername().":".$authCreds->setPassword());
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);

if ($response === false){
    
    $response = curl_error($ch);
}else{
   
    stripslashes($response);
 // echo "Before Pre";
    
  
  $res1 = json_decode($response, JSON_PRETTY_PRINT);
   
   //Prints out the array which allows you to see its multi-dimensional array. Used this to access data from the for loop created further down in the code.
  //print_r($res1) ; 
# Bootstrap code used below to create a respoonsive table to display the API data received from Github for the most starred public repos
 // echo "After PRE";
   echo "<br><br><br>";
  echo '<div class="table-responsive">';
   echo '<table class = "table table-striped">';
   echo '<thead>';
   echo '<tr>';
   echo '<th scope="col">';
   echo '#';
   echo '</th>';
   echo '<th scope="col">';
   echo 'Repository ID';
   echo '</th>';
   echo '<th scope="col">';
   echo 'Name';
   echo '</th>';
   echo '<th scope="col">';
   echo 'Number of Stars';
   echo '</th>';   
   echo '<th scope="col">';
   echo 'GitHub URL';
   echo '</th>';
   echo '<th scope="col">';
   echo 'Created Date';
   echo '</th>';
   echo '<th scope="col">';
   echo 'Last Push Date';
   echo '</th>';
   echo '<th scope="col">';
   echo 'Description';
   echo '</th>';
  
   echo '</tr>';
   echo '</thead>';
   echo '<tbody>';
  
// Instantiate DB Object
   
   include "Database.php";
   $dbh0 = new Database();
   $mysqli = $dbh0->connect();

// Start a for loop that prints the recent Most Starred Public Github Repos to the screen in a Bootstrap Reponsive table
   //echo"Before For Loop";

   for ($i=0;$i < count($res1["items"]); $i++ ){
      
       //echo "After For Loop";
       echo '<tr>';
       echo '<th scope="row">';
       echo $i+1;
       $RepositoryID = $res1["items"][$i]["id"];
       echo '<br>';
     echo '<a class="btn btn-primary" type="submit" href="repoView.php?'.$RepositoryID.'" role="button">Details</a>';
     
       echo '</th>';
       
      echo '<td>';
      echo $res1["items"][$i]["id"];
      //$RepositoryID = $res1["items"][$i]["id"];
      
      echo '</td>';
      
      echo '<td>';
      echo $res1["items"][$i]["name"];
      $Name1 = $res1["items"][$i]["name"];
      //Name Could have an Apostrophe so we use real_escape_string here before inserting into Mysql DB
      $Name = $mysqli->real_escape_string($Name1);
      
      echo '</td>';
      
      echo '<td>';
      $num = $res1["items"][$i]["stargazers_count"];
      echo number_format($num);
      $NumberOfStars = number_format($num);
      
      echo '</td>';      
      
      echo '<td>';
     echo '<a href';
      echo '=';
      echo '"';
      echo $res1["items"][$i]["owner"]["html_url"];
      echo '"';
      echo '>';
      echo $res1["items"][$i]["owner"]["html_url"];
      $URL = $res1["items"][$i]["owner"]["html_url"];
      
     echo '</a>';
      echo '</td>';
      
      echo '<td>';
      echo $res1["items"][$i]["created_at"];
      $c2at = $res1["items"][$i]["created_at"];
      
      echo '</td>';
      
      echo '<td>';
      echo $res1["items"][$i]["pushed_at"];
      $LastPushed = $res1["items"][$i]["pushed_at"];
      
      echo '</td>';
      
      echo '<td>';
      echo $res1["items"][$i]["description"];
      $Description1 = $res1["items"][$i]["description"];
      //Description Could have an Apostrophe so we use real_escape_string here before inserting into Mysql DB
      $Description = $mysqli->real_escape_string($Description1);
      echo '</td>';
   
       
   echo '</tr>';
   date_default_timezone_set('America/Chicago');
   $updatedInThisDB = date('l jS \of F Y h:i:s A');
# These following are the statements I used on the first run to insert the Repos into the Mysql Table I created
# After the records successfully inserted I received a on screen notification saying "Inserted!"
   

/*
 * 
 if ($result = $mysqli->query("INSERT IGNORE INTO Repository(RepositoryID, Name, URL, c2at, LastPushed, Description, NumberOfStars, UpdatedInThisDB) VALUES('$RepositoryID', '$Name', '$URL', '$c2at', '$LastPushed', '$Description', '$num', '$updatedInThisDB');")){
       
       echo 'Inserted!';
   }else{
       echo 'Not Inserted' . mysqli_error($mysqli);
       
   } 
*/
  
#The following are the statements in variables. 
   
  // $INSERTquery = $mysqli->query("INSERT IGNORE INTO Repository(RepositoryID, Name, URL, c2at, LastPushed, Description, NumberOfStars) VALUES('$RepositoryID', '$Name', '$URL', '$c2at', '$LastPushed', '$Description', '$num')");

 //  $UPDATEquery = $mysqli->query("UPDATE Respository SET RepositoryID = '$RepositoryID', Name = '$Name', URL = '$URL', c2at = '$c2at', LastPushed = '$LastPushed', Description = '$Description', NumberOfStars = '$num' WHERE (RepositoryID = '$RepositoryID') OR (RepositoryID = '')");
   
   
# Everytime the Page is Opened it Updates the MySQL Table with the Latest Public Most Starred Repo and Updates the Pageas well
   


 $UPDATEquery = "UPDATE Repository SET RepositoryID = '$RepositoryID', Name = '$Name', URL = '$URL', c2at = '$c2at', LastPushed = '$LastPushed', Description = '$Description', NumberOfStars = '$num', UpdatedInThisDB = '$updatedInThisDB' WHERE RepositoryID = '$RepositoryID'";
   
   if($mysqli->query($UPDATEquery)){
       $message[] = $RepositoryID ." " . $Name . " Updated Successfully";
      
       
       
       
   }else{
       echo " did not update " . mysqli_error($mysqli);
   }
   
   
   
  } //Ends for loop
  
  
   echo '</tbody>';
   echo '</table>';
   echo '</div>';
   
   
   
    } //Ends else statement on or around line 47
    curl_close($ch);
    

?>

      
    </div>
    </div>
    <br /><br />
    <div class="row">
       <div class = "col-md-12"><hr></div>
       <div class = "col-md-12"><h5>Updates Below: </h5></div>
  <div class = "col-md-6">
  <?php  foreach ($message as $message){
        echo $message . " <b>Date: </b>" . $updatedInThisDB . "<br>";   
       }?>
  
  </div>
  <div class = "col-md-6"></div>
  
  
  </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>