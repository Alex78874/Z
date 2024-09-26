<?php

class Validator
{
    public function validate($data, $rules, $messages = []): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;

            foreach (explode(separator: '|', string: $fieldRules) as $rule) {
                $ruleParts = explode(separator: ':', string: $rule);
                $ruleName = $ruleParts[0];
                $ruleParam = $ruleParts[1] ?? null;

                if ($ruleName === 'required' && empty($value)) {
                    $errors[$field][] = $messages[$field]['required'] ?? "$field est requis.";
                }

                if ($ruleName === 'min' && strlen(string: $value) < $ruleParam) {
                    $errors[$field][] = $messages[$field]['min'] ?? "$field doit comporter au moins $ruleParam caractères.";
                }

                if ($rule === 'email' && !filter_var(value: $value, filter: FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = $messages[$field]['email'] ?? "$field doit être une adresse email valide.";
                }

                if ($ruleName === 'username_available' && !$this->isUsernameAvailable(username: $value)) {
                    $errors[$field][] = $messages[$field]['username_available'] ?? "Le nom d'utilisateur est déjà pris.";
                }

                // Ajouter d'autres règles de validation ici
            }
        }

        return $errors;
    }

    private function isUsernameAvailable($username): void
    {
        // Vérifier dans la base de données si le nom d'utilisateur est disponible
        // Retourne true si disponible, false sinon
    }
}
