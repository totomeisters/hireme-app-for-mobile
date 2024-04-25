<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/userdetails.php';

require_once '../assets/vendor/libs/phpmailer/src/PHPMailer.php';
require_once '../assets/vendor/libs/phpmailer/src/SMTP.php';
require_once '../assets/vendor/libs/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addUser($username, $password, $email, $role) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("INSERT INTO Users (Username, Password, Email, Role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $hashedPassword, $email, $role);
            $result = $stmt->execute();
            $stmt->close();
            if (!$result) {
                // handle error
                return false;
            }
            return true;
        } catch (Exception $e) {
            // handle exception
            return false;
        }
    }

    // public function rememberUser($username) {
    //     try {
    //         $token = bin2hex(random_bytes(32));
    //         $stmt = $this->conn->prepare("UPDATE Users SET Token=? WHERE Username=?");
    //         $stmt->bind_param("ss", $token, $username);
    //         $result = $stmt->execute();
    //         $stmt->close();
    //         if ($result) {
    //             setcookie('remember_me', $token, time() + (86400 * 30)); // cookie expires in 30 days
    //             return true;
    //         } else {
    //             // handle error
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         // handle exception
    //         return false;
    //     }
    // }

    // public function autoLogin() {
    //     if (isset($_COOKIE['remember_me'])) {
    //         $token = $_COOKIE['remember_me'];
    //         try {
    //             // Retrieve user based on token
    //             $stmt = $this->conn->prepare("SELECT * FROM Users WHERE Token=?");
    //             $stmt->bind_param("s", $token);
    //             $stmt->execute();
    //             $result = $stmt->get_result();
    //             $stmt->close();
    
    //             if ($result->num_rows > 0) {
    //                 $user = $result->fetch_assoc();
    
    //                 // Validate token
    //                 if (password_verify($token, $user['Token'])) {
    //                     // Token is valid, start session
    //                     if (session_status() !== PHP_SESSION_ACTIVE) {
    //                         session_start();
    //                         session_regenerate_id(true); // Regenerate session ID to prevent session fixation
    //                     }
    //                     $_SESSION['loggedin'] = true;
    //                     return true;
    //                 } else {
    //                     // Token is invalid, delete the cookie
    //                     setcookie('remember_me', '', time() - 3600);
    //                     return false;
    //                 }
    //             } else {
    //                 // Token not found, delete the cookie
    //                 setcookie('remember_me', '', time() - 3600, '/', '', true, true);
    //                 return false;
    //             }
    //         } catch (Exception $e) {
    //             // Log the error or display a user-friendly message
    //             error_log("Database error: " . $e->getMessage());
    //             return false;
    //         }
    //     }
    //     return false;
    // }    

    public function login($username, $password) {
        try {

            $hashedPassword = '';
            
            if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            }

            $query = "SELECT Password FROM Users WHERE Username = ?";
            $userDetails = $this->getUserDetails($username);
            $_SESSION['username'] = $username;

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $query = "SELECT Password FROM Users WHERE Email = ?";
                $userDetails = $this->getUserDetailsUsingEmail($username);
                if ($userDetails) {
                    $usernamee = $userDetails->getUsername();
                    $_SESSION['username'] = $usernamee;
                    // $msg = $query.$username;
                    // return $msg;
                } else {
                    $msg = 'User details not found with email provided.';
                    return $msg;
                }
            }
            // else {
            //     $msg = $query;
            //     return $msg;
            // }

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            $stmt->close();


            if ($hashedPassword) {
                if (password_verify($password, $hashedPassword)) {
                    return true;
                } else {
                    $msg = 'Wrong password.';
                    return $msg;
                }
            } else {
                $msg = 'User not found.';
                return $msg;
            }
        } catch (Exception $e) {
            $msg = 'Login try-catch failed.';
            return $msg;
        }
    }

    // public function logintest($username, $password) {
    //     try {
    //         $stmt = $this->conn->prepare("SELECT Password FROM Users WHERE Username = ?");
    //         $stmt->bind_param("s", $username);
    //         $stmt->execute();
    //         $stmt->bind_result($hashedPassword);
    //         $stmt->fetch();
    //         $stmt->close();
            
    //         if (password_verify($password, $hashedPassword)) {
    //             // Passwords match, user is authenticated
    //             return true;
    //         } else {
    //             // Passwords don't match
    //             return false;
    //         }
    //     } catch (Exception $e) {
    //         // handle exception
    //         return false;
    //     }
    // }
    

    public function getUserDetails($username) {
        $query = "SELECT * FROM users WHERE Username = ?";
        $statement = $this->conn->prepare($query);
        $statement->bind_param('s', $username);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
    
        if (!$result) {
            return null;
        }
    
        return new UserDetails(
            $result['UserID'],
            $result['Username'],
            $result['Password'],
            $result['Email'],
            $result['Role'],
            $result['Token']
        );
    }

    public function getUserDetailsUsingEmail($email) {
        $query = "SELECT * FROM users WHERE Email = ?";
        $statement = $this->conn->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
    
        if (!$result) {
            return null;
        }
    
        return new UserDetails(
            $result['UserID'],
            $result['Username'],
            $result['Password'],
            $result['Email'],
            $result['Role'],
            $result['Token']
        );
    }
    
    public function getUserDetailsByUserID($userID) {
        $query = "SELECT * FROM users WHERE UserID = ?";
        $statement = $this->conn->prepare($query);
        $statement->bind_param('s', $userID);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
    
        if (!$result) {
            return null;
        }
    
        return new UserDetails(
            $result['UserID'],
            $result['Username'],
            $result['Password'],
            $result['Email'],
            $result['Role'],
            $result['Token']
        );
    }

    public function sendEmailForgotPassword($email){

        $token = $this->generateRandomToken(); //create random 150-character string as token 

        $mail = new PHPMailer(true); //Create an instance; passing `true` enables exceptions

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;                  //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'live.smtp.mailtrap.io';                //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'api';                                  //SMTP username
            $mail->Password   = '36d1cde56b9f332e6c39dbd40914b75f';     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption; use 465 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_SMTPS`
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom('mailtrap@demomailtrap.com', 'HireMe-App');
            $mail->addAddress($email);
        
            //Content
            $mail->isHTML(true);                                        //Set email format to HTML
            $mail->Subject = 'Password Reset';
            $mail->Body    = 'Click this link to change your password.<br><a href="localhost/hireme/resetpassword.php?token='.$token.'">Change Password</a>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
            
            //set the token that was generated
                $stmt = $this->conn->prepare("UPDATE Users SET Token = ? WHERE Email = ?");
                $stmt->bind_param("ss", $token, $email);
                $result = $stmt->execute();
                $stmt->close();
                if (!$result) {
                    $msg = "Oops.. there was an error with the database.";
                    return $msg;
                }
                else{
                    if($mail->send()){

                        return true;
                    }
                    else{
                        $msg = "Oops.. there was an error: ". $mail->ErrorInfo;
                        return $msg;
                    }
                }
        } catch (Exception $e) {
            $msg = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return $msg;
        }
    }

    private function generateRandomToken($length = 150) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $token;
    }

    public function changePasswordByToken($password, $token){
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("UPDATE Users SET Password = ?, Token = null WHERE Token = ?");
            $stmt->bind_param("ss", $hashedPassword, $token);
            $result = $stmt->execute();
            $stmt->close();
            if (!$result) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
