<?php 
function ElementaryCellularAutomata($rule, $seed){
	$seed = str_split($seed); /* Split $seed string into array */
    $seed_length = count($seed); /* How long is the seed array */
    $new_seed = "";
	
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
		if($left == 1 && $center == 1 && $right == 1){$new_seed .= $rule[0];}
		elseif($left == 1 && $center == 1 && $right == 0){$new_seed .= $rule[1];}
		elseif($left == 1 && $center == 0 && $right == 1){$new_seed .= $rule[2];}
		elseif($left == 1 && $center == 0 && $right == 0){$new_seed .= $rule[3];}
		elseif($left == 0 && $center == 1 && $right == 1){$new_seed .= $rule[4];}
		elseif($left == 0 && $center == 1 && $right == 0){$new_seed .= $rule[5];}
		elseif($left == 0 && $center == 0 && $right == 1){$new_seed .= $rule[6];}
		elseif($left == 0 && $center == 0 && $right == 0){$new_seed .= $rule[7];}
		/////////////////////////////////////////////////////////////////////////
    }

	/* Return the new seed which was created by applying the rule set */
    return $new_seed; 
}

function OutputRule(&$file, &$rule, $postion, $left, $center, $right){
	fwrite($file, "$left $center $right" . PHP_EOL);
    fwrite($file, $rule[$postion] . PHP_EOL);
}


////////////////////////////////////////////
// Output all rules
for($i = 0; $i <= 255; $i++){
	
	$file = fopen("rule_$i.data", 'w');
	fwrite($file, "8 3 1" . PHP_EOL);
	
	$rule = sprintf('%08b', $i); /* Convert rule to bin */
    $rule = str_split($rule); /* Split the binary value of $rule as a string into an array */
	
	/* Walk over the $rule array and replace 0's with -1's */
    array_walk($rule, function (&$rv){$rv = str_replace("0","-1", $rv);});

	OutputRule($file, $rule, 0, 1, 1, 1);
    OutputRule($file, $rule, 1, 1, 1, -1);
	OutputRule($file, $rule, 2, 1, -1, 1);
    OutputRule($file, $rule, 3, 1, -1, -1);
    OutputRule($file, $rule, 4, -1, 1, 1);
    OutputRule($file, $rule, 5, -1, 1, -1);
    OutputRule($file, $rule, 6, -1, -1, 1);
    OutputRule($file, $rule, 7, -1, -1, -1);	
}
echo PHP_EOL . "All Rule Files Created!" . PHP_EOL;
///////////////////////////////////////////////////////
