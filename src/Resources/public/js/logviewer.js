$(document).ready( function () {
    $('#logsTable').DataTable({
        "stateSave": true,
        "processing": true,
        "serverSide": false,
        "ajax": {
            url: $('#logsTable').data('remote-url'),
            dataSrc: '',
            dataType: "json",
            data: function ( d ) {
                // Add date informations
                d.year = '2021';
                d.month = '07';
                d.day = '08';
                d.draw = 1;
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
        ]
    });
});