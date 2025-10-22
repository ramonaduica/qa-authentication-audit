<?php
// Set up database connection (SQLite for simplicity)
$db_file = 'auth_app_data.sqlite';
try {
    // Check if the database file exists. If not, it will be created.
    $db = new PDO("sqlite:$db_file");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the 'users' table if it doesn't exist.
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$message = '';
$current_user = null;

// --- LOGIN LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert"><p>Please fill in all fields.</p></div>';
    } else {
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $current_user = $user['username'];
            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert"><p>Login Successful! Welcome, ' . htmlspecialchars($current_user) . '.</p></div>';
        } else {
            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert"><p>Invalid credentials provided. Please try again.</p></div>';
        }
    }
}

// --- REGISTRATION LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_submit'])) {
    $username = trim($_POST['reg_username']);
    $password = $_POST['reg_password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if (empty($username) || empty($password)) {
        $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert"><p>Please fill in all fields.</p></div>';
    } 
    
    else {
        try {
            $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
            $stmt->execute([':username' => $username, ':password' => $hashed_password]);
            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-4" role="alert"><p>Registration successful! You can now log in.</p></div>';
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // SQLite unique constraint violation code
                $message = '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-4" role="alert"><p>Username already exists. Please choose another.</p></div>';
            } else {
                $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-4" role="alert"><p>An unexpected database error occurred.</p></div>';
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QA Target: User Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .card { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- HEADER AREA -->
        <div class="lg:col-span-2">
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-6">QA Target Application</h1>
            <p class="text-center text-gray-600 mb-6">Test the Registration and Login features below.</p>
            <?= $message ?>
        </div>

        <!-- REGISTRATION CARD -->
        <div class="card bg-white p-8 rounded-xl border border-gray-200">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">New User Registration</h2>
            <form method="POST">
                <input type="hidden" name="register_submit" value="1">
                
                <div class="mb-4">
                    <label for="reg_username" class="block text-sm font-medium text-gray-700 mb-1">Username (Email)</label>
                    <input type="email" id="reg_username" name="reg_username" placeholder="testuser@example.com" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <p class="text-xs text-gray-500 mt-1">Accepts any unique email format.</p>
                </div>
                
                <div class="mb-6">
                    <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="reg_password" name="reg_password" placeholder="Minimum 8 characters (supposedly)" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-150 shadow-md">
                    Register Account
                </button>
            </form>
        </div>

        <!-- LOGIN CARD -->
        <div class="card bg-white p-8 rounded-xl border border-gray-200">
            <h2 class="text-2xl font-semibold mb-6 text-gray-700">Existing User Login</h2>
            <form method="POST">
                <input type="hidden" name="login_submit" value="1">

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="email" id="username" name="username" placeholder="Enter your registered email" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="Your password" required 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                </div>
                
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg font-semibold hover:bg-green-700 transition duration-150 shadow-md">
                    Log In
                </button>
            </form>
        </div>
        
    </div>

    <?php if ($current_user): ?>
        <div class="fixed bottom-4 right-4 bg-gray-800 text-white p-3 rounded-lg text-sm shadow-xl">
            Currently logged in as: <strong><?= htmlspecialchars($current_user) ?></strong>
        </div>
    <?php endif; ?>

</body>
</html>
