<?php require "vendor/autoload.php";

use Ciarand\Pulp\Pulp as Pulp;
use Ciarand\Pulp\Plugins;

$pulp    = new Pulp;
$concat  = $pulp->requirePlugin(Plugins\Concat::className());
$assetic = $pulp->requirePlugin(Plugins\Assetic::className());
$sass    = $assetic->createFilter("ScssphpFilter");
$jsmin   = $assetic->createFilter("JSqueezeFilter");

$pulp->task("scss", [], function () use ($pulp, $sass) {
    $pulp->log("Compiling SCSS files");

    $pulp->src("**/*.scss")
        ->pipe($sass(["enableCompass" => true]))
        ->pipe($pulp->dest("./dest/"));
});

$pulp->task("js", [], function () use ($pulp, $jsmin, $concat) {
    $pulp->log("Minifying JS files");

    $pulp->src("**/*.js")
        ->pipe($concat())
        ->pipe($pulp->dest("./dest/all.js"))
        ->pipe($jsmin())
        ->pipe($pulp->dest("./dest/all.min.js"));
});

$pulp->task("default", ["scss"], function () use ($pulp) {
    $pulp->log("Watching SCSS files");

    $pulp->watch("{.,**}/*.scss", ["scss"]);
});

$pulp->run();
