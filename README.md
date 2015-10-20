# puppet-lamp

vagrant からpuppetを使用してのLAMP環境を構築
フレームワークを使用しないPHPサイトを実装

※composerとDBへのテーブル追加は自動化していない状態

**テストの出力と実行コマンド例**
 
* スケルトン作成

> cd www/html  
> vendor/phpunit/phpunit-skeleton-generator/phpunit-skelgen generate-test -- Validate core/Validate.php

* テスト実施  

> cd www/html  
> vendor/phpunit/phpunit/phpunit --bootstrap core/Validate.php --verbose core/ValidateTest  

* テスト自動生成用アノテーション  

> @assert (0, 0) == 0
