<?php

function createButton($buttonNumber, $label, $type = 'button') {
    // Define button classes
    $buttonClasses = [
        1 => 'button-style-1',
        2 => 'button-style-2',
        3 => 'button-style-3',
    ];

    // Check if the button number is valid
    if (isset($buttonClasses[$buttonNumber])) {
        $class = $buttonClasses[$buttonNumber];
        $type = ($buttonNumber === 1) ? 'submit' : 'button'; // Set submit type for button 1
        return "<button type='$type' class='$class'>$label</button>\n";
    } else {
        return "Invalid button number: $buttonNumber. Please use 1, 2, or 3.<br>";
    }
}

?>
