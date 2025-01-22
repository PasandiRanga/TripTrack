<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $from = $data['from'];
    $to = $data['to'];
    $selectedBus = $data['selectedBus'];
    $distanceData = $data['distanceData'];
    $leastPrice = $data['leastPrice'];
            // Calculate the price per seat based on the distance
            if($selectedBus['destination'] == trim($to) && $selectedBus['start_location'] !=  trim($from)) {
            // Get the distance between the two cities
            $Sdistance = 0;
            $Tdistance = 0;
            $Fdistance = 0;
            foreach($distanceData as $dist) {
                // echo($dist['start']);
                // echo($selectedBus['start_location']);
                // echo($dist['location']);
                // echo($selectedBus['destination']);
                // echo($dist['distance']);
                // echo "<br>";
                if($dist['start'] == $selectedBus['start_location'] && trim($dist['location']) == trim($selectedBus['destination'])) {
                    $Sdistance = $dist['distance'];
                    // echo("s$Sdistance");
                    break;
                }
            }
            $Fdistance = $Sdistance - $Tdistance;
            $pricePerSeat = $leastPrice * $Fdistance;
        }else if($selectedBus['destination'] == trim($to) && $selectedBus['start_location'] ==  trim($from)) {
            $pricePerSeat = $selectedBus['price'];
        }else if($selectedBus['destination'] != trim($to) && $selectedBus['start_location'] != trim($from)){
            $Sdistance = 0;
            $Tdistance = 0;
            $Fdistance = 0;
            foreach($distanceData as $dist){
                if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($to)){
                    $Sdistance = $dist['distance'];
                    break;
                }
            }
            foreach($distanceData as $dist){
                if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($from)){
                    $Tdistance = $dist['distance'];
                    break;
                }
            }
            $Fdistance = $Sdistance - $Tdistance;
            $pricePerSeat = $leastPrice * $Fdistance;
        }else if($selectedBus['start_location'] == trim($from) && $selectedBus['destination'] != trim($to)){
            foreach($distanceData as $dist){
                if($selectedBus['start_location'] == $dist['start'] && $dist['location'] == trim($to)){
                    $Fdistance = $dist['distance'];
                }
            }
            $pricePerSeat = $leastPrice * $Fdistance;
        }

    echo json_encode(['price' => $pricePerSeat]);

    }
?>