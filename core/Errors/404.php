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
            .others{
                width:90%; 
                margin:0 auto; 
            }
            .error-header{
                margin:0px;
                background: rgb(152, 196, 199);
                padding:10px;
            }
            p.error-text{
              font-weight: none;
              font-size: 25px;
            }
        </style>
</head>
<body style="">
    <div class="error-wrapper">
        <h2 style="" class="error-header">Jadme: 404 Page Not Found</h2>
		<table>
			<tr>
				<td>
                 <?php img("_fm/56c217138f0f1.jpg")?>
				</td>
				<td>
					<p>
						<?php 
						echo "<b>Error Message</b>: <p class='error-text'>".utf8_encode($e->getMessage())."</p>";
						echo "<b>Function Name</b>: <a href='javascript:void(0);'>".$e->getTrace()[0]["function"]."()</a><br>";
						echo "<b>Function Arguments</b>:<br>";
						foreach ($e->getTrace()[0]["args"] as $key => $value) {
							echo $key."="."'$value'"."<br>";
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
    <div class="others">
        <h3>Stack trace</h3>
        <?php
        if(isset($e->current_file))
       echo "<b>Current File</b>: <a href='#'>".utf8_encode($e->current_file)."</a><br>";
        if(isset($e->custom_messages))
       echo "<b>Custom Messahe</b>: ".utf8_encode($e->custom_messages)."<br>";
   $stackTrace = $e->getTrace(); 

   $stack_position = 0;
   foreach ($stackTrace as $key => $value) {
    $stack_position+=1;
      foreach ($value as $key => $_value) {
            if(!is_array($_value)){
              if($stack_position == 3)
                echo "<b style='color:red;'>".ucwords($key)."</b>   <a href='' class='error'>".$_value."</a><br>";
              else
                echo "<b>".ucwords($key)."</b>   ".$_value."<br>";


            }
       }
       echo "<br>";
   }
    
    ?>
    </div>

</body>
</html>



