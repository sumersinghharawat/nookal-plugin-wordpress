// Table Location

jQuery(document).ready(function($) {
    var items = $('#table-pagination tbody tr');
    var numItems = items.length;
    var perPage = 10; // Number of items per page

    items.slice(perPage).hide();

    $('#pagination-container').pagination({
        dataSource: Array.from({length: numItems}, (_, i) => i + 1),
        pageSize: perPage,
        callback: function(data, pagination) {
            var start = (pagination.pageNumber - 1) * perPage;
            var end = start + perPage;

            items.hide().slice(start, end).show();
        }
    });
});






// 
jQuery(document).ready(function($) {
    var items = $('#appointments tbody tr');
    var numItems = items.length;
    var perPage = 10; // Number of items per page

    items.slice(perPage).hide();

    $('#pagination-container').pagination({
        dataSource: Array.from({length: numItems}, (_, i) => i + 1),
        pageSize: perPage,
        callback: function(data, pagination) {
            var start = (pagination.pageNumber - 1) * perPage;
            var end = start + perPage;

            items.hide().slice(start, end).show();
        }
    });
});

jQuery(document).ready(function($) {
    var items = $('#my-table tbody tr');
    var numItems = items.length;
    var perPage = 10; // Number of items per page

    items.slice(perPage).hide();

    $('#pagination-container').pagination({
        dataSource: Array.from({length: numItems}, (_, i) => i + 1),
        pageSize: perPage,
        callback: function(data, pagination) {
            var start = (pagination.pageNumber - 1) * perPage;
            var end = start + perPage;

            items.hide().slice(start, end).show();
        }
    });
});


// Copy shortcode
function copyShortcode() {
    // Get the input field
    var inputField = document.querySelector('#nab_shortcode_field');
    
    // Select the text field
    inputField.select();
    inputField.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the text field
    document.execCommand('copy');

    // Optionally, alert the user or show a confirmation
    alert('Shortcode copied to clipboard!');
}
