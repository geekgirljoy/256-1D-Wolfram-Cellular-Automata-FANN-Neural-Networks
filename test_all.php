<?php
function test($ann, $test_data) {
	$train_file = (dirname(__FILE__) . '/' . $ann . '_float.net');
	if (!is_file($train_file)) {
		echo 'The file ' . $ann . '_float.net has not been created! Please run train_all.php to generate it.' . PHP_EOL;
	} else{
		$ann = fann_create_from_file($train_file);
		if ($ann) {
			$calc_out = fann_run($ann, $test_data);

			if($calc_out[0] > 0){
			    $calc_out = 1;
			}
			elseif($calc_out[0] <= 0){
				$calc_out = -1;
			}
			
			fann_destroy($ann);
			
		    return $calc_out;
		} else {
			die("Invalid file format" . PHP_EOL);
		}
	}
}


for($i = 0; $i <= 255; $i++){
	echo "Rule $i (-1, -1 , -1) -> ";
	echo test("rule_$i", array(-1, -1, -1)) . PHP_EOL;
}



?>