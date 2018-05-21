<?php
function train($data, $num_input, $num_output, $num_layers, $num_neurons_hidden, $desired_error, $max_epochs, $epochs_between_reports){
	
	$ann = fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output);
	if ($ann) {
		fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC);
		fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC);
		
		$filename = dirname(__FILE__) . '/' . $data . '.data';
		if (fann_train_on_file($ann, $filename, $max_epochs, $epochs_between_reports, $desired_error)) {
			echo $data . ' trained.' . PHP_EOL;
        }
		
		if (fann_save($ann, dirname(__FILE__) . '/' . $data . '_float.net')) {
			echo $data . '_float.net saved.' . PHP_EOL;
        }
		
		fann_destroy($ann);
	}
}

// Modify this array for the rule numbers you want to train
$rules = array(30, 54, 57, 75, 147, 149, 156, 198, 201, 227, 255);
foreach($rules as $rule){
    train("rule_$rule", 3, 1, 3, 3, 0.0001, 5000000, 1);
}