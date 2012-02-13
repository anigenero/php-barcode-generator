<?php
    
    //Include the barcode script
    
    include_once '../barcode.php';
    
    //Handle if text posted
	
	if($_POST['text']) {
		
        //Create the barcode
        
		$img			=	code128BarCode($_POST['text'], 1);
		
        //Start output buffer to capture the image
        //Output PNG image
        
		ob_start();
		imagepng($img);
		
        //Get the image from the output buffer
        
		$output_img		=	ob_get_clean();
		
	}
?>

<!DOCTYPE html>

<html lang="en-US">
	
	<head>
		
		<title>Barcode Generator</title>
		
	</head>
	
<body>
	
	<form action="barcode.php" method="post">
		
		Enter Your Text Here: <input type="text" style="width:200px;" name="text" />
		<input type="submit" value="Create Barcode >" />
		
	</form>
	
	<br /><br /><br /><br />
	
	<?php if($_POST['text']) echo '<img src="data:image/png;base64,' . base64_encode($output_img) . '" />'; ?>
	
</body>
	
</html>