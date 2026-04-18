<?php
function ensureUploadDirectory($location)
{
    $base = rtrim(PUB_ROOT, '/');
    $relative = '/' . ltrim($location, '/');
    $directory = dirname($base . $relative . 'placeholder');

    if (!is_dir($directory) && !@mkdir($directory, 0777, true)) {
        return false;
    }

    return is_writable($directory);
}

function uploadImage($img, $img_name, $location)
{
    if (!ensureUploadDirectory($location)) {
        return false;
    }

    $target = PUB_ROOT . $location . $img_name;

    return @move_uploaded_file($img, $target);
}

function updateImage($old, $img, $img_name, $location)
{
    if (!ensureUploadDirectory($location)) {
        return false;
    }

    if (!empty($old) && file_exists($old)) {
        @unlink($old);
    }

    $target = PUB_ROOT . $location . $img_name;

    return @move_uploaded_file($img, $target);
}

function deleteImage($img)
{
    if (file_exists($img)) {
        @unlink($img);
        return true;
    }
    return false;
}
