<?php
    $data = json_decode(file_get_contents("php://input"));
    $request = $data->request;
    if ($request == 1) {
        $key = $data->key;
        $file = fopen("public.txt", 'w');
        fwrite($file, $key);

        echo "Update successfully";
    }
    if ($request == 2) {
        $key = $data->key;
        $file = fopen("private.txt", 'w');
        fwrite($file, $key);

        echo "Update successfully";
    }
    if ($request == 3) {
        $file = fopen("public.txt", 'r');
        $response = fread($file, filesize("public.txt"));
        echo $response;
    }
    if ($request == 4) {
        $file = fopen("private.txt", 'r');
        $response = fread($file, filesize("private.txt"));
        echo $response;
    }
?>