<section class="dashboard">
    <div class="dash-content">
        <div class="activity">
            <div class="title">
                <i class="ri-time-line"></i>
                <span class="text">Image</span>
                <a href="index.php?page=upload&method=addImage"><button class="btn add-btn">Add</button></a>
            </div>

            <table class="activity-data">
                <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Tên</th>
                        <th>Danh mục</th>
                        <th>Ngày thêm</th>
                        <th class="status">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $image) { ?>
                        <tr>
                            <td style="text-align: center" ; width="100" ;>
                                <img src="./Uploads/Images/<?= $image['pre_name_image']; ?>" style=" object-fit : cover;
                                        width: 100px;
                                        height: 60px" ; alt="">
                            </td>
                            <td><?= $image['image_name']; ?></td>
                            <td><?= $image['name']; ?></td>
                            <td><?= date(" H:i - d/m/Y", strtotime($image['created_at']))  ?></td>
                            <td class="status">
                                <a href="index.php?page=upload&method=editImage&id=<?= $image['id_image']; ?>"><button class="btn edit-btn">Sửa</button></a>
                                <a href="index.php?page=upload&method=deleteImage&id=<?= $image['id_image']; ?>" onclick="return confirm('Are you sure delete?');"><button class="btn delete-btn">Xóa</button></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</section>
</div>