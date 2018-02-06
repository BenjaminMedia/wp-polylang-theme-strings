<?php

    /*
    Plugin Name: Polylang Theme Strings (Blade support)
    Plugin URI: http://modeewine.com/en-polylang-theme-strings
    Description: Automatic scanning of strings translation in the theme and registration of them in Polylang plugin. Extension for Polylang plugin.
    Version: 1.0.3
    Author: Modeewine
    Author URI: http://modeewine.com
    License: proprietary
    */

    new MW_Polylang_Theme_Strings();

    class MW_Polylang_Theme_Strings
    {
        static $prefix = 'mw_polylang_strings_';
        static $plugin_version = '1.0.1';
        static $pll_f = 'pll_register_string';
        static $php_file_size_limit = 2097152;
        private $paths;
        private $var = array();

        public function __construct()
        {
            $this->Init();
        }

        public static function Install()
        {
            if (!version_compare(phpversion(), '5', '>='))
            {
                echo 'Your PHP version (' . phpversion() . ') is incompatible with the plug-in code.';
                echo '<br />';
                echo 'The minimum supported PHP version is 5.0.';
                exit;
            }
            else
            {
                self::Themes_PLL_Strings_Scan();
            }
        }

        public function Uninstall()
        {
            global $wpdb;

            $wpdb->query("DELETE FROM `" . $wpdb->prefix . "options` WHERE `option_name` LIKE '" . self::$prefix . "%'");
        }

        public function Init()
        {
            $this->Paths_Init();
            $this->Plugin_Install_Hooks_Init();

            add_action('init', array($this, 'Plugin_Hooks_Init'));
            add_action('admin_enqueue_scripts', array($this, 'Styles_Scripts_Admin_Init'));
            add_action('admin_head', array($this, 'Head_Admin_Init'));
        }

        private function Paths_Init()
        {
            $theme = realpath(get_template_directory());
            $theme_dir_name = preg_split("/[\/\\\]/uis", $theme);
            $theme_dir_name = (string)$theme_dir_name[count($theme_dir_name) - 1];

            $this->paths = array(
                'plugin_file_index' => __FILE__,
                'plugin_url'        => plugins_url('/', __FILE__),
                'theme'             => $theme,
                'theme_dir_name'    => $theme_dir_name,
                'theme_name'        => wp_get_theme()->Name
            );
        }

        private function Plugin_Install_Hooks_Init()
        {
            register_activation_hook($this->Path_Get('plugin_file_index'), array('MW_Polylang_Theme_Strings', 'Install'));
            register_uninstall_hook($this->Path_Get('plugin_file_index'), array('MW_Polylang_Theme_Strings', 'Uninstall'));
        }

        public function Plugin_Hooks_Init()
        {
            if (!is_admin() && function_exists(self::$pll_f))
            {
                $this->Theme_Current_PLL_Strings_Init();
            }
            else
            if (self::Is_PLL_Strings_Settings_Page())
            {
                $this->Themes_PLL_Strings_Scan();

                if (!pll_default_language())
                {
                    if (defined('POLYLANG_VERSION') && (float)POLYLANG_VERSION < 2.1)
                    {
                        wp_redirect(admin_url('options-general.php?page=mlang'));
                    }
                    else // for Polylang >= 2.1
                    {
                        wp_redirect(admin_url('admin.php?page=mlang'));
                    }

                    exit;
                }

                $this->Themes_PLL_Strings_Init();
            }
        }

        public function Styles_Scripts_Admin_Init()
        {
            if (self::Is_PLL_Strings_Settings_Page() || self::Is_WP_Plugins_Page())
            {
                wp_enqueue_style(self::$prefix . 'admin', $this->Path_Get('plugin_url') . 'css/admin.css', array(), self::$plugin_version, 'all');
                wp_enqueue_script(self::$prefix . 'admin', $this->Path_Get('plugin_url') . 'js/admin.js', array('jquery'), self::$plugin_version);
            }
        }

        public function Head_Admin_Init()
        {
            if (self::Is_PLL_Strings_Settings_Page())
            {
                ?>
                <script type="text/javascript">
                    if (typeof(window.<?php echo self::$prefix; ?>admin) == 'object')
                    {
                        window.<?php echo self::$prefix; ?>admin.attr.prefix = '<?php echo self::$prefix; ?>';

                        <?php

                            if (defined('POLYLANG_VERSION') && (float)POLYLANG_VERSION < 2.1)
                            {
                                ?>
                                window.<?php echo self::$prefix; ?>admin.attr.urls['polylang_strings'] = '<?php echo admin_url('options-general.php?page=mlang&tab=strings'); ?>';
                                window.<?php echo self::$prefix; ?>admin.attr.urls['polylang_strings_theme_current'] = '<?php echo admin_url('options-general.php?page=mlang&tab=strings&s&group=' . __('Theme') . ': ' . wp_get_theme()->Name . '&paged=1'); ?>';
                                <?php
                            }
                            else // for Polylang >= 2.1
                            {
                                ?>
                                window.<?php echo self::$prefix; ?>admin.attr.urls['polylang_strings'] = '<?php echo admin_url('admin.php?page=mlang_strings'); ?>';
                                window.<?php echo self::$prefix; ?>admin.attr.urls['polylang_strings_theme_current'] = '<?php echo admin_url('admin.php?page=mlang_strings&s&group=' . __('Theme') . ': ' . wp_get_theme()->Name . '&paged=1'); ?>';
                                <?php
                            }

                        ?>

                        window.<?php echo self::$prefix; ?>admin.lng[10] = '<?php _e('Polylang Theme Strings'); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[11] = '<?php _e('works'); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[20] = '<?php _e('Current theme polylang-strings detected'); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[21] = '<?php echo $this->var['theme-strings-count'][$this->Path_Get('theme_dir_name')]; ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[30] = '<?php _e('All themes polylang-strings detected'); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[31] = '<?php echo array_sum($this->var['theme-strings-count']); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[40] = '<?php _e('Plugin web-page'); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[50] = '<?php _e('Donation'); ?>';
                        window.<?php echo self::$prefix; ?>admin.lng[60] = '<?php _e('Please, give plugin feedback'); ?>';

                        jQuery(document).ready(function(){
                            window.<?php echo self::$prefix; ?>admin.init.polylang_info_area();
                        });
                    }
                </script>
                <?php
            }

            if (self::Is_WP_Plugins_Page())
            {
                ?>
                <script type="text/javascript">
                    if (typeof(window.<?php echo self::$prefix; ?>admin) == 'object')
                    {
                        window.<?php echo self::$prefix; ?>admin.attr.urls['polylang_strings'] = '<?php

                            if (defined('POLYLANG_VERSION') && (float)POLYLANG_VERSION < 2.1)
                            {
                                echo admin_url('options-general.php?page=mlang&tab=strings');
                            }
                            else // for Polylang >= 2.1
                            {
                                echo admin_url('admin.php?page=mlang_strings');
                            }

                        ?>';

                        window.<?php echo self::$prefix; ?>admin.lng[70] = '<?php _e('Go to polylang-strings settings page'); ?>';

                        jQuery(document).ready(function(){
                            window.<?php echo self::$prefix; ?>admin.init.plugins_page();
                        });
                    }
                </script>
                <?php
            }
        }

        public function Path_Get($key)
        {
            if (isset($this->paths[$key]))
            {
                return $this->paths[$key];
            }
        }

        public static function Files_Recursive_Get($dir)
        {
            $files = array();

            if ($h = opendir($dir))
            {
                while (($item = readdir($h)) !== false)
                {
                    $f = $dir . '/' . $item;

                    if (is_file($f) && filesize($f) <= self::$php_file_size_limit)
                    {
                        $files[] = $f;
                    }
                    else
                    if (is_dir($f) && !preg_match("/^[\.]{1,2}$/uis", $item))
                    {
                        $files = array_merge($files, self::Files_Recursive_Get($f));
                    }
                }

                closedir($h);
            }

            return $files;
        }

        public static function Is_PLL_Strings_Settings_Page()
        {
            if
            (
                is_admin() &&
                function_exists(self::$pll_f) &&
                isset($_REQUEST['page']) &&
                (
                    ($_REQUEST['page'] == 'mlang' && isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'strings') ||
                    $_REQUEST['page'] == 'mlang_strings' // for Polylang >= 2.1
                )
            )
            {
                return true;
            }
        }

        public static function Is_WP_Plugins_Page()
        {
            if (preg_match("/\/plugins.php[^a-z0-9]?/uis", $_SERVER['REQUEST_URI']))
            {
                return true;
            }
        }

        private static function Themes_PLL_Strings_Scan()
        {
            $themes = wp_get_themes();

            if (count($themes))
            {
                foreach ($themes as $theme_dir_name => $theme)
                {
                    $data = array(
                        'name'    => $theme->Name,
                        'strings' => array()
                    );

                    $theme_path = $theme->theme_root . '/' . $theme_dir_name;

                    if (file_exists($theme_path))
                    {
                        $files = self::Files_Recursive_Get($theme_path);

                        foreach($files as $v)
                        {
                            if (preg_match("/\/.*?\.(php[0-9]?|inc)$/uis", $v))
                            {
                                preg_match_all("/(?:\<\?.*?\?\>)|(?:\<\?.*?[^\?]+[^\>]+)|(?:\{\{.*?\}\})|(?:\{\!\!.*?\!\!\})|(?:@php.*?@endphp)/uis", file_get_contents($v), $p);

                                if (count($p[0]))
                                {
                                    foreach ($p[0] as $pv)
                                    {
                                        preg_match_all("/pll_[_e][\s]*\([\s]*[\'\"](.*?)[\'\"][\s]*[\),]/uis", $pv, $m);

                                        if (count($m[0]))
                                        {
                                            foreach ($m[1] as $mv)
                                            {
                                                if (!in_array($mv, $data))
                                                {
                                                    $data['strings'][] = $mv;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        update_option(self::$prefix . $theme_dir_name . '_data', $data);
                    }
                }
            }
        }

        public function Theme_Current_PLL_Strings_Init()
        {
            $data = get_option(self::$prefix . $this->Path_Get('theme_dir_name') . '_data');

            if (is_array($data) && is_array($data['strings']) && count($data['strings']))
            {
                foreach ($data['strings'] as $v)
                {
                    pll_register_string($v, $v, __('Theme') . ': ' . $data['name']);
                }
            }
        }

        public function Themes_PLL_Strings_Init()
        {
            $themes = wp_get_themes();

            if (count($themes))
            {
                foreach ($themes as $theme_dir_name => $theme)
                {
                    $data = get_option(self::$prefix . $theme_dir_name . '_data');
                    $tsc = &$this->var['theme-strings-count'][$theme_dir_name];
                    $tsc = 0;

                    if (is_array($data) && is_array($data['strings']) && count($data['strings']))
                    {
                        foreach ($data['strings'] as $v)
                        {
                            pll_register_string($v, $v, __('Theme') . ': ' . $data['name']);
                        }

                        $tsc = count($data['strings']);
                    }
                }
            }
        }
    }
