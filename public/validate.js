function validateName(input, errorId) {
  let value = input.value.trim();
  if (value.length === 0) {
    setError(input, errorId, "Full name is required");
    return false;
  }
  setError(input, errorId, "");
  return true;
}

function validateEmail(input, errorId) {
  let value = input.value.trim();
  if (value.length === 0) {
    setError(input, errorId, "Email is required");
    return false;
  }
  let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(value)) {
    setError(input, errorId, "Please enter a valid email address");
    return false;
  }
  setError(input, errorId, "");
  return true;
}

function validatePhoneNumber(input, errorId) {
  let value = input.value.trim();
  if (value.length === 0) {
    setError(input, errorId, "Phone number is required");
    return false;
  }
  let phonePattern = /^0\d{10}$/;
  if (!phonePattern.test(value)) {
    setError(input, errorId, "Enter a valid phone number");
    return false;
  }
  setError(input, errorId, "");
  return true;
}

function validatePassword(input, errorId) {
  let value = input.value;
  if (value.length === 0) {
    setError(input, errorId, "Password is required");
    return false;
  }
  if (value.length < 8) {
    setError(input, errorId, "Must be at least 8 characters");
    return false;
  }
  if (!/[A-Z]/.test(value)) {
    setError(input, errorId, "Must include an uppercase letter");
    return false;
  }
  if (!/[0-9]/.test(value)) {
    setError(input, errorId, "Must include a number");
    return false;
  }
  if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) {
    setError(input, errorId, "Must include a special character");
    return false;
  }
  setError(input, errorId, "");
  return true;
}

function validatePasswordMatch(passwordInput, confirmPasswordInput, errorId) {
  let value = confirmPasswordInput.value;
  if (value.length === 0) {
    setError(confirmPasswordInput, errorId, "Please confirm your password");
    return false;
  }
  if (value !== passwordInput.value) {
    setError(confirmPasswordInput, errorId, "Passwords do not match");
    return false;
  }
  setError(confirmPasswordInput, errorId, "");
  return true;
}

function validateTextField(fieldId, errorId, message) {
  const el = document.getElementById(fieldId);
  if (!el.value.trim()) {
    el.classList.add("is-invalid");
    document.getElementById(errorId).textContent = message;
    return false;
  }
  el.classList.remove("is-invalid");
  document.getElementById(errorId).textContent = "";
  return true;
}

function validateSelect(fieldId, errorId, message) {
  const el = document.getElementById(fieldId);
  if (!el.value) {
    el.classList.add("is-invalid");
    document.getElementById(errorId).textContent = message;
    return false;
  }
  el.classList.remove("is-invalid");
  document.getElementById(errorId).textContent = "";
  return true;
}

function validateNumber(fieldId, errorId, label) {
  const el = document.getElementById(fieldId);
  if (el.value === "") {
    el.classList.add("is-invalid");
    document.getElementById(errorId).textContent = label + " is required.";
    return false;
  }
  if (Number(el.value) < 0) {
    el.classList.add("is-invalid");
    document.getElementById(errorId).textContent = label + " cannot be negative.";
    return false;
  }
  el.classList.remove("is-invalid");
  document.getElementById(errorId).textContent = "";
  return true;
}

function validateExpiry(fieldId, errorId) {
  const el = document.getElementById(fieldId);
  if (!el.value) {
    el.classList.add("is-invalid");
    document.getElementById(errorId).textContent = "Expiry date is required.";
    return false;
  }
  if (new Date(el.value) <= new Date()) {
    el.classList.add("is-invalid");
    document.getElementById(errorId).textContent = "Expiry date must be in the future.";
    return false;
  }
  el.classList.remove("is-invalid");
  document.getElementById(errorId).textContent = "";
  return true;
}

function setError(input, errorId, message) {
  let errorDiv = document.getElementById(errorId);
  if (message) {
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    errorDiv.textContent = message;
  } else {
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    errorDiv.textContent = "";
  }
}
