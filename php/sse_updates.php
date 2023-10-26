<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

//doma bija automatiski skatit vai nav jauni notifi, bet nemaku to izdarit
while (true) {
    // Simulate an update
    

    
    //ignore everything here
    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();
    
    sleep(2); // Simulate a delay before the next update
}
?>