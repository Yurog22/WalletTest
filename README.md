Тест "Мультивалютный кошелек"

Инструкция по развертыванию
===========================
Изменить DATABASE_URL строкой подключения к базе данных MySQL в файле .env

Запустить установку пакетов:
composer install

Запустить миграцию:
php bin/console doctrine:migrations:migrate

Инструкция по использованию
============================
Команды:
	php bin/console charge  - начисление средств в указанной валюте на кошелек
		аргументы: 
			currency - валюта средств для начисления
			amount - количество средств для зачисления
		пример использования:
			php bin/console charge USD 10 - зачисление валюты USD в количестве 10 едениц
	
	php bin/console chargeoff - списание средств в указанной валюте кошелька
		аргументы: 
			currency - валюта средств для начисления
			amount - количество средств для зачисления
		пример использования:
			php bin/console chargeoff EUR 20 - списание валюты EUR в количестве 20 едениц
		
	php bin/console showall - вывод результата накоплений по всем валютам, имеющимся в кошельке
		пример использования:
			php bin/console showall
	
	php bin/console showbalance - вывод текущей суммы баланса по всем валютам в указанной валюте конвертации одной суммой
		аргументы:
			currency - валюта конвертации
		пример использования:
			php bin/console showbalance KGS - вывод текущей суммы баланса в валюте конвертации KGS
	