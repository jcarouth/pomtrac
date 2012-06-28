<?php

namespace PomTrac\Helpers;

function buildUrl($request, $uri = '') {
    return rtrim(
        sprintf(
            "%s://%s/%s",
            $request->getScheme(),
            ($request->getPort() === 80) ? $request->getHost() : $request->getHostWithPort(),
            $uri
        ),
        '/'
    );
}

function convertMongoTypes(&$item) {
    //fix mongo data types
    $item['id'] = (string)$item['_id'];
    $item['createdDate'] = date('c', $item['createdDate']->sec);

    if ($item['estimate'] === null) {
        $item['estimate'] = '';
    }

    unset($item["_id"]);
}
