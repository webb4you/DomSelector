<?php
namespace W4Y\Dom;

use W4Y\Dom\Element;
use Symfony\Component\DomCrawler\Crawler as Query;

class Selector implements \Iterator, \Countable
{
    private $position = 0;
    
    private $dom = null;
    
    private $queryResult = array();
    
    public function __construct(array $options = array())
    {
        $body = !empty($options['body']) ? $options['body'] : null;
        $dom = new Query();
        $dom->addContent($body);
        
        $this->dom = $dom;
    }
    
    public function setBody($body, $encoding = null)
    {
        $this->dom->addContent($body);
    }
    
    public function query($query, Element $context = null)
    {
        $this->resetResult();

        // Set default DOM
        $dom = $this->dom;

        // If context is given, search only within the context
        if (null !== $context) {

            $domDocument = $context->getAsDomDocument();
            $xml = $domDocument->saveXml();
            $tmpDom = new Query();
            $tmpDom->addContent($xml);
            
            // Overwrite DOM if using context
            $dom = $tmpDom;           
        }
        
        $res = array();
        
        try {
            $res = $dom->filter($query);
        } catch (\Exception $e) {
            trigger_error(sprintf($e->getMessage() . ' :: "%s"', $query));
        }
        
        $results = array();
        foreach ($res as $r) {
            $results[] = new Element($r);
        }
        
        $this->queryResult = $results;
        
        return $this;
    }
    
    public function resetResult()
    {
        $this->queryResult = array();
    }
    
    public function result()
    {
        return $this->queryResult;
    }
    
    public function count()
    {
        return count($this->queryResult);
    }
    
    public function first()
    {
        return current($this->queryResult);
    }
    
    public function rewind()
    {
        $this->position = 0;
    }
    
    public function current()
    {
        $current = ($this->valid()) 
            ? $this->queryResult[$this->position] 
            : null;
        
        return $current;
    }
    
    public function key()
    {
        return $this->position;
    }
    
    public function next()
    {
        ++$this->position;
    }
    
    public function valid()
    {
        return isset($this->queryResult[$this->position]);
    }
}