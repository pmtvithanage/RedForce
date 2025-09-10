<?php
    function uploadImage($img, $img_name, $location) {
        $target = PUB_ROOT.$location.$img_name;

        return move_uploaded_file($img, $target);
        
    }

    function updateImage($old, $img, $img_name, $location) {
        unlink($old);

        $target = PUB_ROOT.$location.$img_name;

        return move_uploaded_file($img, $target);
    }   

    function deleteImage($img) {
        if (file_exists($img)) {
            unlink($img);
            return true;
        }
        return false;
    }