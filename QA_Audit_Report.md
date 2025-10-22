# QA Audit Report: Simple PHP User Authentication App

## Project Details

| Detail | Value |
|--------|--------|
| **Project Version** | Final Integration Build (Pre-Deployment) |
| **Auditor** | Ramona-Elena Duică (QA & Software Testing) |
| **Date** | October 2025 |

---

## 🧾 Executive Summary

The application's core functionality (user registration and login) is functional, passing all positive and boundary-case tests.

However, a vulnerability audit revealed **two key flaws** that must be addressed before deployment:

- ⚠️ **CRITICAL SECURITY FLAW:** The server-side code does not enforce a minimum password length, allowing users to register with trivially weak passwords (e.g., `123`).  
- ⚠️ **MODERATE UX/SECURITY FLAW:** The login error message is generic, offering no feedback on whether a username exists — this prevents enumeration but creates UX friction.

---

## 🔍 Findings and Recommendations

### 🧩 Finding 1: Weak Password Policy (CRITICAL)

| Metric | Details |
|--------|----------|
| **Test Case** | TC-REG-004 |
| **Vulnerability** | The application allows registration with passwords shorter than 8 characters, severely compromising user account security. |
| **Impact** | High risk of account compromise via simple dictionary or brute-force attacks. |

#### ✅ Recommendation (Code Fix)
Implement a server-side check to enforce a **minimum password length** of 8 characters (or higher) during registration.  
This check must occur **before** the password is hashed and stored in the database.

**PHP Implementation Example:**
```php
if (strlen($_POST['reg_password']) < 8) {
    $message = '<p class="error-message">Password must be at least 8 characters long.</p>';
    // Abort registration here
}
// If length is OK, proceed with hashing and database insertion
```

---

### 🧩 Finding 2: Generic Login Error Message (MODERATE)

| Metric | Details |
|--------|----------|
| **Test Case** | TC-LOG-003 |
| **Vulnerability** | The application displays the same error message ("Invalid credentials provided...") for both non-existent usernames and valid usernames with wrong passwords. |
| **Impact** | While secure (prevents username enumeration), this creates a poor user experience since users cannot tell if they mistyped their username or password. |

#### ✅ Recommendation (Code/UX Improvement)
Maintain the generic error message to protect against enumeration, but **implement rate-limiting** to reduce brute-force risk.  
For production systems, add IP-based rate-limiting or temporary account lockouts after repeated failed attempts.

> For this prototype, no code change is strictly required — but document the UX limitation clearly for stakeholders.

---

## 🧠 Conclusion

The application is **fully functional** but contains a **Critical (TC-REG-004)** vulnerability due to weak password validation.  
The provided fix must be implemented before deployment.  

**Summary of Risk Levels:**

| Finding | Risk Level | Action Required |
|----------|-------------|-----------------|
| Weak Password Policy | 🔴 Critical | Immediate fix (enforce length validation) |
| Generic Login Message | 🟠 Moderate | Document UX limitation; add rate-limiting in production |

---

**Prepared by:** Ramona-Elena Duică (QA & Software Testing)  
**Audit Period:** October 2025  
**System Under Test:** PHP User Authentication App