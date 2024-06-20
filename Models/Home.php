<?php
include_once './Configs/Connect.php';
class Home extends Connect
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */

    /* IMAGE */

    public function getImages()
    {
        $sql = "SELECT * FROM images JOIN image_size ON images.size_type_id = image_size.id_size WHERE active = 1 ORDER BY size_type_id ASC, created_at DESC";
        $pre = $this->pdo->prepare($sql);
        $pre->execute();
        return $pre->fetchAll();
    }

    public function getImagesBySizeTypeId($size_type_id)
    {
        try {
            $sql = "SELECT * FROM images WHERE size_type_id = :size_type_id AND active = 1 ORDER BY created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':size_type_id', $size_type_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public function showImageById($id_image)
    {
        $sql = "SELECT * FROM images WHERE id_image = :id_image";
        $pre = $this->pdo->prepare($sql);
        $pre->bindParam(':id_image', $id_image);
        $pre->execute();
        return $pre->fetch(PDO::FETCH_ASSOC);
    }

    public function createImage($image_name, $image_path, $pre_name_image, $size_type_id)
    {
        try {
            // Kiểm tra xem size_type_id có tồn tại trong bảng image_size không
            if (!$this->checkSizeTypeIdExists($size_type_id)) {
                // Xử lý khi size_type_id không hợp lệ
                echo "Invalid size type ID";
                return false;
            }

            // Câu lệnh SQL để chèn dữ liệu
            $sql = "INSERT INTO images (image_name, image_path, pre_name_image, size_type_id, created_at) 
                    VALUES (:image_name, :image_path, :pre_name_image, :size_type_id, current_timestamp())";

            // Chuẩn bị và thực thi câu lệnh SQL
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':image_name', $image_name, PDO::PARAM_STR);
            $stmt->bindParam(':image_path', $image_path, PDO::PARAM_STR);
            $stmt->bindParam(':pre_name_image', $pre_name_image, PDO::PARAM_STR);
            $stmt->bindParam(':size_type_id', $size_type_id, PDO::PARAM_INT);
            $stmt->execute();

            // Trả về true nếu chèn thành công
            return true;
        } catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    private function checkSizeTypeIdExists($size_type_id)
    {
        $sql = "SELECT COUNT(*) FROM image_size WHERE id_size = :size_type_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':size_type_id', $size_type_id, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    private function deleteImageFile($image_path)
{
    if (file_exists($image_path)) {
        if (unlink($image_path)) {
            return true;
        } else {
            // Xử lý lỗi khi không thể xóa tệp tin
            error_log("Không thể xóa tệp tin: " . $image_path);
            return false;
        }
    }
    return true;
}

public function deleteImageById($id_image)
{
    try {
        $image = $this->showImageById($id_image);
        if (!$image) {
            return false;
        }
        $sql = "DELETE FROM images WHERE id_image = :id_image";
        $pre = $this->pdo->prepare($sql);
        $pre->bindParam(':id_image', $id_image);
        $pre->execute();
        
        // Xóa tệp tin ảnh từ thư mục
        if ($image['pre_name_image']) {
            $deleted = $this->deleteImageFile('./Uploads/Images/' . $image['pre_name_image']);
            if (!$deleted) {
                // Xử lý lỗi khi không thể xóa tệp tin
                error_log("Không thể xóa tệp tin ảnh: " . './Uploads/Images/' . $image['pre_name_image']);
                return false;
            }
        }

        return true;
    } catch (PDOException $e) {
        // Xử lý lỗi PDO
        error_log("Lỗi khi xóa ảnh từ cơ sở dữ liệu: " . $e->getMessage());
        return false;
    }
}

public function updateImage($id_image, $image_name, $compressed_image, $pre_name_image)
{
    try {
        // Lấy thông tin ảnh cần update từ cơ sở dữ liệu
        $image = $this->showImageById($id_image);

        // Kiểm tra xem ảnh có tồn tại hay không
        if (!$image) {
            return false; // Không tìm thấy ảnh để cập nhật
        }

        // Kiểm tra xem size_type_id có tồn tại trong bảng image_size không
        $size_type_id = $image['size_type_id']; // Lấy size_type_id hiện tại của ảnh
        if (!$this->checkSizeTypeIdExists($size_type_id)) {
            // Xử lý khi size_type_id không hợp lệ
            echo "Invalid size type ID";
            return false;
        }

        // Cập nhật thông tin ảnh vào database
        $sql = "UPDATE images SET image_name = :image_name, image_path = :image_path, pre_name_image = :pre_name_image WHERE id_image = :id_image";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':image_name', $image_name, PDO::PARAM_STR);
        $stmt->bindParam(':pre_name_image', $pre_name_image, PDO::PARAM_STR); // Lưu pre_name_image là tên file mới
        $stmt->bindParam(':image_path', $compressed_image, PDO::PARAM_STR); // Lưu image_path là đường dẫn đầy đủ của file mới
        $stmt->bindParam(':id_image', $id_image, PDO::PARAM_INT);
        $stmt->execute();

        return true; // Cập nhật thành công
    } catch (PDOException $e) {
        // Xử lý ngoại lệ nếu có lỗi
        echo "Error: " . $e->getMessage();
        return false;
    }
}









}