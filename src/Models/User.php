<?php
namespace ForgeFlow\Models;

class User extends BaseModel
{
    protected $table = 'users';
    
    const ROLE_ADMIN = 'Admin';
    const ROLE_TEAM_MEMBER = 'Team Member';

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_TEAM_MEMBER,
        ];
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function create($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return parent::create($data);
    }

    public function update($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        return parent::update($id, $data);
    }

    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === self::ROLE_ADMIN;
    }
}