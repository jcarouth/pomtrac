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
