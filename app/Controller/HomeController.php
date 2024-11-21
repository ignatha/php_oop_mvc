<?php

namespace App\Controller;

use App\Services\View;
use App\Model\User;

class HomeController {

    public function index(){
        $user = new User();

        $data_user = $user->all();

        $data = [
            'users' => $data_user
        ];

        $view = new View();
        echo $view->render('home',$data);
        
    }

    public function login()
    {
        echo "ini Halaman Login";
    }

    public function add()
    {
        $view = new View();
        echo $view->render('user.add');
    }

    public function store()
    {

        $user = new User();

        $simpan = $user->create([
            'username' => $_POST['username'],
            'name' => $_POST['name'],
            'password' => $_POST['password']
        ]);

        if ($simpan) {
            header("Location: /");
        }

    }

    public function edit($id)
    {

        $user = new User();

        $data_user = $user->find($id);

        $data = [
            'user' => $data_user
        ];

        $view = new View();
        echo $view->render('user.edit',$data);
    }

    public function update($id)
    {
        $user = new User();

        $simpan = $user->update($id,[
            'username' => $_POST['username'],
            'name' => $_POST['name'],
            'password' => $_POST['password'],
        ]);

        if ($simpan) {
            header("Location: /");
        }

    }

    public function delete($id)
    {

        $user = new User();

        $simpan = $user->delete($id);

        if ($simpan) {
            header("Location: /");
        }

    }



    // public function home(){
    //     $data = [
    //         'judul' => 'Halaman Penjualan',
    //         'content' => 'Ini isi COntent nya'
    //     ];

    //     $view = new View();
    //     echo $view->render('siswa',$data);
    // }
}