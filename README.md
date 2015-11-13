# puppet-lamp  
  
vagrant からpuppetを使用してのLAMP環境を構築  
フレームワークを使用しないPHPサイトを実装  
  
※composerは自動化していない状態  

**環境構築**  
  
> vagrant up --provision  
> vagrant ssh  
> cd /vagrant/www  
> composer install  
> ~~mysql -u xxx -pyyy -D myapp < sql/create_user_table.sql~~  
> ~~mysql -u xxx -pyyy -D myapp < sql/create_address_table.sql~~  


> vagrant provision  (provisionし忘れた場合)  
  
**テストの出力と実行コマンド例**  
 
* スケルトン作成  
  
> cd www/html    
> vendor/phpunit/phpunit-skeleton-generator/phpunit-skelgen generate-test -- Validate core/Validate.php  
> vendor/phpunit/phpunit-skeleton-generator/phpunit-skelgen generate-test -- AddressRepository models/AddressRepository.php
  
  
* テスト実施  

> cd www/html    
> vendor/phpunit/phpunit/phpunit --bootstrap core/Validate.php --verbose core/ValidateTest  
> vendor/phpunit/phpunit/phpunit --configuration test/phpunit.xml models/UserRepositoryTest.php  
  
> /bin/sh test/start_test.sh  

* テスト自動生成用アノテーション  
  
> @assert (0, 0) == 0  
  
  
* 静的解析  
  
> cd www/html  
> vendor/phpmd/phpmd/src/bin/phpmd core/Validate.php text codesize,controversial,design,naming,unusedcode  
  
* Doc出力  
  
> cd www/html  
> vendor/phpdocumentor/phpdocumentor/bin/phpdoc -d web -t doc  
  
* PhpStorm用Xdebug設定  
  
> help/Xdebug_setting.txtを参照  
