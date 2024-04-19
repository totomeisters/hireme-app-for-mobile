<?php

class UserDetails {
    private $userID;
    private $username;
    private $password;
    private $email;
    private $role;
    private $token;

    public function __construct($userID, $username, $password, $email, $role, $token) {
        $this->userID = $userID;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;
        $this->token = $token;
    }

    // Getters
    public function getUserID() {
        return $this->userID;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getToken() {
        return $this->token;
    }

    // Setters
    public function setUserID($userID) {
        $this->userID = $userID;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setToken($token) {
        $this->token = $token;
    }
}
