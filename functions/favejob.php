<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once '../classes/job.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job = new Job($conn);

    $jobID = htmlspecialchars($_POST['jobID']);
    $jobSeekerID = htmlspecialchars($_POST['jobSeekerID']);
    $favoriteAction = $_POST['favoriteAction'];
    $referer = $_POST['referer'];

    if($favoriteAction == 'favorite'){
        $returnstatus = $job->addFavoriteJob($jobSeekerID, $jobID);
    } elseif($favoriteAction == 'unfavorite'){
        $returnstatus = $job->deleteFavoriteJob($jobSeekerID, $jobID);
    }

    if(!$returnstatus == false){
        $status = 'success';
        $message = ($favoriteAction == 'favorite') ? 'Successfully added to favorite list.' : 'Successfully removed from favorite list.';
    } else {
        $status = 'error';
        $message = 'Error. Please try again.';
    }
    
    $response = array('status'=>$status, 'message'=>$message, 'redirect'=>$referer);

    echo json_encode($response);
    exit();
}
?>
