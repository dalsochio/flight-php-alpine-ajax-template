import {defineConfig, loadEnv} from 'vite'
import {resolve} from 'path'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig(({mode}) => {
    const env = loadEnv(mode, process.cwd(), '');
    const isProduction = mode === 'production';

    return {
        base: isProduction ? '/assets/' : '/',
        plugins: [tailwindcss()],
        build:
            {
                // Sets the output directory as public/build
                outDir: './public/assets',
                // Cleans the output directory before building
                emptyOutDir:
                    false,
                // Does not copy the public directory automatically
                copyPublicDir:
                    false,
                // Rollup settings
                rollupOptions:
                    {
                        // Configures entry points for JS and CSS
                        input: {
                            // JS Files
                            'js/app':
                                resolve(__dirname, 'app/views/assets/js/app.js'),
                            // CSS Files
                            'css/app':
                                resolve(__dirname, 'app/views/assets/css/app.css')
                        },
                        output: {
                            // Maintains the original directory structure
                            entryFileNames: '[name].js',
                            chunkFileNames:
                                'chunks/[name]-[hash].js',
                            assetFileNames:
                                (assetInfo) => {
                                    // get extension
                                    const extType = assetInfo.name.split('.').pop();
                                    // define fonts types
                                    const fontExtensions = ['woff', 'woff2', 'eot', 'ttf', 'otf'];

                                    // put fonts inside fonts folder
                                    if (fontExtensions.includes(extType)) {
                                        return `fonts/[name].[ext]`;
                                    }
                                    return `[name].[ext]`;
                                },
                        }
                    },
                // Other important settings
                sourcemap: true,
                minify: 'terser',
            },
        server: {
            watch: {
                ignored: [
                    '**/{node_modules,storage,scripts,public,vendor}/**'
                ]
            },
        },
    }
})
