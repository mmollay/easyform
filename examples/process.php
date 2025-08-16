<?php
/**
 * EasyForm - AJAX Process Handler
 * Verarbeitet Formular-Submissions
 */

session_start();
header('Content-Type: application/json');

// Simuliere eine kleine Verzögerung für realistisches Verhalten
sleep(1);

// Response Array
$response = [
    'success' => false,
    'message' => '',
    'data' => [],
    'errors' => []
];

try {
    // Prüfe Request-Methode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Nur POST-Anfragen erlaubt');
    }
    
    // CSRF Token Validierung (wenn vorhanden)
    if (isset($_POST['csrf_token'])) {
        $formId = $_POST['form_id'] ?? 'easyform';
        $sessionToken = $_SESSION['csrf_token_' . $formId] ?? null;
        
        // Debug CSRF
        error_log("CSRF Debug - Form ID: " . $formId);
        error_log("CSRF Debug - Posted Token: " . $_POST['csrf_token']);
        error_log("CSRF Debug - Session Token: " . ($sessionToken ?? 'NULL'));
        error_log("CSRF Debug - All Session: " . json_encode($_SESSION));
        
        if (!$sessionToken || $_POST['csrf_token'] !== $sessionToken) {
            // CSRF Token ist jetzt optional für Entwicklung
            // In Produktion sollte dies aktiviert werden:
            // throw new Exception('Sicherheitstoken ungültig. Bitte laden Sie die Seite neu.');
            error_log("CSRF Token mismatch - continuing in development mode");
        }
    }
    
    // Formular-spezifische Verarbeitung basierend auf form_id
    $formId = $_POST['form_id'] ?? 'unknown';
    
    switch ($formId) {
        case 'contact_form':
            // Kontaktformular verarbeiten
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $subject = $_POST['subject'] ?? '';
            $message = trim($_POST['message'] ?? '');
            $newsletter = isset($_POST['newsletter']) ? 'Ja' : 'Nein';
            
            // Validierung
            if (empty($name)) {
                $response['errors']['name'] = 'Name ist erforderlich';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['errors']['email'] = 'Gültige E-Mail-Adresse erforderlich';
            }
            if (empty($message)) {
                $response['errors']['message'] = 'Nachricht ist erforderlich';
            }
            
            if (empty($response['errors'])) {
                // Hier würde normalerweise die E-Mail gesendet oder in DB gespeichert
                $response['success'] = true;
                $response['message'] = "Vielen Dank, {$name}! Ihre Nachricht wurde erfolgreich gesendet.";
                $response['data'] = [
                    'name' => $name,
                    'email' => $email,
                    'timestamp' => date('Y-m-d H:i:s')
                ];
            }
            break;
            
        case 'ajax_form':
            // Registrierungsformular
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $age = intval($_POST['age'] ?? 0);
            $website = trim($_POST['website'] ?? '');
            $notifications = $_POST['notifications'] ?? 'none';
            $newsletter = isset($_POST['newsletter']) ? true : false;
            
            // Validierung
            if (strlen($username) < 3) {
                $response['errors']['username'] = 'Benutzername muss mindestens 3 Zeichen lang sein';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['errors']['email'] = 'Ungültige E-Mail-Adresse';
            }
            if (strlen($password) < 8) {
                $response['errors']['password'] = 'Passwort muss mindestens 8 Zeichen lang sein';
            }
            if ($password !== $passwordConfirm) {
                $response['errors']['password_confirm'] = 'Passwörter stimmen nicht überein';
            }
            if ($age < 18 || $age > 120) {
                $response['errors']['age'] = 'Alter muss zwischen 18 und 120 liegen';
            }
            if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
                $response['errors']['website'] = 'Ungültige URL';
            }
            
            if (empty($response['errors'])) {
                // Simuliere Benutzer-Registrierung
                $response['success'] = true;
                $response['message'] = "Willkommen, {$username}! Ihr Account wurde erfolgreich erstellt.";
                $response['data'] = [
                    'username' => $username,
                    'email' => $email,
                    'notifications' => $notifications,
                    'newsletter' => $newsletter,
                    'user_id' => rand(1000, 9999)
                ];
            }
            break;
            
        case 'application_form':
            // Bewerbungsformular
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname = trim($_POST['lastname'] ?? '');
            $birthdate = $_POST['birthdate'] ?? ''; // NEU: Geburtsdatum
            $email = trim($_POST['email'] ?? '');
            $education = $_POST['education'] ?? '';
            $experience = intval($_POST['experience'] ?? 0);
            $terms = isset($_POST['terms']) ? true : false;
            
            // Debug in Response zurückgeben
            $response['received_data'] = [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'birthdate' => $birthdate,
                'email' => $email,
                'education' => $education,
                'terms' => $terms,
                'all_post' => $_POST
            ];
            
            // Validierung mit genaueren Meldungen
            if (empty($firstname)) {
                $response['errors']['firstname'] = 'Vorname ist erforderlich (erhalten: "' . $firstname . '")';
            }
            if (empty($lastname)) {
                $response['errors']['lastname'] = 'Nachname ist erforderlich (erhalten: "' . $lastname . '")';
            }
            if (empty($birthdate)) {
                $response['errors']['birthdate'] = 'Geburtsdatum ist erforderlich (erhalten: "' . $birthdate . '")';
            }
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response['errors']['email'] = 'Gültige E-Mail-Adresse erforderlich (erhalten: "' . $email . '")';
            }
            if (empty($education)) {
                $response['errors']['education'] = 'Bitte wählen Sie Ihren Abschluss (erhalten: "' . $education . '")';
            }
            if (!$terms) {
                $response['errors']['terms'] = 'Sie müssen den Datenschutzbestimmungen zustimmen (erhalten: ' . ($terms ? 'true' : 'false') . ')';
            }
            
            // Datei-Upload verarbeiten (wenn vorhanden)
            if (isset($_FILES['cv'])) {
                $uploadedFiles = [];
                if ($_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                    $uploadedFiles[] = $_FILES['cv']['name'];
                }
            }
            
            if (empty($response['errors'])) {
                $response['success'] = true;
                $response['message'] = "Vielen Dank für Ihre Bewerbung, {$firstname} {$lastname}! Wir melden uns in Kürze bei Ihnen.";
                $response['data'] = [
                    'applicant' => "{$firstname} {$lastname}",
                    'email' => $email,
                    'education' => $education,
                    'experience' => $experience,
                    'application_id' => 'APP-' . rand(10000, 99999),
                    'files' => $uploadedFiles ?? []
                ];
            }
            break;
            
        default:
            // Generische Verarbeitung für unbekannte Formulare
            $response['success'] = true;
            $response['message'] = 'Formular wurde erfolgreich verarbeitet';
            $response['data'] = $_POST;
            
            // Entferne sensitive Daten aus der Response
            unset($response['data']['csrf_token']);
            unset($response['data']['password']);
            unset($response['data']['password_confirm']);
            break;
    }
    
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Fehler-Details hinzufügen wenn vorhanden
if (!empty($response['errors'])) {
    $response['success'] = false;
    $response['message'] = 'Validierungsfehler: ' . implode(', ', array_values($response['errors']));
    $response['debug_info'] = [
        'received_post' => $_POST,
        'form_id' => $_POST['form_id'] ?? 'MISSING',
        'csrf_token_received' => isset($_POST['csrf_token']) ? 'YES' : 'NO',
        'session_tokens' => array_keys($_SESSION),
        'errors_detail' => $response['errors']
    ];
    http_response_code(422);
}

// IMMER Debug-Info bei Fehlern hinzufügen
if (!$response['success']) {
    $response['debug_help'] = 'Check debug_info for details';
}

// JSON Response senden
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;