<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * AddressController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class AddressController extends Controller
{
//    protected $auth_actions = array('index', 'post');

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
                           'id'      => $params['user_id'],
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

    $post["id"] = $this->request->getPost('id');
    $post["name"] = $this->request->getPost('name');
    $post["address"] = $this->request->getPost('address');

    $this->log->addInfo(
      sprintf(self::LOG_FORMAT, $this->finger, var_export(
        $post, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
    );

    $errors = array();

    if (!strlen($post["name"])) {
      $errors[] = '名前を入力してください';
    } else if (mb_strlen($post["name"]) > 20) {
      $errors[] = '名前は20 文字以内で入力してください';
    }
    if (!strlen($post["address"])) {
      $errors[] = '住所を入力してください';
    } else if (mb_strlen($post["address"]) > 250) {
      $errors[] = '住所は250 文字以内で入力してください';
    }

    if (count($errors) === 0) {
      try {
        $user = $this->session->get('user');
        if (empty($post["id"]) or !$post["id"]) {
          $this->db_manager->get('Address')->insert($user['id'], $post);
        } else {
          $this->db_manager->get('Address')->update($user['id'], $post);
        }
      } catch (Exception $e) {
        die("登録に失敗しました: " . $e->getMessage());
      }
      $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ----');

      return $this->redirect('/');
    }

    $this->log->addDebug($this->finger . ' ' . __METHOD__ . '  failed ----');

    return $this->render(array(
                           'errors'  => $errors,
                           'name'    => $post["name"],
                           'address' => $post["address"],
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

    $errors = array();

    if (!strlen($params["id"])) {
      $errors[] = '対象を指定してください';
    } else if (mb_strlen($params["id"]) > 8) {
      $errors[] = '対象は8 文字以内で入力してください';
    }
    if (count($errors) !== 0) {
      // 一覧に戻る
      $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' not found ----');

      return $this->redirect('/');
    }

    $address = $this->db_manager->get('Address')->fetchById($params['id']);

    if (count($address) === 0) {
      // 一覧に戻る
      $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' not found ---');

      return $this->redirect('/');
    }

    try {
      $this->db_manager->get('Address')->delete($params['id']);

      $myself = $this->session->get('user');
      $myself["deleted_to"] = $params['id'];
      $this->log->addInfo(
        sprintf(self::LOG_FORMAT, $this->finger, var_export(
          $myself, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
      );

    } catch (Exception $e) {
      $this->log->addDebug($this->finger . ' ' . __METHOD__ . '  failed ---');
      die("削除に失敗しました: " . $e->getMessage());
    }

    $this->log->addDebug($this->finger . ' ' . __METHOD__ . ' completed ----');

    return $this->redirect('/');
  }

}
