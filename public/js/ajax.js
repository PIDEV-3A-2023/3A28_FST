$(document).ready(function() {
    $('#searchf').on('submit', function(event) {
        event.preventDefault(); // empêche la soumission normale du formulaire
        var searchTerm = $('#search-input').val();
        console.log(searchTerm)
        if (searchTerm) {
            var url = 'https://127.0.0.1:8000/gestion/users/'+encodeURIComponent(searchTerm)
            window.location.href = url;
        }else{
            var url = 'https://127.0.0.1:8000/gestion/users/'
            window.location.href = url;
        }
        $.ajax({
            type: 'POST',
            url: 'https://127.0.0.1:8000/gestion/users/search',
            data: {searchTerm:searchTerm},
            success: function(data) {
                // Traitez la réponse de votre contrôleur ici
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });
});