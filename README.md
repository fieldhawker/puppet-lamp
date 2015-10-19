# puppet-lamp

vagrant からpuppetを使用してのLAMP環境を構築
フレームワークを使用しないPHPサイトを実装

※composerとDBへのテーブル追加は自動化していない状態

*テストの出力と実行コマンド例
`cd www/html`
`vendor/phpunit/phpunit-skeleton-generator/phpunit-skelgen generate-test -- Validate core/Validate.php`
`vendor/phpunit/phpunit/phpunit --bootstrap core/Validate.php --verbose core/ValidateTest`

*アノテーション
`@assert (0, 0) == 0`