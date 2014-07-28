<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Version Screenshot Tool</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
</head>

<body>
<div class="container">
	<h1>Version Bubblinator Creatooooaaar</h1>
	<form target="shot"  action="shot.php"   method="post">
		<fieldset>
			<legend>Make a new version bubble image</legend>
			<div class="form-group">
				<label for="date">Choose date</label>
				<input type="text" class="form-control" id="datepicker" name="date">
				<label for="version">Version (like 1.4.2)</label>
				<input type="text" class="form-control" name="version">
				<label for="content">Content (HTML allowed)</label>
				<textarea rows="3" class="form-control" name="content"></textarea>
				<div class="checkbox">
					<label>
					  <input type="checkbox" name="download"> Also download generated image?
					</label>
				  </div>
				</div>
				<input type="hidden" value="http://pixelgrade.com/version_screenshot/version-bubble.php" name="url">
				<input type="hidden" value="616" name="w">
				<input type="hidden" value="71" name="h">
			<button type="submit" class="btn btn-default">Fire it up!</button>
		</fieldset>
	</form>
	<br>
	<iframe src="shot.php" name="shot" id="shot" width="100%" height="1000" frameborder="0"></iframe>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->

<!-- Latest compiled and minified JavaScript -->
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
 
<script>
$(function() {
$( "#datepicker" ).datepicker();
});
</script>
  
</body>
</html>
