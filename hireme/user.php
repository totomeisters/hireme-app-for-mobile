<?php
class UserService
{
    function user_profile($userId)
    {
        $conn = new ConnectDb();
        if ($conn->get_db()) {
            $sql = "SELECT * FROM `jobseekers` a, `users` b WHERE a.UserID = '$userId' && a.UserID = b.UserID;";
            $result = mysqli_query($conn->get_db(), $sql);
            if (mysqli_num_rows($result) == 1) {
                while ($row = mysqli_fetch_assoc($result)) {
                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['user_id'] = $row['UserID'];
                    $response['first_name'] = $row['FirstName'];
                    $response['last_name'] = $row['LastName'];
                    $response['birth_date'] = $row['BirthDate'];
                    $response['address'] = $row['Address'];
                    $response['contact_number'] = $row['ContactNumber'];
                    $response['email'] = $row['Email'];
                    $response['username'] = $row['Username'];
                    $response['message'] = "User data retrieved!";
                    return json_encode($response, JSON_PRETTY_PRINT);
                }
            } else {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "User does not exist!";
                return json_encode($response, JSON_PRETTY_PRINT);
            }

        } else {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No DB Connection!";
            return json_encode($response, JSON_PRETTY_PRINT);
        }
    }
}