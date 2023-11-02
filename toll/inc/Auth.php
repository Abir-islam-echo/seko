<?php

namespace Toll_Integration;

class Auth
{
    protected $db;
    protected $log;

    public function __construct()
    {
        $this->db = new DB();
        $this->log = new Log();
    }
    public function verify($request)
    {
        $email = strcmp($request->email, $this->db->getUser('email', $request->email));
        $password = password_verify($request->password, $this->db->getUser('password', $request->email));
        /**
         * strcm returns 0 if comparison is equal and password_verify need to be 1
         * */
        if ($email == 0 && $password == 1) {
            $_SESSION['root'] = true;
            return ['status' => true, 'redirect_url' => getRoute('dashboard')];
        } else {
            return ['status' => false, 'redirect_url' => getRoute('login')];
        }
    }

    public function status()
    {
        return $_SESSION['root'] == true;
    }

    public function logout()
    {
        session_destroy();
    }
}
