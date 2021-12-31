<?php 

namespace App;

class EditEnv {

    private $env;

    public function __construct() {
        $env = base_path(".env");
        $this->env = $env;
    }

    public function getContent() {
        return $this->envToArray($this->env);
    }

    public function updateEnv($data = array()) {
        //return $data;
        if (count($data) > 0) {

            $env = $this->getContent();

            foreach ($data as $key => $value) {

                foreach (array_keys($env) as $envKey) {
                    if ($key === $envKey) {
                        $env[$envKey] = $value;
                    }
                }

            }
            return $this->save($env);
        }
        return false;
    }

    protected function save($array) {
        if (is_array($array)) {
            $newArray = array();
            $c = 0;
            foreach ($array as $key => $value) {
                if (preg_match('/\s/', $value) > 0 && (strpos($value, '"') > 0 && strpos($value, '"', -0) > 0)) {
                    $value = '"' . $value . '"';
                }

                $newArray[$c] = $key . "=" . $value;
                $c++;
            }

            $newArray = implode("\n", $newArray);

            file_put_contents($this->env, $newArray);

            return true;
        }
        return false;
    }

    public function sanitize($value = '') {
        if ($this->isStartOrEndWith($value, '"')) {
            $value = $this->setStartAndEndWith($value, '"');
        }
        if (preg_match('/\s/', $value)) {
            $value = $this->setStartAndEndWith($value, '"');
        }
        return $value;
    }

    public function isStartOrEndWith($value, $string = '') {
        return $this->startsWith($value, $string) || $this->endsWith($value, $string);
    }

    public function setStartAndEndWith($value, $string = '') {
        $value = $value;
        return $value;
    }

    public function startsWith($value, $string) {
        $len = \strlen($string);
        return (\substr($value, 0, $len) === $string);
    }

    public function endsWith($value, $string) {
        $len = \strlen($string);
        if($len == 0) {
            return true;
        }
        return (\substr($value, -$len) === $string);
    }

    protected function envToArray($file) {
        $string      = file_get_contents($file);
        $string      = preg_split('/\n+/', $string);
        $returnArray = array();

        foreach ($string as $one) {
            if (preg_match('/^(#\s)/', $one) === 1 || preg_match('/^([\\n\\r]+)/', $one)) {
                continue;
            }
            $entry                  = explode("=", $one, 2);
            $returnArray[$entry[0]] = isset($entry[1]) ? $entry[1] : null;
        }

        return array_filter(
            $returnArray,
            function ($key) {
                return !empty($key);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function keyExists($key) {
        $env = $this->getContent();
        return (array_key_exists($key, $env));
    }

}