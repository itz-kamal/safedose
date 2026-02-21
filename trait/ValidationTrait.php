<?php

trait ValidationTrait {
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validatePhoneNumber($phoneNumber) {
        return preg_match('/^0\d{10}$/', $phoneNumber);
    }

    public function validatePassword($password) {
        return strlen($password) >= 6;
    }
}