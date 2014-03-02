<?php

require "vendor/autoload.php";

use Ciarand\Pulp\Manager as Pulp;

$pulp = new Pulp;

$pulp->task("scss", [], function () use ($pulp) {
    $pulp->log("Compiling SCSS files");

    $pulp->src("**/*.scss")
        ->pipe($pulp->dest("./dest/"));
});

$pulp->task("default", ["scss"], function () use ($pulp) {
    $pulp->log("Watching SCSS files");

    $pulp->watch("{.,**}/*.scss", ["scss"]);
});

$pulp->run();
