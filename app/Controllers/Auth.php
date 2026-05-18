<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/');
        }
        return view('login');
    }

    public function authenticate()
    {
        $session = session();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Hardcoded Credentials
        $hardcodedUser = 'admin';
        $hardcodedPass = 'admin123';

        if ($username === $hardcodedUser && $password === $hardcodedPass) {
            $ses_data = [
                'username' => $username,
                'isLoggedIn' => TRUE
            ];
            $session->set($ses_data);
            return redirect()->to('/');
        } else {
            $session->setFlashdata('msg', 'Username atau Password salah!');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
