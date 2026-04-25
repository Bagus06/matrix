$(function() {
    const $modal = $("#modal-upload-photo");
    const canvas = $("canvas")[0];
    const ctx = canvas.getContext("2d");
    const container = $("#editor-container");

    let img = new Image();
    let scale = 1;
    let rotation = 0;
    let flipH = 1;
    let flipV = 1;
    let posX = 0;
    let posY = 0;
    let isDragging = false;
    let startX = 0;
    let startY = 0;

    canvas.width = container.width();
    canvas.height = container.height();

    /* UPLOAD */
    $("#btn-upload, #avatar-placeholder").on("click", function(e) {
        $("#file-input").click()
        $.loader('show')
    });

    // DRAW FUNCTION
    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.save();

        ctx.translate(canvas.width / 2 + posX, canvas.height / 2 + posY);
        ctx.rotate(rotation);
        ctx.scale(scale * flipH, scale * flipV);
        ctx.drawImage(img, -img.width / 2, -img.height / 2);

        ctx.restore();
    }

    // HANDLE UPLOAD
    $("#file-input").on("change", function() {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(ev) {
            img.onload = () => draw();
            img.src = ev.target.result;
        };
        reader.readAsDataURL(file);

        $modal.modal('show')
        $.loader('hide')
    }).on("cancel", function() {
        $.loader('hide')
    });

    // ROTATE
    $("#rotate").on("click", () => {
        rotation += Math.PI / 2;
        draw();
    });

    // FLIP
    $("#flip-h").on("click", () => {
        flipH *= -1;
        draw();
    });
    $("#flip-v").on("click", () => {
        flipV *= -1;
        draw();
    });

    // ZOOM
    $('#zoom').on('input', function(params) {
        scale = $(this).val();
        draw();
    })

    function wheelZoom(e, el) {
        let step = parseFloat(el.attr("step")) || 0.01;
        let min = parseFloat(el.attr("min"));
        let max = parseFloat(el.attr("max"));
        let val = parseFloat(el.val());

        if (e.originalEvent.deltaY < 0) {
            val += step;
        } else {
            val -= step;
        }
        val = Math.min(Math.max(val, min), max);

        el.val(val).trigger("input");
    }

    $("#zoom").on("wheel", function(e) {
        e.preventDefault();
        wheelZoom(e, $(this))
    });

    $('#canvas').on('wheel', function(e) {
        e.preventDefault();
        wheelZoom(e, $('input[id="zoom"]'))
    })

    // DRAG / MOVE
    $("#canvas").on("mousedown", function(e) {
        isDragging = true;
        startX = e.clientX - posX;
        startY = e.clientY - posY;
        canvas.style.cursor = "grabbing";
    });

    $(document).on("mousemove", function(e) {
        if (isDragging) {
            posX = e.clientX - startX;
            posY = e.clientY - startY;
            draw();
        }
    });

    $(document).on("mouseup mouseleave", function() {
        isDragging = false;
        canvas.style.cursor = "grab";
    });

    // CROP
    $("#save").on("click", function() {
        const tempCanvas = document.createElement("canvas");
        const tctx = tempCanvas.getContext("2d");

        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height;

        tctx.drawImage(canvas, 0, 0);
        const dataURL = tempCanvas.toDataURL("image/png");

        $.ajax({
            url: `${BASE_URL}users/profile_upload/${jsURI[3]}`,
            type: 'POST',
            dataType: 'json',
            async: true,
            data: {
                image: dataURL,
            },
            beforeSend: function() {
                $.loader('show');
            },
            success: function(response) {
                $("#avatar-preview").html(`<img src="${BASE_URL}assets/img/profile/${response}">`);

                // close modal
                $('*:focus').blur();
                $modal.modal('hide');
                $.loader('hide');
                $.invyAlert({
                    title: response.code,
                    text: response.message,
                    icon: response.level,
                    cabtn: response.cabtn || '',
                    catext: response.catext || false,
                    redirectUrl: response.redirectUrl
                })
            },
            error: function() {
                $.loader('hide');
            }
        });
    });

});