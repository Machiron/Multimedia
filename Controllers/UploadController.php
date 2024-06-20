<?php
include_once './Core/Controller.php';

class UploadController extends Controller
{
    public $homeModel;
    public function __construct()
    {
        $this->homeModel = parent::model('Home');
        $this->index();
    }

    public function index()
    {
        $method = isset($_GET['method']) ? $_GET['method'] : 'show';
        switch ($method) {
                // Image
            case 'showImage':
                $images = $this->homeModel->getImages();
                include_once('./Views/upload/show_image.php');
                break;
            case 'addImage':
                include_once('./Views/upload/add_image.php');
                break;
            case 'storeImage':
                $this->createImage();
                break;
            case 'deleteImage':
                $this->deleteImage();
                break;
            case 'editImage':
                $this->editImage();
                break;
            case 'updateImage':
                $this->updateImage();
                break;
            default:
                include_once('./Views/404page.php');
                break;
        }
    }

    // Image

    public function createImage()
    {
        if (isset($_POST['submit_add_image'])) {
            $image_name = isset($_POST['image_name']) ? $_POST['image_name'] : '';
            $image_path = '';
            $pre_name_image = '';

            if (!empty($_FILES['avatarImage']['name'])) {
                $files = $_FILES['avatarImage'];
                $imageExtension = strtolower(pathinfo($files['name'], PATHINFO_EXTENSION));
                $nameFile = time() . '_' . $files['name'];
                $image_path = './Uploads/Images/' . $nameFile;

                if ($imageExtension == 'jpg' || $imageExtension == 'jpeg') {
                    $pre_name_image = $this->compressImageJPEG($files['tmp_name'], $image_path);
                } elseif ($imageExtension == 'png') {
                    $pre_name_image = $this->compressImagePNG($files['tmp_name'], $image_path);
                } else {
                    die("Định dạng tệp ảnh không được hỗ trợ.");
                }

                list($width, $height) = getimagesize($image_path);
                $size_type_id = $this->determineSizeType($width, $height);

                $addImage = $this->createImageInDB($image_name, $image_path, $pre_name_image, $size_type_id);
                if ($addImage) {
                    header("Location: index.php?page=upload&method=showImage");
                    exit;
                } else {
                    header("Location: index.php?page=upload&method=addImage");
                    exit;
                }
            } else {
                die("Vui lòng chọn một tệp ảnh để tải lên.");
            }
        } else {
            header("Location: index.php?page=upload&method=showImage");
            exit;
        }
    }

    private function createImageInDB($image_name, $image_path, $pre_name_image, $size_type_id)
    {
        return $this->homeModel->createImage($image_name, $image_path, $pre_name_image, $size_type_id);
    }

    private function determineSizeType($width, $height)
    {
        $ratio = $width / $height;
        if (abs($ratio - 1) < 0.15) {
            return 1;
        } elseif ($ratio > 1) {
            return 2;
        } else {
            return 3;
        }
    }

    private function compressImageJPEG($source, $destination, $quality = 75)
    {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
        imagedestroy($image);
        return basename($destination);
    }

    private function compressImagePNG($source, $destination, $quality = 6)
    {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, $quality);
        imagedestroy($image);
        return basename($destination);
    }

    public function deleteImage()
    {
        if (isset($_GET['id'])) {
            $image_id = $_GET['id'];
            $image = $this->homeModel->showImageById($image_id);
            if ($image) {
                $this->deleteImageFile($image['image_path']);
                $delete = $this->homeModel->deleteImageById($image_id);
                if ($delete) {
                    header("Location: index.php?page=upload&method=showImage");
                    exit;
                } else {
                    header("Location: index.php?page=upload&method=showImage");
                    exit;
                }
            } else {
                header("Location: index.php?page=upload&method=showImage");
                exit;
            }
        } else {
            header("Location: index.php?page=upload&method=showImage");
            exit;
        }
    }

    private function deleteImageFile($image_path)
    {
        unlink($image_path);
    }

    
    public function editImage()
    {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $images = $this->homeModel->showImageById($id);
            if ($images) {
                include_once('./Views/upload/update_image.php');
            } else {
                header("Location: index.php?page=upload&method=showImage");
                exit;
            }
        } else {
            header("Location: index.php?page=upload&method=showImage");
            exit;
        }
    }

    public function updateImage()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Retrieve form data
            $image_id = $_POST['image_id'];
            $image_name = $_POST['image_name'];
            $current_image_path = $_POST['current_image_path'];
            
            // Check if a new image was uploaded
            if ($_FILES['avatarImage']['error'] === UPLOAD_ERR_OK) {
                $temp_name = $_FILES['avatarImage']['tmp_name'];
                $original_name = $_FILES['avatarImage']['name'];
                $image_path = './uploads/images/' . $original_name;
    
                // Move uploaded file to desired directory
                if (move_uploaded_file($temp_name, $image_path)) {
                    // Determine image type and compress if necessary
                    $image_type = exif_imagetype($image_path);
                    if ($image_type === IMAGETYPE_JPEG) {
                        $compressed_image = $this->compressImageJPEG($image_path, $image_path);
                    } elseif ($image_type === IMAGETYPE_PNG) {
                        $compressed_image = $this->compressImagePNG($image_path, $image_path);
                    } else {
                        $compressed_image = $image_path; // Default to original path if not JPEG or PNG
                    }
    
                    // Check if compression was successful
                    if (!$compressed_image) {
                        echo "Failed to compress image.";
                        exit;
                    }
    
                    // Delete the old image file if it exists
                    if (!empty($current_image_path)) {
                        $real_current_image_path = realpath($current_image_path);
                        if ($real_current_image_path && file_exists($real_current_image_path)) {
                            unlink($real_current_image_path);
                        } else {
                            echo "Failed to delete current image file.";
                            // Handle error or log it
                        }
                    }
    
                    // Update pre_name_image in database
                    $pre_name_image = basename($image_path); // Or whatever logic you use to get pre_name_image
    
                    // Check if pre_name_image is valid
                    if (empty($pre_name_image)) {
                        echo "Failed to get pre_name_image.";
                        exit;
                    }
                } else {
                    // Handle file upload error
                    echo "Failed to move uploaded file.";
                    exit;
                }
            } else {
                // No new image uploaded, keep the current path and pre_name_image
                $compressed_image = $current_image_path;
                // Retrieve pre_name_image from current_image_path
                $pre_name_image = basename($current_image_path); // Adjust this based on your naming convention
            }
            
            // Update image information in the database using model method
            $update_result = $this->homeModel->updateImage($image_id, $image_name, $compressed_image, $pre_name_image);
    
            if ($update_result) {
                // Redirect to image display page upon successful update
                header("Location: index.php?page=upload&method=showImage");
                exit;
            } else {
                // Handle update failure
                echo "Update failed!";
            }
        } else {
            // Redirect if not a POST request
            header("Location: index.php?page=upload&method=showImage");
            exit;
        }
    }

function generateUniqueImagePath($original_path, $destination_directory, $file_extension) {
    $basename = pathinfo($original_path, PATHINFO_FILENAME);
    $index = 0;
    $unique_path = $basename . $file_extension;

    // Check if the file exists in the destination directory
    while (file_exists($destination_directory . '/' . $unique_path)) {
        $index++;
        $unique_path = $basename . '_' . $index . $file_extension;
    }

    return $unique_path;
}


}
