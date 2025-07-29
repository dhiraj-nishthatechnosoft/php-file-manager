<?php

declare(strict_types=1);

namespace NishthaTechnosoft\PhpFileManager\Resources;

/**
 * Resource Manager for handling views and assets
 *
 * This class manages the loading of view templates and asset files
 * for the PHP File Manager package.
 *
 * @package Dhiraj\PhpFileManager\Resources
 * @author Dhiraj Dhiman <dhiraj@nishthatechnosoft.com>
 * @version 1.0.0
 * @since 1.0.0
 */
class ResourceManager
{
    /**
     * Package root directory
     */
    private string $packageRoot;

    /**
     * ResourceManager constructor
     */
    public function __construct()
    {
        $this->packageRoot = dirname(__DIR__, 2);
    }

    /**
     * Get the path to a view file
     *
     * @param string $viewName View name (without .php extension)
     * @return string Full path to view file
     * @throws \RuntimeException If view file doesn't exist
     */
    public function getViewPath(string $viewName): string
    {
        $viewPath = $this->packageRoot . '/views/' . $viewName . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View file not found: {$viewName}");
        }
        
        return $viewPath;
    }

    /**
     * Render a view with variables
     *
     * @param string $viewName View name
     * @param array<string, mixed> $variables Variables to extract into view
     * @return string Rendered view content
     */
    public function renderView(string $viewName, array $variables = []): string
    {
        $viewPath = $this->getViewPath($viewName);
        
        // Extract variables into current scope
        extract($variables, EXTR_SKIP);
        
        // Start output buffering
        ob_start();
        
        try {
            // Include the view file
            include $viewPath;
            
            // Get the buffered content
            return ob_get_clean() ?: '';
        } catch (\Throwable $e) {
            // Clean up buffer on error
            ob_end_clean();
            throw $e;
        }
    }

    /**
     * Get CSS content
     *
     * @return string CSS content
     */
    public function getCssContent(): string
    {
        $cssPath = $this->packageRoot . '/assets/styles.css';
        
        if (file_exists($cssPath)) {
            return file_get_contents($cssPath) ?: '';
        }
        
        return '';
    }

    /**
     * Get JavaScript content
     *
     * @return string JavaScript content
     */
    public function getJsContent(): string
    {
        $jsPath = $this->packageRoot . '/assets/script.js';
        
        if (file_exists($jsPath)) {
            return file_get_contents($jsPath) ?: '';
        }
        
        return '';
    }

    /**
     * Get asset URL for external assets (CDN etc.)
     *
     * @param string $asset Asset name
     * @return string Asset URL
     */
    public function getAssetUrl(string $asset): string
    {
        $assets = [
            'sweetalert2' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11',
            'jquery' => 'https://code.jquery.com/jquery-3.6.0.min.js',
            'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css',
        ];

        return $assets[$asset] ?? '';
    }

    /**
     * Get package version
     *
     * @return string Package version
     */
    public function getVersion(): string
    {
        $composerPath = $this->packageRoot . '/composer.json';
        
        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);
            return $composer['version'] ?? '1.0.0';
        }
        
        return '1.0.0';
    }

    /**
     * Get package root directory
     *
     * @return string Package root directory
     */
    public function getPackageRoot(): string
    {
        return $this->packageRoot;
    }

    /**
     * Check if running in development mode
     *
     * @return bool True if in development mode
     */
    public function isDevelopment(): bool
    {
        return defined('FILEMANAGER_DEBUG') && FILEMANAGER_DEBUG === true;
    }

    /**
     * Get inline CSS for embedding
     *
     * @return string Inline CSS wrapped in style tags
     */
    public function getInlineCSS(): string
    {
        $css = $this->getCssContent();
        return $css ? "<style>\n{$css}\n</style>" : '';
    }

    /**
     * Get inline JavaScript for embedding
     *
     * @return string Inline JavaScript wrapped in script tags
     */
    public function getInlineJS(): string
    {
        $js = $this->getJsContent();
        return $js ? "<script>\n{$js}\n</script>" : '';
    }
}
