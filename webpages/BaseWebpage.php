<?php namespace IdkChat\Webpages;

interface BaseWebpage {
    public function getTitle(): string;

    public function setTitle(string $title): BaseWebpage;

    public function getHeader(): string;

    public function setHeader(string $header): BaseWebpage;

    public function getBody(): string;

    public function setBody(string $body): BaseWebpage;

    public function getFooter(): string;

    public function setFooter(string $footer): BaseWebpage;
}