<?php
// ============================================================
//  CarLoc – Contrôleur de base
// ============================================================

abstract class Controller {

    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewFile)) {
            die("Vue introuvable : $view");
        }
        require __DIR__ . '/../views/layout/header.php';
        require $viewFile;
        require __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirect(string $path): void {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }

    protected function isAuthenticated(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth(string $role = ''): void {
        if (!$this->isAuthenticated()) {
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Veuillez vous connecter.'];
            $this->redirect('auth/login');
        }
        if ($role && $_SESSION['user_role'] !== $role && !($role === 'agent' && $_SESSION['user_role'] === 'admin')) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Accès non autorisé.'];
            $this->redirect('');
        }
    }

    // ── CSRF ───────────────────────────────────────────────
    protected function generateCsrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrfToken(): bool {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    protected function csrfField(): string {
        return '<input type="hidden" name="csrf_token" value="' . $this->generateCsrfToken() . '">';
    }

    protected function sanitize(string $val): string {
        return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
    }

    protected function post(string $key): string {
        return $this->sanitize($_POST[$key] ?? '');
    }
}
