<?php
if (file_exists('partials/nav.php')) {
    include 'partials/nav.php';
    echo 'Login to explore';
} else {
    echo "Navigation file not found.";
}
