<div class="pin_container">
    <?php 
    shuffle($combinedImages);

    foreach ($combinedImages as $item) : ?>
        <?php if ($item['type'] == 'small') : ?>
            <div class="card card_small">
                <a href="index.php?page=home&method=imagedetail&id=<?= $item['image']['id_image'] ?>">
                    <img src="./Uploads/Images/<?= $item['image']['pre_name_image'] ?>" alt="Small Image">
                </a>
            </div>
        <?php elseif ($item['type'] == 'medium') : ?>
            <div class="card card_medium">
                <a href="index.php?page=home&method=imagedetail&id=<?= $item['image']['id_image'] ?>">
                    <img src="./Uploads/Images/<?= $item['image']['pre_name_image'] ?>" alt="Medium Image">
                </a>
            </div>
        <?php elseif ($item['type'] == 'large') : ?>
            <div class="card card_large">
                <a href="index.php?page=home&method=imagedetail&id=<?= $item['image']['id_image'] ?>">
                    <img src="./Uploads/Images/<?= $item['image']['pre_name_image'] ?>" alt="Large Image">
                </a>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
