$(function () {
    $('#export').click(function (e) {

        return ExcellentExport.excel(this, 'students', 'Sheet Name Here');

    })
    $('#refresh').click(function () {
        location.reload();
    })
})

