<?php

session_start();

function router()
{
    $uri = $_SERVER['REQUEST_URI'];
    $uri = str_replace(INSTALL_DIR, '', $uri);
    $uri = explode('/', $uri);
    $method = $_SERVER['REQUEST_METHOD'];

    if ('GET' == $method && $uri[0] == '') {
        return homeController();
    }
    if ('GET' == $method && $uri[0] == 'accounts') {
        return accountsController();
    }
    if ('GET' == $method && $uri[0] == 'create') {
        return createController();
    }
    if ('GET' == $method && $uri[0] == 'credit') {
        $id = $uri[1] ?? null;
        return creditController($id);
    }
    if ('GET' == $method && $uri[0] == 'debit') {
        $id = $uri[1] ?? null;
        return debitController($id);
    }
    if ('GET' == $method && $uri[0] == 'login') {
        return loginController();
    }

    if ('POST' == $method && $uri[0] == 'store') {
        return storeController();
    }

    if ('POST' == $method && $uri[0] == 'add-funds') {
        $id = $uri[1] ?? null;
        return addFundsController($id);
    }

    if ('POST' == $method && $uri[0] == 'withdraw-funds') {
        $id = $uri[1] ?? null;
        return withdrawFundsController($id);
    }

    if ('POST' == $method && $uri[0] == 'delete-account') {
        $id = $uri[1] ?? null;
        return deleteAccountController($id);
    }
}


function homeController()
{
    return view('index', [
        'title' => 'Home'
    ]);
}

function accountsController()
{
    // read from data/clients.json
    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    usort($clients, function ($a, $b) {
        return strcmp($a['lastName'], $b['lastName']);
    });

    return view('accounts', [
        'title' => 'Accounts',
        'clients' => $clients
    ]);
}

function createController()
{
    return view('create', [
        'title' => 'Create account'
    ]);
}

function creditController($id = null)
{
    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    $clientData = null;

    foreach ($clients as $client) {
        if ($client['id'] == $id) {
            $clientData = $client;
            break;
        }
    }

    if (!$clientData) {
        header('Location: ' . URL . 'accounts');
        return '';
    }

    return view('credit', [
        'title' => 'Credit',
        'client' => $clientData
    ]);
}

function debitController($id = null)
{
    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    $clientData = null;

    foreach ($clients as $client) {
        if ($client['id'] == $id) {
            $clientData = $client;
            break;
        }
    }

    if (!$clientData) {
        header('Location: ' . URL . 'accounts');
        return '';
    }

    return view('debit', [
        'title' => 'Debit',
        'client' => $clientData
    ]);
}

function loginController()
{
    return view('login', [
        'title' => 'Log in'
    ]);
}

function storeController()
{
    $_SESSION['old'] = $_POST;

    $personalId = $_POST['personalId'] ?? '';

    // 🔴 1. validacija: tik skaičiai ir tiksliai 11 simbolių
    if (!ctype_digit($personalId) || strlen($personalId) != 11) {
        $_SESSION['error'] = [
            'message' => 'Personal ID must be exactly 11 digits.',
            'id' => null
        ];
        header('Location: ' . URL . 'create');
        return '';
    }

    // read from data/clients.json
    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    // 🔴 2. tikrinam ar jau egzistuoja
    foreach ($clients as $client) {
        if ($client['personalId'] === $personalId) {
            $_SESSION['error'] = [
                'message' => 'This Personal ID is already registered.',
                'id' => null
            ];
            header('Location: ' . URL . 'create');
            return '';
        }
    }

    // 🔴 3. Kontrolinio skaičiaus patikrinimas
    $digits = str_split($personalId); // A,B,C,...,K
    $sum = 0;
    for ($i = 0; $i < 10; $i++) {
        $sum += $digits[$i] * ($i + 1); // svertiniai 1..10, bet paskutinis 1
    }
    $mod = $sum % 11;
    if ($mod != 10) {
        $controlDigit = $mod;
    } else {
        // antras svoris
        $weights = [3, 4, 5, 6, 7, 8, 9, 1, 2, 3];
        $sum2 = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum2 += $digits[$i] * $weights[$i];
        }
        $mod2 = $sum2 % 11;
        $controlDigit = ($mod2 != 10) ? $mod2 : 0;
    }

    if ($controlDigit != $digits[10]) {
        $_SESSION['error'] = [
            'message' => 'Personal ID has invalid control digit.',
            'id' => null
        ];
        header('Location: ' . URL . 'create');
        return '';
    }


    // jei viskas ok — išvalom old
    unset($_SESSION['old']);



    $storeData['firstName'] = $_POST['firstName'] ?? '';
    $storeData['lastName'] = $_POST['lastName'] ?? '';
    $storeData['accountNumber'] = $_POST['accountNumber'] ?? '';
    $storeData['personalId'] = $_POST['personalId'] ?? '';
    $storeData['balance'] = $_POST['balance'] ?? '';
    $storeData['id'] = uniqid();


    // add new note to array
    $clients[] = $storeData;
    // save back to data/clients.json
    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT), LOCK_EX);

    $_SESSION['success'] = [
        'message' => "{$client['firstName']} {$client['lastName']} account has been successfully created.",
        'id' => $storeData['id'] // susiejame su naujai sukurta id
    ];

    header('Location: ' . URL . 'accounts');
    return '';
}

