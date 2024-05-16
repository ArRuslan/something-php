<?php namespace IdkChat\Webpages;

interface BaseView {
    public function getTitle(): string;

    public function setTitle(string $title): BaseView;

    public function getHeader(): string;

    public function setHeader(string $header): BaseView;

    public function getBody(): string;

    public function setBody(string $body): BaseView;

    public function getFooter(): string;

    public function setFooter(string $footer): BaseView;
}