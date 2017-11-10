<?php
/**
 * This file is meant to be renamed to index.php and placed in /home/okbvtfr/public_html/data_center/app_home/webroot
 * of the production server (overwriting the default index.php file)
 *
 * Some absolutely inexplicable mechanism (discussed in support ticket YVQ-566-11911) is causing
 * https://cberdata.org (note the https) to have the aforementioned directory as its document root, and Interserver
 * support has not (yet) determined any cause or way to correct that.
 *
 * This file hacks a solution by routing requests back to the correct document root,
 * /home/okbvtfr/public_html/data_center_home/webroot
 */

include '../../../data_center_home/webroot/index.php';
