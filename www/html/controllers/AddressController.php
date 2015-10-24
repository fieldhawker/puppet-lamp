<?php

//require_once __DIR__ . '/../core/Controller.php';

/**
 * AddressController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AddressController extends Controller
{
//    protected $auth_actions = array('index', 'post');

    const LOG_FORMAT                  = "%s %s\n %s %s %s (%d)\n=====\n\n";
    const ERR_MSG_NOT_REGISTER_FAILED = "登録に失敗しました: ";
    const ERR_MSG_NOT_DELETE_FAILED   = "削除に失敗しました: ";

    /**
     * 住所一覧
     *
     * @return string|void
     */
    public function indexAction()
    {

        if (!$this->session->isAuthenticated()) {
            // 未認証の場合はログイン画面へ遷移
            return $this->redirect('/account/signin');
        }

        $addresses = $this->db_manager->get('Address')->fetchAllAddress();
        
        return $this->render(array(
          'addresses' => $addresses,
        ));
    }

    /**
     * 住所登録
     *
     * @return string|void
     */
    public function registerAction()
    {
        if (!$this->session->isAuthenticated()) {
            // 未認証の場合はログイン画面へ遷移
            return $this->redirect('/account/signin');
        }

        return $this->render(array(
          'id'      => 0,
          'name'    => '',
          'address' => '',
          '_token'  => $this->generateCsrfToken('status/post'),
        ));
    }

    /**
     * 住所更新
     *
     * @param $params
     *
     * @return string|void
     */
    public function updateAction($params)
    {
        if (!$this->session->isAuthenticated()) {
            // 未認証の場合はログイン画面へ遷移
            return $this->redirect('/account/signin');
        }

        $address = $this->db_manager->get('Address')->fetchById($params['id']);

        return $this->render(array(
          'id'      => $params['id'],
          'name'    => $address["name"],
          'address' => $address["address"],
          '_token'  => $this->generateCsrfToken('status/post'),
        ), 'register');
    }

    /**
     * 登録処理
     *
     * @return string|void
     * @throws HttpNotFoundException
     */
    public function postAction()
    {
        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' start ----');

        if (!$this->session->isAuthenticated()) {
            // 認証済みの場合はアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' auth end ----');

            return $this->redirect('/account');
        }

        if (!$this->request->isPost()) {
            // POSTでなければ404
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' 404 end ----');
            $this->forward404();
        }

        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('status/post', $token)) {
            // 不正なリクエストはアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' csrf end ----');

            return $this->redirect('/');
        }

        $user = $this->session->get('user');

        $params["id"]      = $this->request->getPost('id');
        $params["name"]    = $this->request->getPost('name');
        $params["address"] = $this->request->getPost('address');

        $log            = $params;
        $log["post_by"] = $user["id"];

        $this->log->addInfo(
          sprintf(self::LOG_FORMAT, $this->finger, var_export(
            $log, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
        );

        $errors = $this->db_manager->get('Address')->validPost($params);

        if (count($errors) === 0) {
            try {

                $this->db_manager->get('Address')->saveAddress($params, $user);

            } catch (Exception $e) {

                die(self::ERR_MSG_NOT_REGISTER_FAILED . $e->getMessage());

            }
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ----');

            return $this->redirect('/');
        }

        $this->log->addDebug($this->finger . ' ' . __METHOD__ . '  failed ----');

        return $this->render(array(
          'errors'  => $errors,
          'id'      => $params["id"],
          'name'    => $params["name"],
          'address' => $params["address"],
          '_token'  => $this->generateCsrfToken('status/post'),
        ), 'register');
    }

    /**
     * 住所削除
     *
     * @return string|void
     */
    public function deleteAction($params)
    {
        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' start ----');

        if (!$this->session->isAuthenticated()) {
            // 認証済みの場合はアカウント画面へ遷移
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' auth end ----');

            return $this->redirect('/account/signin');
        }

        $errors = $this->db_manager->get('Address')->validDelete($params);

        if (count($errors) !== 0) {
            // 一覧に戻る
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' validate end ----');

            return $this->redirect('/');
        }

        $address = $this->db_manager->get('Address')->fetchById($params['id']);

        if (count($address) === 0) {
            // 一覧に戻る
            $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' not found ---');

            return $this->redirect('/');
        }

        try {

            $user           = $this->session->get('user');
            $log            = $params;
            $log["post_by"] = $user["id"];

            $this->log->addInfo(
              sprintf(self::LOG_FORMAT, $this->finger, var_export(
                $log, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
            );

            $this->db_manager->get('Address')->delete($params['id']);

        } catch (Exception $e) {

            $this->log->addDebug($this->finger . ' ' . __METHOD__ . '  failed ---');
            die(self::ERR_MSG_NOT_DELETE_FAILED . $e->getMessage());

        }

        $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ----');

        return $this->redirect('/');
    }

}
