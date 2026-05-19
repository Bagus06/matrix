$(document).ready(function() {

    /* ======================= CONFIG ======================= */
    const modalState = {};
    let defaultMaxSize = 2; // MB

    function getFileInfoText(type) {
        const maxSizeText = 'Max ' + defaultMaxSize + 'MB';

        if (type === 'image') return 'Allowed: JPG, PNG (' + maxSizeText + ')';
        if (type === 'pdf') return 'Allowed: PDF only (' + maxSizeText + ')';
        if (type === 'image,pdf') return 'Allowed: JPG, PNG, PDF (' + maxSizeText + ')';

        return 'Allowed file (' + maxSizeText + ')';
    }

    /* ======================= OPEN MODAL ======================= */
    $('.btnOpenUploadModal').on('click', function() {

        const modal = $('#uploadModal');

        const acceptInput = $(this).data('accept');
        const inputName = $(this).data('inputname');
        const fileUrl = $(this).data('fileurl');
        const fileType = ($(this).data('filetype') || '').toLowerCase();
        const modalTitle = $(this).data('modaltitle');

        modal.data('accept', acceptInput);
        modal.data('inputName', inputName);

        /* RESET UI */
        modal.find('.fileTemp').val('');
        modal.find('.previewContainer').hide();
        modal.find('.imagePreview').attr('src', '').hide();
        modal.find('.pdfPreview').hide();
        modal.find('.btnRemoveFile').hide();

        modal.find('.modal-title').html(modalTitle);

        /* ACCEPT */
        let acceptAttr = 'image/*,application/pdf';
        if (acceptInput === 'image') acceptAttr = 'image/*';
        if (acceptInput === 'pdf') acceptAttr = 'application/pdf';

        modal.find('.fileTemp').attr('accept', acceptAttr);
        modal.find('.fileInfoText').text(getFileInfoText(acceptInput));

        /* ================= LOAD STATE ================= */
        const state = modalState[inputName];

        if (state) {

            modal.find('.previewContainer').show();
            modal.find('.btnRemoveFile').show();

            // IMAGE
            if (state.imageUrl) {
                modal.find('.imagePreview')
                    .attr('src', state.imageUrl)
                    .show();
            }
            // fallback dari file (kalau imageUrl belum ada)
            else if (state.file && state.file.type.indexOf('image/') === 0) {
                const tempUrl = URL.createObjectURL(state.file);
                modal.find('.imagePreview')
                    .attr('src', tempUrl)
                    .show();
            }

            // PDF
            else if (state.pdfUrl) {
                modal.find('.pdfPreview').show();
                modal.find('.btnViewPdf').data('url', state.pdfUrl);
            }
        }

        /* ================= FALLBACK DB ================= */
        else if (fileUrl && fileType) {

            modal.find('.previewContainer').show();
            modal.find('.btnRemoveFile').show();

            if (['jpg', 'jpeg', 'png'].indexOf(fileType) !== -1) {

                modal.find('.imagePreview')
                    .attr('src', fileUrl)
                    .show();

                modalState[inputName] = {
                    file: null,
                    imageUrl: fileUrl,
                    pdfUrl: null,
                    isFromDB: true
                };
            } else if (fileType === 'pdf') {

                modal.find('.pdfPreview').show();
                modal.find('.btnViewPdf').data('url', fileUrl);

                modalState[inputName] = {
                    file: null,
                    imageUrl: null,
                    pdfUrl: fileUrl,
                    isFromDB: true
                };
            }
        }

        modal.modal('show');
    });

    /* ======================= SELECT FILE ======================= */
    $(document).on('click', '.btnSelectFile, .dropZone', function() {
        $(this).closest('.modal').find('.fileTemp').click();
    });

    /* ======================= DRAG ======================= */
    $(document).on('dragover', '.dropZone', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $(document).on('dragleave', '.dropZone', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $(document).on('drop', '.dropZone', function(e) {

        e.preventDefault();
        $(this).removeClass('dragover');

        const modal = $(this).closest('.modal');
        const input = modal.find('.fileTemp')[0];

        const file = e.originalEvent.dataTransfer.files[0];
        if (!file) return;

        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;

        $(input).trigger('change');
    });

    /* ======================= CHANGE FILE ======================= */
    $(document).on('change', '.fileTemp', function(e) {

        const modal = $(this).closest('#uploadModal');
        const file = e.target.files[0];
        if (!file) return;

        const acceptInput = modal.data('accept');
        const inputName = modal.data('inputName');

        /* VALIDATION TYPE */
        let isValid = false;

        if (acceptInput === 'image' && file.type.indexOf('image/') === 0) isValid = true;
        else if (acceptInput === 'pdf' && file.type === 'application/pdf') isValid = true;
        else if (acceptInput === 'image,pdf') {
            if (file.type.indexOf('image/') === 0 || file.type === 'application/pdf') isValid = true;
        }

        if (!isValid) {
            let errInfo = $.getErrorInfo('SYS-FRM-E003')

            $.invyAlert({
                title: errInfo.code,
                text: errInfo.message,
                icon: errInfo.level,
                cabtn: errInfo.cabtn,
                catext: errInfo.catext
            })

            $(this).val('');
            return;
        }

        /* VALIDATION SIZE */
        if (file.size > defaultMaxSize * 1024 * 1024) {
            let errInfo = $.getErrorInfo('SYS-FRM-E002')

            $.invyAlert({
                title: errInfo.code,
                text: errInfo.message,
                icon: errInfo.level,
                cabtn: errInfo.cabtn,
                catext: errInfo.catext
            })

            $(this).val('');
            return;
        }

        const preview = modal.find('.previewContainer');
        const img = modal.find('.imagePreview');
        const pdf = modal.find('.pdfPreview');

        preview.show();
        modal.find('.btnRemoveFile').show();
        img.hide();
        pdf.hide();

        /* ===== IMAGE ===== */
        if (file.type.indexOf('image/') === 0) {

            const reader = new FileReader();

            reader.onload = function(e) {

                const imageUrl = e.target.result;

                img.attr('src', imageUrl).show();

                // SIMPAN SETELAH ADA HASIL
                modalState[inputName] = {
                    file: file,
                    imageUrl: imageUrl,
                    pdfUrl: null,
                    isFromDB: false
                };
            };

            reader.readAsDataURL(file);
        }

        /* ===== PDF ===== */
        else {

            const pdfUrl = URL.createObjectURL(file);

            pdf.show();
            modal.find('.btnViewPdf').data('url', pdfUrl);

            modalState[inputName] = {
                file: file,
                imageUrl: null,
                pdfUrl: pdfUrl,
                isFromDB: false
            };
        }

        // sync to real input
        const dt = new DataTransfer();
        dt.items.add(file);
        $('input[name="remove_' + inputName + '"]').val('0');

        const input = $('input[name="' + inputName + '"]')[0];

        if (input) {
            input.files = dt.files;
        }
    });

    /* ======================= REMOVE ======================= */
    $(document).on('click', '.btnRemoveFile', function() {

        const modal = $(this).closest('#uploadModal');
        const inputName = modal.data('inputName');

        modal.find('.fileTemp').val('');
        modal.find('.previewContainer').hide();
        modal.find('.imagePreview').attr('src', '').hide();
        modal.find('.pdfPreview').hide();
        modal.find('.btnRemoveFile').hide();

        delete modalState[inputName];

        const realInput = $('#' + inputName)[0];
        if (realInput) realInput.value = '';

        $('input[name="remove_' + inputName + '"]').val('1');
    });

    /* ======================= VIEW PDF ======================= */
    $(document).on('click', '.btnViewPdf', function() {

        const modal = $(this).closest('#uploadModal');
        const inputName = modal.data('inputName');
        const state = modalState[inputName];

        let url = null;

        if (state) {
            if (state.file) url = URL.createObjectURL(state.file);
            else if (state.pdfUrl) url = state.pdfUrl;
        }

        if (!url) return;

        // detect mobile
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

        if (isMobile) {
            // open directly (mobile-safe)
            window.open(url, '_blank');
            return;
        }

        // desktop → custom viewer
        const newWindow = window.open('', '_blank',
            'width=900,height=700,toolbar=no,menubar=no,scrollbars=yes');

        if (!newWindow) {
            alert('Popup blocked! Please allow popups.');
            return;
        }

        newWindow.document.open();
        newWindow.document.write(`
        <html>
            <head>
                <title>PDF Preview</title>
                <style>
                    body {
                        margin:0;
                        background:#000;
                    }
                    iframe {
                        width:100%;
                        height:100vh;
                        border:none;
                    }
                </style>
            </head>
            <body>
                <iframe src="${url}"></iframe>
            </body>
        </html>
    `);
        newWindow.document.close();

    });

    /* ======================= VIEW IMAGE ======================= */
    $(document).on('click', '.imagePreview', function() {

        const modal = $(this).closest('#uploadModal');
        const inputName = modal.data('inputName');
        const state = modalState[inputName];

        let url = null;

        if (state) {
            if (state.file) url = URL.createObjectURL(state.file);
            else if (state.imageUrl) url = state.imageUrl;
        }

        if (!url) return;

        const newWindow = window.open('', '_blank',
            'width=900,height=700,toolbar=no,menubar=no,scrollbars=yes');

        if (!newWindow) {
            alert('Popup blocked! Please allow popups.');
            return;
        }

        newWindow.document.open();
        newWindow.document.write(`
        <html>
            <head>
                <title>Image Preview</title>
                <style>
                    body {
                        margin:0;
                        display:flex;
                        justify-content:center;
                        align-items:center;
                        background:#000;
                    }
                    img {
                        max-width:100%;
                        max-height:100vh;
                    }
                </style>
            </head>
            <body>
                <img src="${url}">
            </body>
        </html>
    `);
        newWindow.document.close();

    });

});