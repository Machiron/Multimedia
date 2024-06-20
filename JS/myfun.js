function zoomIn() {
    var image = document.querySelector(".card__img");
    var currentWidth = image.offsetWidth;
    var newWidth = currentWidth * 1.1;
    image.style.width = newWidth + "px";
    image.style.height = "auto"; // Giữ chiều cao tự động
    centerImage(image);
}

function zoomOut() {
    var image = document.querySelector(".card__img");
    var currentWidth = image.offsetWidth;
    var newWidth = currentWidth * 0.9;
    image.style.width = newWidth + "px";
    image.style.height = "auto"; // Giữ chiều cao tự động
    centerImage(image);
}

function centerImage(image) {
    var cardArticle = image.closest(".card__article");
    var marginLeft = (cardArticle.offsetWidth - image.offsetWidth) / 2;
    var marginTop = (cardArticle.offsetHeight - image.offsetHeight) / 2;
    image.style.marginLeft = marginLeft + "px";
    image.style.marginTop = marginTop + "px";
}

var blurCount = 0;
function blurImage() {
    var image = document.querySelector(".card__img");
    if (blurCount % 2 === 0) {
        image.style.filter = "blur(5px)";
    } else {
        image.style.filter = "none";
    }
    blurCount++;
}

function rotateImage() {
    var image = document.querySelector(".card__img");
    var currentRotation = getRotationDegrees(image);
    var newRotation = currentRotation + 90;
    image.style.transform = "rotate(" + newRotation + "deg)";
}

function getRotationDegrees(obj) {
    var matrix = window.getComputedStyle(obj).getPropertyValue("transform");
    if (matrix !== "none") {
        var values = matrix.split("(")[1].split(")")[0].split(",");
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
        return angle < 0 ? angle + 360 : angle;
    }
    return 0;
}

var colorState = 0;

function toggleColor() {
    var image = document.querySelector(".card__img");

    switch (colorState) {
        case 0:
            image.style.filter = "hue-rotate(120deg)"; // Blue
            break;
        case 1:
            image.style.filter = "hue-rotate(0deg)"; // Red
            break;
        case 2:
            image.style.filter = "hue-rotate(270deg)"; // Purple
            break;
        case 3:
            image.style.filter = "hue-rotate(60deg)"; // Yellow
            break;
        case 4:
            image.style.filter = "hue-rotate(180deg)"; // Green
            break;
        case 5:
            image.style.filter = "grayscale(100%)"; // Grayscale
            break;
        default:
            image.style.filter = "none"; // Original color
            colorState = -1;
            break;
    }
    colorState++;
}

function downloadImage() {
    var image = document.querySelector(".card__img");
    var canvas = document.createElement("canvas");
    var ctx = canvas.getContext("2d");
    canvas.width = image.naturalWidth;
    canvas.height = image.naturalHeight;
    ctx.filter = window.getComputedStyle(image).getPropertyValue("filter");
    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
    var imageUrl = canvas.toDataURL("image/png");

    var link = document.createElement("a");
    link.href = imageUrl;
    link.download = "image.png";
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function applyColorFilter(colorCode) {
    var image = document.querySelector(".card__img");

    switch (colorCode) {
        case "blue":
            image.style.filter = "hue-rotate(120deg)";
            colorState = 1;
            break;
        case "red":
            image.style.filter = "hue-rotate(0deg)";
            colorState = 2;
            break;
        case "purple":
            image.style.filter = "hue-rotate(270deg)";
            colorState = 3;
            break;
        case "yellow":
            image.style.filter = "hue-rotate(60deg)";
            colorState = 4;
            break;
        case "green":
            image.style.filter = "hue-rotate(180deg)";
            colorState = 5;
            break;
        case "grayscale":
            image.style.filter = "grayscale(100%)";
            colorState = 6;
            break;
        default:
            image.style.filter = "none";
            colorState = 0;
            break;
    }
}
