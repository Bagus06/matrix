<?php
$base_directory = base_url() . 'assets/modules/' . $this->uri->rsegments[1] . '/js/';

switch ($this->uri->rsegments[2]) {
    case 'main':
        // echo '<script src="' . $base_directory . '{js file name}';
        echo '<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>';
        break;
    case 'recycle':
        // echo '<script src="' . $base_directory . '{js file name}';
        break;
};
