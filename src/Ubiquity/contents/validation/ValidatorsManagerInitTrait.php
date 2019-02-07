<?php

namespace Ubiquity\contents\validation;

use Ubiquity\cache\CacheManager;
use Ubiquity\controllers\Startup;

/**
 *
 * @author jcheron <myaddressmail@gmail.com>
 * @property array $validatorTypes
 */
trait ValidatorsManagerInitTrait {

	/**
	 * Parses models and save validators in cache
	 * to use in dev only
	 *
	 * @param array $config
	 */
	public static function initModelsValidators(&$config) {
		$models = CacheManager::getModels ( $config, true );
		foreach ( $models as $model ) {
			self::initClassValidators ( $config, $model );
		}
	}

	/**
	 *
	 * Parses a class and save validators in cache
	 * to use in dev only
	 *
	 * @param array $config
	 * @param string $class
	 */
	public static function initClassValidators(&$config, $class) {
		$parser = new ValidationModelParser ();
		$parser->parse ( $class );
		$validators = $parser->getValidators ();
		if (sizeof ( $validators ) > 0) {
			self::store ( $class, $parser->__toString () );
		}
	}

	/**
	 * Parses a class and save validators in cache
	 *
	 * @param string $class
	 */
	public static function addClassValidators($class) {
		$config = Startup::getConfig ();
		CacheManager::start ( $config );
		self::initClassValidators ( $config, $class );
	}

	/**
	 * Registers a validator type for using with @validator annotation
	 *
	 * @param string $type
	 * @param string $validatorClass
	 */
	public static function registerType($type, $validatorClass) {
		self::$validatorTypes [$type] = $validatorClass;
	}
}

