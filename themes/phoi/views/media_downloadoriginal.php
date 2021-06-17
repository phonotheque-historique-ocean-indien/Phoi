<?php
$url = $this->getVar("url");
$name = $this->getVar("name");
header('Content-Type: application/octet-stream');
header("Content-disposition: attachment; filename=\"" . $name . "\""); 
readfile($url);exit();