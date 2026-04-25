$(document).ready(function() {
    var btnReceipt = $('#btn-receipt')
    var modalReceipt = $('#modal-receipt')

    btnReceipt.on('click', function() {
        modalReceipt.modal('show')
    })
})