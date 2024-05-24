<?php 

class DashboardController extends Controller{
    public function index(){
        $data = [
            'title' => 'Mobil',
        ];

        $this->view('views/auth/sign-up.php', $data);
        
    }
}
