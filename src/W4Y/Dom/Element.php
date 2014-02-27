<?php
namespace W4Y\Dom;

use Zend\Dom\Query;

class Element
{
    private $tag = null;
    
    private $parent = null;
    
    private $attributes = array();
    
    private $text = null;
    
    private $element = null;
    
    public function __construct(\DOMelement $el = null)
    {
        if (null !== $el) {
            $this->setElement($el);
        }
    }
    
    public function setElement(\DOMelement $el)
    {        
        $this->element = $el;
        $this->constructObject($el);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
    
    public function getAttribute($attrib)
    {
        $attribs = $this->getAttributes();
        
        if (empty($attribs[$attrib])) {
            return false;
        }
        
        return $attribs[$attrib];
    }
    
    public function getText()
    {
        return $this->text;
    }
    
    public function getName()
    {
        return $this->tag;
    }
    
    public function getDomElement()
    {
        return $this->element;
    }
    
    /**
     * Get this element's parent.
     * 
     * @return \W4Y\Crawler\Dom\ElementObject
     */
    public function getParent()
    {
        $parent = $this->parent;
        if (null === $parent) {
            $parent = new self($this->element->parentNode);
            $this->parent = $parent;
        }
        
        return $parent;
    }
    
    public function isValid()
    {
        
    }
    
    public function toArray()
    {
        $data = array(
            'tag' => $this->tag,
            'parentTag' => $this->getParent()->getName(),
            'attributes' => $this->attributes,
            'text' => $this->filterText($this->getText())
        );
        
        return $data;
    }
    
    public function __toString()
    {
        return $this->element->ownerDocument->saveXml($this->element);
    }
    
    public function toJson()
    {
        $arr = $this->toArray();
        return json_encode($arr);
    }
    
    private function constructObject($el)
    {
        $attributes = $el->attributes;
        $parent = $el->tagName;
        
        $this->tag = $this->filterText($el->tagName);
        $this->text = $this->filterText($el->textContent);

        for ($i = 0; $i < $attributes->length; $i++) {
            
            $attribute = $attributes->item($i);
            $this->attributes[$attribute->name] = $this->filterText($attribute->value);
        }
    }
    
    public function getAsDomDocument()
    {
        $domDocument = new \DOMDocument();
        $element = $this->getDomElement();
        $domDocument->appendChild($domDocument->importNode($element, true));
        
        return $domDocument;
    }
    
    private function filterText($text)
    {
        return preg_replace('/\s\s+/', ' ', $text);
    }
    
    public function query($query)
    {
        $sel = new Selector();
        $sel->setBody($this->getAsDomDocument()->saveXml());        
        $results = $sel->query($query);

        return $results;
    }
}

