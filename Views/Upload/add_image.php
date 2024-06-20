<div class="formbold-main-wrapper">
    <div class="show-image">
        <h2>Add Image</h2>
        <p>Đây là nơi thêm hình ảnh.</p>
    </div>

    <div class="formbold-form-wrapper">
        <form method="POST" action="index.php?page=upload&method=storeImage" enctype="multipart/form-data">
            <div class="formbold-input-flex">
                <div>
                    <label for="image_name" class="formbold-form-label">Tên</label>
                    <input type="text" name="image_name" placeholder="Image Name" id="image_name" class="formbold-form-input" />
                </div>
            </div>
            <div class="formbold-input-group">
                <label for="avatarImage" class="formbold-form-label">Avatar</label>
                <input type="file" name="avatarImage" id="avatarImage" class="formbold-form-input" />
            </div>
            <div class="formbold-form-btn-wrapper">
                <button class="formbold-btn" name="submit_add_image" type="submit">
                    Add Image
                </button>
            </div>
        </form>
    </div>
</div>
