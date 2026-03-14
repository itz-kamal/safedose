<?php

trait ValidationTrait {
  public function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  public function validatePhoneNumber($phoneNumber) {
    return preg_match('/^0\d{10}$/', $phoneNumber);
  }

  public function validatePassword($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[!@#$%^&*()\-_=+\[\]{};:\'",.<>?\/\\\\|]/', $password)) return false;
    return true;
  }
}