# Simple PHP User Authentication App (QA Target)

This project is a **QA testing and security audit practice app** built in PHP.
It simulates a basic user registration and login system, intentionally containing known flaws for audit and bug reporting exercises.

---

## 🧰 Tech Stack

* **Language:** PHP 8+
* **Database:** SQLite (auto-created)
* **Frontend:** TailwindCSS (CDN)
* **File:** `index.php` (single-page app)

---

## 🎯 QA Testing Objectives

| Objective            | Description                                                 |
| -------------------- | ----------------------------------------------------------- |
| ✅ Functional Testing | Validate registration and login processes                   |
| 🔒 Security Testing  | Identify weak password validation and error handling issues |
| 🧠 Usability Testing | Assess clarity of user messages and UX behavior             |

---

## 🧪 Known QA Findings

| ID         | Test Case                                               | Severity    | Description |
| ---------- | ------------------------------------------------------- | ----------- | ----------- |
| TC-REG-004 | Registration allows passwords shorter than 8 characters | 🔴 Critical |             |
| TC-LOG-003 | Login uses same error for invalid username & password   | 🟠 Moderate |             |

Full audit available in the [`QA_Audit_Report.md`](./QA_Audit_Report.md) file.
All executed test cases are listed in [`QA_Test_Cases.md`](./QA_Test_Cases.md).

---

## 🚀 How to Run the App

1. Install **XAMPP** or any local PHP environment
2. Place the project folder (`qa-authentication-audit/`) in the `htdocs` directory
3. Start Apache and open in your browser:

   ```
   http://localhost/qa-authentication-audit/index.php
   ```
4. The app automatically creates a SQLite file (`auth_app_data.sqlite`) in the same folder.

---

## 🧾 Documentation

* [`QA_Test_Cases.md`](./QA_Test_Cases.md) → Full list of test cases and results
* [`QA_Audit_Report.md`](./QA_Audit_Report.md) → Findings, vulnerabilities, and code fix recommendations

---

## 💬 Author

**Ramona-Elena Duică**
📧 duica.ramona99@gmail.com
🌐 [GitHub Profile or LinkedIn](https://www.linkedin.com/in/ramonaduica)
