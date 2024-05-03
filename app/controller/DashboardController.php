<?php 

class DashboardController extends Controller{
    public function index(){
        $data = [
            'title' => 'Mobil',
        ];

        $this->view('pages/admin/dashboard', $data);
    }
}