<?php
if (class_exists('ZipArchive')) {
    echo "👍 ZipArchive available (version: " . ZipArchive::CLAZZ . ")";
} else {
    echo "❌ ZipArchive NOT available";
}
