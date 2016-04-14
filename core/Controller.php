<?php

namespace DMF;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ContainerAwareInterface
{
    public $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * 提供一个公用的render方法
     *
     * @param string $view 模板名称
     * @param array $parameters 模板变量
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($view, array $parameters=array())
    {
        $response = new Response();

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response; 
    }
}
