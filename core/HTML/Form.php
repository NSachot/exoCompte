<?php

namespace Core\HTML;

/**
 * Class Form
 * Permet de générer un formulaire
 */
class Form {

	/**
	 * @var array Données utilisées par le formulaire
	 */
	private $data;

	/**
	 * @param $data array Données utilisées par le formulaire
	 */
	public function __construct($data = array()) {
		$this->data = $data;
	}

	/**
	 * Permet de créer un champ input
	 * @param $name string
	 * @param $label string
	 * @param $type string
	 * @return string
	 */
	public function input($name, $label, $type='text', $disabled=false, $required=true) {
		$html = '';
		$html .= '<div class="row">';
		$html .= '<label for="'.$name.'">'.$label.'</label>';
		$html .= '<input type="'.$type.'" name="'.$name.'" id="form-'.$name.'" value="'.$this->getValue($name).'" ';
		$html .= ($disabled) ? 'disabled ' : '';
		$html .= ($required) ? 'required="required" ' : '';
		$html .= '>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * @param $id string
	 * @return string
	 */
	public function submit() {
		return '<button type="submit">Envoyer</button>';
	}

	/**
	 * @param $index string Index de la valeur à récupérer
	 * @return string
	 */
	private function getValue($index) {
		return isset($this->data[$index]) ? $this->data[$index] : null;
	}

}