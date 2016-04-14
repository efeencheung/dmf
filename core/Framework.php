<?php

namespace DMF;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DiYamlFileLoader;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class Framework
{
    protected $booted = false;
    protected $isDebug;

    public function __construct($isDebug=false)
    {
        $this->isDebug = $isDebug;
    }

    public function boot()
    {
        if (true === $this->booted) {
           return; 
        }

        $container = $this->initializeContainer();

        $this->booted = true;

        return $container;
    }

    /**
     * 初始化Container
     * 
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function initializeContainer()
    {
        /* 把配置目录加入Locator，方便读取各种配置文件 */
        $locator = new FileLocator(array(__DIR__ . '/../config'));

        /* 定义Container缓存，具体用法参照ConfigCache组件的文档 */
        $file = __DIR__ .'/../cache/container.php';
        $containerConfigCache = new ConfigCache($file, $this->isDebug);

        /* 
         * 初始化Container 
         */
        if (!$containerConfigCache->isFresh()) {
            $containerBuilder = new ContainerBuilder();
            $containerBuilder->setParameter('root_dir', realpath(__DIR__ . '/..'));
            $loader = new DiYamlFileLoader($containerBuilder, $locator);
            $loader->load('config.yml');
            $loader->load('services.yml');

            /* 如果调试模式开启，关闭Route缓存 */
            if ($this->isDebug) {
                $routerDefinition = $containerBuilder->getDefinition('router');   
                $routerDefinition->replaceArgument(2, array());
            }

            /* 添加EventDispatcher到Container，并使用tag标记，以便可以在service中配置事件，初始化时被加载 */
            $containerBuilder->addCompilerPass(new RegisterListenersPass(), PassConfig::TYPE_BEFORE_REMOVING);
            $containerBuilder->compile();

            $dumper = new PhpDumper($containerBuilder);
            $containerConfigCache->write(
                $dumper->dump(array('class' => 'CachedContainer')),
                $containerBuilder->getResources()
            );
        }

        require_once $file;
        $container = new \CachedContainer();   

        return $container;
    }
}
