<?php

namespace Controller;

class Router
{
	public static function create($router, $auth, $acl)
	{
		$routeData = new \Service\XML_Query(\Service\Config::get('route.path'));
		$routeArray = $routeData->getNodeListByPath('/route/*');
		
		$User = \Service\Auth\User::instance($auth);
		
		foreach ($routeArray as $item)
		{
			$_type = $item->getAttribute('type');
			
			
			switch ($_type)
			{
				case 'static':
					
					$router->addRoute
					(
						$item->getTagName(), 
						new \Zend_Controller_Router_Route_Static( $item->getAttribute('rule'),  array('controller' => $item->getAttribute('controller'), 'action' => $item->getAttribute('action')) )
					);      
					
					
				break;
				case 'regex':
					
					$defaults = array();
					
					foreach ($item->getNodeListByPath('defaults/*') as $defaultItem) 
					{
						$defaults[$defaultItem->getTagName()] = $defaultItem->getNodeValue();
					}
					
					$names = array();
					foreach ($item->getNodeListByPath('names/*') as $nameItem) 
					{
						$names[$nameItem->getTagName()] = $nameItem->getNodeValue();
					}
					
					$router->addRoute(
						$item->getTagName(),
						new \Zend_Controller_Router_Route_Regex
						(
							$item->getAttribute ( 'rule' ),
							array_merge
							(
								array
								(
									"controller" => $item->getAttribute('controller'),
									"action" => $item->getAttribute('action')
								),
								$defaults
							),
							$names,
							$item->getAttribute ( 'reverse' )
						)
					);
					
				break;
				default:
					
					$reqs = array();
					foreach ($item->getNodeListByPath('req/*') as $reqItem) 
					{
						$reqs[$reqItem->getTagName()] = $reqItem->getNodeValue();
					}
					
					$defaults = array();
					foreach ($item->getNodeListByPath('defaults/*') as $defaultItem) 
					{
						$defaults[$defaultItem->getTagName()] = $defaultItem->getNodeValue();
					}
					
					$router->addRoute(
						$item->getTagName(),
						new \Zend_Controller_Router_Route($item->getAttribute('rule'),  array_merge ( array ('controller' => $item->getAttribute('controller'), 'action' => $item->getAttribute('action')), $defaults), $reqs));
					
				break;
			}

		}
		
		return $router;
	}
	
}