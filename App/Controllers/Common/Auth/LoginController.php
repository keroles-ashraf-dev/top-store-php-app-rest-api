<?php

namespace App\Controllers\Common\Auth;

use System\Controller;

class LoginController extends Controller
{
	/**
	 * Display Login Form
	 *
	 * @return mixed
	 */
	public function index()
	{

		$loginModel = $this->load->model('Login');

		if ($loginModel->isLogged()) {
			return $this->url->redirectTo('/');
		}

		$this->storeLayout->title('Login');

		// disable navbar
		$this->storeLayout->disable('navbar');

		$token = $this->security->setNewTokenToStorageAndReturnIt('form-token', 'session');

		$data['token'] = $token;

		$view = $this->view->render('common/auth/login', $data);

		return $this->storeLayout->render($view);
	}

	/**
	 * Submit Login form
	 *
	 * @return json
	 */
	public function submit()
	{

		if (!$this->security->isUserInputTokenValid('form-token')) {
			// it means form token not valid
			$json['success'] = false;
			$json['message'] = 'Something wrong happens. try again later';
			return json_encode($json);
		}

		if (!$this->isInputDataValid()) {
			// it means there are errors in form validation
			$json['success'] = false;
			$json['message'] = flatten($this->validator->getErrors());
			return json_encode($json);
		}

		$loginModel = $this->load->model('Login');

		if (!$loginModel->isValidLogin()) {
			// it means user email or password or both is wrong
			$json['success'] = false;
			$json['message'] = 'Invalid email or password or both';
			return json_encode($json);
		}

		$this->session->destroy();

		$token = '';

		if ($this->request->fileGetContents('keep-signed-in')) {
			// save login data in cookie
			$token = $this->security->setNewTokenToStorageAndReturnIt('auth-token', 'cookie');
		} else {
			// save login data in session
			$token = $this->security->setNewTokenToStorageAndReturnIt('auth-token', 'session');
		}

		$loginModel->setAuthToken($token);

		$json['success'] = true;
		$json['message'] = 'Successful login';
		$json['redirect-to'] = $this->url->link('/');

		return json_encode($json);
	}

	/**
	 * Validate Login Form
	 *
	 * @return bool
	 */
	private function isInputDataValid()
	{
		$this->validator->required('email')->email('email');
		$this->validator->required('password')->minLen('password', 8);

		if (!$this->validator->passes()) {
			return false;
		}

		return true;
	}
}
