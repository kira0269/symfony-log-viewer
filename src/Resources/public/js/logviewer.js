const myTable = document.querySelector("#logTable");
const logsUrl = myTable.getAttribute('data-remote-url');
const dataTable = new simpleDatatables.DataTable(myTable);

var xhttp = new XMLHttpRequest();
xhttp.dataTable = dataTable;
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        this.dataTable.import({
            type: "json",
            data: this.responseText
        });
    }
};

xhttp.open("GET", logsUrl, true);
xhttp.send();