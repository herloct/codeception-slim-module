<?php
$I = new FunctionalTester($scenario);
$I->wantTo('upload file');

$I->sendPOST('/rest', [], [
    'dump' => new \Slim\Http\UploadedFile(codecept_data_dir('dump.sql'), 'dump.sql', 'text/plain', 57, 0)
]);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(['files' => [
    'dump' => [
        'name' => 'dump.sql',
        'size' => 57,
    ]
]]);