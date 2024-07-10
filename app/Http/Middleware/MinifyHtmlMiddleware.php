<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtmlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!Auth::check() || (Auth::check() && !Auth::user()->hasPermission('have_official_icon'))) {
            // Minify the HTML only if the user has the permission
            if ($response instanceof \Illuminate\Http\Response) {
                $output = $response->getContent();

                // Minify HTML excluding content within script and style tags
                $output = $this->minifyHtml($output);

                $response->setContent($output);
            }
        }

        return $response;
    }

    private function minifyHtml($content)
    {
        // Define patterns for identifying inline script and style blocks
        $patterns = [
            '/<script\b[^>]*>[\s\S]*?<\/script>/i', // Example: <script>...</script>
            '/<style\b[^>]*>[\s\S]*?<\/style>/i', // Example: <style>...</style>
        ];

        // Iterate through patterns and minify content within script and style blocks
        foreach ($patterns as $pattern) {
            $content = preg_replace_callback($pattern, function ($matches) {
                // Preserve newlines and spaces within the script and style blocks
                return $matches[0];
            }, $content);
        }

        // Minify the remaining HTML (remove extra spaces at the beginning and end of lines)
        $content = preg_replace('/^\s+/m', '', $content);
        $content = preg_replace('/\s+$/m', '', $content);

        // Remove newlines at the end of script and style blocks
        $content = preg_replace('/<\/script>\s*\n/', '</script>', $content);
        $content = preg_replace('/<\/style>\s*\n/', '</style>', $content);

        // Remove newlines within other HTML tags
        $content = preg_replace('/>\s+</', '><', $content);

        return $content;
    }


    
}
