<?php
namespace whixenna\underconstruction;

use Yii;
use yii\base\Component;
use yii\helpers\Html;

class UnderConstruction extends Component {
	public $active = false;
	public $allowedIPs = [];
	public $allowingGetParam;
	public $redirectURL;

	public $viewPath;
	public $headContent = 'Under construction';
	public $boxContent = 'Site is under construction.<br>Please try again later.';
	public $boxStyle = 'margin: 100px auto; text-align: center; font-size: 2em;';

	public function init() {
	    if (!$this->active) return true;

	    //пропустить по IP
	    $ip = Yii::$app->request->userIP;
        if (!empty($this->allowedIPs)) {
            foreach ($this->allowedIPs as $filter)
                if ($filter === '*' || $filter === $ip
                    || (($pos = strpos($filter,'*'))!==false && !strncmp($ip, $filter, $pos)))
                    return true;
        }
        if (!empty($this->allowingGetParam) && Yii::$app->request->get($this->allowingGetParam))
            return true;

        //not allowed
        if (isset($this->redirectURL)) {
            Yii::$app->response->redirect($this->redirectURL);
        } else if (isset($this->viewPath)) {
            Yii::$app->view->render($this->viewPath);
        } else {
            $content = Html::tag('div', Yii::t('app', $this->boxContent), ['style' => $this->boxStyle]);
            $content = Html::tag('body', $content);
            $content = Html::tag('head', $this->headContent) . $content;
            $content = Html::tag('html', $content);
            echo $content;
        }
        Yii::$app->end();
	}
}