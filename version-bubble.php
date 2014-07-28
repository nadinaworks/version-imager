<?php
//this is the image html file
?>

<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <title>Theme Forest Version Bubble</title>
  
  <style type="text/css">
@import url(http://fonts.googleapis.com/css?family=Roboto:400,400italic,100,100italic,300,300italic,500italic,500,700,700italic,900,900italic);
@import url(http://fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,700,300);
.update-bar {
    position:relative; 
    max-width: 616px;
    
    color: white;
    font-family: sans-serif;
    font-weight:300;
    font-family: 'Roboto', sans-serif;
    
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
    padding: 9px 18px;
}

li {
    position:relative;
    display: inline-block;
    list-style: none;
    margin: 0;
    padding: 0;
}

li.version {
    width: 12%;
    font-size: 28px;
    font-family: 'Roboto Condensed', sans-serif;
}

li.date {
    top: -3px;
    width: 13%;

    font-size: 28px;
    line-height: 1.1;
}
    .day {
        float: left;
        margin-right: 6px;
        letter-spacing: -3px;
    }
    .month, .year {
        position:relative;
        top:2px;

        font-size: 11px;
        text-transform: uppercase;
    }
    .year {
        color: #828282;
    }
li.description {
    top:2px;
    width: 62%;

    font-size: 13px;
}

li.changelog {
    top: 6px;
    right: 3px;
    width: 10%;
    float:right;
    
    font-size: 11px;
    text-transform: uppercase;
}

.small {
    font-size: 65%;
}

em {
    font-style: normal;
    color: #07d3f8;
    font-weight: 400;
}

.muted {
    color: #828282;
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

$day = '16';
$month = 'Aug';
$year = '2014';
if (isset($_REQUEST['date'])) {
	$date = DateTime::createFromFormat('m/d/Y',urldecode($_REQUEST['date']));
	
	$day = $date->format('d');
	$month = strtoupper($date->format('M'));
	$year = $date->format('Y');
    
}
$content = 'Added <em>WooCommerce</em> support, NEW Theme Options panel, bordered layout and waves edges option.';
if (isset($_REQUEST['content'])) {
    $content = urldecode($_REQUEST['content']);
}

?>

<body>
  <div class="update-bar">
    <ul>
        <li class="version"><span class="small">v</span><?php echo $version ?>.<span class="small"><?php echo $version_s ?></span></li>
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