<?php
include_once './Core/Controller.php';
class HomeController extends Controller
{
    public $homeModel;

    public function __construct()
    {
        $this->homeModel = parent::model('Home');
        $this->index();
    }

    public function index()
    {
        $method = isset($_GET['method']) ? $_GET['method'] : '';
        switch ($method) {
            case 'home':
                $this->showHome();
                break;

            case 'imagedetail':
                if (isset($_GET['id'])) {
                    $this->showImageDetail($_GET['id']);
                } else {
                    $this->showHome();
                }
                break;

            default:
                $this->showHome();
                break;
        }
    }

    private function showHome()
    {
        $home1 = $this->homeModel->getImagesBySizeTypeId(1);
        $home2 = $this->homeModel->getImagesBySizeTypeId(2);
        $home3 = $this->homeModel->getImagesBySizeTypeId(3);

        $combinedImages = [];
        $maxLength = max(count($home1), count($home2), count($home3));

        for ($i = 0; $i < $maxLength; $i++) {
            if (isset($home1[$i])) {
                $combinedImages[] = ['image' => $home1[$i], 'type' => 'small'];
            }
            if (isset($home2[$i])) {
                $combinedImages[] = ['image' => $home2[$i], 'type' => 'medium'];
            }
            if (isset($home3[$i])) {
                $combinedImages[] = ['image' => $home3[$i], 'type' => 'large'];
            }
        }

        include_once './Views/home.php';
    }

    private function showImageDetail($id)
    {
        $anhDetail = $this->homeModel->showImageById($id);
        include_once './Views/image_detail.php';
    }
}