function addFundsController($id = null)
{
    $amount = floatval($_POST['creditAmount'] ?? 0);

    // validacija

    if (!is_numeric($amount) || $amount <= 0) {
        $_SESSION['error'] = [
            'message' => 'Please enter a valid amount greater than 0.',
            'id' => $id
        ];
        header('Location: ' . URL . 'credit/' . $id);
        return '';
    }

    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    // $found = false;

    foreach ($clients as &$client) {
        if ($client['id'] == $id) {

            $current = floatval($client['balance']);
            $client['balance'] = number_format($current + $amount, 2, '.', '');

            // 🔹 flash message apie sėkmę
            $_SESSION['success'] = [
                'message' => "Successfully credited {$amount} EUR to {$client['firstName']} {$client['lastName']}.",
                'id' => $client['id']
            ];

            // $found = true;
            break;
        }
    }

    unset($client);

    // if ($found) {
    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT), LOCK_EX);
    // }

    header('Location: ' . URL . 'accounts');
    return '';
}

function withdrawFundsController($id = null)
{
    $amount = floatval($_POST['debitAmount'] ?? 0);

    // Patikriname, kad įvestas kiekis būtų teigiamas
    if (!is_numeric($amount) || $amount <= 0) {
        $_SESSION['error'] = [
            'message' => 'Please enter a valid amount greater than 0.',
            'id' => $id
        ];
        header('Location: ' . URL . 'debit/' . $id);
        return '';
    }

    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    foreach ($clients as &$client) {
        if ($client['id'] == $id) {

            $current = floatval($client['balance']);
            $newBalance = $current - $amount;

            // Nauja validacija: balansas negali būti mažesnis nei 0
            if ($newBalance < 0) {
                $_SESSION['error'] = [
                    'message' => "Withdrawal denied. {$client['firstName']} {$client['lastName']} has only {$current} EUR.",
                    'id' => $id
                ];
                header('Location: ' . URL . 'debit/' . $id);
                return '';
            }

            $client['balance'] = number_format($newBalance, 2, '.', '');

            $_SESSION['success'] = [
                'message' => "Successfully debited {$amount} EUR from {$client['firstName']} {$client['lastName']}.",
                'id' => $client['id']
            ];
            break;
        }
    }

    unset($client);

    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT));

    header('Location: ' . URL . 'accounts');
    return '';
}

function deleteAccountController($id = null)
{
    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];

    foreach ($clients as $index => $client) {

        if ($client['id'] == $id) {
            if (floatval($client['balance']) > 0) {
                $_SESSION['error'] = [
                    'message' => "Cannot delete account: account has remaining balance ({$client['balance']} EUR).",
                    'id' => $id
                ];
                header('Location: ' . URL . 'accounts');
                return '';
            }
            unset($clients[$index]);
            break;
        }
    }

    // perindexuojam masyvą
    $clients = array_values($clients);

    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT));


    $_SESSION['success'] = [
        'message' => "{$client['firstName']} {$client['lastName']} account successfully deleted.",
        'id' => $id
    ];

    header('Location: ' . URL . 'accounts');
    return '';
}


function view(string $template, array $data = [])
{
    extract($data); // indeksai iš masyvo yra paverčiami atskirais kintamaisiais

    // viskas bus buferinama
    ob_start();
    require DIR . "view/top.php"; // top
    require DIR . "view/{$template}.php"; // top
    require DIR . "view/bottom.php"; // bottom

    // clear output buffer and return result
    return ob_get_clean();
}
