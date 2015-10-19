<?php

require_once __DIR__.'/../core/Controller.php';

/**
 * AccountController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AccountController extends Controller
{
//  protected $auth_actions = array('index', 'signout', 'follow');

    const LOG_FORMAT                     = "%s %s\n %s %s %s (%d)\n=====\n\n";
    const ERR_MSG_UNMATCH_MAIL_PASSWORD  = 'メールアドレスかパスワードが不正です';
    const ERR_MSG_REGISTER_FAILED        = "登録に失敗しました: ";

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

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' auth end ---');

            // 認証済みの場合はアカウント画面へ遷移
            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' 404 end ---');
            // POSTでなければ404
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signup', $token)) {

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' csrf end ---');

            // 不正なリクエストはアカウント画面へ遷移
            return $this->redirect('/account');
        }

        $params["email"]    = $this->request->getPost('email');
        $params["password"] = $this->request->getPost('password');

        $this->log->addInfo(
          sprintf(self::LOG_FORMAT, $this->finger, var_export(
            $params["email"], 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
        );

        $errors = $this->db_manager->get('User')->validInsert($params);

        if (count($errors) === 0) {
            try {

                $this->db_manager->get('User')->insert($params);
                $this->session->setAuthenticated(true);

                $user = $this->db_manager->get('User')->fetchByEmail($params["email"]);
                $this->session->set('user', $user);

            } catch (Exception $e) {

                die(self::ERR_MSG_REGISTER_FAILED . $e->getMessage());

            }
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ---');

            return $this->redirect('/');
        }

        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' failed ---');

        return $this->render(array(
          'email'    => $params["email"],
          'password' => $params["password"],
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

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' auth end ---');

            // 認証済みの場合はアカウント画面へ遷移
            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' 404 end ---');
            // POSTでなければ404
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('account/signin', $token)) {

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' csrf end ---');

            // 不正なリクエストはアカウント画面へ遷移
            return $this->redirect('/account/signin');
        }

        $params["email"]    = $this->request->getPost('email');
        $params["password"] = $this->request->getPost('password');

        $errors = $this->db_manager->get('User')->validAuth($params);

        if (count($errors) === 0) {
            $user_repository = $this->db_manager->get('User');
            $user            = $user_repository->fetchByEmail($params["email"]);

            if (!$user
              || ($user['password'] !== $user_repository->hashPassword($params["password"]))
            ) {
                $errors[] = self::ERR_MSG_UNMATCH_MAIL_PASSWORD;
            } else {
                $this->session->setAuthenticated(true);
                $this->session->set('user', $user);

                $this->log->addInfo(
                  sprintf(self::LOG_FORMAT, $this->finger, var_export(
                    $params["email"], 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
                );

                $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ---');

                // ログイン後は住所録画面を表示
                return $this->redirect('/');
            }
        }

        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' failed ---');

        // 失敗時はログイン画面を再表示
        return $this->render(array(
          'email'    => $params["email"],
          'password' => $params["password"],
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
