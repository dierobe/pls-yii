<?php
/**
 * @class      SiteController
 *
 * This is the controller that contains the /site actions.
 *
 * @author     Developer
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */

class SiteController extends Controller {

	/**
	 * Specifies the action filters.
	 *
	 * @return array action filters
	 */
	public function filters() {
		return [
			'accessControl',
		];
	}

	/**
	 * Specifies the access control rules.
	 *
	 * @return array access control rules
	 */
	public function accessRules() {
		return [
			[
				'allow',  // allow all users to access specified actions.
				'actions' => ['index', 'login', 'about', 'error'],
				'users'   => ['*'],
			],
			[
				'allow', // allow authenticated users to access all actions
				'users' => ['@'],
			],
			[
				'deny',  // deny all users
				'users' => ['*'],
			],
		];
	}

	/**
	 * Initialize the controller.
	 *
	 * @return void
	 */
	public function init() {
		$this->defaultAction = 'login';
	}

	/**
	 * Renders the about page.
	 *
	 * @return void
	 */
	public function actionAbout() {
		$this->render('about');
	}

	/**
	 * Renders the login page.
	 *
	 * @return void
	 */
	public function actionLogin() {
		if (!defined('CRYPT_BLOWFISH') || !CRYPT_BLOWFISH) {
			throw new CHttpException(500, 'This application requires that PHP was compiled with Blowfish support for crypt().');
		}
		$model = new LoginForm();
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login()) {
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		
		// get RRS Feed Slide #3
		$slide_three = self::getSlide(Yii::app()->params['slideThreeURL']);
		
		// get Blog post Slide #4
		$slide_four = self::getSlide(Yii::app()->params['slideFourURL']);
		
		
		$this->render('login', ['model' => $model, 'slide_three' => $slide_three, 'slide_four' => $slide_four]);
	}

	/**
	 * Logs out the current user and redirects to homepage.
	 *
	 * @return void
	 */
	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * The action that handles external exceptions.
	 *
	 * @return void
	 */
	public function actionError() {
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			}
			else {
				$this->render('//site/error', $error);
			}
		}
	}
	
	/**
	 * Get most recent post from RSS feed
	 *
	 * @return RSS item or null
	 */
	public function getSlide($url){
	    
	    /* Original Code done by me 
	    $rss_feed_array = array();
	    try {
	        $rss_feed_resp = @file_get_contents($url);
	        if ($rss_feed_resp !== false){
	            $xml = @simplexml_load_string($rss_feed_resp);
	            if ($xml !== false) {
	                $key = 0;
	                foreach ($xml->channel->item as $rss_item){
	                    $rss_feed_item = array();
	                    $rss_feed_item['title'] =  (string) $rss_item->title;
	                    $rss_feed_item['pubDate'] =  strtotime((string) $rss_item->pubDate); 
	                    $rss_feed_item['description'] =  (string) $rss_item->description;
	                    if ($key == 0){
	                        $rss_feed_array = $rss_feed_item;
	                    // get most recent
	                    } else if ($rss_feed_item['pubDate'] > $rss_feed_array['pubDate']) {
	                        $rss_feed_array = $rss_feed_item;
	                    }
	                    $key++;
	                }
	            } else {
	                $rss_feed_array = array();
	            }
	        } else {
	            $rss_feed_array = array();
	        }
	    } catch (Exception $e) {
	        // Handle error accordingly
	        $rss_feed_array = array();
	    }
	    return $rss_feed_array;
	    */
	    
	    // Shorter code using Extension
	    $rss_slide = null;
	    try {
    	    Feed::$userAgent = Yii::app()->params['curlUserAgent'];
    	    Feed::$cacheDir = Yii::app()->params['latestUpdatesFeedCacheDir'];
    	    Feed::$cacheExpire = Yii::app()->params['latestUpdatesFeedCacheExp'];
    	    $feed = Feed::loadRss($url);
    	    if (!empty($feed)) {
    	        $key = 0;
    	        foreach ($feed->item as $item) {
    	            if ($key == 0){
    	                $rss_slide = $item;
    	            // get most recent
    	            } else if ($item->timestamp > $rss_slide->timestamp) {
    	                $rss_slide = $item;
    	            }
    	            $key++;
    	        }
    	    }
	    } catch (Exception $e) {
	        // Handle error accordingly
	        $rss_slide = null;
	    }
	    return $rss_slide;
	}
}