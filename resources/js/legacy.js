
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.spoiler').forEach(function(spoiler) {
        spoiler.addEventListener('click', function() {
            spoiler.classList.toggle('show');
        });
    });
});
