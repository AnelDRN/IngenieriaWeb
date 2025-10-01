<?php

class Utilities
{
    /**
     * Valida y sanitiza un dato.
     * @param string $data El dato a sanitizar.
     * @return string El dato sanitizado.
     */
    public static function sanitize($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Aquí se pueden agregar más funciones de utilidad estáticas
}
