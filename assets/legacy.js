document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.spoiler').forEach(function (spoiler) {
        spoiler.addEventListener('click', function () {
            spoiler.classList.toggle('show');
        });
    });

    /* Autocomplete */
    document.querySelectorAll('input[data-autocomplete]').forEach(function (input) {
        let results = document.createElement('div');
        results.classList.add('autocomplete-items');
        input.parentNode.appendChild(results);

        input.addEventListener('keyup', function () {
            fetch('live/search?q=' + input.value)
                .then(response => response.text())
                .then(data => {
                    results.innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
