<?php
//this is the image html file
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Theme Forest Version Bubble</title>
  
  <style type="text/css">
    .update-bar {
    position:relative; 
    
    color: white;
    font-family: sans-serif;
    font-weight:300;
    
    background-color: #2e302f;
    
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    
    -webkit-box-shadow: 0 4px 0 0 #000000;
    box-shadow: 0 4px 0 0 #000000;
}

.update-bar:after {
	top: 100%;
	left: 40px;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
	border-color: rgba(47, 52, 51, 0);
	border-top-color: #2f3433;
	border-width: 12px;
	margin-left: -12px;
}

ul {
    padding: 12px 18px;
}

li {
    display: inline-block;
    list-style: none;
    margin: 0;
    padding: 0;
}

li.version {
    width: 15%;
    font-size: 28px;
}

li.date {
    width: 10%;
}

li.description {
    width: 60%;
}

li.changelog {
    width: 10%;
    float:right;
    
    font-size: 11px;
    text-transform: uppercase;
}

* { 
	-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
	-moz-box-sizing: border-box;    /* Firefox, other Gecko */
	box-sizing: border-box;         /* Opera/IE 8+ */
}
  </style>

</head>
<?php
$version = '1.0';
$version_s = '0';
if (isset($_REQUEST['version'])) {
	$temp = urldecode($_REQUEST['version']);
    $version = substr( $temp, 0, strrpos($temp,'.'));
    $version_s = substr($temp, strrpos($temp,'.')+1);
}

$day = '01';
$month = 'JAN';
$year = '1971';
if (isset($_REQUEST['date'])) {
	$date = DateTime::createFromFormat('m/d/Y',urldecode($_REQUEST['date']));
	
	$day = $date->format('d');
	$month = strtoupper($date->format('M'));
	$year = $date->format('Y');
    
}
$content = 'Some content!';
if (isset($_REQUEST['content'])) {
    $content = urldecode($_REQUEST['content']);
}

?>

<body>
  <div class="update-bar">
    <ul>
        <li class="version">v<?php echo $version ?>.<span class="small"><?php echo $version_s ?></span></li>
        <li class="date">
            <span class="day"><?php echo $day ?></span>
            <span class="month"><?php echo $month ?></span>
            <span class="year"><?php echo $year ?></span>
        </li>
        <li class="description"><?php echo $content ?></li>
        <li class="changelog"><span class="muted">View the</span> changelog</li>
    </ul>
</div>
</body>
</html>