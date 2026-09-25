<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Servis adresi
    |--------------------------------------------------------------------------
    |
    | Test ortamı: https://servisler.yok.gov.tr/resttest/obs/
    | Canlı ortam: https://servisler.yok.gov.tr/rest/obs/
    |
    */

    'base_uri' => env('YOKSIS_BASE_URI', 'https://servisler.yok.gov.tr/resttest/obs/'),

    /*
    |--------------------------------------------------------------------------
    | Kimlik bilgileri
    |--------------------------------------------------------------------------
    */

    'username' => env('YOKSIS_USERNAME'),

    'password' => env('YOKSIS_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | HTTP ayarları
    |--------------------------------------------------------------------------
    |
    | timeout ve connect_timeout saniye, retry_delay milisaniye cinsindendir.
    | Yalnızca GET/HEAD istekleri yeniden denenir; POST istekleri mükerrer
    | kayıt oluşturmamak için denenmez.
    |
    */

    'timeout' => env('YOKSIS_TIMEOUT', 30),

    'connect_timeout' => env('YOKSIS_CONNECT_TIMEOUT', 10),

    'retries' => env('YOKSIS_RETRIES', 2),

    'retry_delay' => env('YOKSIS_RETRY_DELAY', 500),

    /*
    |--------------------------------------------------------------------------
    | Loglama
    |--------------------------------------------------------------------------
    |
    | İstekleri loglamak için bir log kanalı adı verin (ör. "stack", "daily").
    | null ise loglama kapalıdır. Kişisel veri sızmaması için sorgu
    | parametreleri ve istek/yanıt gövdeleri loglanmaz.
    |
    */

    'log_channel' => env('YOKSIS_LOG_CHANNEL'),

];
