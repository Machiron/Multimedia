<div class="container-card">
    <div class="card__container">
        <article class="card__article">
            <img id="cardImage" src="./Uploads/Images/<?= $anhDetail['pre_name_image'] ?>" alt="image" class="card__img">
        </article>
        <div class="card__data">
            <h2 class="card__title"><?= $anhDetail['image_name']; ?></h2>
            <div class="card__icons">
                <a href="#" class="card__icon" onclick="rotateImage()">
                <i class="ri-clockwise-line"  alt="Rotate"></i>
                </a>
                <a href="#" class="card__icon" onclick="zoomIn()">
                    <i class="ri-zoom-in-line" alt="Zoom In"></i>
                </a>
                <a href="#" class="card__icon" onclick="zoomOut()">
                    <i class="ri-zoom-out-line" alt="Zoom Out"></i>
                </a>
                <a href="#" class="card__icon" onclick="toggleColor()">
                    <i class="ri-palette-line" alt="Change Color"></i>
                </a>
                <a href="#" class="card__icon" onclick="blurImage()">
                    <i class="ri-brush-3-fill" alt="Blur"></i>
                </a>
                <a href="#" class="card__icon" onclick="downloadImage()">
                    <i class="ri-folder-download-fill" alt="Download"></i>
                </a>
            </div>
        </div>
    </div>
</div>
