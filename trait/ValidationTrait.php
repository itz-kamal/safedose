<?php

trait ValidationTrait {
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validatePhoneNumber($phoneNumber) {
        return preg_match('/^09\d{9}$/', $phoneNumber);
    }

    public function validatePassword($password) {
        return strlen($password) >= 8;
    }
}