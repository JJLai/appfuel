<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Appfuel\Html\Tag;

use LogicException,
	InvalidArgumentException;

/**
 */
class GenericTag implements GenericTagInterface
{
	/**
	 * Used to hold html tag attributes
	 * @var TagAttributesInterface
	 */
	protected $attrs = null;

	/**
	 * Used to hold the contents of the tag
	 * @var TagContentInterface
	 */ 
	protected $content = null;

	/**
	 * Name of the html tag. This name will be used to generate the actual
	 * tag string ex) <table> </table> the tag name is table
	 */
	protected $tagName = null;

	/**
	 * Used to allow concrete classes extending this class to ensure no one can
	 * change the tag name of their class
	 * @var bool
	 */
	protected $isTagNameLocked = false;

	/**
	 * Used to determine if a full closing tag is needed
	 * @var bool
	 */
	protected $isClosingTag = true;

	/**
	 * Flag used to determine if the html tag should be rendered when it has
	 * no content. When false and no content exists and $isClosingTag is true
	 * then build will return an empty string. This does not effect buildTag
	 * @var bool
	 */
	protected $isRenderWhenEmpty = true;

	/**
	 * @param	TagContentInterface $content 
	 * @param	TagAttributesInterface $attrs
	 * @return	HtmlTag
	 */
	public function __construct($tagName, 
								TagContentInterface $content = null,
								TagAttributesInterface $attrs = null,
								$isTagNameLocked = true)
	{
		$this->setTagName($tagName);
		if (null === $content) {
			$content = $this->createTagContent();
		}
		$this->content = $content;

		if (null === $attrs) {
			$attrs = $this->createTagAttributes();
		}
		$this->attrs = $attrs;

		$isTagNameLocked = ($isTagNameLocked === false) ? false : true;
		if ($isTagNameLocked) {
			$this->lockTagName();
		}
	}

	/**
	 * @return string
	 */
	public function getTagName()
	{
		return $this->tagName;
	}

	/**
	 * @param	string	name of the tag
	 * @return	Tag
	 */
	public function setTagName($name)
	{
		if ($this->isTagNameLocked()) {
			$err = 'tag name is read-only and can not be modified';
			throw new LogicException($err);
		}

		if (! is_string($name) || ! ($name = trim($name))) {
			throw new InvalidArgumentException(
				"Invalid name for tag must be a string"
			);
		}
		$this->tagName = $name;
		return $this;
	}
	
	/**
	 * @return bool
	 */
	public function isClosingTag()	
	{
		return $this->isClosingTag;
	}

	/**
	 * @return Tag
	 */
	public function enableClosingTag()
	{
		$this->isClosingTag = true;
		return $this;
	}

	/**
	 * @return Tag
	 */
	public function disableClosingTag()
	{
		$this->isClosingTag = false;
		return $this;
	}

	/**
	 * @return	bool
	 */
	public function isRenderWhenEmpty()
	{
		return $this->isRenderWhenEmpty;
	}

	/**
	 * Do not render the tag when there is no content and the closing tag
	 * is enabled
	 *
	 * @return	HtmlTag
	 */
	public function disableRenderWhenEmpty()
	{
		$this->isRenderWhenEmpty = false;
		return $this;
	}

	/**
	 * Render the tag eventhough there is no content to render. 
	 * 
	 * @return	HtmlTag
	 */
	public function enableRenderWhenEmpty()
	{
		$this->isRenderWhenEmpty = true;
		return $this;
	}

	/**
	 * @param	array	$attrs	list of attributes to add
	 * @return	Tag
	 */
	public function loadAttributes(array $attrs)
	{
		$this->getTagAttributes()
			 ->load($attrs);
		return $this;
	}

	/**
	 * @param	string	$name
	 * @param	string	$value
	 * @return	Tag
	 */
	public function addAttribute($name, $value = null)
	{
		$this->getTagAttributes()
			 ->add($name, $value);

		return $this;
	}

	/**
	 * returns the value assigned to the attribute name given.
	 *
	 * @param	string	$name
	 * @return	string
	 */
	public function getAttribute($name, $default = null)
	{
		return $this->getTagAttributes()
					->get($name, $default);
	}

	/**
	 * @param	string	$name
	 * @return	bool
	 */
	public function isAttribute($name)
	{
		return $this->getTagAttributes()
					->exists($name);
	}

	/**
	 * Can only load content into a tag that supports content
	 *
	 * @param	array	$list
	 * @return	GenericTag
	 */
	public function loadContent(array $list)
	{
		if ($this->isClosingTag()) {
			$this->getTagContent()
				 ->load($list);
		}

		return $this;
	}

