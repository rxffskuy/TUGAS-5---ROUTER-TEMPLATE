<?php

class MobilController extends Controller
{
    private $rokokmodels;

    public function __construct()
    {
        $this->rokokmodels = $this->model('Mobil');
    }

    public function index()
    {
        $data = [
            'title' => 'Mobil',
            'rokok' => $this->rokokmodels->getAll(),
        ];

        $this->view('pages/admin/mobil/list', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Mobil',
        ];
        $this->view('pages/admin/mobil/create', $data);
    }

    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $namaRokok = $_POST['nama_rokok'];
                $hargaPack = $_POST['harga_pack'];
                $type = $_POST['type'];

                $extension = pathinfo($_FILES['gambar_rokok']['name'], PATHINFO_EXTENSION);
                $namaFile = uniqid() . '.' . $extension;
                $tmpName = $_FILES['gambar_rokok']['tmp_name'];
                $location = $_SERVER['DOCUMENT_ROOT'] . '/tugas10fahmi/public/uploads';
                $located = $location . '/' . $namaFile;
                $uploaded = move_uploaded_file($tmpName, $located);
                
                if ($uploaded) {
                    $data = [
                        'nama_rokok' => $namaRokok,
                        'harga_pack' => $hargaPack,
                        'type' => $type,
                        'gambar_rokok' => $namaFile
                    ];
                    $result = $this->rokokmodels->store($data);
    
                    if ($result) {
                        Message::setFlash('success', 'Data berhasil ditambahkan');
                        $this->redirect('dashboard');
                    } else {
                        Message::setFlash('success', 'Data gagal ditambahkan');
                        $this->redirect('rokok');
                    }
                } else {
                    echo 'Permintaan tidak valid.';
                }

                
            } else {
                echo 'Permintaan tidak valid.';
            }
        } catch (\Exception $e) {
            Message::setFlash('error', 'Terjadi kesalahan: ', $e->getMessage());
            $this->redirect('dashboard');
        }
    }

    public function show($id)
    {
        try {
            $url = $_SERVER['REQUEST_URI'];
            $result = explode('/', $url);

            $id = end($result);
            $result = $this->rokokmodels->getbyid($id);
            $data = [
                'title' => 'Mobil',
                'rokok' => $result,
            ];
            $this->view('pages/admin/mobil/edit', $data);
        } catch (\Exception $e) {
            Message::setFlash('error', 'Terjadi kesalahan: ', $e->getMessage());
            $this->redirect('dashboard');
        }
    }

    public function update($id)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $namaRokok = $_POST['nama_rokok'];
                $hargaPack = $_POST['harga_pack'];
                $type = $_POST['type'];

                /**
                 * Get data from user uploads
                 */
                $gambarRokok = $_FILES['gambar_rokok'];
                /**
                 * Get data from database if user doesnt edited image
                 */
                $result = $this->rokokmodels->getbyid($id);

                if (!empty($gambarRokok['name'])) {
                    $oldfiles = $result['gambar_rokok'];
                    $location = $_SERVER['DOCUMENT_ROOT'] . '/tugas10fahmi/public/uploads';
                    $located = $location . '/' . $oldfiles;
                    if(file_exists($located)){
                        $deleted = unlink($located);
                        if($deleted){
                            $extension = pathinfo($_FILES['gambar_rokok']['name'], PATHINFO_EXTENSION);
                            $namaFile = uniqid() . '.' . $extension;
                            $tmpName = $_FILES['gambar_rokok']['tmp_name'];
                            $location = $_SERVER['DOCUMENT_ROOT'] . '/tugas10fahmi/public/uploads';
                            $located = $location . '/' . $namaFile;
                            $uploaded = move_uploaded_file($tmpName, $located);
                            if($uploaded){
                                $data = [
                                    'nama_rokok' => $namaRokok,
                                    'harga_pack' => $hargaPack,
                                    'type' => $type,
                                    'id_rokok' => $id,
                                    'gambar_rokok' => $namaFile,
                                ];
    
                                $result = $this->rokokmodels->updateDataRokok($data);
    
                                if ($result) {
                                    Message::setFlash('success', 'Data berhasil update');
                                    $this->redirect('dashboard');
                                } else {
                                    Message::setFlash('success', 'Data gagal update');
                                    $this->redirect('rokok');
                                }
                            } 
                        }  
                    } 
                } else {
                    $gambarRokokOld = $result['gambar_rokok'];
                    $data = [
                        'nama_rokok' => $namaRokok,
                        'harga_pack' => $hargaPack,
                        'type' => $type,
                        'id_rokok' => $id,
                        'gambar_rokok' => $gambarRokokOld,
                    ];

                    $result = $this->rokokmodels->updateDataRokok($data);

                    if ($result) {
                        Message::setFlash('success', 'Data berhasil update');
                        $this->redirect('dashboard');
                    } else {
                        Message::setFlash('success', 'Data gagal update');
                        $this->redirect('rokok');
                    }
                }
            } else {
                echo 'Permintaan tidak valid.';
            }
        } catch (\Exception $e) {
            echo 'Error';
            $this->redirect('dashboard');
        }
    }

    public function destroy(){
        try {
            $url = $_SERVER['REQUEST_URI'];
            $result = explode('/', $url);

            $id = end($result);
            $data = $this->rokokmodels->getbyid($id);
            if ($data['gambar_rokok']){
                $deletefiles = $_SERVER['DOCUMENT_ROOT'] . '/tugas10fahmi/public/uploads/'.$data['gambar_rokok'];
                var_dump($deletefiles);
                if (unlink($deletefiles)){
                    $result = $this->rokokmodels->destroy($id);
                    if ($result) {
                        Message::setFlash('success', 'Data berhasil terhapu');
                        $this->redirect('dashboard');
                    } else {
                        Message::setFlash('success', 'Data gagal terhapu');
                        $this->redirect('rokok');
                    }
                }
                else {
                    echo 'data tidak terhapus';
                }
            } else {
                
                echo 'data tidak ada';
            }
        } catch (\Exception $e) {
            echo 'Error';
            $this->redirect('dashboard');
        }
    }
}
