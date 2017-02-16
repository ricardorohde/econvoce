<?php
function print_l($_array = null){
	if($_array){
		echo '<pre>' . print_r($_array, true) . '</pre>';
	}
}


function in_multiarray_process($search, $field, $array, $returnindex = null) {
    if(is_array($array)){
        foreach($array as $index => $value){
            if(is_array($value)){
                if($match = in_multiarray_process($search, $field, $value, ($returnindex !== null ? $returnindex : $index))){
                    $return = $match;
                }
            }else{
                if($index == $field && $value == $search){
                    $return = ($returnindex === null ? $index : $returnindex);
                }
            }
            if(isset($return)) return '__' . $return;
        }
    }
    return false;
}

function in_multiarray($search, $field, $array, $return_item = false){
    $return = in_multiarray_process($search, $field, $array);
    
    if(!$return_item){
        return $return === false ? false : true;
    }

    return $return === false ? false : $array[str_replace('__', '', $return)];
}


?>
