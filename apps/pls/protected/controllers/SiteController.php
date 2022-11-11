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
		$rss_feed_array = array();
		try {
		    $rss_feed_resp = @file_get_contents('https://supereval.com/blog/category/supereval-updates/feed');
		    if ($rss_feed_resp !== false){
		        $xml = @simplexml_load_string($rss_feed_resp);
		        if ($xml !== false) {
		            foreach ($xml->channel->item as $rss_item){
		                $rss_feed_item = array();
		                $rss_feed_item['title'] =  (string) $rss_item->title;
		                $pubDate = (string) $rss_item->pubDate;
		                $rss_feed_item['pubDate'] =  strtotime($pubDate); // Wed, 13 Jul 2022 19:30:25 +0000
		                $rss_feed_item['description'] =  (string) $rss_item->description;
		                array_push($rss_feed_array,$rss_feed_item);
		            }
		            // sort results by date
		            usort($rss_feed_array, function($a, $b)
		            {
		                return -strcmp($a['pubDate'], $b['pubDate']);
		            });
		            // get only first most recent
		            if (count($rss_feed_array) > 1){
		                $rss_feed_array = $rss_feed_array[0];
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
		
		
		$this->render('login', ['model' => $model, 'rss_feed_array' => $rss_feed_array]);
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
}