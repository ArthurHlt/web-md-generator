<?php
/**
 * Created by IntelliJ IDEA.
 * User: arthurhalet
 * Date: 29/06/14
 * Time: 17:18
 */

namespace ArthurH\JrGenerator;

use Arhframe\Util\File;
use Arhframe\Util\Folder;
use Symfony\Component\Yaml\Yaml;

class JrGenerate
{
    private $footer = false;
    private $mdFiles = array();
    private $option = array();
    private $menu = array('menu' => array());
    private $containsCssFw = array('bootstrap', 'default');
    private $css = array(
        'bootstrap' => 'themes/bootstrap/css/bootstrap.css',
        'default' => 'themes/default.css'
    );
    private $cssStyle = null;
    private $finalExtension = 'html';
    private $jqueryUrl = '//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';

    public function __construct($folder)
    {
        $this->folderPath = $folder;
        $folderOld = new Folder($folder . '/old');
        if ($folderOld->isFolder()) {
            $this->recurseCopy($folderOld->absolute(), $this->folderPath);
            @$folderOld->remove();
        }
        $this->getMdFiles();
        if (!is_file(getcwd() . '/options.yml')) {
            $yamlarh = new \Arhframe\Yamlarh\Yamlarh(__DIR__ . '/../../../options.yml');
            copy(__DIR__ . '/../../../options.yml', $folder .'/options.yml');
        } else {
            $yamlarh = new \Arhframe\Yamlarh\Yamlarh(getcwd() . '/options.yml');
        }

        $this->option = $yamlarh->parse();
        $this->option['body'] = null;
        $this->option['markdownContent'] = null;
        $this->option['plugins'] = array();
        if (is_file(getcwd() . '/menu.yml')) {
            $yamlarh = new \Arhframe\Yamlarh\Yamlarh(getcwd() . '/menu.yml');
            $this->menu = $yamlarh->parse();
        }
    }

    public function recurseCopy($src, $dst, $firstFolder = true)
    {

        $dir = opendir($src);
        $result = ($dir === false ? false : true);

        if ($result !== false) {
            if (!$firstFolder) {
                $result = @mkdir($dst);
            }
            if ($result === true) {
                while (false !== ($file = readdir($dir))) {
                    if (($file != '.') && ($file != '..') && $result) {
                        if (is_dir($src . '/' . $file)) {
                            $result = $this->recurseCopy($src . '/' . $file, $dst . '/' . $file, false);
                        } else {
                            $result = copy($src . '/' . $file, $dst . '/' . $file);
                        }
                    }
                }
                closedir($dir);
            }
        }

        return $result;
    }

    public function copyJr()
    {
        $this->recurseCopy(__DIR__ . '/../../../jr', $this->folderPath);
    }

    public function generateHtml()
    {
        foreach ($this->mdFiles as $mdFile) {
            $content = $mdFile->getContent();
            $mdFile->remove();
            if ($mdFile->getBase() == $this->option['index']) {
                $mdFile->setBase('index');
            }
            if (!$this->isInMenu($mdFile->getBase())) {
                $this->menu['menu'][] = array($mdFile->getBase() => $mdFile->getBase());
            }
            $mdFile->setExtension($this->finalExtension);
            $mdFile->setContent("$content\n<script src=\"" . $this->jqueryUrl . "\"></script>\n<script src=\"js/options.js\"></script>\n<script src=\"js/jr.js\"></script>");
            if ($mdFile->getBase() == 'footer') {
                $this->footer = true;
            }
        }
        $fileMenu = new File(getcwd() . '/menu.yml');
        $fileMenu->setContent(Yaml::dump($this->menu));
    }

    public function isInMenu($base)
    {
        foreach ($this->menu['menu'] as $menu) {
            foreach ($menu as $menuName => $value) {
                if ($menuName == $base) {
                    return true;
                }
            }
        }
        return false;
    }

    public function generateFooter()
    {
        if ($this->footer) {
            return;
        }
        $footer = new File(__DIR__ . '/../../../footer.html');
        $content = $footer->getContent();
        $footer->setFolder($this->folderPath);
        $footer->setContent($content);
    }

    public function getFavicon()
    {
        $folder = new Folder($this->folderPath);
        $favicon = $folder->getFiles('#favicon\.(png|jpg|jpeg|gif|ico|svg)$#i');
        if (count($favicon) == 0) {
            $this->option['favicon'] = null;
            return;
        }
        $favicon = $favicon[0];
        $this->option['favicon'] = $favicon->getName();
    }

    public function getLogo()
    {
        $folder = new Folder($this->folderPath);
        $logo = $folder->getFiles('#logo\.(png|jpg|jpeg|gif|ico|svg)$#i');
        if (count($logo) == 0) {
            $this->option['logo'] = null;
            return;
        }
        $logo = $logo[0];
        $this->option['logo'] = $logo->getName();
    }

    public function generateOption()
    {
        $jsOption = "var jr = " . json_encode($this->option) . ";";
        $jsOptionFile = new File($this->folderPath . '/js/options.js');
        $jsOptionFile->setContent($jsOption);
    }

    public function generateMenu()
    {
        if (!empty($this->option['noMenu'])) {
            return;
        }
        if (!empty($this->cssStyle)) {
            $method = 'generateMenu' . ucfirst($this->cssStyle);
        } else {
            $method = 'generateMenuDefault';
        }
        $content = $this->$method();
        $jsOptionFile = new File($this->folderPath . '/menu.html');
        $jsOptionFile->setContent($content);
    }

    public function generateMenuDefault()
    {
        if ($this->option['menuPosition'] == 'side' || $this->option['menuPosition'] == 'left') {
            $class = 'left';
        } else {
            $class = 'top';
        }

        $content = '<nav class="' . $class . '"><ul>';
        foreach ($this->menu['menu'] as $menu) {
            $content .= '<li><a href="' . key($menu) . '.' . $this->finalExtension . '">'
                . current($menu) . '</a></li>';
        }
        $content .= '</ul></nav>';
        return $content;
    }

    public function generateMenuBootstrap()
    {
        $content = '<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.html">' . $this->option['title'] . '</a>
          <div class="nav-collapse collapse">
            <ul class="nav">';
        foreach ($this->menu['menu'] as $menu) {
            $content .= '<li><a href="' . key($menu) . '.' . $this->finalExtension . '">'
                . current($menu) . '</a></li>';
        }
        $content .= '
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>';
        return $content;
    }

    public function getFwCssStyle()
    {
        $keyToRemove = null;
        if (!is_array($this->option['styles'])) {
            return;
        }
        foreach ($this->option['styles'] as $key => $style) {
            if (in_array($style, $this->containsCssFw)) {
                $keyToRemove = $key;
                $this->cssStyle = $style;
            }
        }
        if ($keyToRemove === null) {
            $this->option['cssFw'] = null;
            return;
        }
        $this->option['styles'][$keyToRemove] = $this->css[$this->cssStyle];
        $this->option['cssFw'] = $this->cssStyle;
    }

    public function generate()
    {
        $this->getFwCssStyle();
        $this->getLogo();
        $this->getFavicon();
        $this->copyJr();
        $this->generateHtml();
        $this->generateFooter();
        $this->generateOption();
        $this->generateMenu();

    }

    public function getMdFiles()
    {
        $folder = new Folder($this->folderPath);
        $this->mdFiles = $folder->getFiles('#.*\.md$#');
    }
} 
