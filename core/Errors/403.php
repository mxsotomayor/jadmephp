<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Error</title>
        <style>
            body{
                background: rgb(171, 204, 223)
            }
            .error-wrapper{
                width:90%; 
                margin:5% auto; 
                background:#eee;
                padding:10px 10px 0 10px;
                border:10px solid #86B2B8;
                border-radius:10px;
            }
            .error-header{
                margin:0px;
                background: rgb(152, 196, 199);
                padding:10px;
            }
        </style>
</head>
<body style="">
    <div class="error-wrapper">
        <h2 style="" class="error-header">Jadme: 403 Forbidden Access</h2>
		<table>
			<tr>
				<td>
                                        <?php img("own_resources/craying.jpg");?>
				</td>
				<td>
					<p>
						<?php 
						echo "<b>Error Message</b>: ".utf8_encode($e->getMessage())."<br>";
						echo "<b>Function Name</b>: <a href='javascript:void(0);'>".$e->getTrace()[0]["function"]."()</a><br>";
						echo "<b>Function Arguments</b>:<br>";
						foreach ($e->getTrace()[0]["args"] as $key => $value) {
							//echo $key."="."'$value'"."<br>";
						}
						echo "<b>Function Call Line</b>: <a href='javascript:void(0);'>".$e->getTrace()[0]["line"]."</a><br>";
						echo "<b>Current File Error</b>: <a href='javascript:void(0);'>".$e->getFile()."</a><br>";
						echo "<b>Dropped Line Exception</b>: <a href='javascript:void(0);'>".$e->getLine()."</a><br>";
						?>
					</p>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>



