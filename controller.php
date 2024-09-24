<?php
namespace Concrete\Package\EasyImageGallery;

defined('C5_EXECUTE') or die('Access Denied.');
use \Concrete\Core\Block\BlockType\BlockType;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Route;
use Events;
use Loader;

use Concrete\Package\EasyImageGallery\Src\Helper\MclInstaller;

class Controller extends \Concrete\Core\Package\Package {

    protected $pkgHandle = 'easy_image_gallery';
    protected $appVersionRequired = '5.8';
    protected $pkgVersion = '1.4.2';
    protected $pkg;

    public function getPackageDescription() {
        return t("Easy Image made gallery easy for your client");
    }

    public function getPackageName() {
        return t("Easy Image Gallery");
    }

    public function on_start() {

        $this->registerRoutes();
        $this->registerAssets();
    }

    public function registerAssets() {
        $al = AssetList::getInstance();


        $al->register( 'css', 'view', 'blocks/easy_image_gallery/view.css', 
            array('version' => '1.0.0', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );

        /* https://github.com/aterrien/jQuery-Knob */        
        $al->register( 'javascript', 'knob', 'blocks/easy_image_gallery/javascript/build/jquery.knob.js', 
            array('version' => '1.2.12', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
        /* https://github.com/blueimp/jQuery-File-Upload */            
        $al->register( 'javascript', 'jquery/file-upload', 'blocks/easy_image_gallery/javascript/build/jquery.fileupload.js', 
            array('version' => '10.32.0', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), 
            $this );                 
        $al->register( 'css', 'jquery/file-upload', 'blocks/easy_image_gallery/stylesheet/jquery.fileupload.css', 
            array('version' => '10.32.0', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
                 
        $al->register( 'javascript', 'easy-gallery-edit', 'blocks/easy_image_gallery/javascript/build/block-edit.js', 
            array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );                 
        $al->register( 'css', 'easy-gallery-edit', 'blocks/easy_image_gallery/stylesheet/block-edit.css', 
            array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );                 
        $al->register( 'css', 'easy-gallery-view', 'blocks/easy_image_gallery/stylesheet/block-view.css',         
            array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );

        /* https://vitalets.github.io/x-editable/ */
        $al->register( 'javascript', 'editable', 'blocks/easy_image_gallery/javascript/build/bootstrap5-editable.js', 
            array('version' => '1.5.3', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this ); 
        $al->register( 'css', 'editable', 'blocks/easy_image_gallery/stylesheet/bootstrap5-editable.css',         
            array('version' => '1.5.3', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
            

        /* was removed with C5 v9, we put it here */
        /* https://github.com/select2/select2 */
        $al->register( 'javascript', 'select2', 'blocks/easy_image_gallery/javascript/build/select2.js', 
            array('version' => '3.5.3', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), 
            $this );
        $al->register( 'css', 'select2', 'blocks/easy_image_gallery/stylesheet/select2.css', 
            array('version' => '3.5.3', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), 
            $this );

        // View items
        /* https://github.com/tholman/intense-images */
        $al->register( 'javascript', 'intense', 'blocks/easy_image_gallery/javascript/build/intense.js',
            array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
        /* https://github.com/fancyapps/fancybox latest free version */
        $al->register( 'javascript', 'fancybox', 'blocks/easy_image_gallery/javascript/build/jquery.fancybox.pack.js', 
            array('version' => '2.1.5', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true),             
            $this );        
        $al->register( 'css', 'fancybox', 'blocks/easy_image_gallery/stylesheet/jquery.fancybox.css', 
            array('version' => '2.1.5', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
        /* https://github.com/desandro/masonry */            
        $al->register( 'javascript', 'masonry', 'blocks/easy_image_gallery/javascript/build/masonry.pkgd.min.js', 
            array('version' => '4.2.2', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
        /* https://github.com/desandro/imagesloaded */            
        $al->register( 'javascript', 'imagesloaded', 'blocks/easy_image_gallery/javascript/build/imagesloaded.pkgd.js', 
            array('version' => '4.1.4', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
        /* https://isotope.metafizzy.co */            
        $al->register( 'javascript', 'isotope', 'blocks/easy_image_gallery/javascript/build/isotope.pkgd.min.js', 
            array('version' => '3.1.4', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );
        /* https://github.com/tuupola/jquery_lazyload */            
        $al->register( 'javascript', 'lazyload', 'blocks/easy_image_gallery/javascript/build/jquery.lazyload.js', 
            array('version' => '1.9.7', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => true), 
            $this );    
    }

    public function registerRoutes() {
        Route::register('/easyimagegallery/tools/savefield','\Concrete\Package\EasyImageGallery\Controller\Tools\EasyImageGalleryTools::save');
        Route::register('/easyimagegallery/tools/getfilesetimages','\Concrete\Package\EasyImageGallery\Controller\Tools\EasyImageGalleryTools::getFileSetImage');
        Route::register('/easyimagegallery/tools/getfiledetailsjson','\Concrete\Package\EasyImageGallery\Controller\Tools\EasyImageGalleryTools::getFileDetailsJson');
    }

    public function install() {

        // Get the package object
        $this->pkg = parent::install();

        // Installing
        $this->installOrUpgrade();
    }


    private function installOrUpgrade() {
        $ci = new MclInstaller($this->pkg);
        $ci->importContentFile($this->getPackagePath() . '/config/install/base/blocktypes.xml');
        $ci->importContentFile($this->getPackagePath() . '/config/install/base/attributes.xml');
    }

    public function upgrade () {
        $this->pkg = $this;

        $this->installOrUpgrade();
        parent::upgrade();
    }

}
