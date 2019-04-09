<?php



//namespace XRA\XRA\Helpers;

if (!\function_exists('ddd')) {
    function ddd($params)
    {   
        /*   
        try{
            \header('Content-type: text/html');
        }catch(\Exception $e)
                dd($e);
            // headers already sent 
        }
        */
        $tmp = \debug_backtrace();
        $file = $tmp[0]['file'];
        $file = \str_replace('/', DIRECTORY_SEPARATOR, $file);
        $doc_root = $_SERVER['DOCUMENT_ROOT'];
        $doc_root = \str_replace('/', DIRECTORY_SEPARATOR, $doc_root);
        $dir_piece = \explode(DIRECTORY_SEPARATOR, __DIR__);
        $dir_piece = \array_slice($dir_piece, 0, -6);
        $dir_copy = \implode(DIRECTORY_SEPARATOR, $dir_piece);
        $file = \str_replace($dir_copy, $doc_root, $file);
        echo '<h3>LINE: ['.$tmp[0]['line'].']<br/>
		FILE: ['.$file.']<br/>
		</h3>';
        dd($params);
    }
}

if (!\function_exists('getFilename')) {
    function getFilename($params)
    {
        $tmp = \debug_backtrace();
        $class = class_basename($tmp[1]['class']);
        $func = $tmp[1]['function'];
        $params_list = collect($params)->except(['_token', '_method'])->implode('_');
        $filename = str_slug(
            \str_replace('Controller', '', $class).
                    '_'.\str_replace('do_', '', $func).
                    '_'.$params_list
                );

        return $filename;
    }
}

if (!\function_exists('setConfig')) {
    function setConfig($params)
    {
        $data = getConfig($params);
        $data = \array_merge($data, $params['data']);

        $config_files = getConfigFiles($params);
        if (\count($config_files) > 1) {
            foreach ($config_files as $k => $file) {
                arraySave(['filename' => $config_files[$k], 'data' => $data[$k]]);
            }
        } else {
            arraySave(['filename' => $config_files[0], 'data' => $data]);
        }
        //\Session::flash('status', $params['msg'].' '.\Carbon\Carbon::now());
        //return \Redirect::back();
    }
}

if (!\function_exists('getConfig')) {
    function getConfig($params)
    {
        $config_files = getConfigFiles($params);
        if (\count($config_files) > 1) {
            $data = [];
            foreach ($config_files as $k => $config_file) {
                $tmp = include $config_file;
                $data[$k] = $tmp;
            }
        } else {
            $data = include $config_files[0];
        }

        return $data;
    }
}

if (!\function_exists('getConfigFile')) {
    function getConfigFiles($params)
    {
        //if(count($params)>1){
        if (\is_dir(base_path('config/'.$params['file']))) {
            $tmps = (\array_keys(config($params['file'])));
            $files = [];
            foreach ($tmps as $tmp) {
                $files[$tmp] = base_path('config'.DIRECTORY_SEPARATOR.$params['file'].DIRECTORY_SEPARATOR.$tmp.'.php');
            }

            return $files;
        }
        //ddd($params);
        //}
        if (!isset($_SERVER['SERVER_NAME']) || '127.0.0.1' == $_SERVER['SERVER_NAME']) {
            $_SERVER['SERVER_NAME'] = 'localhost';
        }
        $server_name = str_slug(\str_replace('www.', '', $_SERVER['SERVER_NAME']));
        $config_file = base_path('config'.DIRECTORY_SEPARATOR.$server_name.DIRECTORY_SEPARATOR.$params['file']);
        if (!\file_exists($config_file)) {
            //ddd($config_file);
            if (\file_exists(base_path('config/'.$params['file']))) {
                //ddd(base_path('config/'.$params['file']));
                return [base_path('config/'.$params['file'])];
            }
            if (\file_exists(base_path('config/'.$params['file'].'.php'))) {
                //ddd('b');
                return [base_path('config/'.$params['file'].'.php')];
            }
            echo '<h3>'.$config_file.'</h3>';
            dd('<br/>LINE:['.__LINE__.']['.__FILE__.']');
        }
        return [$config_file];
    }
}

if (!\function_exists('arraySave')) {
    function arraySave($params)
    {
        \XRA\Extend\Services\ArrayService::save($params);
        /*
        \extract($params);
        $writer = new Zend\Config\Writer\PhpArray();
        $content = $writer->toString($data);
        $content = \str_replace('\\\\', '\\', $content);
        $content = \str_replace('\\\\', '\\', $content);
        //$content=str_replace("\\'","\'", $content);
        $content = \str_replace("'".storage_path(), 'storage_path()'.".'", $content);
        \File::put($filename, $content);
        */
    }
}

if (!\function_exists('in_admin')) {
    function in_admin()
    {
        return 'admin' == \Request::segment(1);
    }
}


    /**
     * Replaces spaces with full text search wildcards
     *
     * @param string $term
     * @return string
     */
if (!\function_exists('fullTextWildcards')) {
    /*protected */ function fullTextWildcards($term)
    {
        // removing symbols used by MySQL
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
 
        $words = explode(' ', $term);
 
        foreach($words as $key => $word) {
            /*
             * applying + operator (required word) only big words
             * because smaller ones are not indexed by mysql
             */
            if(strlen($word) >= 3) {
                $words[$key] = '+' . $word . '*';
            }
        }
 
        $searchTerm = implode( ' ', $words);
 
        return $searchTerm;
    }
}