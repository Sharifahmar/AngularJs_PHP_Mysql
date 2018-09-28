<?php

include './Admin.php';

$admin = new PanelAdmin();

if (isset($_GET) || isset($_POST)) {

  $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

    if ($action) {

        try {

            if (method_exists($admin, $action)) {
                if ($action == 'saveReorederdItems') {
                    $aid = filter_input(INPUT_GET, 'aid', FILTER_UNSAFE_RAW);
                    $admin->$action($aid);
                } else {
                    $admin->$action();
                }
            } else {
                throw new \BadMethodCallException('Bad Method Call', 501);
            }
        } catch (BadMethodCallException $ex) {

            $admin->error = $ex->getMessage();

            $admin->responseCode = $ex->getCode();
        }
    }
}
