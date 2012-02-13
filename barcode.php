<?php
	
	/*
	 
		Code 128 Barcode Encoder for PHP
		Copyright (C) 2012 Robin Schultz (www.cunae.com)
		
		This program is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.
		
		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
		
		You should have received a copy of the GNU General Public License
		along with this program.  If not, see <http://www.gnu.org/licenses/>
	 
	*/
	
	/*
        
        code128BarCode( code , density )
        
            --code          -   the alphanumeric string to be outputted
            --density       -   the width of the bar/space (in pixels). Default is 1 pixel
      
    */
	
	//Defines the starting base for Code 128 A, B, C
	
    define('CODE128A_START_BASE', 103);
	define('CODE128B_START_BASE', 104);
    define('CODE128C_START_BASE', 105);
	define('STOP', 106);
	
	
	
	//Creates a Code 128 barcode (currently only Code B)
	//Function returns an image
	
	function code128BarCode ( $code , $density = 1 ) {
		
		//Creates an array for alphanumeric codes
		//Formatted as numerical representations of "B S B S B S", where B is the number of lines and S is the number of spaces
		
		$code128_bar_codes  	= 	array(
									212222, 222122, 222221, 121223, 121322, 131222, 122213, 122312, 132212, 221213, 221312, 231212, 112232, 122132, 122231, 113222, 123122, 123221, 223211, 221132, 221231,
									213212, 223112, 312131, 311222, 321122, 321221, 312212, 322112, 322211, 212123, 212321, 232121, 111323, 131123, 131321, 112313, 132113, 132311, 211313, 231113, 231311,
									112133, 112331, 132131, 113123, 113321, 133121, 313121, 211331, 231131, 213113, 213311, 213131, 311123, 311321, 331121, 312113, 312311, 332111, 314111, 221411, 431111,
									111224, 111422, 121124, 121421, 141122, 141221, 112214, 112412, 122114, 122411, 142112, 142211, 241211, 221114, 413111, 241112, 134111, 111242, 121142, 121241, 114212,
									124112, 124211, 411212, 421112, 421211, 212141, 214121, 412121, 111143, 111341, 131141, 114113, 114311, 411113, 411311, 113141, 114131, 311141, 411131, 211412, 211214,
									211232, 23311120
								);
		
		//Get the width and height of the barcode
		//Determine the height of the barcode, which is >= .5 inches
		
		$width			=	(((11 * strlen($code)) + 35) * ($density/72)); // density/72 determines bar width at image DPI of 72
		$height			=	($width * .15 > .7) ? $width * .15 : .7;
		
		$px_width		=	round($width * 72);
		$px_height		=	($height * 72);
		
		//Create a true color image at the specified height and width
		//Allocate white and black colors
		
		$img		=	imagecreatetruecolor($px_width, $px_height);
		$white     	=	imagecolorallocate($img, 255, 255, 255);
		$black     	=	imagecolorallocate($img, 0, 0, 0);
		
		//Fill the image white
		//Set the line thickness (based on $density)
		
		imagefill($img, 0, 0, $white);
		imagesetthickness($img, $density);
		
		//Create the checksum integer and the encoding array
		//Both will be assembled in the loop
		
		$checksum	=	CODE128B_START_BASE;
		$encoding	=	array($code128_bar_codes[CODE128B_START_BASE]);
		
		//Add Code 128 values from ASCII values found in $code
		
		for($i = 0; $i < strlen($code); $i++) {
			
			//Add checksum value of character
			
			$checksum	+=	(ord(substr($code, $i, 1)) - 32) * ($i + 1);
			
			//Add Code 128 values from ASCII values found in $code
			//Position is array is ASCII - 32
			
			array_push($encoding, $code128_bar_codes[(ord(substr($code, $i, 1))) - 32]);
			
		}
		
		//Insert the checksum character (remainder of $checksum/103) and STOP value
				
		array_push($encoding, $code128_bar_codes[$checksum%103]);
		array_push($encoding, $code128_bar_codes[STOP]);
		
		//Implode the array as string
		
		$enc_str	=	implode($encoding);
		
		//Assemble the barcode
		
		for($i = 0, $x = 0, $inc = round(($density/72) * 100); $i < strlen($enc_str); $i++) {
			
			//Get the integer value of the string element
			
			$val	=	intval(substr($enc_str, $i, 1));
			
			//Create lines/spaces
			//Bars are generated on even sequences, spaces on odd
			
			for($n = 0; $n < $val; $n++, $x+=$inc) { if($i%2 == 0) imageline($img, $x, 0, $x, $px_height, $black); }
			
		}
		
		//Return the image
		
		return $img;
		
	}
	
?>