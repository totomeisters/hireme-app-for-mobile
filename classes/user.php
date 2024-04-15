<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/userdetails.php';

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

    public function rememberUser($username) {
        try {
            $token = bin2hex(random_bytes(32));
            $stmt = $this->conn->prepare("UPDATE Users SET Token=? WHERE Username=?");
            $stmt->bind_param("ss", $token, $username);
            $result = $stmt->execute();
            $stmt->close();
            if ($result) {
                setcookie('remember_me', $token, time() + (86400 * 30)); // cookie expires in 30 days
                return true;
            } else {
                // handle error
                return false;
            }
        } catch (Exception $e) {
            // handle exception
            return false;
        }
    }

    public function autoLogin() {
        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            try {
                // Retrieve user based on token
                $stmt = $this->conn->prepare("SELECT * FROM Users WHERE Token=?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
    
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
    
                    // Validate token
                    if (password_verify($token, $user['Token'])) {
                        // Token is valid, start session
                        if (session_status() !== PHP_SESSION_ACTIVE) {
                            session_start();
                            session_regenerate_id(true); // Regenerate session ID to prevent session fixation
                        }
                        $_SESSION['loggedin'] = true;
                        return true;
                    } else {
                        // Token is invalid, delete the cookie
                        setcookie('remember_me', '', time() - 3600);
                        return false;
                    }
                } else {
                    // Token not found, delete the cookie
                    setcookie('remember_me', '', time() - 3600, '/', '', true, true);
                    return false;
                }
            } catch (Exception $e) {
                // Log the error or display a user-friendly message
                error_log("Database error: " . $e->getMessage());
                return false;
            }
        }
        return false;
    }    

    public function login($username, $password) {
        try {

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $query = "SELECT Password FROM Users WHERE Email=?";
                $userDetails = $this->getUserDetailsUsingEmail($username);
                if ($userDetails) {
                    $username = $userDetails->getUsername();
                    $_SESSION['username'] = $username;
                } else {
                    $msg = array('status' => 'error', 'message' => 'User details not found.');
                    return $msg;
                    exit();
                }
            } else {
                $query = "SELECT Password FROM Users WHERE Username=?";
                $_SESSION['username'] = $username;
            }

            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();


            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $hashedPassword = $row['Password'];
                if (password_verify($password, $hashedPassword)) {
                    if (session_status() !== PHP_SESSION_ACTIVE) {
                        session_start();
                    }
                    $_SESSION['loggedin'] = true;
                    return true;
                } else {
                    $msg = array('status' => 'error', 'message' => 'Wrong password.');
                    return $msg;
                }
            } else {
                $msg = array('status' => 'error', 'message' => 'User not found.');
                return $msg;
            }
        } catch (Exception $e) {
            $msg = array('status' => 'error', 'message' => 'Login try-catch failed.');
            return $msg;
        }
    }

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
    
}
?>
