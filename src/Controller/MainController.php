<?php

namespace Controller;

final class MainController extends BaseController {

    function index() {

        http_response_code(204);
        return '';
    }
}
