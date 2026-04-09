<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class AlbaranTemplateHelper
{
    /**
     * Obtiene todos los templates de albaranes disponibles
     * Escanea la carpeta resources/views/pdf/ para encontrar templates
     * 
     * @return array Array con los templates disponibles ['value' => 'label']
     */
    public static function getAvailableTemplates(): array
    {
        $templates = [];
        $viewsPath = resource_path('views/pdf');

        if (!File::exists($viewsPath)) {
            return self::getDefaultTemplates();
        }

        $files = File::files($viewsPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();

            // Detectar templates de albaranes por patrón de nombre
            // Patrones: albaran-{nombre}.blade.php, albaran{nombre}.blade.php, albaranEnviado.blade.php
            if (preg_match('/^albaran([-_]?)([a-z0-9_-]+)\.blade\.php$/i', $filename, $matches)) {
                $templateName = strtolower($matches[2]);

                // Excluir el template "enviado" (simple)
                if ($templateName === 'enviado') {
                    continue;
                }

                // Mapear nombres conocidos a valores estándar
                $nameMapping = [
                    'moderno' => 'modern',
                ];

                $templateValue = $nameMapping[$templateName] ?? $templateName;

                // Normalizar label para nombres conocidos
                $labelMapping = [
                    'modern' => 'Moderno',
                    'moderno' => 'Moderno',
                ];

                $label = $labelMapping[$templateValue] ?? ucfirst(str_replace(['-', '_'], ' ', $templateName));
                $templates[$templateValue] = $label;
            }
        }

        // Siempre incluir 'classic' que usa new-recibo.blade.php
        $templates['classic'] = 'Clásico';

        // Ordenar alfabéticamente pero mantener 'classic' primero
        $classic = $templates['classic'] ?? null;
        unset($templates['classic']);
        ksort($templates);
        if ($classic) {
            $templates = ['classic' => $classic] + $templates;
        }

        return $templates;
    }

    /**
     * Obtiene los templates por defecto si no se pueden detectar
     */
    private static function getDefaultTemplates(): array
    {
        return [
            'classic' => 'Clásico',
            'modern' => 'Moderno',
        ];
    }

    /**
     * Normaliza el nombre del template y valida que exista
     * 
     * @param string|null $template Nombre del template
     * @return string Template normalizado o 'classic' por defecto
     */
    public static function normalizeTemplate(?string $template): string
    {
        $default = 'classic';

        if (!$template) {
            return $default;
        }

        $template = strtolower(trim($template));
        
        // Si el template es 'simple' o 'enviado', normalizarlo a 'classic' ya que lo hemos quitado
        if ($template === 'simple' || $template === 'enviado') {
            return $default;
        }
        
        $availableTemplates = self::getAvailableTemplates();

        // Si el template existe en los disponibles, retornarlo
        if (isset($availableTemplates[$template])) {
            return $template;
        }

        // Si no existe, retornar el default
        return $default;
    }

    /**
     * Verifica si un template existe
     * 
     * @param string $template Nombre del template
     * @return bool
     */
    public static function templateExists(string $template): bool
    {
        $template = strtolower(trim($template));

        // 'classic' siempre existe (usa new-recibo.blade.php)
        if ($template === 'classic') {
            return true;
        }

        // Verificar si existe el archivo de vista
        $viewPath = resource_path("views/pdf/albaran-{$template}.blade.php");
        $viewPathAlt = resource_path("views/pdf/albaran{$template}.blade.php");

        return File::exists($viewPath) || File::exists($viewPathAlt);
    }

    /**
     * Obtiene el nombre de la vista Blade para un template
     * 
     * @param string $template Nombre del template
     * @return string Nombre de la vista
     */
    public static function getViewName(string $template): string
    {
        $template = strtolower(trim($template));

        // 'classic' usa new-recibo.blade.php
        if ($template === 'classic') {
            return 'pdf.new-recibo';
        }

        // Mapeo de nombres conocidos a nombres de archivo
        $viewMapping = [
            'simple' => 'pdf.albaranEnviado',
            'modern' => 'pdf.albaran-moderno',
        ];

        if (isset($viewMapping[$template])) {
            return $viewMapping[$template];
        }

        // Intentar diferentes patrones de nombre para templates nuevos
        $patterns = [
            "pdf.albaran-{$template}",
            "pdf.albaran{$template}",
        ];

        foreach ($patterns as $pattern) {
            $viewPath = resource_path("views/" . str_replace('.', '/', $pattern) . ".blade.php");
            if (File::exists($viewPath)) {
                return $pattern;
            }
        }

        // Si no se encuentra, retornar el patrón más común
        return "pdf.albaran-{$template}";
    }
}
