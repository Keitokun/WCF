<?php
namespace wcf\system\html\toc;

/**
 * Represents an item of a table of contents with its children.
 *
 * @author      Alexander Ebert
 * @copyright	2001-2019 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package     WoltLabSuite\Core\System\Html\Toc
 * @since       5.2
 */
class HtmlTocItem implements \Countable, \RecursiveIterator {
	/** @var HtmlTocItem[] */
	protected $children = [];
	
	protected $id = '';
	
	protected $level = 0;
	
	protected $title = '';
	
	protected $depth = 0;
	
	/**
	 * iterator position
	 * @var int
	 */
	private $position = 0;
	
	/** @var HtmlTocItem */
	private $parent;
	
	public function __construct($level, $id, $title) {
		$this->level = $level;
		$this->id = $id;
		$this->title = $title;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function getLevel() {
		return $this->level;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setParent($parent) {
		$this->parent = $parent;
	}
	
	/**
	 * @return HtmlTocItem|null
	 */
	public function getParent() {
		return $this->parent;
	}
	
	public function addChild(HtmlTocItem $child) {
		$this->children[] = $child;
		$child->setParent($this);
	}
	
	public function setDepth($depth) {
		$this->depth = $depth;
	}
	
	public function getDepth() {
		return $this->depth;
	}
	
	/**
	 * Returns the number of children.
	 *
	 * @return        integer
	 */
	public function count() {
		return count($this->children);
	}
	
	/**
	 * Returns true if this element is the last sibling.
	 *
	 * @return        bool
	 */
	public function isLastSibling() {
		foreach ($this->getParent() as $key => $child) {
			if ($child === $this) {
				return ($key === count($this->getParent()) - 1);
			}
		}
		
		return false;
	}
	
	/**
	 * Returns the number of open parent nodes.
	 *
	 * @return        int
	 */
	public function getOpenParentNodes() {
		$element = $this;
		$i = 0;
		
		while ($element->getParent()->getParent() !== null && $element->isLastSibling()) {
			$i++;
			$element = $element->getParent();
		}
		
		return $i;
	}
	
	/**
	 * @inheritDoc
	 */
	public function rewind() {
		$this->position = 0;
	}
	
	/**
	 * @inheritDoc
	 */
	public function valid() {
		return isset($this->children[$this->position]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function next() {
		$this->position++;
	}
	
	/**
	 * @inheritDoc
	 */
	public function current() {
		return $this->children[$this->position];
	}
	
	/**
	 * @inheritDoc
	 */
	public function key() {
		return $this->position;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getChildren() {
		return $this->children[$this->position];
	}
	
	/**
	 * @inheritDoc
	 */
	public function hasChildren() {
		return count($this->children) > 0;
	}
	
	public function getIterator() {
		return new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);
	}
}
