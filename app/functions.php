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

    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');

    // validacija vardui
    if (strlen($firstName) < 3) {
        $_SESSION['error'] = [
            'message' => 'First name must be at least 3 characters long.',
            'id' => null
        ];
        header('Location: ' . URL . 'create');
        return '';
    }

    // validacija pavardei
    if (strlen($lastName) < 3) {
        $_SESSION['error'] = [
            'message' => 'Last name must be at least 3 characters long.',
            'id' => null
        ];
        header('Location: ' . URL . 'create');
        return '';
    }


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

    /* 
    https://lt.wikipedia.org/wiki/Asmens_kodas

    Jei asmens kodas užrašomas ABCDEFGHIJK, 
    tai: S = A*1 + B*2 + C*3 + D*4 + E*5 + F*6 + G*7 + H*8 + I*9 + J*1
    Suma S dalinama iš 11, ir jei liekana nelygi 10, ji yra asmens kodo kontrolinis skaičius K. 

    Jei liekana lygi 10, tuomet skaičiuojama nauja suma su tokiais svertiniais koeficientais:
    S = A*3 + B*4 + C*5 + D*6 + E*7 + F*8 + G*9 + H*1 + I*2 + J*3
    Ši suma S vėl dalinama iš 11, ir jei liekana nelygi 10, ji yra asmens kodo kontrolinis skaičius K. 
    Jei vėl liekana yra 10, kontrolinis skaičius K yra 0. 
    */

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

    // 🔴 4. Vardo ir pavardės simbolių patikrinimas
    if (!preg_match('/^[a-zA-ZąčęėįšųūžĄČĘĖĮŠŲŪŽ]{3,}$/u', $firstName)) {
        $_SESSION['error'] = [
            'message' => 'First name must contain only letters and be at least 3 characters.',
            'id' => null
        ];
        header('Location: ' . URL . 'create');
        return '';
    }

    if (!preg_match('/^[a-zA-ZąčęėįšųūžĄČĘĖĮŠŲŪŽ]{3,}$/u', $lastName)) {
        $_SESSION['error'] = [
            'message' => 'Last name must contain only letters and be at least 3 characters.',
            'id' => null
        ];
        header('Location: ' . URL . 'create');
        return '';
    }



    // jei viskas ok — išvalom old
    unset($_SESSION['old']);


    $storeData['firstName'] = $firstName;
    $storeData['lastName'] = $lastName;
    $storeData['accountNumber'] = $_SESSION['newIban'];
    unset($_SESSION['newIban']);
    $storeData['personalId'] = $_POST['personalId'] ?? '';
    $storeData['balance'] = $_POST['balance'] ?? '';
    $storeData['id'] = uniqid();


    // add new note to array
    $clients[] = $storeData;
    // save back to data/clients.json
    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT), LOCK_EX);

    $_SESSION['success'] = [
        'message' => "{$storeData['firstName']} {$storeData['lastName']} account has been successfully created.",
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
            $deletedClient = $client;
            unset($clients[$index]);
            break;
        }
    }

    // perindexuojam masyvą
    $clients = array_values($clients);

    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT));


    $_SESSION['success'] = [
        'message' => "{$deletedClient['firstName']} {$deletedClient['lastName']} account successfully deleted.",
        'id' => $id
    ];

    header('Location: ' . URL . 'accounts');
    return '';
}


function mod97($number)
{
    /*
    🔴 IBAN https://en.wikipedia.org/wiki/International_Bank_Account_Number 
    Lietuvoje IBAN ilgis = 20 simbolių
    LT IBAN struktūra: LTkk BBBB BAAA AAAA AAAA
    LT - 21 29 
    k — kontroliniai skaičiai pradžioje 00
    B — banko kodas (pas tave 77777)
    A — sąskaitos numeris random
    
    validacijos tikrinimas BBBB BAAA AAAA AAAA 2129 kk / 97 turi likti liekana 1.

    */

    $checksum = 0;

    for ($i = 0; $i < strlen($number); $i++) {
        $checksum = ($checksum * 10 + intval($number[$i])) % 97;
    }

    return $checksum;
}


// Stabilus IBAN per vieną formos sesiją
function getNewIban()
{
    if (!isset($_SESSION['newIban'])) {
        $countryCode = 'LT';
        $bankCode = '77777'; // banko kodas
        $accountNumber = str_pad(rand(0, 99999999999), 11, '0', STR_PAD_LEFT);

        // LT = 2129
        $numeric = $bankCode . $accountNumber . '212900';
        $mod = mod97($numeric);
        $checkDigits = str_pad(98 - $mod, 2, '0', STR_PAD_LEFT);

        $_SESSION['newIban'] = $countryCode . $checkDigits . $bankCode . $accountNumber;
    }

    return $_SESSION['newIban'];
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
