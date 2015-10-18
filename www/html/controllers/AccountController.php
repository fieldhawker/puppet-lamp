<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * AccountController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AccountController extends Controller
{
//  protected $auth_actions = array('index', 'signout', 'follow');

    const LOG_FORMAT = "%s %s\n %s %s %s (%d)\n=====\n\n";

    /**
     * コンストラクタ
     *
     * @param Application $application
     */
    public function __construct($application)
    {
        parent::__construct($application);
    }

    /**
     * アカウント登録
     *
     * @return string|void
     */
    public function signupAction()
    {
        if ($this->session->isAuthenticated()) {
            // 認証済みの場合はアカウント情報表示
            return $this->redirect('/account');
        }

        return $this->render(array(
          'email'    => '',
          'password' => '',
          '_token'   => $this->generateCsrfToken('account/signup'),
        ));
    }

    /**
     * アカウント登録処理
     *
     * @return string|void
     * @throws HttpNotFoundException
     */
    public function registerAction()
    {
        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' start ---');

        if ($this->session->isAuthenticated()) {
            // 認証済みの場合はアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' auth end ---');

            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {
            // POSTでなければ404
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' 404 end ---');
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signup', $token)) {
            // 不正なリクエストはアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' csrf end ---');

            return $this->redirect('/account');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $this->log->addInfo(
          sprintf(self::LOG_FORMAT, $this->finger, var_export(
            $email, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
        );

        $errors = array();

        if (!strlen($email)) {
            $errors[] = 'メールアドレスを入力してください';
        } else {
            if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
                $errors[] = 'メールアドレスは半角英数字およびアンダースコアを3 ～ 256 文字以内で入力してください';
            } else {
                if (!$this->db_manager->get('User')->isUniqueEmail($email)) {
                    $errors[] = 'メールアドレスは既に使用されています';
                }
            }
        }

        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        } else {
            if (4 > strlen($password) || strlen($password) > 30) {
                $errors[] = 'パスワードは4 ～ 30 文字以内で入力してください';
            }
        }

        if (count($errors) === 0) {
            try {
                $this->db_manager->get('User')->insert($email, $password);
                $this->session->setAuthenticated(true);

                $user = $this->db_manager->get('User')->fetchByEmail($email);
                $this->session->set('user', $user);
            } catch (Exception $e) {
                die("登録に失敗しました: " . $e->getMessage());
            }
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ---');

            return $this->redirect('/');
        }

        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' failed ---');

        return $this->render(array(
          'email'    => $email,
          'password' => $password,
          'errors'   => $errors,
          '_token'   => $this->generateCsrfToken('account/signup'),
        ), 'signup');
    }

    /**
     * アカウント情報
     *
     * @return string
     */
    public function indexAction()
    {
        if (!$this->session->isAuthenticated()) {
            // 未認証の場合はログイン画面へ遷移
            return $this->redirect('/account/signin');
        }

        $user = $this->session->get('user');

        return $this->render(array(
          'user' => $user,
        ));
    }

    /**
     * ログイン画面
     *
     * @return string|void
     */
    public function signinAction()
    {
        if ($this->session->isAuthenticated()) {
            // 認証済みの場合はアカウント画面へ遷移
            return $this->redirect('/account');
        }

        return $this->render(array(
          'email'    => '',
          'password' => '',
          '_token'   => $this->generateCsrfToken('account/signin'),
        ));
    }

    /**
     * 認証処理
     *
     * @return string|void
     * @throws HttpNotFoundException
     */
    public function authenticateAction()
    {
        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' start ---');

        if ($this->session->isAuthenticated()) {
            // 認証済みの場合はアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' auth end ---');

            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {
            // POSTでなければ404
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' 404 end ---');
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signin', $token)) {
            // 不正なリクエストはアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' csrf end ---');

            return $this->redirect('/account/signin');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $errors = array();

        if (!strlen($email)) {
            $errors[] = 'ユーザIDを入力してください';
        }

        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        }

        if (count($errors) === 0) {
            $user_repository = $this->db_manager->get('User');
            $user = $user_repository->fetchByEmail($email);

            if (!$user
              || ($user['password'] !== $user_repository->hashPassword($password))
            ) {
                $errors[] = 'ユーザIDかパスワードが不正です';
            } else {
                $this->session->setAuthenticated(true);
                $this->session->set('user', $user);

                $this->log->addInfo(
                  sprintf(self::LOG_FORMAT, $this->finger, var_export(
                    $email, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
                );

                $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ---');

                // ログイン後は住所録画面を表示
                return $this->redirect('/');
            }
        }

        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' failed ---');

        // 失敗時はログイン画面を再表示
        return $this->render(array(
          'email'    => $email,
          'password' => $password,
          'errors'   => $errors,
          '_token'   => $this->generateCsrfToken('account/signin'),
        ), 'signin');
    }

    /**
     * ログアウト
     *
     */
    public function signoutAction()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);

        return $this->redirect('/account/signin');
    }

}
