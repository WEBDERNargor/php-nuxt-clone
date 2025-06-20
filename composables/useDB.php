<?php

function useDB()
{
    return [
        'connect' => function () {
            try {
                $config = getConfig();

                show_error_log('DB Config: ' . print_r($config['db'], true));

                $dbConfig = $config['db'];

                $requiredSettings = ['host', 'database', 'username', 'port', 'charset'];
                foreach ($requiredSettings as $setting) {
                    if (!isset($dbConfig[$setting]) || $dbConfig[$setting] === null) {
                        show_error_log("Missing required database setting: $setting");
                        throw new Exception("Database configuration error: missing $setting");
                    }
                }

                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                    $dbConfig['host'],
                    $dbConfig['port'],
                    $dbConfig['database'],
                    $dbConfig['charset']
                );

                show_error_log('Attempting to connect with DSN: ' . $dsn);
                show_error_log('Username: ' . $dbConfig['username']);
                $password = $dbConfig['password'] ?? '';

                $db = new PDO(
                    $dsn,
                    $dbConfig['username'],
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

                show_error_log('Database connection successful');
                return $db;

            } catch (PDOException $e) {
                show_error_log('PDO connection error: ' . $e->getMessage());
                show_error_log('PDO error code: ' . $e->getCode());
                throw new Exception('Database connection error: ' . $e->getMessage());
            } catch (Exception $e) {
                show_error_log('General error in database connection: ' . $e->getMessage());
                throw $e;
            }
        }
    ];
}

return useDB();
