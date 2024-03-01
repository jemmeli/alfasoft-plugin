jQuery(document).ready(function ($) {
    // Initialize DataTables with server-side processing
    $('#people-list').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": ajax_object.ajaxurl,
            "type": "POST",
            "data": {
                "action": "get_people_data",
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "email" },
            { "data": "avatar_url" },
        ],
    });
});