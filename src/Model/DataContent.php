<?php

namespace Model;

class DataContent extends Element {

    public bool $send_telegram = false;
    public bool $send_email = false;
    public array $data;
}