	/**
	 * Add content to the tag. Can only add content to a tag that supports 
	 * content (has closing tag enabled)
	 * 
	 * @param	mixed	$data	
	 * @param	string	$action		what to do with the content
	 * @return	Tag
	 */
    public function addContent($data, $action = 'append')
    {
		if ($this->isClosingTag()) {
			$this->getTagContent()
				 ->add($data, $action);
		}

        return $this;
    }

	/**
	 * @return array
	 */
	public function getContent($index = null)
	{
		return $this->getTagContent()
					->get($index);
	}

	/**
	 * @return	int
	 */
	public function getContentCount()
	{
		return $this->getTagContent()
					->count();
	}

	/**
	 * @return	string
	 */
	public function getContentSeparator()
	{
		return $this->getTagContent()
					->getSeparator();
	}

	/**
	 * @param	string	$char
	 * @return	GenericTag
	 */
	public function setContentSeparator($char)
	{
		$this->getTagContent()
			 ->setSeparator($char);

		return $this;
	}

	/**
	 * @return	bool
	 */
	public function isEmpty()
	{
		return $this->getTagContent()
					->isEmpty();
	}

	/**
	 * Since content is held as blocks stored sequentially in an array, you 
	 * can clear the whole array by leaving index null or you can clear an 
	 * individual block by giving its index
	 *
	 * @param	int	$index null 
	 * @return	bool
	 */
	public function clearContent($index = null)
	{
		return $this->getTagContent()
					->clear($index);
	}

	/**
	 * Build the content by concatenating each item in the content array,
	 * use the separator between each item.
	 *
	 * @return string
	 */
	public function getContentString()
	{
		return $this->getTagContent()
					->build();

	}

	/**
	 * @return string
	 */
	public function getAttributeString()
	{
		return $this->getTagAttributes()
					->build();
	}

	/**
	 * We don't call HtmlTag::isEmpty here because we have and need the 
	 * content tag and that method is just a wrapper.
	 *
	 * @return string
	 */
	public function build()
	{
		$content = $this->getTagContent();
		if (false === $this->isRenderWhenEmpty() && $content->isEmpty()) {
			return '';
		}

		return $this->buildTag($content, $this->getTagAttributes());
	}

	/**
	 * @param	string|TagContentInterface	$content
	 * @param	string|TagAttributesInterface $attrs
	 * @return	string
	 */
	public function buildTag($content, $attrs = '')
	{
		if (! (is_string($attrs) || 
			  ($attrs instanceof TagAttributesInterface))) {
			$err  = 'attributes must be a string or an object that implements ';
			$err .= 'Appfuel\View\Html\Tag\TagAttributesInterface';
			throw new InvalidArgumentException($err);
		}

		if (! (is_string($content) ||
			  ($content instanceof TagContentInterface))) {
			$err  = 'content must be a string of an object that implements ';
			$err .= 'Appfuel\View\Html\Tag\TagContentInterface';
			throw new InvalidArgumentException($err);
		}

		$tagName = $this->getTagName();
		$isClosingTag = $this->isClosingTag();
		$tag = "<{$tagName}";

		$isAttrs = (is_string($attrs) && ! empty($attrs)) ||
				  ($attrs instanceof TagAttributesInterface && 
				   $attrs->count() > 0);
			
			
		/* an html element that need no closing tag must have some attributes
		 * otherwise it servers no purpose
		 */
		if (! $isClosingTag && ! $isAttrs) {
			return '';
		}

		if ($isAttrs) {
			$tag .= " $attrs";
		}
	
		/*
		 * content is only used for tags that close
		 */	
		if ($isClosingTag) {
			$tag .= ">$content</{$tagName}>";
		} 
		else {
			$tag .= '>';
		}

		return $tag;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->build();
	}

	/**
	 * @return	bool
	 */
	protected function isTagNameLocked()
	{
		return $this->isTagNameLocked;
	}

	/**
	 * @return	GenericTag
	 */
	protected function lockTagName()
	{
		$this->isTagNameLocked = true;
		return $this;
	}

	/**
	 * @return	GenericTag
	 */
	protected function unlockTagName()
	{
		$this->isTagNameLocked = false;
		return $this;
	}

	/**
	 * @return	TagAttributesInterface
	 */
	protected function getTagAttributes()
	{
		return $this->attrs;
	}

	/**
	 * @return	TagContentInterface
	 */
	protected function getTagContent()
	{
		return $this->content;
	}

	/**
	 * @param	mixed	string|array	$data	content
	 * @param	string					$sep	content separator
	 * @return	TagContent
	 */
	protected function createTagContent($data = null, $sep = null)
	{
		return new TagContent($data, $sep);
	}

	/**
	 * @param	array	$whiteList
	 * @return	TagAttributes
	 */
	protected function createTagAttributes(array $whiteList = null)
	{
		return new TagAttributes($whiteList);
	}
}
