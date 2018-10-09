<?php

return [

    'FILE_TYPE_FOLDER' => 'folder',
    'MAX_FILE_SIZE' => 500,// In Kilobytes
    'FILE_UPLOAD_PATH' => '/public/uploads/',
    'FILE_UPLOAD_PATH_COMPLETE' => storage_path('app') . '/public/uploads/',
    'USER_FILE_KEY_PARENT_ID' => 'parent_id',
    'USER_FILE_KEY_NAME' => 'name',
    'FILE_SIZE_CALCULATE_EXPRESSION' => '/1000'// In Kilobytes
];
