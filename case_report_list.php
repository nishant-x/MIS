<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case List</title>
    <link rel="stylesheet" href="case_report_list.css">

    <style>

    </style>
</head>

<body>
    <nav class="header">
        <p class="portal_name">MP Police Central Command</p>
        <img src="3logo3.png"  alt="">
    </nav>
    <header>
        <h1>Case List</h1>
        <form action="" method="GET">
            <input type="text" name="search" id="search" placeholder="Search Cases.." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </header>

    <?php
 session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "mis";

$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_SESSION['username'])) {
$id= $_SESSION['username'];  //get the id
// Default query to fetch all cases of perticular jawan
$sql = "SELECT * FROM case_study_of_jawaan  WHERE jawaan_id='$id' ";  
$search_result = "";
$case_no = ""; // Initialize case_no variable

// Check if the search form is submitted
if (!empty($search_query)) {
    // If search query is provided, add filter condition
    $sql .= " AND (case_number LIKE '%$search_query%' OR crime_type LIKE '%$search_query%' OR status LIKE '%$search_query%' OR location LIKE '%$search_query%')";
}
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Encode the case number to ensure it doesn't contain special characters
        $encoded_name = urlencode($row['case_number']);
        $jawaan_id = $row["jawaan_id"];
        $sql2 = "SELECT * FROM jawaan_details WHERE jawaan_id='$jawaan_id' ";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        echo "      
            <div class='case-details'>
            <p><h4> Case No.:" . $row['case_number'] . "</h4></p>
            <p> Assigned Officer: " . $row2['jawaan_name'] . "</p>
            <p> Crime Type: " . $row['crime_type'] . "</p>
            <p> Date: " . $row['date'] . "</p>
            <p> Crime Location: " . $row['location'] . "</p>
            <p> Case Status: " . $row['status'] . "</p>
        </div>
    </div>
";
        $case_no = $row['case_number']; // Update case number here
    }
} else {
    echo "No data found.";
}
} else {
echo "User not logged in.";
}

// code for displaying results of the second query
if (!empty($case_no)) { // Check if case_no is not empty
    $sql_1 = "SELECT * FROM case_study_of_station WHERE case_number = '$case_no'";
    $result_1 = mysqli_query($conn, $sql_1);
    if (mysqli_num_rows($result_1) > 0) {
        while ($row_1 = mysqli_fetch_assoc($result_1)) {
           $case_details = "
           <section class='case_details'>
               <div class='incident'>
                   <h2>Incident Reports</h2>
                   <div class='incident_details'>
                       <div class='display_list'></div>
                       <button class='add' id='incidentAddButton'>Add</button>
                   </div>
                   <div class='incident_reports'>
                       <textarea placeholder='' value='$row_1[incident_report]'> </textarea>
                       <button class='update' id='incidentUpdateButton'>update</button>
                   </div>
               </div>
   
               <div class='witness'>
                   <h2>Witness Statements</h2>
                   <div class='witness_details'>
                       <div class='display_list'></div>
                       <button class='add' id='witnessAddButton'>Add</button>
                   </div>
                   <div class='witness_statments'>
                       <textarea value='$row_1[incident_report]'></textarea>
                       <button class='update' id='witnessUpdateButton'>update</button>
                   </div>
               </div>
   
               <div class='investigation'>
                   <h2>Investigation Notes</h2>
                   <div class='investigation_details'>
                       <div class='display_list'></div>
                       <button class='add' id='investigationAddButton'>Add</button>
                   </div>
                   <div class='investigation_notes'>
                       <textarea placeholder='' value='$row_1[incident_report]'></textarea>
                       <button class='update' id='investigationUpdateButton'>update</button>
                   </div>
               </div>
   
               <div class='evidence'>
                   <h2>Evidence Collection</h2>
                   <div class='evidence_details'>
                       <div class='display_list'></div>
                       <button class='add' id='evidenceAddButton'>Add</button>
                   </div>
                   <div class='evidence_collection'>
                       <input type='file'>
                       <button class='update' id='evidenceUpdateButton'>update</button>
                   </div>
               </div>
   
               <div class='documentation'>
                   <h2>Documentation</h2>
                   <div class='documentation_details'>
                       <div class='display_list'></div>
                       <button class='add' id='documentationAddButton'>Add</button>
                   </div>
                   <div class='documentation_collection'>
                       <input type='file'>
                       <button class='update' id='documentationUpdateButton'>update</button>
                   </div>
               </div>
           </section>";
        }
    } else {
        $case_details = "No matching records found.";
    }
}

// Close connection
mysqli_close($conn);
?>

    <div class="main_content">
        <div class="heading">CASE REPORT</div>

        <?php echo $case_details; ?>
       
       
        <div class="button">
            <button class="submit">Submit Case</button>
            <button class="submit">Case Complete</button>
        </div>

    </div>

    <script>
        var case_list = document.querySelector('.container');
        var case_report = document.querySelector(".main_content");
        var case_report_btn = document.querySelector(".submit");
        
        case_list.addEventListener("click", () => {
            case_report.style.display = 'block';
            case_list.style.display = 'none';
        });

        case_report_btn.addEventListener("click", () => {
            case_report.style.display = 'none';
            case_list.style.display = 'flex';
        });
    </script>
    <footer>
        <p>© 2024 Ministry of Home Affairs, Madhya Pradesh. All Rights Reserved.</p>
    </footer>
</body>

</html>
