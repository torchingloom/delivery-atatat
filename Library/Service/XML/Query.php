<?php
namespace Service;

class XML_Query
{
    private $_xmlDoc;
    
    public function setDocumentRoot($node)
    {
        $this->_xmlDoc = $node;
    }
	
	function __construct($fileName = null)
    {
	    if(isset($fileName))
        {
            $this->load($fileName);   
	    }
	}
	
	public function load($fileName)
    {
	     $this->_xmlDoc = simplexml_load_file($fileName);   
	}
	
	public function getNodeListByPath($patern)
    {
	    $a = array();
	    foreach ($this->_xmlDoc->xpath($patern) as $node)
        {
	        array_push($a, new XML_Node($node));
	    }
	    return $a;
	}
	
    public function getNodeByPath($patern)
    {
        if ($nodes = $this->getNodeListByPath($patern))
        {
            return $nodes[0];
        }
        return null;
	}

}