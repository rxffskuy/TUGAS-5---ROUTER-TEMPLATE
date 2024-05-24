<?php 

class DashboardController extends Controller{
    public function index(){
        $data = [
            'title' => 'Mobil',
        ];

        $this->view('views/auth/login.blade.php', $data);
        
    }
}
