<?php namespace WebpageClasses;

interface BaseWebpage {
    public function getTitle(): String;
    public function setTitle(String $title): BaseWebpage;

    public function getHeader(): String;
    public function setHeader(String $header): BaseWebpage;

    public function getBody(): String;
    public function setBody(String $body): BaseWebpage;

    public function getFooter(): String;
    public function setFooter(String $footer): BaseWebpage;
}