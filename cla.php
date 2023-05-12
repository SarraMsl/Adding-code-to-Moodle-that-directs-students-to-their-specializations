<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    text-align: left;
    padding: 8px;
    border-bottom: 1px solid #ddd;
  }

  th {
    background-color: #88b77b;
    color: white;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  .button-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  button {
    padding: 10px 20px;
    background-color: #88b77b;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    margin: 0 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
divs {
    display: inline-block;
    margin: 0 10px;
    vertical-align: middle;
}

  button:hover {
    background-color: #6c9a63;
  }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
// connect to database
include('dbconnect.php');

// retrieve user choices based on their preferences
if (isset($_POST["new"])) {
    $query = "SELECT 
    u.firstname, 
    u.lastname, 
    (SELECT ud.data FROM mdl_tableuser_info_data ud WHERE ud.fieldid = 2 AND ud.userid = u.id LIMIT 1) AS choix,
    (SELECT ud.data FROM mdl_tableuser_info_data ud WHERE ud.fieldid = 4 AND ud.userid = u.id LIMIT 1) AS moyenne,
    MAX(CASE WHEN qc.content = 'GL' THEN rr.rankvalue END) AS gl,
    MAX(CASE WHEN qc.content = 'RT' THEN rr.rankvalue END) AS rt,
    MAX(CASE WHEN qc.content = 'GI' THEN rr.rankvalue END) AS gi
FROM mdl_tablequestionnaire_response r
JOIN mdl_tableuser u ON r.userid = u.id
JOIN mdl_tablequestionnaire_response_rank rr ON r.id = rr.response_id
JOIN mdl_tablequestionnaire_quest_choice qc ON rr.choice_id = qc.id
WHERE u.username NOT LIKE 'admin%' -- exclude admin users
AND r.questionnaireid = 1
GROUP BY u.id
ORDER BY moyenne DESC";


$result = pg_query($query);

// display the results in a table with delete and classify buttons
echo "<table>
      <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Spécialité Dérigée</th>
      <th>moyenne</th>
      <th>GL</th>
      <th>RT</th>
      <th>GI</th>

      </tr>";

while ($row = pg_fetch_assoc($result)) {
  $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $choix = $row['choix'];
    $moyenne = $row['moyenne'];
    $gl = $row['gl'];
    $rt = $row['rt'];
    $gi = $row['gi'];


    echo "<tr>";
    echo "<td>" . $row['firstname'] . "</td>";
    echo "<td>" . $row['lastname'] . "</td>";
    echo "<td>" . $row['choix'] . "</td>";
    echo "<td>" . $row['moyenne'] . "</td>";
    echo "<td>" . $row['gl'] . "</td>";
    echo "<td>" . $row['rt'] . "</td>";
    echo "<td>" . $row['gi'] . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "<div class='button-container'>";


echo "</div>";

// close database connection
pg_close($conn);}
else if (isset($_POST["old"])) {

    $query = "SELECT 
    u.firstname, 
    u.lastname, 
    ud1.data AS choix,
    ud2.data AS moyenne,
    MAX(CASE WHEN qc.content = 'GL' THEN rr.rankvalue END) AS gl,
    MAX(CASE WHEN qc.content = 'RT' THEN rr.rankvalue END) AS rt,
    MAX(CASE WHEN qc.content = 'GI' THEN rr.rankvalue END) AS gi
FROM mdl_tablequestionnaire_response r
JOIN mdl_tableuser u ON r.userid = u.id
JOIN mdl_tablequestionnaire_response_rank rr ON r.id = rr.response_id
JOIN mdl_tablequestionnaire_quest_choice qc ON rr.choice_id = qc.id
LEFT JOIN mdl_tableuser_info_data ud1 ON ud1.userid = u.id AND ud1.fieldid = 2 AND ud1.data IS NOT NULL
LEFT JOIN mdl_tableuser_info_data ud2 ON ud2.userid = u.id AND ud2.fieldid = 4 AND ud2.data IS NOT NULL
WHERE r.questionnaireid = 1 -- add the WHERE clause
AND u.username NOT LIKE 'admin%' -- exclude admin users
GROUP BY u.id, ud1.data, ud2.data
HAVING ud1.data IS NOT NULL AND ud2.data IS NOT NULL
ORDER BY ud2.data DESC";



$result = pg_query($query);

// display the results in a table with delete and classify buttons
echo "<table>
      <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Spécialité Dérigée</th>
      <th>moyenne</th>
   
      </tr>";

while ($row = pg_fetch_assoc($result)) {
  $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $choix = $row['choix'];
    $moyenne = $row['moyenne'];
 


    echo "<tr>";
    echo "<td>" . $row['firstname'] . "</td>";
    echo "<td>" . $row['lastname'] . "</td>";
    echo "<td>" . $row['choix'] . "</td>";
    echo "<td>" . $row['moyenne'] . "</td>";
 
    echo "</tr>";
}

echo "</table>";
echo "<div class='button-container'>";


echo "</div>";

// close database connection
pg_close($conn);}

?> 

<br>



<div  style="display:flex; flex-direction:row;">
    <form action="" method="post">
        <button data-text="Goo" name="classe">
            <span>Classify</span>
        </button>
    </form>

    <form action="" method="post">
        <button data-text="Goo" name="delete1">
            <span>Delete All reponse</span>
        </button>
    </form>

    <form action="" method="post">
        <button data-text="Goo" name="old">
            <span>old</span>
        </button>
    </form>

    <form action="" method="post">
        <button data-text="Goo" name="new">
            <span>new</span>
        </button>
    </form>

    <form action="" method="post">
        <button data-text="Goo" name="submitd">
            <span>Delete classment</span>
        </button>
    </form>
</div>

</form>
</body>
</html>







<?php



if (isset($_POST["classe"])) {
    $con=0;
    $classgl=0;
    $classgi=0;
    $classrt=0;
// connect to database
include('dbconnect.php');

// retrieve user choices based on their preferences
$sql = "SELECT 
u.id as usid,
u.firstname, 
u.lastname, 
(SELECT ud.data FROM mdl_tableuser_info_data ud WHERE ud.fieldid = 2 AND ud.userid = u.id LIMIT 1) AS choix,
(SELECT ud.data FROM mdl_tableuser_info_data ud WHERE ud.fieldid = 4 AND ud.userid = u.id LIMIT 1) AS moyenne,
MAX(CASE WHEN qc.content = 'GL' THEN rr.rankvalue END) AS gl,
MAX(CASE WHEN qc.content = 'RT' THEN rr.rankvalue END) AS rt,
MAX(CASE WHEN qc.content = 'GI' THEN rr.rankvalue END) AS gi
FROM mdl_tablequestionnaire_response r
JOIN mdl_tableuser u ON r.userid = u.id
JOIN mdl_tablequestionnaire_response_rank rr ON r.id = rr.response_id
JOIN mdl_tablequestionnaire_quest_choice qc ON rr.choice_id = qc.id
WHERE u.username NOT LIKE 'admin%' -- exclude admin users
AND r.questionnaireid = 1
GROUP BY u.id
ORDER BY moyenne DESC";


$result = pg_query($sql);


 if (pg_num_rows($result) > 0) {
 

    while ($rowData = pg_fetch_array($result)) {
        $usid = $rowData['usid'];
        $firstname = $rowData['firstname'];
        $lastname = $rowData['lastname'];
        $choix = $rowData['choix'];
        $moyenne = $rowData['moyenne'];
        $gl = $rowData['gl'];
        $rt = $rowData['rt'];
        $gi = $rowData['gi'];




        if ($rowData["gl"] == "1") {
            if ($classgl < pg_num_rows($result) / 3) {
                $sql = "update mdl_tableuser_info_data set data='gl' where userid = ".$usid." and fieldid = 2";
                $res = pg_query($sql);
                $classgl++;
            } elseif ($rowData["gi"] == "2") {
                if ($classgi < pg_num_rows($result) / 3) {
                    $sql = "update mdl_tableuser_info_data set data='gi'where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classgi++;
                } else {
                    $sql = "update mdl_tableuser_info_data set data='rt' where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classrt++;
                }
            } elseif ($rowData["rt"] == "2") {
                if ($classrt < pg_num_rows($result) / 3) {
                    $sql = "update mdl_tableuser_info_data set data='rt' where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classrt++;
                } else {
                    $sql = "update mdl_tableuser_info_data set data='gi' where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classgi++;
                }
            }
        } elseif ($rowData["gi"] == "1") {
            if ($classgi < pg_num_rows($result) / 3) {
                $sql = "update mdl_tableuser_info_data set data='gi' where userid = ".$usid." and fieldid = 2";
                $res = pg_query($sql);
                $classgi++;
            } elseif ($rowData["gl"] == "2") {
                if ($classgl < pg_num_rows($result) / 3) {
                    $sql = "update mdl_tableuser_info_data set data='gl' where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classgl++;
                } else {
                    $sql = "update mdl_tableuser_info_data set data='rt' where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classrt++;
                }
            } elseif ($rowData["rt"] == "2") {
                if ($classrt < pg_num_rows($result) / 3) {
                    $sql = "update mdl_tableuser_info_data set data='rt' where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classrt++;
                } else {
                    $sql = "update mdl_tableuser_info_data set data='gl'where userid = ".$usid." and fieldid = 2";
                    $res = pg_query($sql);
                    $classgl++;
                }
            }
        } 

        elseif ($rowData["rt"]=="1") {
            if ($classrt<pg_num_rows($result)/3) {
                $sql ="update mdl_tableuser_info_data set data='rt' where userid = ".$usid." and fieldid = 2";
                $res=pg_query($sql);
                $classrt++;
            } elseif ($rowData["gl"]=="2") {
                if ($classgl<pg_num_rows($result)/3) {
                    $sql ="update mdl_tableuser_info_data set data='gl' where userid = ".$usid." and fieldid = 2";
                    $res=pg_query($sql);
                    $classgl++;
                } else {
                    $sql ="update mdl_tableuser_info_data set data='gi' where userid = ".$usid." and fieldid = 2";
                    $res=pg_query($sql);
                    $classgi++;
                }
            } elseif ($rowData["gi"]=="2") {
                if ($classgi<pg_num_rows($result)/3) {
                    $sql ="update mdl_tableuser_info_data set data='gi' where userid = ".$usid." and fieldid = 2";
                    $res=pg_query($sql);
                    $classgi++;
                } else {
                    $sql ="update mdl_tableuser_info_data set data='gl' where userid = ".$usid." and fieldid = 2";
                    $res=pg_query($sql);
                    $classgl++;
                }
            }
        }
        
    }

 }
    header("location:classement.php");

}

?>


<?php

include('dbconnect.php');

if(isset($_POST['submitd']))
{	 
	
    $sql = "UPDATE mdl_tableuser_info_data
    SET data = NULL
    WHERE fieldid = 2
    AND EXISTS (SELECT * FROM mdl_tablequestionnaire_response
                WHERE questionnaireid = 1
                AND userid = mdl_tableuser_info_data.userid)";

	 if (pg_query($conn, $sql)) {
		echo "";
	 } else {
		echo "Error: " . $sql . "
" . pg_last_error($conn);}
     header("location:classement.php");
	 pg_close($conn);
	 
}
?>





<?php

include('dbconnect.php');

if(isset($_POST['delete1']))
{	 
	
    $sql =" DELETE FROM mdl_tablequestionnaire_response_rank";
$sql1 = "DELETE FROM mdl_tablequestionnaire_response";


	 if (pg_query($conn, $sql)||pg_query($conn, $sql1)) {
		echo "";
	 } else {
		echo "Error: " . $sql . "," . $sql1 . "
" . pg_last_error($conn);}
     header("location:classement.php");
	 pg_close($conn);
	 
}
?>