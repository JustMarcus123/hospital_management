<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

include './common/simpledb.php';

$obj = new simple_db();

// $conn = $obj->connect();
// var_dump($conn);

//getting raw data from the frontend 
$raw_data = file_get_contents("php://input");
$data = json_decode($raw_data, true);


//debugging the incoming data
$response = [
    'post_data' => $_POST,
    'raw_data' => $raw_data,
    'received' => true
];

// send the response back

header('content-type:application/json');

echo json_encode($response);
