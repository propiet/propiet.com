<?php

namespace NucleusHub\CmsBundle;

use NucleusHub\CmsBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NucleusHubCmsBundle extends Bundle
{
	public function build(ContainerBuilder $container)
    {
		parent::build($container);

	    $extension = $container->getExtension('security');
	    $extension->addSecurityListenerFactory(new WsseFactory());
	}
}
