<?php
use ForgeFlow\Models\User;

Auth::requireAdmin();

$userModel = new User();

$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'role' => $_POST['role'] ?? 'Team Member',
            ];
            
            // Check if email already exists
            if ($userModel->findByEmail($userData['email'])) {
                $error = 'A user with this email already exists.';
            } else {
                $userModel->create($userData);
                redirect('/users');
            }
        }
        
        view('users/create', ['error' => $error ?? null, 'roles' => User::getRoles()]);
        break;
        
    case 'edit':
        $user = $userModel->find($id);
        if (!$user) {
            http_response_code(404);
            view('404');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'role' => $_POST['role'],
            ];
            
            if (!empty($_POST['password'])) {
                $updateData['password'] = $_POST['password'];
            }
            
            // Check if email already exists (excluding current user)
            $existingUser = $userModel->findByEmail($_POST['email']);
            if ($existingUser && $existingUser['id'] != $id) {
                $error = 'A user with this email already exists.';
            } else {
                $userModel->update($id, $updateData);
                redirect('/users');
            }
        }
        
        view('users/edit', [
            'user' => $user,
            'error' => $error ?? null,
            'roles' => User::getRoles()
        ]);
        break;
        
    case 'delete':
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Don't allow deletion of own account
            if ($id != Auth::user()['id']) {
                $userModel->delete($id);
            }
            redirect('/users');
        }
        break;
        
    default:
        $users = $userModel->findAll();
        view('users/index', ['users' => $users]);
}