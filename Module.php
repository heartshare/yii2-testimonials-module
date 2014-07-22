<?php

namespace cics\modules\testimonials;

/* use Yii; */

class Module extends \yii\base\Module
{

    /**
     * @var string Alias for module
     */
    public $alias = "@user";

	public function init()
	{
	    parent::init();
	    // initialize the module with the configuration loaded from config.php
/* 	    \Yii::configure($this, require(__DIR__ . '/config.php')); */


        // set alias
/*
        $this->setAliases([
            $this->alias => __DIR__,
        ]);
*/
	}
}