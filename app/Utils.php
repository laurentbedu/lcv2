<?php
class Utils
{

    public static function scanDirectory($directoryName)
    {
        $directories = scandir($directoryName);
        $directories = array_diff($directories, array('.', '..'));
        $result = [];
        foreach ($directories as $element) {
            $elementPath = $directoryName . '/' . $element;
            if (is_file($elementPath)) {
                if (self::endsWith($element, ".php"))
                    $result[strstr($element, '.', true)] = $elementPath;
            } else {
                $result = array_merge($result, self::scanDirectory($elementPath));
            }
        }
        // var_dump($result);
        return $result;
    }


    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }

    public static function intToBase62($id)
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $shortenedId = '';
        while ($id > 0) {
            $remainder = $id % 62;
            $id = ($id - $remainder) / 62;
            $shortenedId = $alphabet[$remainder] . $shortenedId;
        };
        return $shortenedId;
    }
    public static function getPictureId()
    {
        $timeArray = hrtime();
        $timeMs = $timeArray[0] . substr($timeArray[1], 0, 3) . mt_rand(0, 9);
        return self::intToBase62($timeMs);
    }

    public static function is_assoc($var)
    {
        return is_array($var) && array_diff_key($var, array_keys(array_keys($var)));
    }

    public static function validateInput($data){
        return htmlspecialchars(stripslashes(trim($data)));
    }
}
