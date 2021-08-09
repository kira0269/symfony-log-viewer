$(document).ready( function () {
    const logsTable = $('#logsTable');
    const groupsConfig = logsTable.data('groups-config');

    const textFormat = (value) => { return value; }
    const jsonFormat = (value) => { return '<pre>'+JSON.parse(value)+'</pre>'; }

    const format = (value, type) => {
        switch (type) {
            case 'json': return jsonFormat(value);
            case 'text':
            default: return textFormat(value);
        }
    }

    let columns = [];
    Object.keys(groupsConfig).forEach((groupName) => {
        columns.push({
            'data': groupName,
            'render': function(value) {
                return format(value, groupsConfig[groupName].type)
            }
        })
    });

    logsTable.DataTable({
        "stateSave": true,
        "processing": true,
        "serverSide": false,
        "ajax": {
            url: logsTable.data('remote-url'),
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
                console.log(error)
            }
        },
        "language": {
            processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '
        },
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": "no-sort",
                "orderable": false,
            },
            {
                "className": "border border-gray-400 whitespace-nowrap",
                "targets": 0,
            },
            {
                "className": "border border-gray-400",
                "targets": "_all"
            }
        ],
        "columns": columns
    });
});
