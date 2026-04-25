<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" data-backdrop="static" id="modal-upload-photo">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="editor-container">
                    <canvas id="canvas"></canvas>
                    <div class="note">Drag to move image. Use zoom slider or mouse wheel to scale.</div>
                </div>

                <div class="crop-right">
                    <div class="control-row">
                        <label class="small" for="zoom">Zoom</label>
                        <input id="zoom" class="range" type="range" min="0.05" max="2.5" step="0.01" value="1">
                    </div>

                    <div class="control-row">
                        <button class="btn btn-link-default" id="flip-h"><i class="fa-solid fa-left-right"></i></button>
                        <button class="btn btn-link-default" id="flip-v"><i class="fa-solid fa-up-down"></i></button>
                        <button class="btn btn-link-default" id="rotate"><i class="fa-solid fa-camera-rotate"></i></button>
                    </div>

                    <div class="actions" style="margin-top:auto">
                        <button class="btn btn-sm btn-primary" id="save">Save</button>
                        <button class="btn btn-sm btn-default" data-dismiss="modal" aria-label="Close">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>