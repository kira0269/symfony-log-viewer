$(document).ready( function () {
    $('#logsTable').DataTable({
        "stateSave": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: $('#logsTable').data('remote-url'),
            dataType: "json",
            data: function ( d ) {
                d.var1 = 'value';
            },
            error: function (xhr, error, thrown) {
                $("#dt-overlay").hide();
            }
        },
        "preDrawCallback": function( settings ) {
            $("#dt-overlay").show();
        },
        "initComplete" : function(settings, json) {
            $("#dt-overlay").hide();
        },
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": "no-sort",
                "orderable": false,
            }
        ],
        "columns": [
            {"data": "date"},
            {"data": "context"},
            {"data": "level"},
            {"data": "description"},
            {"data": "body"}
        ]
    });
});