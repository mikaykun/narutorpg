function transparenz(id, transparenz) {
    var style = document.getElementById(id).style;
    style.filter = "alpha(opacity=" + transparenz + ")";
    transparenz /= 100;
    style.opacity = transparenz;
    style.MozOpacity = transparenz;
    style.KhtmlOpacity = transparenz;
}

function fadeIn(id) {
    if (document.getElementById(id).style.display != "block") {
        schritte = 100;

        transparenz(id, 0);
        document.getElementById(id).style.display = 'block';

        if (schritte == 0) return;
        var geschwindigkeit = Math.round(200 / schritte);

        for (var i = 1; i <= schritte; i++) {
            window.setTimeout("transparenz('" + id + "', " + i + ")", (i * geschwindigkeit));
        }
    }
}

function hide(id) {
    document.getElementById(id).style.display = 'none';
}

function fadeOut(id) {
    schritte = 100;

    if (schritte == 0) return;
    var geschwindigkeit = Math.round(200 / schritte);
    transparenz(id, 100);

    for (var i = 1; i <= schritte; i++) {
        transp = 100 - i;
        setTimeout("transparenz('" + id + "', " + transp + ")", (i * geschwindigkeit));
    }

    setTimeout("hide('" + id + "')", 200);
}
