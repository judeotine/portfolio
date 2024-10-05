<?php

// Configuration
$config = [
    'admin_email' => 'judextine28@gmail.com',
    'project_name' => 'Jude\'s portfolio',
    'form_subject' => 'Jude\'s portfolio',
];

// Function to sanitize input data
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate input data
function validateInput($data) {
    if (empty($data)) {
        return false;
    }
    return true;
}

// Function to construct email headers
function constructEmailHeaders($projectName, $adminEmail) {
    $headers = "MIME-Version: 1.0" . PHP_EOL .
        "Content-Type: text/html; charset=utf-8" . PHP_EOL .
        'From: ' . $projectName . ' <' . $adminEmail . '>' . PHP_EOL .
        'Reply-To: ' . $adminEmail . '' . PHP_EOL;
    return $headers;
}

// Function to construct email message
function constructEmailMessage($postData) {
    $message = '<table style="width: 100%;">';
    $c = true;
    foreach ($postData as $key => $value) {
        if (validateInput($value) && $key !== 'project_name' && $key !== 'admin_email' && $key !== 'form_subject') {
            $message .= '
                ' . (($c = !$c) ? '<tr>' : '<tr style="background-color: #f8f8f8;">') . '
                    <td style="padding: 10px; border: #e9e9e9 1px solid;"><b>' . sanitizeInput($key) . '</b></td>
                    <td style="padding: 10px; border: #e9e9e9 1px solid;">' . sanitizeInput($value) . '</td>
                </tr>
            ';
        }
    }
    $message .= '</table>';
    return $message;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postData = $_POST;
    $projectName = sanitizeInput($postData['project_name'] ?? $config['project_name']);
    $adminEmail = sanitizeInput($postData['admin_email'] ?? $config['admin_email']);
    $formSubject = sanitizeInput($postData['form_subject'] ?? $config['form_subject']);

    if (validateInput($projectName) && validateInput($adminEmail) && validateInput($formSubject)) {
        $emailHeaders = constructEmailHeaders($projectName, $adminEmail);
        $emailMessage = constructEmailMessage($postData);

        if (mail($adminEmail, $formSubject, $emailMessage, $emailHeaders)) {
            echo 'Email sent successfully!';
        } else {
            echo 'Error sending email!';
        }
    } else {
        echo 'Invalid input data!';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo 'Invalid request method!';
} else {
    echo 'Invalid request!';
}

?>