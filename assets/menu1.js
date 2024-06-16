function fadeIn(id) {
    let element = document.getElementById(id);

    if (element.style.display === 'block') {
        return;
    }

    element.style.display = 'block';
    let opacity = 0;  // initial opacity
    let timer = setInterval(function () {
        if (opacity >= 1) {
            clearInterval(timer);
        } else {
            element.style.opacity = opacity;
            element.style.filter = 'alpha(opacity=' + opacity * 100 + ")";
            opacity += 0.1;
        }
    }, 10);
}

function fadeOut(id) {
    let element = document.getElementById(id);
    let opacity = 1;  // initial opacity
    let timer = setInterval(function () {
        if (opacity <= 0) {
            clearInterval(timer);
            element.style.display = 'none';
        } else {
            element.style.opacity = opacity;
            element.style.filter = 'alpha(opacity=' + opacity * 100 + ")";
            opacity -= 0.1;
        }
    }, 10);
}

function hide(id) {
    document.getElementById(id).style.display = 'none';
}
