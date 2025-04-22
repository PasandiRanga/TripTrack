<?php 
// Prevent any output before JSON response
header('Content-Type: application/json');

$busData = $data['bus'];
$distanceData = $data['distance'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $from = $data['from'];
    $to = $data['to'];
    $selectedBus = $data['selectedBus'];
    $License_id = $selectedBus['License_id'];
    $leastPrice = 0;
    $pricePerSeat = 0;

    // Find the selected bus
    foreach($busData as $bus) {
        if ($bus['License_id'] === $License_id) {
            $selectedBus = $bus;
            $busType = $bus['passengers'];
            $leastPrice = $bus['priceperkm'];
            break;
        }
    }

    // Calculate the price per seat based on the distance
    if($selectedBus['destination'] == trim($to) && $selectedBus['start_location'] !=  trim($from)) {
        // Full journey minus distance from start to boarding point
        $Sdistance = 0;
        $Tdistance = 0;
        
        foreach($distanceData as $dist) {
            if($dist['start'] == $selectedBus['start_location'] && trim($dist['location']) == trim($selectedBus['destination'])) {
                $Sdistance = $dist['distance'];
                break;
            }
        }
        foreach($distanceData as $dist) {
            if($dist['start'] == $selectedBus['start_location'] && trim($dist['location']) == trim($from)) {
                $Tdistance = $dist['distance'];
                break;
            }
        }
        $Fdistance = $Sdistance - $Tdistance;
        $pricePerSeat = $leastPrice * $Fdistance;
    } 
    else if($selectedBus['destination'] == trim($to) && $selectedBus['start_location'] ==  trim($from)) {
        // Full journey price
        $pricePerSeat = $selectedBus['price'];
    }
    else if($selectedBus['destination'] != trim($to) && $selectedBus['start_location'] != trim($from)) {
        // Partial journey between two intermediate stops
        $Sdistance = 0;
        $Tdistance = 0;
        
        foreach($distanceData as $dist) {
            if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($to)) {
                $Sdistance = $dist['distance'];
                break;
            }
        }
        foreach($distanceData as $dist) {
            if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($from)) {
                $Tdistance = $dist['distance'];
                break;
            }
        }
        $Fdistance = $Sdistance - $Tdistance;
        $pricePerSeat = $leastPrice * $Fdistance;
    }
    else if($selectedBus['start_location'] == trim($from) && $selectedBus['destination'] != trim($to)) {
        // Journey from start to intermediate stop
        foreach($distanceData as $dist) {
            if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($to)) {
                $Fdistance = $dist['distance'];
                break;
            }
        }
        $pricePerSeat = $leastPrice * $Fdistance;
    }

    // Ensure we return a valid number
    $pricePerSeat = max(0, floatval($pricePerSeat));
    
    // Return JSON response
    echo json_encode(['price' => $pricePerSeat]);
    exit;
}

// Return error if not POST request
echo json_encode(['error' => 'Invalid request method']);
?>