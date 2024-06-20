<div class="formbold-main-wrapper">
    <div class="show-image">
        <h2>Update Image</h2>
        <p>Đây là nơi sửa hình ảnh.</p>
    </div>

    <div class="formbold-form-wrapper">
        <form method="POST" action="index.php?page=upload&method=updateImage&id=<?= $images['id_image']; ?>" enctype="multipart/form-data">
            <div class="formbold-input-flex">
                <div>
                    <label for="image_name" class="formbold-form-label">Tên</label>
                    <input type="text" name="image_name" placeholder="Image Name" value="<?= !empty($images['image_name']) ? $images['image_name'] : '' ?>" id="image_name" class="formbold-form-input" />
                </div>
            </div>
            <div class="formbold-input-group">
                <label for="avatarImage" class="formbold-form-label">Avatar</label>
                <input type="file" name="avatarImage" id="avatarImage" class="formbold-form-input" />
            </div>
            <input type="hidden" name="image_id" value="<?= $images['id_image']; ?>">
            <input type="hidden" name="current_image_path" value="<?= $images['image_path']; ?>">
            <div class="formbold-form-btn-wrapper">
                <button class="formbold-btn" name="submit_update_image" type="submit">
                    Update Image
                </button>
            </div>
        </form>
    </div>
</div>
