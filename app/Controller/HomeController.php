<?php

namespace App\Controller;

use App\Services\{Auth,Validator,Flash,View,Redirect};
use App\Services\LocalStorage as Storage;
use App\Model\User;

class HomeController {

    public function index(){

        $currentPage = $_GET['page'] ?? 1;

        $user = new User();

        $paginationData = $user->paginate(2,$currentPage);

        $data = [
            'users' => $paginationData['data'],
            'paginationData' => $paginationData['pagination']
        ];

        $view = new View();
        echo $view->render('home',$data);
        
    }

    public function login()
    {
        $view = new View();
        echo $view->render('login');
    }

    public function loginStore()
    {
        $data = $_POST;
        
        $validator = new Validator();

        // Validasi input
        $validator->validate($data, [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            // Set error messages in flash
            foreach ($validator->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    Flash::set("error_{$field}", $message);
                }
            }

            Redirect::back($data);
        }

        $user = new User();

        $data_user = $user->where('username','=',$data['username'])->first();

        if($data_user && Auth::verifyPassword($data['password'],$data_user->password)){
            Auth::login($data_user);

            header('Location: /');
            exit;
        }else {
            Flash::set("login_error", "Credential not match our records");
            Redirect::back($data);
        }

    }

    public function logout()
    {
        Auth::logout();
        header('Location: /login');
        exit;
    }

    public function add()
    {
        $view = new View();
        echo $view->render('user.add');
    }

    public function store()
    {
        $fileName = null;
        if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['img'];
            $fileName = Storage::name($file['name'],'_','img');

            try {
                $path = Storage::upload($file, $fileName);

            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }

        }

        $user = new User();

        $simpan = $user->create([
            'username' => $_POST['username'],
            'name' => $_POST['name'],
            'password' => Auth::bcrypt($_POST['password']),
            'img' => $fileName
        ]);

        if ($simpan) {
            header('Location: /');
            exit;
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
            header('Location: /');
            exit;
        }

    }

    public function delete($id)
    {

        $user = new User();

        $getUser = $user->find($id);

        try {
            Storage::delete($getUser->img);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        $simpan = $user->delete($id);

        if ($simpan) {
            header('Location: /');
            exit;
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