web-md-generator
================

Create a static website from markdown files easily.

What is it?
-----------
It is a static website generator.
It will takes all your markdown files from a folder and make a real website (Thanks to [jr](https://github.com/Xeoncross/jr)).
It will also make a simple menu directly with your markdown files

Usage
-----
The best way is to get the phar archive from here [web-md-generator.phar](http://cloud.arthurh.fr/public.php?service=files&t=62c09b7e6e744c9a3371e3762299cead&download).
Put this archive inside your future website with all your markdown files inside (Markdown files should have .md extension).
Finally run:
```
php web-md-generator.phar
```

Change your option.yml
----------------------
When you will generate your website you will have a new `options.yml` created.
By default you will have that:
```yaml
styles:
  - "default"
  - "//fonts.googleapis.com/css?family=Average"
  - "//fonts.googleapis.com/css?family=Roboto:400,700"
charset: "UTF-8"
title: "My Website"
menuPosition: "top"
index: index
scripts:
  - "js/showdown.js"
```
`styles`: you can change `default` by `bootstrap` to get the bootstrap by twitter style. and add all css you want.
`title`: Change title by your website name.
`menuPosition`: change by `top` or `side`to have your menu on the side or on the top (only work for `default` style).
`index`: set your markdown file which will use as your index.html (don't put md extension).
`scripts`: add all javascript files you want (jquery is load by default).

And finally regenerate your website by running again `php web-md-generator.phar`.

Get a logo and favicon
----------------------
For favicon: Simply add a favicon.(png|ico|jpg|gif) at your root website folder and regenerate your website.
For logo: Simply add a logo.(png|ico|jpg|gif) at your root website folder and regenerate your website.

