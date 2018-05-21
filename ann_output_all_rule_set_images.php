<?php

function ElementaryCellularAutomata($ann, $rule, $seed) {
	$train_file = (dirname(__FILE__) . '/' . $ann . '_float.net');
	if (!is_file($train_file)) {
		echo 'The file ' . $ann . '_float.net has not been created! Please run train_all.php to generate it.' . PHP_EOL;
	} else{
		$ann = fann_create_from_file($train_file);

		if ($ann) {
		   
			$seed = str_split($seed); /* Split $seed string into array */
			
			/* Walk over the $rule array and replace 0's with -1's */
            array_walk($seed, function (&$s){$s = str_replace("0","-1", $s);});
            
			$seed_length = count($seed); /* How long is the seed array */
			$new_seed = '';

			if(strlen($rule) < 8){ /* If rule is dec */
			    $rule = sprintf('%08b', $rule); /* Convert rule to bin */
	        }$rule = str_split($rule); /* Split the binary value of $rule as a string into an array */
			

			/* For each value in the seed */
            for($i = 0; $i < $seed_length; $i++){
				$left = $center = $right = null; /* set all positions to null */

				/* $left */
				/* If this is the first element in the array */
				/* Wrap around and set $left to last element in array */
				/* Otherwise $left = the element to the left of $center */
				if($i == 0) {$left = $seed[$seed_length - 1];}
				else{$left = $seed[$i - 1];}
				
				/* $center */
				/* Set $center to the current position */
				$center = $seed[$i];
				
				/* $right */
				/* If this is the last element in the array */
				/* Wrap around and set $right to first element in array */
				/* Otherwise $right = the element to the right of $center */
				if($i + 1 >= $seed_length) {$right = $seed[0];}
				else{$right = $seed[$i + 1];}
			
				// Apply Rule ///////////////////////////////////////////////////////////
				$calc_out = fann_run($ann, array($left, $center, $right));
				if($calc_out[0] > 0){
					$new_seed .= '1';
				}
				elseif($calc_out[0] <= 0){
					$new_seed .= '0';
				}
		        /////////////////////////////////////////////////////////////////////////
            }
			//$new_seed = str_replace('-1', "0", $new_seed);

			fann_destroy($ann);

		    return $new_seed;
		} else {
			die("Invalid file format" . PHP_EOL);
		}
	}
}


// Image Size Setting //////////////////////
$full_size = pow(2, 10) + 1; // 1025
////////////////////////////////////////////

////////////////////////////////////////////
// Run through all rules
for($i = 0; $i <= 255; $i++){

    $cellular_automata = imagecreatetruecolor($full_size, $full_size); /* New Image */
	$black = imagecolorallocate($cellular_automata, 0, 0, 0);  /* Allocate Black */
    $white = imagecolorallocate($cellular_automata, 255, 255, 255); /* Allocate White */
 
    /* Create seed of zeros with a one in the exact center */
	$seed = str_repeat('0', round($full_size / 2) - 1) . '1' . str_repeat('0', round($full_size / 2) - 1);
	$current_seed = str_split($seed); /* Split the $seed string into an array */
	
	/* For each value in the current_seed array */
	foreach($current_seed as $col=>$value){
		if($value == 1){ 
		    imagesetpixel($cellular_automata, $col, 0, $white); /* Set Pixel */
		}
	}

	/* For each row and column pixel in the image */
	$rule = $i;
	for($row = 1; $row <= $full_size; $row++){
		$seed = ElementaryCellularAutomata("rule_$i", $rule, $seed); /* Compute the new $seed */

		$current_seed = str_split($seed); /* Split the $seed string into an array */
		 
		/* For each value in the current_seed array */
		foreach($current_seed as $col=>$value){
			if($value == 1){ 
				imagesetpixel($cellular_automata, $col, $row, $white); /* Set Pixel */
			}
		}
	}
	imagepng($cellular_automata, "$rule.png"); /* Output Image */
	imagedestroy($cellular_automata);/* Free memory */
}
echo PHP_EOL . "Program Complete!" . PHP_EOL;
///////////////////////////////////////////////////////




?>