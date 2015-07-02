<?php
class RequireLogin extends CBehavior
{
	public function attach($owner)
	{
		$owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
	}
	public function handleBeginRequest($event)
	{
		$url = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
		if (Yii::app()->user->isGuest && !in_array($url,array(
				'admin/user/login',
				'admin/user/logout',
				'front/user/login',
				'front/user/logout',
				'front/user/register',
				'front/seminar/joinSeminar'
			))) {
			Yii::app()->user->loginRequired();
		}
	}
}
