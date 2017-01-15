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
The best way is to get the phar archive from here [web-md-generator.phar](http://cloud.arthurh.fr/public.php?service=files&t=aaf3cdad4a9147fee9ffd4fe96605843&download).

Put this archive inside your future website with all your markdown files inside (Markdown files should have .md extension).
Finally run:
```shell
php web-md-generator.phar
```
After you can simply add other markdown files and regenerate to add in your menu. And you can also simply modified `.html` generated, it's still markdown inside so it will be easy to modify.
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

Custom your menu
----------------
When you generate you will have a `menu.yml`, you can modify it to set link and/or menu name.
```yaml
menu:
    - { index: 'read me' } #on the left file without .html extension and on the right your menu name
    - { 'who am i': 'who am i' }
```

Example and demos
-----------------
For the example go to: [https://github.com/ArthurHlt/web-md-generator-example](https://github.com/ArthurHlt/web-md-generator-example)

Demo with `default` style with menu on top: [http://web-md-generator.arthurh.fr/default-top/](http://web-md-generator.arthurh.fr/default-top/)

Demo with `default` style with menu on side: [http://web-md-generator.arthurh.fr/default-side/](http://web-md-generator.arthurh.fr/default-side/)

Demo with `bootstrap` style: [http://web-md-generator.arthurh.fr/bootstrap/](http://web-md-generator.arthurh.fr/bootstrap/)
