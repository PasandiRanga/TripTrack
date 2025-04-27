function flash($name, $message = '') {
    // Set a flash message
    if (!empty($message)) {
        $_SESSION[$name] = $message;
    } 
    // Return and clear the message if it exists
    else if (isset($_SESSION[$name])) {
        $message = $_SESSION[$name];
        unset($_SESSION[$name]);
        return $message;
    }
    return '';
}