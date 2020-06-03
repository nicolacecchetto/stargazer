<!DOCTYPE html>
<html lang="en">
    <head>
		<title> StarGazer </title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=base_url('style.css')?>">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<script>
			$(document).ready(function(){
  				$('[data-toggle="tooltip"]').tooltip();   
			});
		</script>
    </head>
    <body class="container-fluid bg-1">
        <div class="container-fluid title">
			<h1><?php echo $title?></h1>
		</div>
        