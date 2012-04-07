<?php
namespace Service;


class XML_Node
{
    private $_xmlNode;
    
    function __construct($nodes)
    {
	    $this->_xmlNode = $nodes;
	}
	
    public function getNodeListByPath($patern)
    {
        $a = array();
	    foreach ($this->_xmlNode->xpath($patern) as $node)
        {
	        array_push($a, new self($node));
	    }
	    return $a;
	}
	
    public function getAttributes()
    {
        $_ = array();
        foreach ($this->_xmlNode->attributes() AS $name => $valuev)
        {
            $_[$name] = (string) $valuev;
        }
	    return $_;
	}

    public function getAttribute($name)
    {
	    return (string) $this->_xmlNode->attributes()->{$name};
	}
	
    public function getTagName()
    {
	    return (string) $this->_xmlNode->getName();
	}
	
    public function getNodeValue($name = 0)
    {
	    return (string) $this->_xmlNode->{$name};
	}
	
	public function insertNode($xml, $patern, $position = 'bottom'){
	    $owner = new \DOMDocument('1.0', 'UTF-8');
	    $owner->loadXML($this->_xmlNode->asXML());
	    
	    $doc = $owner->createDocumentFragment();
        $doc->appendXML($xml);
	    
	    $xpath = new \DOMXPath($owner);
	    if($patern == '' || ($node = $xpath->query($patern)) == null) {
	        $node = $owner->firstChild;    
	    }
	    
	    if($position == 'bottom') {
	        $node->item(0)->appendChild($doc);    
	    } else {
	        $node->item(0)->parentNode->insertBefore($doc, $node->item(0));
	    }
	    
	    $this->_xmlNode = simplexml_import_dom($owner->documentElement);
	}
	
	public function ifDefAttribute($name, $default)
    {
	    $attrValue = $this->getAttribute($name); 
	    return !empty($attrValue) ? $attrValue : $default;    
	}
    
	public function getChild($name = null)
    {
	    return new self($this->_xmlNode->children($name));
	}
}