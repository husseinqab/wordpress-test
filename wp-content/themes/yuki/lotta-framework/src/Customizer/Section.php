<?php

namespace LottaFramework\Customizer;

abstract class Section {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var int|mixed
	 */
	protected $priority;

	/**
	 * @param $id
	 * @param $title
	 * @param int $priority
	 */
	public function __construct( $id, $title, int $priority = 10 ) {
		$this->id       = $id;
		$this->title    = $title;
		$this->priority = $priority;
	}

	/**
	 * Get section id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get section args
	 *
	 * @return array
	 */
	public function getSectionArgs() {
		return [
			'title'    => $this->title,
			'priority' => $this->priority,
		];
	}

	/**
	 * get section controls
	 *
	 * @return array
	 */
	abstract public function getControls();
}