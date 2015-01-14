<?php

require "vendor/autoload.php";

class SimpleLightNCandy {
	protected $templates = array();
	protected $basePath = '';
	protected $helpers = array();
	protected $blockHelpers = array();

	public function __construct( $basePath ) {
		if ( substr( $basePath, -1, 1 ) !== DIRECTORY_SEPARATOR ) {
			$basePath .= DIRECTORY_SEPARATOR;
		}

		$this->basePath = $basePath;
	}

	public function renderTemplate( $templateName, array $input ) {
		$renderFunction = $this->getTemplate( $templateName );

		return $renderFunction( $input );
	}

	public function getTemplate( $templateName ) {
		if ( ! isset( $this->templates[$templateName] ) ) {
			$phpFile = $this->basePath . $templateName . '.php';
			$templateFile = $this->basePath . $templateName . '.template';

			$cacheOk = file_exists( $phpFile ) &&
				filemtime( $phpFile ) >= filemtime( $templateFile );

			if ( ! $cacheOk ) {
				if ( ! file_exists( $templateFile ) ) {
					throw new TemplatingException( "Unable to load $templateName from $templateFile" );
				}
				$templateStr = file_get_contents( $templateFile );
				$phpStr = LightnCandy::compile( $templateStr, $this->getCompileOptions() );
				file_put_contents( $phpFile, $phpStr );
			}

			$renderFunction = include $phpFile;

			$this->templates[$templateName] = $renderFunction;
		}

		return $this->templates[$templateName];
	}

	public function addHelper( $name, $fn ) {
		$this->helpers[$name] = $fn;
	}

	public function addBlockHelper( $name, $fn ) {
		$this->blockHelpers[$name] = $fn;
	}

	protected function getCompileOptions() {
		return array(
			'flags' => LightnCandy::FLAG_STANDALONE |
				LightnCandy::FLAG_MUSTACHE |
				LightnCandy::FLAG_ERROR_EXCEPTION,
			'basedir' => array(
				$this->basePath,
			),
			'fileext' => array(
				'.template',
			),
			'helpers' => $this->helpers,
			'blockhelpers' => $this->blockHelpers,
		);
	}
}

class TemplatingException extends Exception {

}
