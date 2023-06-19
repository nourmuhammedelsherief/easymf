<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAAHotEoPs:APA91bG-BO8_ITXtsmxoKpk-aWdD__1aEtt-42bYWiqRkMOAtqGvJylUaCCb2s3zUEB0fgQGEJr5T-jBiYBKTqgFuiTKcWSwEcXLsU0DNu330UkgU61VTun_FqARZYKzjnkdsP3p7X7V'),
        'sender_id' => env('FCM_SENDER_ID', '131185549563'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'server_topic_url' => 'https://iid.googleapis.com/iid/v1/',
        'timeout' => 30.0, // in second
    ],
];
