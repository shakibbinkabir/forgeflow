<?php
use ForgeFlow\Models\User;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $userModel = new User();
    $user = $userModel->authenticate($email, $password);
    
    if ($user) {
        Auth::login($user);
        redirect('/');
    } else {
        $error = 'Invalid email or password';
    }
}

// If already logged in, redirect to dashboard
if (Auth::check()) {
    redirect('/');
}

view('login', ['error' => $error ?? null]);