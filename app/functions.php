<?php

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
    $storeData['firstName'] = $_POST['firstName'] ?? '';
    $storeData['lastName'] = $_POST['lastName'] ?? '';
    $storeData['accountNumber'] = $_POST['accountNumber'] ?? '';
    $storeData['personalId'] = $_POST['personalId'] ?? '';
    $storeData['initialBalance'] = $_POST['initialBalance'] ?? '';
    $storeData['id'] = rand(100000000, 999999999);

    // read from data/clients.json
    $clients = json_decode(file_get_contents(DIR . 'data/clients.json'), true) ?? [];
    // add new note to array
    $clients[] = $storeData;
    // save back to data/clients.json
    file_put_contents(DIR . 'data/clients.json', json_encode($clients, JSON_PRETTY_PRINT));

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
