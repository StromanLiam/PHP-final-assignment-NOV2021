<?php

function DisplayLabel($prompt)
{
    echo "<label>" . $prompt . "</label>";
}

function DisplayTextBox($Type, $Name, $Size, $Value = 0)
{
        echo "<input type = $Type, value = $Value name = \"$Name\" size = \"$Size\"></input>\n";   
}

function WriteHeaders($Heading="Welcome",$TitleBar="MySite")
{
    echo "
    <!doctype html> 
   <html lang = \"en\">
   <head>
       <meta charset = \"UTF-8\">
       <title>" . $TitleBar . "</title>

       <link rel=\"stylesheet\" type = \"text/css\" href = \"asstStyle.css\" />

   </head>
   <body>
       <h1>" . $Heading . "</h1>
   ";
}

function displayContactInfo()
{
    echo "<footer>Questions? Comments?
    <a href = \"mailto:Liam.Stroman@student.sl.on.ca\">Liam.Stroman@student.sl.on.ca
    </a></footer>";
}

function WriteFooters()
{
    displayContactInfo();

    echo "</body></html>";
}

function DisplayImage($filename, $alt, $height= 'auto', $width='auto')
{
    echo "<img src = \"$filename\" height=\"$height\" width=\"$width\" alt=\"$alt\"/>";
}

 

function DisplayButton($name, $text="Null", $filename = "", $alt = "")
{
    if ($filename == "" || $alt == "")
    {
        echo "<button type=Submit name=\"$name\">$text</button>";
    }
    else
    {
        echo "<button type=Submit name=\"$name\">"; 
        DisplayImage($filename, $alt, 40);
        echo "</button>";
    }
    
}

function CreateConnectionObject()
{
    $fh = fopen('auth.txt','r');
    $Host =  trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh)); 
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password,$Database,$Port);
    // if the connection and authentication are successful, 
    // the error number is 0
    // connect_errno is a public attribute of the mysqli class.
    if ($mysqlObj->connect_errno != 0) 
    {
     echo "<p>Connection failed. Unable to open database $Database. Error: "
              . $mysqlObj->connect_error . "</p>";
     // stop executing the php script
     exit;
    }
    return ($mysqlObj);
}


?>