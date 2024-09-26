<?php

class AuthService
{
    function log_in($username, $password)
    {
        $conn = new ConnectDb();
        if ($conn->get_db()) {
            if ($username == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your Username!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($password == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your password!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else {
                $sql = "SELECT UserID FROM `users` WHERE Username ='$username' AND Password = '$password'";
                $result = mysqli_query($conn->get_db(), $sql);
                if (mysqli_num_rows($result) == 1) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        header("Content-Type: JSON");
                        $response['verdict'] = true;
                        $response['user_id'] = $row['UserID'];
                        $response['message'] = "User logged in!";
                        return json_encode($response, JSON_PRETTY_PRINT);
                    }
                } else {
                    header("Content-Type: JSON");
                    $response['verdict'] = false;
                    $response['message'] = "Log in failed!";
                    return json_encode($response, JSON_PRETTY_PRINT);
                }
            }
        } else {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No DB Connection!";
            return json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    function register(
        $username,
        $password,
        $email,
        $fname,
        $lname,
        $bdate,
        $address,
        $contactNum
    ) {
        $conn = new ConnectDb();
        $mailDb = new MailService();
        if ($conn->get_db()) {
            if ($username == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your Username!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($password == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your Password!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($fname == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your First Name!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($email == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your email address!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($lname == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your Last Name!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($bdate == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your birthdate!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($address == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your address!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else if ($contactNum == "") {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Please enter your contact number!";
                return json_encode($response, JSON_PRETTY_PRINT);
            } else {
                //Check username availability
                $uname_sql = "SELECT Username FROM `users` WHERE Username ='$username'";

                if (mysqli_query($conn->get_db(), $uname_sql)) {
                    $uname_result = mysqli_query($conn->get_db(), $uname_sql);
                    if (mysqli_num_rows($uname_result) == 1) {
                        header("Content-Type: JSON");
                        $response['verdict'] = false;
                        $response['message'] = "Username already used!";
                        return json_encode($response, JSON_PRETTY_PRINT);
                    } else {
                        //Create User account
                        $rand = substr(md5(microtime()), rand(0, 26), 6);
                        $sql = "INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `Role`, `Token`) VALUES (NULL, '$username', '$password', '$email', 'User', '$rand')";

                        if (mysqli_query($conn->get_db(), $sql)) {
                            $sql = "SELECT UserID FROM `users` WHERE Username ='$username'";
                            $result = mysqli_query($conn->get_db(), $sql);
                            
                            if (mysqli_num_rows($result) == 1) {
                                while ($row = mysqli_fetch_assoc($result)) {

                                    $userId = $row['UserID'];

                                    //Create jobseeker profile
                                    $sql = "INSERT INTO `jobseekers` (`JobSeekerID`, `UserID`, `FirstName`, `LastName`, `BirthDate`, `Address`, `ContactNumber`) VALUES (NULL, '$userId', '$fname', '$lname', '$bdate', '$address', '$contactNum');";

                                    if (mysqli_query($conn->get_db(), $sql)) {
                                        if ($mailDb->sendMail($email, $rand)) {
                                            header("Content-Type: JSON");
                                            $response['verdict'] = true;
                                            $response['user_id'] = $userId;
                                            $response['message'] = "Job seeker profile created";
                                            return json_encode($response, JSON_PRETTY_PRINT);
                                        } else {
                                            header("Content-Type: JSON");
                                            $response['verdict'] = false;
                                            $response['message'] = "SMTP failed!";
                                            return json_encode($response, JSON_PRETTY_PRINT);
                                        }
                                    } else {
                                        header("Content-Type: JSON");
                                        $response['verdict'] = false;
                                        $response['message'] = "Registrated failed!";
                                        return json_encode($response, JSON_PRETTY_PRINT);
                                    }
                                }
                            }
                        } else {
                            header("Content-Type: JSON");
                            $response['verdict'] = false;
                            $response['message'] = "Registrated failed!";
                            return json_encode($response, JSON_PRETTY_PRINT);
                        }
                    }
                }
            }
        } else {
            header("Content-Type: JSON");
            $response['verdict'] = false;
            $response['message'] = "No DB Connection!";
            return json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    function user_verification($userId, $otp)
    {
        $conn = new ConnectDb();
        if ($conn->get_db()) {
            $sql = "SELECT UserID FROM `users` WHERE UserID = '$userId' && Token = '$otp';";
            $result = mysqli_query($conn->get_db(), $sql);
            if (mysqli_num_rows($result) == 1) {
                while ($row = mysqli_fetch_assoc($result)) {
                    header("Content-Type: JSON");
                    $response['verdict'] = true;
                    $response['user_id'] = $row['UserID'];
                    $response['message'] = "User confirmed!";
                    return json_encode($response, JSON_PRETTY_PRINT);
                }
            } else {
                header("Content-Type: JSON");
                $response['verdict'] = false;
                $response['message'] = "Token is incorrect!";
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