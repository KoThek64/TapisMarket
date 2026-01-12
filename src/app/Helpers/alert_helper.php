<?php

function set_success(string $message)
{
    session()->setFlashdata('success', $message);
}

function set_error(string $message)
{
    session()->setFlashdata('error', $message);
}

function set_info(string $message)
{
    session()->setFlashdata('info', $message);
}
