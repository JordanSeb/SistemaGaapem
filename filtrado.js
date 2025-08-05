document.getElementById('print-btn').addEventListener('click', function() {
    var doc = new jsPDF();
    var res = doc.autoTableHtmlToJson(document.getElementById("my-table"));
    doc.autoTable(res.columns, res.data);
    doc.save('table.pdf');
});



document.getElementById('update-btn').addEventListener('click', function() {
    location.reload();
});
