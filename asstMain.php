<?php //http://localhost/StromanLiamCodingAsst/asstMain.php

require_once("asstInclude.php");
require_once("clsCreateRoadTestTable.php");

date_default_timezone_set ('America/Toronto');
$mysqlObj; 
$TableName = "RoadTest";

WriteHeaders($Heading="Liam Stroman Coding Assignment",$TitleBar="Coding assignment");

$mysqlObj = createConnectionObject();

echo "<form action = ? method=post>";

//Nested if statement to display the appropriate forms

if(isset($_POST["f_RecordSave"]))
{
    SaveRecordToTableForm($mysqlObj, $TableName);    
}
else if(isset($_POST["f_CreatePage"]))
{
    CreateTableForm($mysqlObj, $TableName);
}
else if (isset($_POST["f_RecordPage"]))
{
    AddRecordForm();
}
else if (isset($_POST["f_DisplayData"]))
{
    DisplayDataForm($mysqlObj, $TableName);
}
else
{
    DisplayMainForm();
}


if (isset($mysqlObj)) 
{
    $mysqlObj->close();
}

    

echo "</form>";

WriteFooters();

function DisplayMainForm()
{
    
    DisplayButton("f_CreatePage", "Create Table Page", "createTableBtn.png", "create table");
    DisplayButton("f_RecordPage", "Add a New Record", "addRecordBtn.png", "Add Record");
    DisplayButton("f_DisplayData", "View Table", "displayDataBtn.png", "Display Data");
    

}

function CreateTableForm(&$mysqlObj, $TableName)
{
    $roadTest = new clsCreateRoadTestTable();

    echo "<form>";
    $roadTest->createTheTable($mysqlObj, $TableName);
    DisplayButton("f_HomeLink", "Return Home", "homeBttn.png", "home");
    echo "</form>";
}

function AddRecordForm()
{
    echo "<form>";
    echo "<div class = \"DataPair\">";
        DisplayLabel("License Plate");
        DisplayTextBox("text", "f_PlateNum", 15);
    echo "</div>";

    echo "<div class = \"DataPair\">";
        DisplayLabel("Date Stamp");
        DisplayTextBox("date", "f_DateStamp", 15, date('y-m-d'));
    echo "</div>";

    echo "<div class = \"DataPair\">";
        DisplayLabel("Time Stamp");
        DisplayTextBox("time", "f_TimeStamp", 15, date('h:i'));
    echo "</div>";

    echo "<div class = \"DataPair\">";
        DisplayLabel("Number of Passengers");
        DisplayTextBox("number", "f_NumPassenger", 15, 3);
    echo "</div>";

    echo "<div class = \"DataPair\">";
        DisplayLabel("Incident Free");
        echo "<input type = \"checkbox\", name = \"f_IncidentFree\", size = \"20\"></input>\n";
    echo "</div>";

    echo "<div class = \"DataPair\">"; 
    DisplayLabel("Danger Status");
    echo "<br><select name = \"f_DangerStatus\", size = 4, value = \"M\">
            <option value = \"L\">Low</option>
            <option value = \"M\">Medium</option>
            <option value = \"H\">High</option>
            <option value = \"C\">Critical</option>
        </select>
    </div>";

    echo "<div class = \"DataPair\">";
        DisplayLabel("Speed");
        DisplayTextBox("text", "f_Speed", 15, 100);
    echo "</div>";

    

    

    DisplayButton("f_RecordSave", "Save Data", "saveBtn.png", "save");
    

    DisplayButton("f_HomeLink", "Return Home", "homeBttn.png", "home");
    echo "</form>";

}

function SaveRecordToTableForm(&$mysqlObj, $TableName)
{

    $plateNum = $_POST["f_PlateNum"];
    $dateStamp = $_POST["f_DateStamp"];
    $timeStamp = $_POST["f_TimeStamp"];
    $numPassenger = $_POST["f_NumPassenger"];
    $DangerStatus = $_POST["f_DangerStatus"];
    $speed =  $_POST["f_Speed"];

    //unchecked checkboxes leave name undeclared
    if (isset($_POST["f_IncidentFree"]))
    {
        $incidentFree = $_POST["f_IncidentFree"];
    }
    else
    {
        $incidentFree = false;
    }
        
    

    $cost = 5000 + (100 * $numPassenger);

    $query = "Insert Into $TableName Values (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqlObj->prepare($query);

    $DateTime = ($dateStamp . " " . $timeStamp);

    $BindSuccess = $stmt->bind_param("ssiisdd", $plateNum, $DateTime, $numPassenger, $incidentFree, $DangerStatus, $speed, $cost);

    $Result = $stmt->execute();

    if ($Result)
    {
        echo "Record successfully added to $TableName.";
    } 
    else
    {
        echo "Unable to add record to $TableName: " . "$stmt->error";
    }


    DisplayButton("f_HomeLink", "Return Home", "homeBttn.png", "home");
    
}

function DisplayDataForm(&$mysqlObj, $TableName)
{
    $query = "SELECT licensePlate, dateTimeStamp, nbrPassengers, incidentFree, dangerStatus, speed, cost FROM $TableName
              ORDER BY dangerStatus DESC;";

    $stmt = $mysqlObj->prepare($query);

    $result = $stmt->execute();

    $bindResult = $stmt->bind_result($licensePlate, $dateTimeStamp, $nbrPassengers, $incidentFree, $dangerStatus, $speed, $cost);

    echo "<table>
        <th>Plate Number</th>
        <th>Date Stamp</th>
        <th>Number of Passengers</th>
        <th>Incident Free</th>
        <th>Danger Status</th>
        <th>Speed (km/h)</th>
        <th>Cost (CAD)</th>
    ";

    while($stmt->fetch())
    {

        $dateTimeStamp = str_replace(" ", " at ", $dateTimeStamp);

        $incidentFree = ($incidentFree) ? "yes" : "no";

        switch($dangerStatus)
        {
            case "L":
                $dangerStatus = "low";
                break; 
            case "M":
                $dangerStatus = "medium";
                break; 
            case "H":
                $dangerStatus = "high";
                break; 
            case "C":
                $dangerStatus = "critical";
                break; 
        }

        echo "<tr>

        <td>$licensePlate</td>
        <td>$dateTimeStamp</td>
        <td>$nbrPassengers</td>
        <td>$incidentFree</td>
        <td>$dangerStatus</td>
        <td>$speed</td>
        <td>$cost</td>
            
        </tr>";
    }

    DisplayButton("f_HomeLink", "Return Home", "homeBttn.png", "home");

    echo "</table>";
}

?>