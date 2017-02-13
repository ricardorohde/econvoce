<?php
function print_l($_array = null){
	if($_array){
		echo '<pre>' . print_r($_array, true) . '</pre>';
	}
}

function in_multiarray($elem, $array, $field) {
    $top = sizeof($array) - 1;
    $bottom = 0;
    while($bottom <= $top)
    {
        if($array[$bottom][$field] == $elem)
            return true;
        else 
            if(is_array($array[$bottom][$field]))
                if(in_multiarray($elem, ($array[$bottom][$field])))
                    return true;

        $bottom++;
    }        
    return false;
}
?>
