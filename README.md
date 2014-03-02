Pulp
====
>A PHP build system configured in PHP, based off [gulp][gulpjs]

[gulpjs]: https://github.com/gulpjs/gulp

Why?
----
I got a chance to use Gulp on a personal project recently and fell in love with
the API. It's clean, well constructed, and very easy to read. Here's a sample:

```javascript
var gulp = require("gulp"),
    sass = require("gulp-sass");

gulp.task("default", ["css"], function() {
    gulp.watch("./assets/scss/**/*.scss", ["css"]);
});

gulp.task("css", function() {
    gulp.src("./assets/scss/*/*.scss")
        .pipe(sass())
        .pipe(gulp.dest("./dest/"));
});
```

And that's all it takes to render all your SCSS files. You can also add other
plugins to concat, minify, copy, rename, etc. It's incredibly powerful and, at
the same time, very easy to read and understand.

While PHP has some at least one [really nice build system][phing], writing huge
XML files is not a fun thing for me to do. So, on a whim, Pulp was born to
alleviate some of the pain I felt. Here's a sample of what I think it should
look like:

[phing]: http://phing.info

```php
<?php require "vendor/autoload.php";

$pulp    = new Ciarand\Pulp\Pulp;
$assetic = $pulp->requirePlugin(Ciarand\Pulp\Plugins\Assetic::className());
$sass    = $assetic->createFilter("ScssphpFilter");

$pulp->task("scss", [], function () use ($pulp, $sass) {
    $pulp->src("**/*.scss")
        ->pipe($sass(["enableCompass" => true]))
        ->pipe($pulp->dest("./dest/"));
});

$pulp->task("default", ["scss"], function () use ($pulp) {
    $pulp->watch("{.,**}/*.scss", ["scss"]);
});

$pulp->run();
```
It takes 5 more lines, but each task is represented by roughly the same amount
of code. Compare that to a Phing or Ant buildfile and you'll start to appreciate
the brevity.

Project goals
-------------
The end goal of this project is to offer a bridge solution for Phing tasks. That
way any Phing task can be run using Pulp, which keeps Pulp's overhead nice and
low. It also means plugin authors can continue writing plugins for Phing, and
means that users can take advantage of the huge number of Phing plugins
available. Phing.

Current state
-------------
This project is still very much in the alpha stages. It doesn't do anything
beyond copy files from place A to place B right now.

License
-------
The MIT License (MIT)
<p>Copyright © 2013 Ciaran Downey &lt;code@ciarand.me&gt;</p>

<p>Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “Software”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:</p>

<p>The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.</p>

<p>THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.</p>
