<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 	<title>Timbrature Hours</title>

 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 	<style type="text/css">
 			body {
 				margin: 0;
 				font-size: 19;
 			}

 			tab1 { padding-left: 4em; }

			#main_info {
            	margin-left: 10px;
            	margin-bottom: 25px;
            	margin-top: 0px;
            }

            #topdiv {
            	text-align: center;
            	background: #EB3349;  /* fallback for old browsers */
				background: -webkit-linear-gradient(to right, #F45C43, #EB3349);  /* Chrome 10-25, Safari 5.1-6 */
				background: linear-gradient(to right, #F45C43, #EB3349); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            	color: white;
            	top: 0;
            	height: 50px;
            	vertical-align: middle;
            	line-height: 50px;
            	font-weight: bold;
            }

            #labelfile {
            	font-size: 14px;
            }

            input[type=submit] {
			    padding:5px 15px; 
			    background: #2196F3;  /* fallback for old browsers */
				border:0 none;
			    color: white;
			    cursor:pointer;
			    -webkit-border-radius: 5px;
			    border-radius: 5px;
			    font-size: 14px;
			}

			input[type='file'] {
				display: none;
			}

			.input-wrapper label {
			  background-color: #2196F3;
			  border-radius: 5px;
			  color: #fff;
			  margin: 10px;
			  padding: 6px 20px
			}

			.input-wrapper label:hover {
			  background-color: #2980b9
			}

			/* The container */
			.container {
			    display: block;
			    position: relative;
			    padding-left: 35px;
			    margin-bottom: 12px;
			    cursor: pointer;
			    font-size: 20px;
			    -webkit-user-select: none;
			    -moz-user-select: none;
			    -ms-user-select: none;
			    user-select: none;
			}

			/* Hide the browser's default radio button */
			.container input {
			    position: absolute;
			    opacity: 0;
			    cursor: pointer;
			}

			/* Create a custom radio button */
			.checkmark {
			    position: absolute;
			    top: 0;
			    left: 0;
			    height: 25px;
			    width: 25px;
			    background-color: #eee;
			    border-radius: 50%;
			}

			/* On mouse-over, add a grey background color */
			.container:hover input ~ .checkmark {
			    background-color: #ccc;
			}

			/* When the radio button is checked, add a blue background */
			.container input:checked ~ .checkmark {
			    background-color: #2196F3;
			}

			/* Create the indicator (the dot/circle - hidden when not checked) */
			.checkmark:after {
			    content: "";
			    position: absolute;
			    display: none;
			}

			/* Show the indicator (dot/circle) when checked */
			.container input:checked ~ .checkmark:after {
			    display: block;
			}

			/* Style the indicator (dot/circle) */
			.container .checkmark:after {
			 	top: 9px;
				left: 9px;
				width: 8px;
				height: 8px;
				border-radius: 50%;
				background: white;
			}

        </style>
</head>

<body>
<?php 
if (!empty($_GET["error"]) && $_GET["error"] == "nofile") {
?>
<div id="topdiv">
	Please, select a file before. 
</div>
<?php } ?>

<div id="main_info">

<br>
Get your timbrature: <a href="http://143.225.200.247/iostimbrature" target="_blank">http://143.225.200.247/iostimbrature</a>
<br><br>
Save it as a PDF.
<br>
<form action="checkpdf.php" method="post" enctype="multipart/form-data">
<p>
	Cohort:
<p/>

<p>
	<label class="container">Morning
	  <input type="radio" checked="checked" name="cohort" value="Morning">
	  <span class="checkmark"></span>
	</label>

	<label class="container">Afternoon
	  <input type="radio" name="cohort" value="Afternoon">
	  <span class="checkmark"></span>
	</label>
	<label class="container">Master
	  <input type="radio" name="cohort" value="Master">
	  <span class="checkmark"></span>
	</label>
</p>

Upload the PDF here:
<p>
<div class='input-wrapper'>
  <label for='input-file' id="labelfile">
    Select a file
  </label>
  <input id='input-file' type='file' value='' name="file" />
  <span id='file-name'></span>
</div>
</p>
<br>
<p><input type="submit" value="Upload" style="height: 30px;" /></p>

</form>
</div>
</body>
</html>

<script type="text/javascript">
	$('#topdiv').delay(4000).fadeOut('slow');

	var $input    = document.getElementById('input-file'),
    $fileName = document.getElementById('file-name');

	$input.addEventListener('change', function(){
		$fileName.textContent = this.value;
	});
</script>