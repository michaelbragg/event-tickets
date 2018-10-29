<?php


/**
 * Class Tribe__Tickets__Status__Abstract
 *
 * @since TBD
 *
 */
abstract class Tribe__Tickets__Status__Abstract {

	public $name                = '';
	public $provider_name       = '';
	public $post_type           = '';
	public $incomplete          = false;
	public $warning             = false;
	public $trigger_option      = false;
	public $attendee_generation = false;
	public $attendee_dispatch   = false;
	public $stock_reduced       = false;
	public $count_attendee      = false;
	public $count_sales         = false;
	public $count_completed     = false;
	public $count_canceled      = false;
	public $count_refunded      = false;
	public $count_not_going     = false;

	/**
	 * Status  Quantity
	 *
	 * @var int
	 */
	protected $_qty        = 0;

	/**
	 * Status Line Total
	 *
	 * @var int
	 */
	protected $_line_total = 0;

	/**
	 * Get this Status' Quantity of Tickets by Post Type
	 *
	 * @return int
	 */
	public function get_qty() {
		return $this->_qty;
	}

	/**
	 * Add to the  Status' Order Quantity
	 *
	 * @param int $value
	 */
	public function add_qty( int $value ) {
		$this->_qty += $value;
	}

	/**
	 * Remove from the  Status' Order Quantity
	 *
	 * @param int $value
	 */
	public function remove_qty( int $value ) {
		$this->_qty -= $value;
	}

	/**
	 * Get  Status' Order Amount of all Orders for a Post Type
	 *
	 * @return int
	 */
	public function get_line_total() {
		return $this->_line_total;
	}

	/**
	 * Add to the  Status' Line Total
	 *
	 * @param int $value
	 */
	public function add_line_total( int $value ) {
		$this->_line_total += $value;
	}

	/**
	 * Remove from the  Status' Line Total
	 *
	 * @param int $value
	 */
	public function remove_line_total( int $value ) {
		$this->_line_total -= $value;
	}

}